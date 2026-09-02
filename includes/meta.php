<?php
/**
 * Everything that belongs inside <head>.
 *
 * Pages set these before including header.php:
 *   $page_title       required — unique per page, aim for 50-60 characters
 *   $meta_description required — unique per page, aim for 140-160 characters
 *   $canonical        required — site-relative path, e.g. 'services.php'
 *   $og_image         optional — site-relative path to a share image
 *   $og_type          optional — defaults to 'website'
 *   $noindex          optional — true to keep a page out of the index
 */

$page_title       = $page_title       ?? BIZ_NAME;
$meta_description = $meta_description ?? BIZ_TAGLINE;
$canonical        = $canonical        ?? 'index.php';
$og_type          = $og_type          ?? 'website';
$og_image_url     = rw_abs($og_image ?? 'assets/img/og-default.jpg');
$canonical_url    = rw_abs($canonical === 'index.php' ? '' : $canonical);
?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($page_title) ?></title>
<meta name="description" content="<?= e($meta_description) ?>">
<link rel="canonical" href="<?= e($canonical_url) ?>">
<?php if (!empty($noindex)): ?>
<meta name="robots" content="noindex, follow">
<?php else: ?>
<meta name="robots" content="index, follow, max-image-preview:large">
<?php endif; ?>
<meta name="author" content="<?= e(BIZ_NAME) ?>">
<meta name="theme-color" content="#0E2E4F">

<!-- Open Graph -->
<meta property="og:site_name" content="<?= e(BIZ_NAME) ?>">
<meta property="og:type" content="<?= e($og_type) ?>">
<meta property="og:title" content="<?= e($page_title) ?>">
<meta property="og:description" content="<?= e($meta_description) ?>">
<meta property="og:url" content="<?= e($canonical_url) ?>">
<meta property="og:image" content="<?= e($og_image_url) ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="<?= e(BIZ_NAME . ' — ' . BIZ_TAGLINE) ?>">
<meta property="og:locale" content="<?= e(SITE_LOCALE) ?>">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e($page_title) ?>">
<meta name="twitter:description" content="<?= e($meta_description) ?>">
<meta name="twitter:image" content="<?= e($og_image_url) ?>">
<meta name="twitter:image:alt" content="<?= e(BIZ_NAME . ' — ' . BIZ_TAGLINE) ?>">

<!-- Icons -->
<link rel="icon" href="<?= e(rw_url('assets/img/favicon.ico')) ?>" sizes="32x32">
<link rel="icon" type="image/png" sizes="32x32" href="<?= e(rw_url('assets/img/favicon-32.png')) ?>">
<link rel="icon" type="image/png" sizes="16x16" href="<?= e(rw_url('assets/img/favicon-16.png')) ?>">
<link rel="apple-touch-icon" href="<?= e(rw_url('assets/img/apple-touch-icon.png')) ?>">
<link rel="manifest" href="<?= e(rw_url('site.webmanifest')) ?>">

<!-- Resource hints: fonts and the image CDN are the only third parties we touch -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
<link rel="preload" as="image" href="<?= e(rw_url('assets/img/logo.png')) ?>" fetchpriority="high">

<link rel="preconnect" href="https://images.pexels.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet">
<link href="<?= e(rw_asset('assets/css/style.css')) ?>" rel="stylesheet">

<?php include __DIR__ . '/seo-schema.php'; ?>
