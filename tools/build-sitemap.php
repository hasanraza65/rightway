<?php
/**
 * Generates sitemap.xml and robots.txt from the actual page catalogue, so the
 * sitemap cannot drift out of sync with the site.
 *
 *   php tools/build-sitemap.php
 *
 * Re-run this whenever a page is added or removed.
 */
if (PHP_SAPI !== 'cli') exit("Command line only.\n");

require __DIR__ . '/../includes/config.php';

$root = dirname(__DIR__);
$base = rtrim(SITE_URL_PROD, '/');
$today = date('Y-m-d');

/** [path, priority, changefreq] */
$pages = [
    ['',                    '1.0', 'weekly'],
    ['about-us.php',        '0.8', 'monthly'],
    ['services.php',        '0.9', 'weekly'],
    ['specialties.php',     '0.9', 'weekly'],
    ['contact-us.php',      '0.8', 'monthly'],
];

foreach (rw_services() as $slug => $s) {
    $pages[] = ['services/' . $slug . '.php', '0.8', 'monthly'];
}
foreach (rw_specialties() as $slug => $sp) {
    $pages[] = ['specialties/' . $slug . '.php', '0.7', 'monthly'];
}

$pages[] = ['credits.php',         '0.2', 'yearly'];
$pages[] = ['privacy-policy.php',  '0.3', 'yearly'];
$pages[] = ['terms-of-service.php','0.3', 'yearly'];

/* thank-you.php and 404.php are deliberately excluded: both are noindex. */

$missing = [];
$xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$xml .= '<urlset xmlns="http://www.sitemap' . 's.org/schemas/sitemap/0.9">' . "\n";
foreach ($pages as [$path, $priority, $freq]) {
    $file = $root . '/' . ($path === '' ? 'index.php' : $path);
    if (!is_file($file)) { $missing[] = $path; continue; }
    $lastmod = date('Y-m-d', filemtime($file));
    $xml .= "  <url>\n";
    $xml .= '    <loc>' . htmlspecialchars($base . '/' . $path, ENT_XML1) . "</loc>\n";
    $xml .= '    <lastmod>' . $lastmod . "</lastmod>\n";
    $xml .= '    <changefreq>' . $freq . "</changefreq>\n";
    $xml .= '    <priority>' . $priority . "</priority>\n";
    $xml .= "  </url>\n";
}
$xml .= "</urlset>\n";

file_put_contents($root . '/sitemap.xml', $xml);

$robots = implode("\n", [
    '# robots.txt for ' . BIZ_NAME,
    'User-agent: *',
    'Allow: /',
    '',
    '# Nothing useful for a crawler, and some of it is sensitive.',
    'Disallow: /handlers/',
    'Disallow: /includes/',
    'Disallow: /tools/',
    'Disallow: /cache/',
    'Disallow: /thank-you.php',
    'Disallow: /404.php',
    '',
    '# Be polite to aggressive crawlers rather than blocking them outright.',
    'User-agent: AhrefsBot',
    'Crawl-delay: 10',
    '',
    'User-agent: SemrushBot',
    'Crawl-delay: 10',
    '',
    'Sitemap: ' . $base . '/sitemap.xml',
    '',
]);
file_put_contents($root . '/robots.txt', $robots);

echo 'sitemap.xml: ' . (count($pages) - count($missing)) . " URLs\n";
echo "robots.txt written\n";
if ($missing) {
    echo "WARNING — listed but not on disk:\n";
    foreach ($missing as $m) echo "  $m\n";
}
