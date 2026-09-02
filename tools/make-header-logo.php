<?php
/**
 * Builds a horizontal lock-up for the site header from the supplied artwork.
 *
 * The delivered logo is a stacked lock-up at roughly 1.55:1 (icon above the
 * wordmark). In a horizontal header bar that is the worst possible ratio: to be
 * wide enough to read, it becomes taller than the bar. This script splits the
 * artwork at the natural gap between the icon and the wordmark and re-composes
 * it side by side, giving roughly 3.4:1 — wide enough to read at a height that
 * leaves real clear space above and below.
 *
 * Nothing is redrawn or recolored; the two elements are only re-arranged. The
 * original stacked lock-up is kept for the footer, where height is not scarce.
 *
 *   php tools/make-header-logo.php
 */
if (PHP_SAPI !== 'cli') exit("Command line only.\n");

$SRC = 'f:/BHAI LOG/More Projects/rightway medical billing/transparent logo.png';
$OUT = __DIR__ . '/../assets/img/';

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
/** Opaque pixel count per row, used to find where the artwork naturally breaks. */
function rowInk($im): array {
    $w = imagesx($im); $h = imagesy($im); $rows = [];
    for ($y = 0; $y < $h; $y++) {
        $n = 0;
        for ($x = 0; $x < $w; $x++) if (((imagecolorat($im, $x, $y) >> 24) & 0x7F) < 100) $n++;
        $rows[$y] = $n;
    }
    return $rows;
}
function greenToIvory($im) {
    $w = imagesx($im); $h = imagesy($im);
    $d = imagecreatetruecolor($w, $h);
    imagealphablending($d, false); imagesavealpha($d, true);
    for ($y = 0; $y < $h; $y++) for ($x = 0; $x < $w; $x++) {
        $c = imagecolorat($im, $x, $y);
        $a = ($c >> 24) & 0x7F; $r = ($c >> 16) & 0xFF; $g = ($c >> 8) & 0xFF; $b = $c & 0xFF;
        if ($a < 120 && !($r > 110 && $r > $g + 15 && $g > $b + 25)) {
            $t = min(1, (0.299 * $r + 0.587 * $g + 0.114 * $b) / 90);
            $v = 158 + 97 * $t;
            $r = (int) round($v); $g = (int) round($v * 0.985); $b = (int) round($v * 0.925);
        }
        imagesetpixel($d, $x, $y, imagecolorallocatealpha($d, $r, $g, $b, $a));
    }
    return $d;
}

/* ------------------------------------------------------------------ build */
$clean = deglow(imagecreatefrompng($SRC));
[$bx, $by, $bw, $bh] = bbox($clean);
$trim = crop($clean, $bx, $by, $bw, $bh);
echo "trimmed lock-up: {$bw}x{$bh}\n";

/* Find the gap between the icon block (with its swoosh) and the wordmark.
   Search the middle band and take the quietest run of rows. */
$rows = rowInk($trim);
$from = (int) ($bh * 0.60);
$to   = (int) ($bh * 0.80);
$best = null; $bestInk = PHP_INT_MAX;
for ($y = $from; $y <= $to; $y++) {
    if ($rows[$y] < $bestInk) { $bestInk = $rows[$y]; $best = $y; }
}
echo "split row: $best (ink {$bestInk}px)\n";

$iconPart = crop($trim, 0, 0, $bw, $best);
$wordPart = crop($trim, 0, $best, $bw, $bh - $best);

[$ix, $iy, $iw, $ih] = bbox($iconPart);
[$wx, $wy, $ww, $wh] = bbox($wordPart);
$icon = crop($iconPart, $ix, $iy, $iw, $ih);
$word = crop($wordPart, $wx, $wy, $ww, $wh);
printf("icon %dx%d   wordmark %dx%d\n", $iw, $ih, $ww, $wh);

/* Compose side by side, both scaled to a common height, optically centered. */
$H    = 300;                                   // working height
$iconW = (int) round($iw * $H / $ih);
$wordH = (int) round($H * 0.78);               // wordmark reads smaller than the icon
$wordW = (int) round($ww * $wordH / $wh);
$gap   = (int) round($H * 0.10);
$W     = $iconW + $gap + $wordW;

$out = imagecreatetruecolor($W, $H);
imagealphablending($out, false); imagesavealpha($out, true);
imagefilledrectangle($out, 0, 0, $W, $H, imagecolorallocatealpha($out, 0, 0, 0, 127));
imagealphablending($out, true);
imagecopyresampled($out, $icon, 0, 0, 0, 0, $iconW, $H, $iw, $ih);
imagecopyresampled($out, $word, $iconW + $gap, (int) round(($H - $wordH) / 2), 0, 0, $wordW, $wordH, $ww, $wh);
imagesavealpha($out, true);

/* Export at 2x for crisp rendering on high-DPI screens. */
function exportPng($im, int $targetH, string $file) {
    $w = (int) round(imagesx($im) * $targetH / imagesy($im));
    $d = imagecreatetruecolor($w, $targetH);
    imagealphablending($d, false); imagesavealpha($d, true);
    imagefilledrectangle($d, 0, 0, $w, $targetH, imagecolorallocatealpha($d, 0, 0, 0, 127));
    imagecopyresampled($d, $im, 0, 0, 0, 0, $w, $targetH, imagesx($im), imagesy($im));
    imagesavealpha($d, true);
    imagepng($d, $file, 9);
    return [$w, $targetH];
}

[$fw, $fh] = exportPng($out, 240, $OUT . 'logo-horizontal.png');
exportPng(greenToIvory($out), 240, $OUT . 'logo-horizontal-light.png');

printf("horizontal lock-up: %dx%d  ratio %.2f:1\n", $fw, $fh, $fw / $fh);
printf("  at 54px tall -> %.0fpx wide\n", 54 * $fw / $fh);
echo "wrote logo-horizontal.png and logo-horizontal-light.png\n";
