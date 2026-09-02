<?php
/**
 * Image layer.
 *
 * Every photograph on the site is addressed by a stable key (e.g. 'home-hero').
 * Keys resolve in this order:
 *
 *   1. includes/data/images.php  — the build manifest, pointing at a locally
 *      cached copy in assets/img/pexels-cache. This is the normal path: no
 *      network call, correctly sized files, no layout shift.
 *   2. A live Pexels lookup through rw_pexels_search(), which runs server-side
 *      only. The API key never reaches the browser.
 *   3. A curated on-brand local fallback, so a slow or unreachable Pexels can
 *      never produce a broken image.
 *
 * Populate the manifest with:  php tools/fetch-images.php
 */

/** The search term + alt text behind every image key on the site. */
function rw_image_map(): array {
    static $m = null;
    return $m ??= require dirname(__DIR__) . '/data/image-map.php';
}

/** The build manifest of already-downloaded photos, if it exists yet. */
function rw_image_manifest(): array {
    static $m = null;
    if ($m === null) {
        $f = dirname(__DIR__) . '/data/images.php';
        $m = is_file($f) ? (require $f) : [];
    }
    return $m;
}

/** On-brand SVG placeholders shipped with the repo; never network-dependent. */
function rw_image_fallback(string $key): array {
    $map   = rw_image_map();
    $group = $map[$key]['fallback'] ?? 'general';
    return [
        'src'    => rw_url('assets/img/fallback-' . $group . '.svg'),
        'srcset' => '',
        'width'  => 1200,
        'height' => 800,
        'alt'    => $map[$key]['alt'] ?? BIZ_NAME,
        'credit' => null,
    ];
}

/**
 * Resolve an image key to everything a template needs to render it.
 * Never throws and never returns an empty src.
 */
function rw_image(string $key): array {
    $map      = rw_image_map();
    $manifest = rw_image_manifest();

    if (isset($manifest[$key]['file'])) {
        $rel  = 'assets/img/pexels-cache/' . $manifest[$key]['file'];
        if (is_file(rw_root() . '/' . $rel)) {
            $m = $manifest[$key];
            $set = [];
            foreach (($m['srcset'] ?? []) as $w => $file) {
                $set[] = rw_url('assets/img/pexels-cache/' . $file) . ' ' . $w . 'w';
            }
            return [
                'src'    => rw_url($rel),
                'srcset' => implode(', ', $set),
                'width'  => $m['width']  ?? 1200,
                'height' => $m['height'] ?? 800,
                'alt'    => $map[$key]['alt'] ?? $m['alt'] ?? BIZ_NAME,
                'credit' => $m['photographer'] ?? null,
                'credit_url' => $m['photographer_url'] ?? null,
            ];
        }
    }
    return rw_image_fallback($key);
}

/**
 * Render a complete <img>. Always lazy unless it is above the fold, always
 * dimensioned so nothing shifts, always with real alt text.
 *
 * @param array $o  class, sizes, eager (bool), figclass, ratio
 */
function rw_img(string $key, array $o = []): void {
    $i     = rw_image($key);
    $eager = !empty($o['eager']);
    $attrs = [
        'src'     => $i['src'],
        'alt'     => $o['alt'] ?? $i['alt'],
        'width'   => $i['width'],
        'height'  => $i['height'],
        'loading' => $eager ? 'eager' : 'lazy',
        'decoding'=> $eager ? 'sync'  : 'async',
        'class'   => trim('rw-img ' . ($o['class'] ?? '')),
    ];
    if ($eager)  $attrs['fetchpriority'] = 'high';
    if ($i['srcset']) {
        $attrs['srcset'] = $i['srcset'];
        $attrs['sizes']  = $o['sizes'] ?? '(max-width: 991px) 100vw, 50vw';
    }
    $html = '<img';
    foreach ($attrs as $k => $v) {
        $html .= ' ' . $k . '="' . e((string) $v) . '"';
    }
    echo $html . '>';
    if (!empty($i['credit'])) {
        echo "\n<!-- Photo: " . e($i['credit']) . ' via Pexels -->';
    }
}

/* ------------------------------------------------------------------ pexels */

/** Where a cached Pexels JSON response for a query lives. */
function rw_pexels_cache_path(string $query, string $orientation): string {
    return rtrim(PEXELS_CACHE_DIR, '/') . '/' . md5($query . '|' . $orientation) . '.json';
}

/**
 * Server-side Pexels search with a simple file cache.
 * Returns a trimmed array of photos, or [] on any failure — callers must cope.
 */
function rw_pexels_search(string $query, string $orientation = 'landscape', int $perPage = 6): array {
    if (!PEXELS_API_KEY) return [];

    $cacheFile = rw_pexels_cache_path($query, $orientation);
    if (is_file($cacheFile) && (time() - filemtime($cacheFile)) < PEXELS_CACHE_TTL) {
        $cached = json_decode((string) file_get_contents($cacheFile), true);
        if (is_array($cached)) return $cached;
    }

    $url = 'https://api.pexels.com/v1/search?' . http_build_query([
        'query'       => $query,
        'orientation' => $orientation,
        'per_page'    => $perPage,
        'size'        => 'medium',
    ]);

    $body = null;
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ['Authorization: ' . PEXELS_API_KEY],
            CURLOPT_TIMEOUT        => 12,
            CURLOPT_CONNECTTIMEOUT => 6,
        ]);
        $body = curl_exec($ch);
        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) $body = null;
        curl_close($ch);
    }
    if ($body === null) return rw_pexels_stale($cacheFile);

    $json = json_decode((string) $body, true);
    if (!isset($json['photos'])) return rw_pexels_stale($cacheFile);

    // Keep only the fields the front end needs — never expose the raw payload.
    $out = [];
    foreach ($json['photos'] as $p) {
        $out[] = [
            'id'               => $p['id'],
            'alt'              => $p['alt'] ?: '',
            'width'            => $p['width'],
            'height'           => $p['height'],
            'large'            => $p['src']['large']  ?? '',
            'large2x'          => $p['src']['large2x'] ?? '',
            'medium'           => $p['src']['medium'] ?? '',
            'photographer'     => $p['photographer'] ?? '',
            'photographer_url' => $p['photographer_url'] ?? '',
        ];
    }

    if (!is_dir(PEXELS_CACHE_DIR)) @mkdir(PEXELS_CACHE_DIR, 0775, true);
    @file_put_contents($cacheFile, json_encode($out));
    return $out;
}

/** Serve an expired cache entry rather than nothing when Pexels is unreachable. */
function rw_pexels_stale(string $cacheFile): array {
    if (is_file($cacheFile)) {
        $cached = json_decode((string) file_get_contents($cacheFile), true);
        if (is_array($cached)) return $cached;
    }
    return [];
}

/**
 * Fetch one specific Pexels photo by id.
 *
 * Search results drift over time, so an image chosen by eye during design work
 * can silently become a different picture on the next build. Pinning the id in
 * includes/data/image-map.php makes that choice reproducible.
 */
function rw_pexels_photo(int $id): ?array {
    if (!PEXELS_API_KEY || $id <= 0) return null;

    $cacheFile = rtrim(PEXELS_CACHE_DIR, '/') . '/photo-' . $id . '.json';
    if (is_file($cacheFile) && (time() - filemtime($cacheFile)) < PEXELS_CACHE_TTL) {
        $cached = json_decode((string) file_get_contents($cacheFile), true);
        if (is_array($cached)) return $cached;
    }
    if (!function_exists('curl_init')) return null;

    $ch = curl_init('https://api.pexels.com/v1/photos/' . $id);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => ['Authorization: ' . PEXELS_API_KEY],
        CURLOPT_TIMEOUT        => 12,
        CURLOPT_CONNECTTIMEOUT => 6,
    ]);
    $body = curl_exec($ch);
    $ok   = curl_getinfo($ch, CURLINFO_HTTP_CODE) === 200;
    curl_close($ch);
    if (!$ok) return null;

    $p = json_decode((string) $body, true);
    if (!isset($p['id'])) return null;

    $out = [
        'id'               => $p['id'],
        'alt'              => $p['alt'] ?: '',
        'width'            => $p['width'],
        'height'           => $p['height'],
        'large'            => $p['src']['large']   ?? '',
        'large2x'          => $p['src']['large2x'] ?? '',
        'medium'           => $p['src']['medium']  ?? '',
        'photographer'     => $p['photographer'] ?? '',
        'photographer_url' => $p['photographer_url'] ?? '',
    ];
    if (!is_dir(PEXELS_CACHE_DIR)) @mkdir(PEXELS_CACHE_DIR, 0775, true);
    @file_put_contents($cacheFile, json_encode($out));
    return $out;
}
