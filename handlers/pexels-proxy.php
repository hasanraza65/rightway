<?php
/**
 * Server-side Pexels proxy.
 *
 * The API key lives in includes/config.php and is used only here, on the
 * server. Nothing in the browser ever sees it — inspect the page source or the
 * network tab and you will find requests to this file, never to Pexels with an
 * Authorization header.
 *
 * Responses are cached on disk (see PEXELS_CACHE_TTL) and trimmed to the few
 * fields a front end actually needs.
 *
 *   GET handlers/pexels-proxy.php?query=dental+clinic&orientation=landscape&per_page=4
 */
require __DIR__ . '/../includes/config.php';

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: public, max-age=3600');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

/* Same-origin only, so the endpoint cannot be used as a public image search. */
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin && rtrim($origin, '/') !== rtrim(SITE_URL, '/')) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Cross-origin requests are not permitted']);
    exit;
}

$query = trim((string) ($_GET['query'] ?? ''));
if ($query === '' || mb_strlen($query) > 80) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'A query between 1 and 80 characters is required']);
    exit;
}

$orientation = $_GET['orientation'] ?? 'landscape';
if (!in_array($orientation, ['landscape', 'portrait', 'square'], true)) {
    $orientation = 'landscape';
}

$perPage = (int) ($_GET['per_page'] ?? 6);
$perPage = max(1, min($perPage, 12));

$photos = rw_pexels_search($query, $orientation, $perPage);

if (!$photos) {
    // Never leave the caller without something renderable.
    echo json_encode([
        'ok'       => false,
        'error'    => 'No results available right now',
        'fallback' => rw_url('assets/img/fallback-general.svg'),
    ]);
    exit;
}

/* Return only what a template needs: a source, dimensions, alt and credit. */
$out = array_map(fn($p) => [
    'src'          => $p['large'],
    'src2x'        => $p['large2x'],
    'thumb'        => $p['medium'],
    'width'        => $p['width'],
    'height'       => $p['height'],
    'alt'          => $p['alt'] ?: $query,
    'credit'       => $p['photographer'],
    'credit_url'   => $p['photographer_url'],
], $photos);

echo json_encode([
    'ok'     => true,
    'query'  => $query,
    'count'  => count($out),
    'photos' => $out,
], JSON_UNESCAPED_SLASHES);
