<?php
/**
 * Regenerates every logo asset from assets/img/logo-source.png.
 *
 * The current artwork (the blue "R" swoosh + red cross + arrow, stacked over
 * the "RIGHTWAY RCM" wordmark) is delivered clean: real alpha transparency,
 * no glow/haze to strip, so this script is just:
 *   1. trim the transparent margin,
 *   2. find the natural gap between the icon and the wordmark (same
 *      row-ink-count technique as before) to build the icon-only mark and
 *      the horizontal lock-up,
 *   3. for the "light" variants (dark navy footer, OG image, favicon tile),
 *      brighten only the navy ink in the WORDMARK into a near-white tone —
 *      the icon's own blue/red/teal ink already reads fine on navy and is
 *      left untouched, same as the wordmark's teal "WAY" and the red
 *      underlines/cross.
 *
 *   php -d extension=php_gd.dll tools/make-logo-assets.php
 */
if (PHP_SAPI !== 'cli') exit("Command line only.\n");

$SRC = __DIR__ . '/../assets/img/logo-source.png';
$OUT = __DIR__ . '/../assets/img/';

/* Tile colour behind the square app/favicon icons — the site's own primary
   navy, so the mark sits on a brand-matched ground rather than a stray one. */
const ICON_TILE = [0x0E, 0x2E, 0x4F]; // --rw-navy-900

/* ---------------------------------------------------------------- helpers */
function bbox($im, int $lim = 100): array {
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

/* Wordmark ink classifier: the "RIGHT" / "RCM" ink is a navy-blue ramp, "WAY"
   is teal, the underlines (and the icon's cross, outside this function's
   reach) are red. Only the navy ramp gets brightened for the light variant —
   teal and red already read fine on the dark footer. */
function isRedInk(int $r, int $g, int $b): bool {
    return $r > 150 && $r > $g + 100 && $r > $b + 100;
}
function isTealInk(int $r, int $g, int $b): bool {
    return $r < 60 && $g > 120 && $b > 120 && abs($g - $b) < 30;
}
function lightenWordmark($im) {
    $w = imagesx($im); $h = imagesy($im);
    $d = imagecreatetruecolor($w, $h);
    imagealphablending($d, false); imagesavealpha($d, true);
    for ($y = 0; $y < $h; $y++) for ($x = 0; $x < $w; $x++) {
        $c = imagecolorat($im, $x, $y);
        $a = ($c >> 24) & 0x7F; $r = ($c >> 16) & 0xFF; $g = ($c >> 8) & 0xFF; $b = $c & 0xFF;
        if ($a < 125 && !isRedInk($r, $g, $b) && !isTealInk($r, $g, $b)) {
            $lum = 0.299 * $r + 0.587 * $g + 0.114 * $b;
            $t   = max(0, min(1, ($lum - 30) / 60));
            $v   = 205 + 50 * $t;
            $r = (int) round($v); $g = (int) round($v * 0.99); $b = (int) round($v * 0.95);
        }
        imagesetpixel($d, $x, $y, imagecolorallocatealpha($d, $r, $g, $b, $a));
    }
    return $d;
}

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

/* ------------------------------------------------------------------ build */
$src = imagecreatefrompng($SRC);
[$bx, $by, $bw, $bh] = bbox($src);
$trim = crop($src, $bx, $by, $bw, $bh);
echo "trimmed lock-up: {$bw}x{$bh}\n";

/* Find the gap between the icon and the wordmark. */
$rows = rowInk($trim);
$from = (int) ($bh * 0.55); $to = (int) ($bh * 0.85);
$split = null; $bestInk = PHP_INT_MAX;
for ($y = $from; $y <= $to; $y++) {
    if ($rows[$y] < $bestInk) { $bestInk = $rows[$y]; $split = $y; }
}
echo "split row: $split (ink $bestInk)\n";

$iconPart = crop($trim, 0, 0, $bw, $split);
$wordPart = crop($trim, 0, $split, $bw, $bh - $split);
[$ix, $iy, $iw, $ih] = bbox($iconPart);
[$wx, $wy, $ww, $wh] = bbox($wordPart);
$icon = crop($iconPart, $ix, $iy, $iw, $ih);
$word = crop($wordPart, $wx, $wy, $ww, $wh);
printf("icon %dx%d   wordmark %dx%d\n", $iw, $ih, $ww, $wh);

/* Stacked lock-up (header / footer / mobile drawer / schema logo). */
imagepng(resizeTo($trim, 720), $OUT . 'logo.png', 9);

// Composite: icon rows untouched, wordmark rows brightened.
$stackLight = crop($trim, 0, 0, $bw, $bh);
imagecopy($stackLight, $iconPart, 0, 0, 0, 0, $bw, $split);
$wordLight = lightenWordmark($wordPart);
imagecopy($stackLight, $wordLight, 0, $split, 0, 0, $bw, $bh - $split);
imagesavealpha($stackLight, true);
imagepng(resizeTo($stackLight, 720), $OUT . 'logo-light.png', 9);

/* Icon-only mark, full colour (used as-is for favicons/app icons). */
imagepng(resizeTo($icon, 512), $OUT . 'logo-mark.png', 9);

/* Horizontal lock-up: icon left, wordmark right. */
$horizDark  = compose($icon, $word, $iw, $ih, $ww, $wh);
$horizLight = compose($icon, lightenWordmark($word), $iw, $ih, $ww, $wh);
imagepng(resizeTo($horizDark,  null, 240), $OUT . 'logo-horizontal.png', 9);
imagepng(resizeTo($horizLight, null, 240), $OUT . 'logo-horizontal-light.png', 9);
printf("horizontal lock-up: %dx%d (ratio %.2f:1)\n",
    imagesx($horizDark), imagesy($horizDark), imagesx($horizDark) / imagesy($horizDark));

/* Favicons + app icons, tiled on the brand navy. */
foreach ([512 => 'icon-512.png', 192 => 'icon-192.png', 180 => 'apple-touch-icon.png',
          32 => 'favicon-32.png', 16 => 'favicon-16.png'] as $size => $name) {
    icon_square($icon, $size, $OUT . $name, ICON_TILE);
}

/* PNG-in-ICO container for legacy /favicon.ico requests. */
$png = file_get_contents($OUT . 'favicon-32.png');
file_put_contents($OUT . 'favicon.ico',
    pack('vvv', 0, 1, 1) . pack('CCCCvvVV', 32, 32, 0, 0, 1, 32, strlen($png), 22) . $png);

echo "all logo assets rebuilt from assets/img/logo-source.png\n";
