<?php
/**
 * Build step: resolve every key in includes/data/image-map.php into locally
 * cached, correctly sized JPEGs and write includes/data/images.php.
 *
 *   php tools/fetch-images.php            resolve anything not already cached
 *   php tools/fetch-images.php --force    re-resolve everything from scratch
 *
 * Running this is optional. Without it the site falls back to the bundled SVG
 * placeholders, so nothing ever renders broken — but real photography is
 * obviously the point, so run it once before deploying.
 */

if (PHP_SAPI !== 'cli') {
    exit("This is a command line build tool.\n");
}

require __DIR__ . '/../includes/config.php';

$force    = in_array('--force', $argv, true);
// --only=key1,key2 re-resolves just those keys, for swapping a weak photo.
$only     = [];
foreach ($argv as $a) {
    if (str_starts_with($a, '--only=')) $only = explode(',', substr($a, 7));
}
$map      = rw_image_map();
$outDir   = PEXELS_IMG_DIR;
$manifest = [];
$existing = is_file(dirname(__DIR__) . '/includes/data/images.php')
    ? (require dirname(__DIR__) . '/includes/data/images.php') : [];

if (!is_dir($outDir) && !mkdir($outDir, 0775, true)) {
    exit("Cannot create $outDir\n");
}

/** Rendition widths and aspect ratio per orientation. */
function rw_render_sizes(string $orientation): array {
    return match ($orientation) {
        'portrait' => ['ratio' => 4 / 3, 'widths' => [400, 600, 900]],   // h = w * ratio
        'square'   => ['ratio' => 1.0,   'widths' => [160, 320]],
        default    => ['ratio' => 2 / 3, 'widths' => [640, 960, 1440]],
    };
}

/** Build a Pexels CDN URL at an exact size. Never request the original file. */
function rw_render_url(string $originalUrl, int $w, int $h): string {
    $base = strtok($originalUrl, '?');
    return $base . '?' . http_build_query([
        'auto' => 'compress',
        'cs'   => 'tinysrgb',
        'fit'  => 'crop',
        'w'    => $w,
        'h'    => $h,
    ]);
}

/**
 * Download and normalize to a real JPEG. Pexels serves the source file in its
 * original format, so a .jpg URL can legitimately return a PNG — re-encoding
 * keeps the extension honest and shrinks the file at the same time.
 */
function rw_download(string $url, string $dest): bool {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT        => 45,
        CURLOPT_CONNECTTIMEOUT => 10,
    ]);
    $data = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($code !== 200 || !$data || strlen($data) < 1024) return false;

    $im = @imagecreatefromstring($data);
    if (!$im) return false;
    // Flatten any alpha onto white so transparency cannot become a black box.
    $flat = imagecreatetruecolor(imagesx($im), imagesy($im));
    imagefilledrectangle($flat, 0, 0, imagesx($im), imagesy($im), imagecolorallocate($flat, 255, 255, 255));
    imagecopy($flat, $im, 0, 0, 0, 0, imagesx($im), imagesy($im));
    $written = imagejpeg($flat, $dest, 82);
    imagedestroy($im);
    imagedestroy($flat);
    return $written;
}

/**
 * Produce square avatar renditions by cropping a tall source near the top.
 * Returns [width => filename], or [] if the source could not be fetched.
 */
function rw_make_avatar(string $src, string $outDir, string $key, array $widths, bool $refresh): array {
    $tmp = $outDir . '/.tmp-' . $key . '.jpg';
    $out = [];

    $need = $refresh;
    foreach ($widths as $w) {
        if (!is_file($outDir . '/' . $key . '-' . $w . '.jpg')) $need = true;
    }
    if (!$need) {
        foreach ($widths as $w) $out[$w] = $key . '-' . $w . '.jpg';
        return $out;
    }

    if (!rw_download(rw_render_url($src, 700, 950), $tmp)) return [];
    $im = @imagecreatefromjpeg($tmp);
    @unlink($tmp);
    if (!$im) return [];

    $sw = imagesx($im); $sh = imagesy($im);
    $side = min($sw, $sh);
    $sx   = (int) (($sw - $side) / 2);
    $sy   = (int) ($sh * 0.06);                    // bias upward toward the head
    if ($sy + $side > $sh) $sy = max(0, $sh - $side);

    foreach ($widths as $w) {
        $d = imagecreatetruecolor($w, $w);
        imagecopyresampled($d, $im, 0, 0, $sx, $sy, $w, $w, $side, $side);
        if (imagejpeg($d, $outDir . '/' . $key . '-' . $w . '.jpg', 84)) {
            $out[$w] = $key . '-' . $w . '.jpg';
        }
    }
    return $out;
}

$usedPhotoIds = [];
$ok = 0; $skipped = 0; $failed = [];

foreach ($map as $key => $spec) {
    // Keep an already-cached entry unless --force was passed.
    $refresh = $force || ($only && in_array($key, $only, true));
    if (!$refresh && isset($existing[$key]['file'])
        && is_file($outDir . '/' . $existing[$key]['file'])) {
        $manifest[$key] = $existing[$key];
        $usedPhotoIds[] = $existing[$key]['photo_id'] ?? 0;
        $skipped++;
        continue;
    }

    $orientation = $spec['orientation'] ?? 'landscape';

    // An entry may pin an exact photo id, so a picture chosen during design
    // work cannot drift when search rankings change.
    $photo = null;
    if (!empty($spec['photo_id'])) {
        $photo = rw_pexels_photo((int) $spec['photo_id']);
        if (!$photo) { $failed[] = "$key (pinned photo {$spec['photo_id']} unavailable)"; continue; }
    } else {
        $photos = rw_pexels_search($spec['query'], $orientation, 12);
        if (!$photos) {
            $failed[] = "$key (no results for: {$spec['query']})";
            continue;
        }
        // Prefer a photo we have not already used elsewhere on the site.
        foreach ($photos as $p) {
            if (!in_array($p['id'], $usedPhotoIds, true)) { $photo = $p; break; }
        }
        $photo ??= $photos[0];
    }
    $usedPhotoIds[] = $photo['id'];

    $sizes  = rw_render_sizes($orientation);
    $srcset = [];
    $default = null;
    $src  = $photo['large2x'] ?: $photo['large'];

    if ($orientation === 'square') {
        // Avatars: Pexels ignores face-aware cropping, and a centered square of a
        // standing portrait lands on the torso. Pull a tall rendition and take
        // the square from near the top, which is where heads actually are.
        $srcset = rw_make_avatar($src, $outDir, $key, $sizes['widths'], $refresh);
        if ($srcset) {
            $w = max(array_keys($srcset));
            $default = [$srcset[$w], $w, $w];
        }
    } else {
        foreach ($sizes['widths'] as $w) {
            $h    = (int) round($w * $sizes['ratio']);
            $file = $key . '-' . $w . '.jpg';
            $dest = $outDir . '/' . $file;
            if (!is_file($dest) || $refresh) {
                if (!rw_download(rw_render_url($src, $w, $h), $dest)) {
                    continue;
                }
            }
            $srcset[$w] = $file;
            // Middle rendition is the default src; falls back to the last that worked.
            if ($default === null || $w <= 960) $default = [$file, $w, $h];
        }
    }

    if (!$srcset) { $failed[] = "$key (all downloads failed)"; continue; }

    $manifest[$key] = [
        'file'             => $default[0],
        'width'            => $default[1],
        'height'           => $default[2],
        'srcset'           => $srcset,
        'alt'              => $spec['alt'],
        'photo_id'         => $photo['id'],
        'photographer'     => $photo['photographer'],
        'photographer_url' => $photo['photographer_url'],
    ];
    $ok++;
    echo str_pad($key, 28) . " ok  ({$photo['photographer']})\n";
    usleep(120000); // stay well inside the Pexels rate limit
}

/* --------------------------------------------------------------- write out */
$php = "<?php\n"
     . "/**\n"
     . " * GENERATED FILE — do not edit by hand.\n"
     . " * Produced by tools/fetch-images.php on " . date('Y-m-d H:i') . ".\n"
     . " * Maps image keys to locally cached Pexels renditions plus photographer\n"
     . " * credit, which is surfaced on /credits.php as the Pexels terms require.\n"
     . " */\n"
     . "return " . var_export($manifest, true) . ";\n";

file_put_contents(dirname(__DIR__) . '/includes/data/images.php', $php);

echo "\n" . str_repeat('-', 60) . "\n";
echo "fetched: $ok   reused: $skipped   failed: " . count($failed) . "\n";
foreach ($failed as $f) echo "  FAILED  $f\n";
echo "manifest written to includes/data/images.php\n";
