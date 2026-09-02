<?php
/**
 * Regenerates every logo asset from the supplied artwork.
 *
 * The delivered logo is dark-green + gold sitting on a baked-in glow. This
 * script:
 *   1. strips the glow from the alpha channel (it shows as a haze on a light
 *      header),
 *   2. builds the stacked lock-up (footer) and a horizontal lock-up (header,
 *      where a 1.55:1 stacked mark would be taller than the bar itself),
 *   3. exports light variants for dark backgrounds plus favicons and app icons,
 *   4. optionally recolours the ink to navy/teal — see RECOLOR_TO_BRAND below.
 *      That is currently OFF: the logo ships in its original green and gold.
 *
 *   php tools/make-logo-assets.php
 */
if (PHP_SAPI !== 'cli') exit("Command line only.\n");

$SRC = 'f:/BHAI LOG/More Projects/rightway medical billing/transparent logo.png';
$OUT = __DIR__ . '/../assets/img/';

/**
 * Whether to recolour the logo ink into the navy/teal site palette.
 *
 * FALSE (current): the logo keeps its original green + gold artwork exactly as
 * supplied. The site palette is navy/teal, but the brand mark is deliberately
 * left alone — a logo is the client's asset, not a theme variable.
 *
 * Set to TRUE to produce a navy/teal version of the mark instead. Everything
 * else in this script (glow removal, the horizontal lock-up, the light variant
 * for dark backgrounds, favicons) behaves the same either way.
 */
const RECOLOR_TO_BRAND = false;

/* Tile colour behind the square app/favicon icons. Matched to the logo rather
   than the site palette, so the mark never sits on a clashing ground. */
const ICON_TILE = RECOLOR_TO_BRAND ? [0x0E, 0x2E, 0x4F]   // navy-900
                                   : [0x01, 0x40, 0x1A];  // original logo green

/* Ink ramps: [darkest, brightest]. Shading is preserved by mapping each
   pixel's position within its source luminance range onto these. */
const NAVY_DARK  = [0x05, 0x16, 0x26];
const NAVY_LIGHT = [0x35, 0x79, 0xB4];
const TEAL_DARK  = [0x06, 0x4E, 0x4D];
const TEAL_LIGHT = [0x8F, 0xE6, 0xE3];

/* ---------------------------------------------------------------- helpers */
function deglow($src, int $thr = 55) {
    $w = imagesx($src); $h = imagesy($src);
    $d = imagecreatetruecolor($w, $h);
    imagealphablending($d, false); imagesavealpha($d, true);
    imagefilledrectangle($d, 0, 0, $w, $h, imagecolorallocatealpha($d, 0, 0, 0, 127));
    for ($y = 0; $y < $h; $y++) for ($x = 0; $x < $w; $x++) {
        $c = imagecolorat($src, $x, $y);
        if ((($c >> 24) & 0x7F) >= $thr) continue;
        imagesetpixel($d, $x, $y, $c);
    }
    return $d;
}
function bbox($im, int $lim = 120): array {
    $w = imagesx($im); $h = imagesy($im); $x0 = $w; $y0 = $h; $x1 = -1; $y1 = -1;
    for ($y = 0; $y < $h; $y++) for ($x = 0; $x < $w; $x++)
        if (((imagecolorat($im, $x, $y) >> 24) & 0x7F) < $lim) {
            if ($x < $x0) $x0 = $x; if ($x > $x1) $x1 = $x;
            if ($y < $y0) $y0 = $y; if ($y > $y1) $y1 = $y;
        }
    return [$x0, $y0, $x1 - $x0 + 1, $y1 - $y0 + 1];
}
function crop($im, int $x, int $y, int $w, int $h) {
    $d = imagecreatetruecolor($w, $h);
    imagealphablending($d, false); imagesavealpha($d, true);
    imagefilledrectangle($d, 0, 0, $w, $h, imagecolorallocatealpha($d, 0, 0, 0, 127));
    imagecopy($d, $im, 0, 0, $x, $y, $w, $h);
    return $d;
}
function rowInk($im): array {
    $w = imagesx($im); $h = imagesy($im); $rows = [];
    for ($y = 0; $y < $h; $y++) {
        $n = 0;
        for ($x = 0; $x < $w; $x++) if (((imagecolorat($im, $x, $y) >> 24) & 0x7F) < 100) $n++;
        $rows[$y] = $n;
    }
    return $rows;
}
function lum(int $r, int $g, int $b): float { return 0.299 * $r + 0.587 * $g + 0.114 * $b; }
function lerp(array $a, array $b, float $t): array {
    return [
        (int) round($a[0] + ($b[0] - $a[0]) * $t),
        (int) round($a[1] + ($b[1] - $a[1]) * $t),
        (int) round($a[2] + ($b[2] - $a[2]) * $t),
    ];
}
/** Gold ink in the source: warm, red>green>blue and reasonably bright. */
function isGold(int $r, int $g, int $b): bool {
    return $r > 90 && $r > $g + 12 && $g > $b + 20;
}

/**
 * Recolour green ink -> navy and gold ink -> teal, preserving relative shading.
 * $mode 'dark'  : for light backgrounds (normal lock-up)
 *       'light' : for dark backgrounds — navy ink becomes near-white so the
 *                 wordmark stays legible on the navy footer.
 */
function recolor($im, string $mode = 'dark') {
    $w = imagesx($im); $h = imagesy($im);
    $d = imagecreatetruecolor($w, $h);
    imagealphablending($d, false); imagesavealpha($d, true);

    for ($y = 0; $y < $h; $y++) for ($x = 0; $x < $w; $x++) {
        $c = imagecolorat($im, $x, $y);
        $a = ($c >> 24) & 0x7F;
        $r = ($c >> 16) & 0xFF; $g = ($c >> 8) & 0xFF; $b = $c & 0xFF;

        if ($a < 125) {
            if (isGold($r, $g, $b)) {
                // Gold ink. Left untouched unless we are recolouring the brand,
                // in which case it maps onto the teal ramp.
                if (RECOLOR_TO_BRAND) {
                    // Gold ramp runs roughly lum 60..250 in the source artwork.
                    $t = max(0, min(1, (lum($r, $g, $b) - 60) / 190));
                    [$r, $g, $b] = lerp(TEAL_DARK, TEAL_LIGHT, $t);
                }
            } else {
                // Green ramp runs roughly lum 0..110.
                $t = max(0, min(1, lum($r, $g, $b) / 110));
                if ($mode === 'light') {
                    /* Dark ink has to become light so the lock-up survives on a
                       dark background. Warm ivory when the artwork is still
                       green/gold; cooler near-white when it is navy/teal. */
                    if (RECOLOR_TO_BRAND) {
                        $v = 176 + 79 * $t;
                        [$r, $g, $b] = [(int) round($v * 0.97), (int) round($v * 0.99), (int) round($v)];
                    } else {
                        $v = 158 + 97 * $t;
                        [$r, $g, $b] = [(int) round($v), (int) round($v * 0.985), (int) round($v * 0.925)];
                    }
                } elseif (RECOLOR_TO_BRAND) {
                    [$r, $g, $b] = lerp(NAVY_DARK, NAVY_LIGHT, $t);
                }
                // else: 'dark' mode with no recolour — original green ink kept.
            }
        }
        imagesetpixel($d, $x, $y, imagecolorallocatealpha($d, $r, $g, $b, $a));
    }
    return $d;
}

function resizeTo($im, ?int $tw = null, ?int $th = null) {
    $w = imagesx($im); $h = imagesy($im);
    if ($tw === null) $tw = (int) round($w * $th / $h);
    if ($th === null) $th = (int) round($h * $tw / $w);
    $d = imagecreatetruecolor($tw, $th);
    imagealphablending($d, false); imagesavealpha($d, true);
    imagefilledrectangle($d, 0, 0, $tw, $th, imagecolorallocatealpha($d, 0, 0, 0, 127));
    imagecopyresampled($d, $im, 0, 0, 0, 0, $tw, $th, $w, $h);
    imagesavealpha($d, true);
    return $d;
}

/* ------------------------------------------------------------------ build */
$clean = deglow(imagecreatefrompng($SRC));
[$bx, $by, $bw, $bh] = bbox($clean);
$trim  = crop($clean, $bx, $by, $bw, $bh);
echo "source lock-up: {$bw}x{$bh}\n";

/* Stacked lock-up (footer / mobile drawer / square icons). */
$stackDark  = recolor($trim, 'dark');
$stackLight = recolor($trim, 'light');
imagepng(resizeTo($stackDark,  720), $OUT . 'logo.png', 9);
imagepng(resizeTo($stackLight, 720), $OUT . 'logo-light.png', 9);

/* Horizontal lock-up: split at the natural gap, then set side by side. */
$rows = rowInk($trim);
$best = null; $bestInk = PHP_INT_MAX;
for ($y = (int) ($bh * 0.60); $y <= (int) ($bh * 0.80); $y++) {
    if ($rows[$y] < $bestInk) { $bestInk = $rows[$y]; $best = $y; }
}
echo "split row: $best (ink $bestInk)\n";

$iconPart = crop($trim, 0, 0, $bw, $best);
$wordPart = crop($trim, 0, $best, $bw, $bh - $best);
[$ix, $iy, $iw, $ih] = bbox($iconPart);
[$wx, $wy, $ww, $wh] = bbox($wordPart);
$icon = crop($iconPart, $ix, $iy, $iw, $ih);
$word = crop($wordPart, $wx, $wy, $ww, $wh);

function compose($icon, $word, int $iw, int $ih, int $ww, int $wh) {
    $H     = 300;
    $iconW = (int) round($iw * $H / $ih);
    $wordH = (int) round($H * 0.78);
    $wordW = (int) round($ww * $wordH / $wh);
    $gap   = (int) round($H * 0.10);
    $W     = $iconW + $gap + $wordW;

    $o = imagecreatetruecolor($W, $H);
    imagealphablending($o, false); imagesavealpha($o, true);
    imagefilledrectangle($o, 0, 0, $W, $H, imagecolorallocatealpha($o, 0, 0, 0, 127));
    imagealphablending($o, true);
    imagecopyresampled($o, $icon, 0, 0, 0, 0, $iconW, $H, $iw, $ih);
    imagecopyresampled($o, $word, $iconW + $gap, (int) round(($H - $wordH) / 2), 0, 0, $wordW, $wordH, $ww, $wh);
    imagesavealpha($o, true);
    return $o;
}

$horiz = compose($icon, $word, $iw, $ih, $ww, $wh);
$hDark  = resizeTo(recolor($horiz, 'dark'),  null, 240);
$hLight = resizeTo(recolor($horiz, 'light'), null, 240);
imagepng($hDark,  $OUT . 'logo-horizontal.png', 9);
imagepng($hLight, $OUT . 'logo-horizontal-light.png', 9);
printf("horizontal lock-up: %dx%d (ratio %.2f:1)\n",
    imagesx($hDark), imagesy($hDark), imagesx($hDark) / imagesy($hDark));

/* Monogram + square icons on navy. */
$markDark = recolor($icon, 'light');   // light ink, since icons sit on navy
imagepng(resizeTo($markDark, 512), $OUT . 'logo-mark.png', 9);

function icon_square($mark, int $size, string $file, array $bg) {
    $d = imagecreatetruecolor($size, $size);
    imagealphablending($d, false); imagesavealpha($d, true);
    imagefilledrectangle($d, 0, 0, $size, $size, imagecolorallocate($d, $bg[0], $bg[1], $bg[2]));
    imagealphablending($d, true);
    $pad = (int) round($size * 0.12);
    $box = $size - 2 * $pad;
    $mw = imagesx($mark); $mh = imagesy($mark);
    $s  = min($box / $mw, $box / $mh);
    $tw = (int) round($mw * $s); $th = (int) round($mh * $s);
    imagecopyresampled($d, $mark, (int) (($size - $tw) / 2), (int) (($size - $th) / 2), 0, 0, $tw, $th, $mw, $mh);
    imagesavealpha($d, true);
    imagepng($d, $file, 9);
}
foreach ([512 => 'icon-512.png', 192 => 'icon-192.png', 180 => 'apple-touch-icon.png',
          32 => 'favicon-32.png', 16 => 'favicon-16.png'] as $size => $name) {
    icon_square($markDark, $size, $OUT . $name, ICON_TILE);
}

/* PNG-in-ICO container for legacy /favicon.ico requests. */
$png = file_get_contents($OUT . 'favicon-32.png');
file_put_contents($OUT . 'favicon.ico',
    pack('vvv', 0, 1, 1) . pack('CCCCvvVV', 32, 32, 0, 0, 1, 32, strlen($png), 22) . $png);

echo RECOLOR_TO_BRAND
    ? "all logo assets rebuilt, ink recoloured to navy/teal\n"
    : "all logo assets rebuilt from the original green/gold artwork\n";
