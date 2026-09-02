<?php
/**
 * Generates the on-brand SVG placeholders used when a Pexels image is missing.
 * These ship with the repo so the site can never render a broken image.
 *
 *   php tools/make-fallbacks.php
 */

if (PHP_SAPI !== 'cli') exit("Command line only.\n");

$out = dirname(__DIR__) . '/assets/img';

$GREEN_900 = '#0E2E4F';   // navy-900
$GREEN_950 = '#071B2E';   // navy-950
$GOLD_500  = '#17A2A0';   // teal-500

/** Bootstrap Icons path data, so the placeholders match the site iconography. */
$glyphs = [
    'general'  => 'M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0M4.5 7.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1',
    'team'     => 'M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4',
    'office'   => 'M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5',
    'clinic'   => 'M8.5 5.5a.5.5 0 0 0-1 0v1h-1a.5.5 0 0 0 0 1h1v1a.5.5 0 0 0 1 0v-1h1a.5.5 0 0 0 0-1h-1zM4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z',
    'security' => 'M5.338 1.59a61 61 0 0 0-2.837.856.48.48 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.7 10.7 0 0 0 2.287 2.233c.346.244.652.42.893.533q.18.085.293.118a1 1 0 0 0 .291-.118c.24-.113.547-.29.893-.533a10.7 10.7 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524',
    'portrait' => 'M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8 7c-1.996 0-3.783-.877-5-2.266.212-.68.66-1.279 1.28-1.707C5.23 10.507 6.581 10 8 10s2.77.507 3.72 1.027c.62.428 1.068 1.026 1.28 1.707A6.98 6.98 0 0 1 8 15',
];

foreach ($glyphs as $name => $path) {
    // 3:2 canvas matching the landscape renditions, so swapping in a real photo
    // does not change the layout.
    $svg = <<<SVG
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 800" width="1200" height="800" role="img" aria-label="Right Way Medical Billing">
      <defs>
        <linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
          <stop offset="0" stop-color="$GREEN_900"/>
          <stop offset="1" stop-color="$GREEN_950"/>
        </linearGradient>
        <pattern id="p" width="56" height="56" patternUnits="userSpaceOnUse">
          <path d="M56 0H0v56" fill="none" stroke="#FFFFFF" stroke-opacity=".05" stroke-width="1.5"/>
        </pattern>
      </defs>
      <rect width="1200" height="800" fill="url(#g)"/>
      <rect width="1200" height="800" fill="url(#p)"/>
      <circle cx="1010" cy="150" r="190" fill="$GOLD_500" opacity=".08"/>
      <g transform="translate(538 318) scale(8.5)" fill="$GOLD_500" opacity=".9">
        <path d="$path"/>
      </g>
      <rect x="538" y="500" width="124" height="4" rx="2" fill="$GOLD_500"/>
    </svg>
    SVG;

    // Strip the heredoc indentation so the file is clean.
    $svg = preg_replace('/^ {4}/m', '', $svg);
    file_put_contents($out . '/fallback-' . $name . '.svg', $svg);
    echo "wrote fallback-$name.svg\n";
}
