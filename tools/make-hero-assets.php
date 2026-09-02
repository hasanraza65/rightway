<?php
/**
 * Prepares the two client-supplied hero images.
 *
 *   doctor cut.png  -> assets/img/hero-doctor.png      (transparent cut-out)
 *   background.png  -> assets/img/hero-backdrop.jpg    (pre-blurred backdrop)
 *
 * The backdrop is blurred at build time rather than with a CSS filter: a
 * runtime blur costs a repaint on every scroll/resize and bleeds transparent
 * edges unless the element is oversized to compensate. Baking it also lets us
 * pull the source's strong green fixtures toward the brand palette, which a CSS
 * filter cannot do selectively.
 *
 *   php tools/make-hero-assets.php
 */
if (PHP_SAPI !== 'cli') exit("Command line only.\n");

$SRC_DIR = 'f:/BHAI LOG/More Projects/rightway medical billing/';
$OUT     = __DIR__ . '/../assets/img/';

/* ------------------------------------------------------------- cut-out --- */
$cut = imagecreatefrompng($SRC_DIR . 'doctor cut.png');
if (!$cut) exit("Cannot read doctor cut.png\n");
imagealphablending($cut, false);
imagesavealpha($cut, true);

/* Trim fully transparent margins so the figure sits flush in its container and
   we are not paying for empty pixels. */
$w = imagesx($cut); $h = imagesy($cut);
$x0 = $w; $y0 = $h; $x1 = -1; $y1 = -1;
for ($y = 0; $y < $h; $y++) for ($x = 0; $x < $w; $x++) {
    if (((imagecolorat($cut, $x, $y) >> 24) & 0x7F) < 120) {
        if ($x < $x0) $x0 = $x; if ($x > $x1) $x1 = $x;
        if ($y < $y0) $y0 = $y; if ($y > $y1) $y1 = $y;
    }
}
$cw = $x1 - $x0 + 1; $ch = $y1 - $y0 + 1;
$trim = imagecreatetruecolor($cw, $ch);
imagealphablending($trim, false); imagesavealpha($trim, true);
imagefilledrectangle($trim, 0, 0, $cw, $ch, imagecolorallocatealpha($trim, 0, 0, 0, 127));
imagecopy($trim, $cut, 0, 0, $x0, $y0, $cw, $ch);
imagepng($trim, $OUT . 'hero-doctor.png', 9);
printf("hero-doctor.png  %dx%d (trimmed from %dx%d)  %.0f KB\n",
    $cw, $ch, $w, $h, filesize($OUT . 'hero-doctor.png') / 1024);

/* ------------------------------------------------------------ backdrop --- */
$bg = imagecreatefrompng($SRC_DIR . 'background.png');
if (!$bg) exit("Cannot read background.png\n");
$bw = imagesx($bg); $bh = imagesy($bg);

/* Blur via a resampling pyramid. Going straight from a tiny thumbnail to full
   size leaves visible square blocks, because GD's gaussian kernel is only 3x3
   and cannot smooth 30px steps. Stepping up gradually, with gaussian passes at
   each level, keeps the kernel large *relative to the current size* and yields
   a genuinely smooth result. */
$OUT_W = 1000; $OUT_H = 1000;
$steps = [96, 220, 480, $OUT_W];

$stage = imagecreatetruecolor($steps[0], $steps[0]);
imagecopyresampled($stage, $bg, 0, 0, 0, 0, $steps[0], $steps[0], $bw, $bh);
for ($i = 0; $i < 5; $i++) imagefilter($stage, IMG_FILTER_GAUSSIAN_BLUR);

for ($s = 1; $s < count($steps); $s++) {
    $next = imagecreatetruecolor($steps[$s], $steps[$s]);
    imagecopyresampled($next, $stage, 0, 0, 0, 0, $steps[$s], $steps[$s],
        imagesx($stage), imagesy($stage));
    for ($i = 0; $i < 4; $i++) imagefilter($next, IMG_FILTER_GAUSSIAN_BLUR);
    $stage = $next;
}
$blur = $stage;

/* Pull the source's saturated green fixtures toward the brand. Fully
   desaturating would leave a flat grey, so this only damps the green channel's
   excess and nudges the result toward teal. */
for ($y = 0; $y < $OUT_H; $y++) {
    for ($x = 0; $x < $OUT_W; $x++) {
        $c = imagecolorat($blur, $x, $y);
        $r = ($c >> 16) & 0xFF; $g = ($c >> 8) & 0xFF; $b = $c & 0xFF;
        $grey = (int) round(0.299 * $r + 0.587 * $g + 0.114 * $b);
        // 55% toward greyscale, then a slight cool bias.
        $r = (int) round($r * 0.45 + $grey * 0.55 * 0.94);
        $g = (int) round($g * 0.45 + $grey * 0.55 * 1.00);
        $b = (int) round($b * 0.45 + $grey * 0.55 * 1.10);
        imagesetpixel($blur, $x, $y, imagecolorallocate($blur,
            min(255, $r), min(255, $g), min(255, $b)));
    }
}
imagefilter($blur, IMG_FILTER_CONTRAST, -22);   // negative = more contrast in GD
imagejpeg($blur, $OUT . 'hero-backdrop.jpg', 84);
printf("hero-backdrop.jpg %dx%d  %.0f KB (source %dx%d)\n",
    $OUT_W, $OUT_H, filesize($OUT . 'hero-backdrop.jpg') / 1024, $bw, $bh);

echo "done\n";
