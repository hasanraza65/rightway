<?php
/**
 * Shared helpers. Loaded by config.php, so every page has these available.
 */

/** Escape for HTML output. Short name because it is used constantly in templates. */
function e(?string $v): string {
    return htmlspecialchars((string) $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Absolute filesystem path to the site root. */
function rw_root(): string {
    return dirname(__DIR__);
}

/**
 * Root-relative URL for an internal path, so the site works from a sub-folder.
 * rw_url('services/medical-coding-services.php') => '/site/services/medical-coding-services.php'
 */
function rw_url(string $path = ''): string {
    static $base = null;
    if ($base === null) {
        $base = rtrim(parse_url(SITE_URL, PHP_URL_PATH) ?? '', '/');
    }
    return $base . '/' . ltrim($path, '/');
}

/** Fully-qualified URL, used for canonicals, OG tags, schema and the sitemap. */
function rw_abs(string $path = ''): string {
    return rtrim(SITE_URL, '/') . '/' . ltrim($path, '/');
}

/** Versioned asset URL so CSS/JS changes bust the browser cache. */
function rw_asset(string $path): string {
    $file = rw_root() . '/' . ltrim($path, '/');
    $v    = is_file($file) ? substr((string) filemtime($file), -6) : '1';
    return rw_url($path) . '?v=' . $v;
}

/** True when $path is the page currently being viewed (used for nav highlighting). */
function rw_is_current(string $path): bool {
    $here = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '', '/');
    $here = preg_replace('#^' . preg_quote(trim(rw_url(), '/'), '#') . '/?#', '', $here);
    $here = ($here === '' || $here === 'index.php') ? '' : $here;
    $target = trim($path, '/');
    $target = ($target === '' || $target === 'index.php') ? '' : $target;
    return $target === $here;
}

/** Data accessors. Both data files return keyed arrays of full page content. */
function rw_services(): array {
    static $d = null;
    return $d ??= require __DIR__ . '/data/services.php';
}
function rw_specialties(): array {
    static $d = null;
    return $d ??= require __DIR__ . '/data/specialties.php';
}
function rw_service(string $slug): ?array {
    return rw_services()[$slug] ?? null;
}
function rw_specialty(string $slug): ?array {
    return rw_specialties()[$slug] ?? null;
}

/** Pull a subset of services/specialties by slug, preserving the order given. */
function rw_pick(array $all, array $slugs): array {
    $out = [];
    foreach ($slugs as $s) {
        if (isset($all[$s])) $out[$s] = $all[$s];
    }
    return $out;
}

/**
 * Render a partial from includes/partials with $data available as local vars.
 * Keeps components genuinely reusable instead of copy-pasted markup.
 */
function rw_partial(string $name, array $data = []): void {
    $file = __DIR__ . '/partials/' . $name . '.php';
    if (!is_file($file)) {
        trigger_error("Missing partial: $name", E_USER_WARNING);
        return;
    }
    extract($data, EXTR_SKIP);
    include $file;
}

/** Phone number with all non-dialable characters stripped, for tel: links. */
function rw_tel(): string {
    return preg_replace('/[^0-9+]/', '', BIZ_PHONE_TEL);
}

/** Years the business has been operating, kept accurate without editing copy. */
function rw_years(): int {
    return max(1, (int) date('Y') - BIZ_FOUNDED);
}

/** Deterministic pseudo-random pick so a page's decorative choices stay stable. */
function rw_seeded(string $seed, array $options) {
    return $options[crc32($seed) % count($options)];
}

require_once __DIR__ . '/lib/images.php';

/* ------------------------------------------------------------- security */

/**
 * Start a session with hardened cookie settings, once per request.
 *
 * Sessions carry the CSRF tokens, so a broken session means every form
 * submission is rejected. Rather than trust whatever session.save_path the host
 * happens to have configured, fall back to a directory we own if it is missing
 * or unwritable.
 */
function rw_session(): void {
    if (session_status() !== PHP_SESSION_NONE) return;

    $savePath = session_save_path();
    if ($savePath === '' || !is_dir($savePath) || !is_writable($savePath)) {
        $own = rw_root() . '/cache/sessions';
        if (is_dir($own) || @mkdir($own, 0700, true)) {
            if (!is_file($own . '/.htaccess')) {
                @file_put_contents($own . '/.htaccess', "Require all denied\n");
            }
            session_save_path($own);
        }
    }

    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
          || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => rw_url(),
        'httponly' => true,
        'secure'   => $https,
        'samesite' => 'Lax',
    ]);
    session_name('rwsess');
    @session_start();
}

/** CSRF token for the current session, generated on first use. */
function rw_csrf_token(): string {
    rw_session();
    if (empty($_SESSION['rw_csrf'])) {
        $_SESSION['rw_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['rw_csrf'];
}

/** Constant-time CSRF check. */
function rw_csrf_valid(?string $token): bool {
    rw_session();
    return !empty($_SESSION['rw_csrf'])
        && is_string($token)
        && hash_equals($_SESSION['rw_csrf'], $token);
}

/**
 * Intrinsic pixel dimensions of a local asset, for correct width/height
 * attributes on <img>.
 *
 * Hardcoding these means a swapped image file silently leaves the markup
 * declaring the old aspect ratio, which reserves the wrong box and shifts the
 * layout as the real image loads. Reading them from the file keeps the two in
 * step no matter which file the markup points at.
 */
function rw_img_dims(string $relPath): array {
    static $cache = [];
    if (!array_key_exists($relPath, $cache)) {
        $file = rw_root() . '/' . ltrim($relPath, '/');
        $size = is_file($file) ? @getimagesize($file) : false;
        $cache[$relPath] = $size ? [(int) $size[0], (int) $size[1]] : [0, 0];
    }
    return $cache[$relPath];
}
