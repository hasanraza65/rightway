<?php
/**
 * Generates assets/img/og-default.jpg — the 1200x630 card shown when a page is
 * shared on social media or in a chat app.
 *
 *   php tools/make-og-image.php
 */
if (PHP_SAPI !== 'cli') exit("Command line only.\n");

require __DIR__ . '/../includes/config.php';

$W = 1200; $H = 630;
$im = imagecreatetruecolor($W, $H);
imageantialias($im, true);

$green950 = imagecolorallocate($im, 0x07, 0x1B, 0x2E);   // navy-950
$green900 = imagecolorallocate($im, 0x0E, 0x2E, 0x4F);   // navy-900
$gold     = imagecolorallocate($im, 0x17, 0xA2, 0xA0);   // teal-500
$white    = imagecolorallocate($im, 0xFF, 0xFF, 0xFF);
$mist     = imagecolorallocate($im, 0xC4, 0xD2, 0xDF);   // gray-300, blue-tinted

// Vertical navy gradient background.
for ($y = 0; $y < $H; $y++) {
    $t = $y / $H;
    $c = imagecolorallocate(
        $im,
        (int) round(0x0E - 0x07 * $t),
        (int) round(0x2E - 0x13 * $t),
        (int) round(0x4F - 0x21 * $t)
    );
    imageline($im, 0, $y, $W, $y, $c);
}

/* Teal glow in the top right. Blended per pixel with a smooth falloff —
   stacking translucent ellipses accumulates alpha and produces a hard blob. */
$gx = 1120; $gy = 60; $gr = 430;
for ($y = 0; $y < $H; $y++) {
    for ($x = 0; $x < $W; $x++) {
        $d = sqrt(($x - $gx) ** 2 + ($y - $gy) ** 2);
        if ($d >= $gr) continue;
        $t = 1 - $d / $gr;
        $t = $t * $t * 0.5;                       // ease the falloff, cap the peak
        $rgb = imagecolorat($im, $x, $y);
        $r = (($rgb >> 16) & 0xFF) + (0x17 - (($rgb >> 16) & 0xFF)) * $t;
        $g = (($rgb >> 8)  & 0xFF) + (0xA2 - (($rgb >> 8)  & 0xFF)) * $t;
        $b = ($rgb & 0xFF)         + (0xA0 - ($rgb & 0xFF))         * $t;
        imagesetpixel($im, $x, $y, imagecolorallocate($im, (int) $r, (int) $g, (int) $b));
    }
}

// Faint grid, same texture as the hero.
$grid = imagecolorallocatealpha($im, 0xFF, 0xFF, 0xFF, 124);
for ($x = 0; $x < $W; $x += 56) imageline($im, $x, 0, $x, $H, $grid);
for ($y = 0; $y < $H; $y += 56) imageline($im, 0, $y, $W, $y, $grid);

// Logo lock-up (ivory variant, since the background is dark).
$logo = imagecreatefrompng(__DIR__ . '/../assets/img/logo-light.png');
$lw   = 244;
$lh   = (int) round(imagesy($logo) * $lw / imagesx($logo));
imagecopyresampled($im, $logo, 74, 56, 0, 0, $lw, $lh, imagesx($logo), imagesy($logo));

/**
 * GD only ships a bitmap font, which looks poor at headline size. Draw the
 * headline with a TrueType face if one is available on the machine, and fall
 * back to a scaled bitmap font otherwise.
 */
function rw_font(): ?string {
    foreach ([
        'C:/Windows/Fonts/segoeuib.ttf',
        'C:/Windows/Fonts/arialbd.ttf',
        '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
        '/System/Library/Fonts/Supplemental/Arial Bold.ttf',
    ] as $f) {
        if (is_file($f)) return $f;
    }
    return null;
}

$font = rw_font();
$lines = ['Billing done the', 'Right Way.'];
$sub   = 'Medical billing, coding, credentialing and denial recovery';
$sub2  = 'for physician practices across the United States.';

// Gold rule separating the lock-up from the headline.
imagefilledrectangle($im, 74, 262, 74 + 96, 262 + 5, $gold);

if ($font) {
    $y = 356;
    foreach ($lines as $i => $line) {
        imagettftext($im, 58, 0, 74, $y, $i === 1 ? $gold : $white, $font, $line);
        $y += 78;
    }
    imagettftext($im, 22, 0, 74, 512, $mist, $font, $sub);
    imagettftext($im, 22, 0, 74, 546, $mist, $font, $sub2);
    imagettftext($im, 20, 0, 74, 592, $gold, $font, BIZ_PHONE . '   ·   rightwaymedicalbilling.com');
} else {
    imagestring($im, 5, 74, 330, $lines[0], $white);
    imagestring($im, 5, 74, 366, $lines[1], $gold);
    imagestring($im, 3, 74, 500, $sub, $mist);
    imagestring($im, 3, 74, 522, $sub2, $mist);
}

imagejpeg($im, __DIR__ . '/../assets/img/og-default.jpg', 88);
echo "wrote assets/img/og-default.jpg (" . $W . "x" . $H . ")"
   . ($font ? " using " . basename($font) : " using the bitmap fallback font") . "\n";
