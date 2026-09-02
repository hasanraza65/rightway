<?php
/**
 * Right Way Medical Billing — single source of truth for site configuration.
 *
 * Everything the site owner needs to change lives in this file. Values marked
 * TODO are placeholders and must be replaced with the real business details
 * before launch.
 */

/* ---------------------------------------------------------------- business */
define('BIZ_NAME',        'Right Way Medical Billing');
define('BIZ_LEGAL_NAME',  'Right Way Medical Billing LLC');           // TODO
define('BIZ_TAGLINE',     'Billing Done the Right Way');
define('BIZ_PHONE',       '+1 (555) 123-4567');                       // TODO
define('BIZ_PHONE_TEL',   '+15551234567');                            // TODO
define('BIZ_FAX',         '+1 (555) 123-4568');                       // TODO
define('BIZ_EMAIL',       'info@rightwayrcm.com');                    // TODO
define('BIZ_EMAIL_SALES', 'info@rightwayrcm.com');                    // TODO
define('BIZ_WHATSAPP',    '15551234567');                             // TODO digits only, incl. country code

define('BIZ_STREET',      '1001 S Main St, Ste 600');
define('BIZ_CITY',        'Kalispell');
define('BIZ_STATE',       'MT');
define('BIZ_ZIP',         '59901-1498');
define('BIZ_COUNTRY',     'US');
define('BIZ_ADDRESS',     BIZ_STREET . ', ' . BIZ_CITY . ', ' . BIZ_STATE . ' ' . BIZ_ZIP);
define('BIZ_LAT',         '48.1919');                                 // TODO verify exact coordinates
define('BIZ_LNG',         '-114.3116');                               // TODO verify exact coordinates

// Round-the-clock coverage. BIZ_HOURS_24_7 drives the opening-hours schema as
// well as the copy, so the two can never disagree.
define('BIZ_HOURS_24_7',  true);
define('BIZ_HOURS_WEEK',  'Open 24 hours a day, 7 days a week');
define('BIZ_HOURS_SAT',   'Weekends and holidays included');
define('BIZ_HOURS_SUN',   'Always open');
define('BIZ_FOUNDED',     2013);                                      // TODO

/* ----------------------------------------------------------------- social */
define('SOCIAL_FACEBOOK', 'https://www.facebook.com/');               // TODO
define('SOCIAL_LINKEDIN', 'https://www.linkedin.com/');               // TODO
define('SOCIAL_TWITTER',  'https://twitter.com/');                    // TODO
define('SOCIAL_INSTAGRAM','https://www.instagram.com/');              // TODO

/* ------------------------------------------------------------------- site */
define('SITE_URL',        rw_detect_base_url());   // auto-detected per request
// The canonical production origin. Used by tools/build-sitemap.php and by
// robots.txt, which must name a real domain rather than whatever host the
// request happened to arrive on.
define('SITE_URL_PROD',   'https://rightwaymedicalbilling.com');   // TODO: real domain
define('SITE_LOCALE',     'en_US');
define('SITE_TZ',         'America/New_York');

/* --------------------------------------------------------------- delivery */
define('FORM_TO_EMAIL',   BIZ_EMAIL);                                 // TODO
define('FORM_FROM_EMAIL', 'no-reply@rightwaymedicalbilling.com');     // TODO must be a domain you control
define('FORM_LOG_DIR',    dirname(__DIR__) . '/cache/submissions');   // fallback log if mail() is unavailable

/* ----------------------------------------------------------------- pexels */
/**
 * The Pexels key is a live credential, so it is NOT stored in this file — this
 * file is committed to version control. Resolution order:
 *
 *   1. PEXELS_API_KEY in the environment (set it in your host's control panel,
 *      or in the vhost with SetEnv). Preferred in production.
 *   2. includes/secrets.php, which is git-ignored. Copy secrets.example.php to
 *      secrets.php and paste the key in. Preferred for local work.
 *
 * With neither present the constant is an empty string, which is handled
 * gracefully: images resolve from the local cache manifest, and anything
 * uncached falls back to the bundled SVG placeholders. The site still runs.
 */
$rwSecrets = __DIR__ . '/secrets.php';
if (is_file($rwSecrets)) require_once $rwSecrets;
unset($rwSecrets);

define('PEXELS_API_KEY', getenv('PEXELS_API_KEY')
    ?: (defined('RW_PEXELS_API_KEY') ? RW_PEXELS_API_KEY : ''));
define('PEXELS_CACHE_DIR',  dirname(__DIR__) . '/cache/pexels');      // JSON responses
define('PEXELS_IMG_DIR',    dirname(__DIR__) . '/assets/img/pexels-cache');
define('PEXELS_CACHE_TTL',  60 * 60 * 6);                             // 6 hours

/* ---------------------------------------------------------------- toggles */
define('SHOW_MAP',        true);
define('GA_MEASUREMENT_ID', '');                                      // TODO e.g. G-XXXXXXXXXX (empty = no analytics)

/**
 * Best-effort base URL detection so the project runs from `php -S localhost:8000`,
 * a sub-folder, or a real domain without editing anything. Hard-code the return
 * value in production if the site sits behind a proxy that rewrites Host.
 */
function rw_detect_base_url(): string {
    $https  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
           || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
           || (($_SERVER['SERVER_PORT'] ?? '') == 443);
    $host   = $_SERVER['HTTP_HOST'] ?? 'rightwaymedicalbilling.com';
    $docRoot = str_replace(DIRECTORY_SEPARATOR, '/', realpath($_SERVER['DOCUMENT_ROOT'] ?? '') ?: '');
    $siteDir = str_replace(DIRECTORY_SEPARATOR, '/', dirname(__DIR__));
    $base    = '';
    if ($docRoot && str_starts_with($siteDir, $docRoot)) {
        $base = rtrim(substr($siteDir, strlen($docRoot)), '/');
    }
    return ($https ? 'https://' : 'http://') . $host . $base;
}

date_default_timezone_set(SITE_TZ);

require_once __DIR__ . '/functions.php';

// Start the session during bootstrap, while nothing has been output yet.
// Forms request a CSRF token from the footer, which is far too late to be
// setting session cookie parameters.
if (PHP_SAPI !== 'cli') {
    rw_session();
}
