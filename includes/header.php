<?php
/**
 * Site header: skip link, utility bar, sticky masthead with mega menus, and
 * the mobile offcanvas. Included by every page — no page duplicates this markup.
 *
 * Requires config.php to have been loaded and the page meta variables to be set.
 */
$services    = rw_services();
$specialties = rw_specialties();
$onServices    = str_contains($canonical ?? '', 'services');
$onSpecialties = str_contains($canonical ?? '', 'specialt');
?><!doctype html>
<html lang="en">
<head>
<?php include __DIR__ . '/meta.php'; ?>
</head>
<body class="<?= e($body_class ?? '') ?>">

<a class="rw-skip" href="#main">Skip to main content</a>

<!-- Utility bar ------------------------------------------------------------ -->
<div class="rw-topbar d-none d-lg-block">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between">
      <ul class="rw-topbar__list">
        <li>
          <i class="bi bi-telephone-fill" aria-hidden="true"></i>
          <a href="tel:<?= e(rw_tel()) ?>"><?= e(BIZ_PHONE) ?></a>
        </li>
        <li>
          <i class="bi bi-envelope-fill" aria-hidden="true"></i>
          <a href="mailto:<?= e(BIZ_EMAIL) ?>"><?= e(BIZ_EMAIL) ?></a>
        </li>
        <li class="rw-topbar__hours">
          <i class="bi bi-clock-fill" aria-hidden="true"></i>
          <span>Open 24/7 &mdash; every day, including holidays</span>
        </li>
      </ul>
      <ul class="rw-topbar__social" aria-label="Social media">
        <li><a href="<?= e(SOCIAL_LINKEDIN) ?>" aria-label="<?= e(BIZ_NAME) ?> on LinkedIn" rel="noopener" target="_blank"><i class="bi bi-linkedin" aria-hidden="true"></i></a></li>
        <li><a href="<?= e(SOCIAL_FACEBOOK) ?>" aria-label="<?= e(BIZ_NAME) ?> on Facebook" rel="noopener" target="_blank"><i class="bi bi-facebook" aria-hidden="true"></i></a></li>
        <li><a href="<?= e(SOCIAL_TWITTER) ?>" aria-label="<?= e(BIZ_NAME) ?> on X" rel="noopener" target="_blank"><i class="bi bi-twitter-x" aria-hidden="true"></i></a></li>
        <li><a href="<?= e(SOCIAL_INSTAGRAM) ?>" aria-label="<?= e(BIZ_NAME) ?> on Instagram" rel="noopener" target="_blank"><i class="bi bi-instagram" aria-hidden="true"></i></a></li>
      </ul>
    </div>
  </div>
</div>

<!-- Masthead --------------------------------------------------------------- -->
<header class="rw-header" id="rw-header">
  <div class="container">
    <div class="rw-header__inner">

      <?php [$rwLogoW, $rwLogoH] = rw_img_dims('assets/img/logo.png'); ?>
      <a class="rw-logo" href="<?= e(rw_url('index.php')) ?>" aria-label="<?= e(BIZ_NAME) ?> home">
        <img src="<?= e(rw_url('assets/img/logo.png')) ?>"
             alt="<?= e(BIZ_NAME) ?> logo"
             width="<?= $rwLogoW ?>" height="<?= $rwLogoH ?>" fetchpriority="high" decoding="sync">
      </a>

      <nav class="rw-nav d-none d-xl-block" aria-label="Primary">
        <ul class="rw-nav__list">
          <li><a href="<?= e(rw_url('index.php')) ?>" class="rw-nav__link<?= rw_is_current('index.php') ? ' is-current' : '' ?>"<?= rw_is_current('index.php') ? ' aria-current="page"' : '' ?>>Home</a></li>
          <li><a href="<?= e(rw_url('about-us.php')) ?>" class="rw-nav__link<?= rw_is_current('about-us.php') ? ' is-current' : '' ?>"<?= rw_is_current('about-us.php') ? ' aria-current="page"' : '' ?>>About Us</a></li>

          <li class="rw-nav__item rw-nav__item--mega">
            <a href="<?= e(rw_url('services.php')) ?>"
               class="rw-nav__link rw-nav__link--parent<?= $onServices ? ' is-current' : '' ?>"
               aria-expanded="false" aria-haspopup="true" data-rw-mega="services">
              Services <i class="bi bi-chevron-down" aria-hidden="true"></i>
            </a>
            <div class="rw-mega" data-rw-mega-panel="services">
              <div class="rw-mega__inner">
                <div class="rw-mega__grid">
                  <?php foreach ($services as $rwNavSlug => $rwNavSvc): ?>
                    <a class="rw-mega__card" href="<?= e(rw_url('services/' . $rwNavSlug . '.php')) ?>">
                      <span class="rw-mega__icon"><i class="bi <?= e($rwNavSvc['icon']) ?>" aria-hidden="true"></i></span>
                      <span class="rw-mega__text">
                        <span class="rw-mega__title"><?= e($rwNavSvc['title']) ?></span>
                        <span class="rw-mega__desc"><?= e($rwNavSvc['card_desc']) ?></span>
                      </span>
                    </a>
                  <?php endforeach; ?>
                </div>
                <p class="rw-mega__foot">
                  <a href="<?= e(rw_url('services.php')) ?>">See how the full revenue cycle fits together <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                </p>
              </div>
            </div>
          </li>

          <li class="rw-nav__item rw-nav__item--mega">
            <a href="<?= e(rw_url('specialties.php')) ?>"
               class="rw-nav__link rw-nav__link--parent<?= $onSpecialties ? ' is-current' : '' ?>"
               aria-expanded="false" aria-haspopup="true" data-rw-mega="specialties">
              Specialties <i class="bi bi-chevron-down" aria-hidden="true"></i>
            </a>
            <div class="rw-mega rw-mega--compact" data-rw-mega-panel="specialties">
              <div class="rw-mega__inner">
                <div class="rw-mega__grid rw-mega__grid--4">
                  <?php foreach ($specialties as $rwNavSlug => $rwNavSpec): ?>
                    <a class="rw-mega__card rw-mega__card--slim" href="<?= e(rw_url('specialties/' . $rwNavSlug . '.php')) ?>">
                      <span class="rw-mega__icon rw-mega__icon--sm"><i class="bi <?= e($rwNavSpec['icon']) ?>" aria-hidden="true"></i></span>
                      <span class="rw-mega__title"><?= e($rwNavSpec['nav']) ?></span>
                    </a>
                  <?php endforeach; ?>
                </div>
                <p class="rw-mega__foot">
                  <a href="<?= e(rw_url('specialties.php')) ?>">Why specialty-specific billing pays better <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                </p>
              </div>
            </div>
          </li>

          <li><a href="<?= e(rw_url('contact-us.php')) ?>" class="rw-nav__link<?= rw_is_current('contact-us.php') ? ' is-current' : '' ?>"<?= rw_is_current('contact-us.php') ? ' aria-current="page"' : '' ?>>Contact</a></li>
        </ul>
      </nav>

      <div class="rw-header__actions">
        <a class="rw-header__phone d-none d-md-inline-flex" href="tel:<?= e(rw_tel()) ?>">
          <span class="rw-header__phone-icon"><i class="bi bi-telephone-fill" aria-hidden="true"></i></span>
          <span class="rw-header__phone-text">
            <span class="rw-header__phone-label">Talk to a specialist</span>
            <span class="rw-header__phone-number"><?= e(BIZ_PHONE) ?></span>
          </span>
        </a>
        <button type="button" class="btn btn-rw-teal d-none d-sm-inline-flex" data-rw-quote>
          Free Consultation
        </button>
        <button class="rw-burger d-xl-none" type="button"
                aria-label="Open navigation menu" aria-expanded="false" aria-controls="rw-mobile-nav">
          <span></span><span></span><span></span>
        </button>
      </div>

    </div>
  </div>
</header>

<!-- Mobile navigation ------------------------------------------------------ -->
<div class="rw-mobile" id="rw-mobile-nav" hidden>
  <div class="rw-mobile__panel" role="dialog" aria-modal="true" aria-label="Site navigation">
    <div class="rw-mobile__head">
      <a class="rw-mobile__logo" href="<?= e(rw_url('index.php')) ?>">
        <img src="<?= e(rw_url('assets/img/logo.png')) ?>" alt="<?= e(BIZ_NAME) ?>" width="<?= $rwLogoW ?>" height="<?= $rwLogoH ?>" loading="lazy" decoding="async">
      </a>
      <button type="button" class="rw-mobile__close" aria-label="Close navigation menu">
        <i class="bi bi-x-lg" aria-hidden="true"></i>
      </button>
    </div>

    <nav class="rw-mobile__nav" aria-label="Mobile">
      <ul>
        <li><a href="<?= e(rw_url('index.php')) ?>">Home</a></li>
        <li><a href="<?= e(rw_url('about-us.php')) ?>">About Us</a></li>

        <li class="rw-mobile__group">
          <div class="rw-mobile__row">
            <a href="<?= e(rw_url('services.php')) ?>">Services</a>
            <button type="button" class="rw-mobile__toggle" aria-expanded="false"
                    aria-label="Show all services">
              <i class="bi bi-chevron-down" aria-hidden="true"></i>
            </button>
          </div>
          <div class="rw-mobile__subwrap">
            <ul class="rw-mobile__sub">
              <?php foreach ($services as $rwNavSlug => $rwNavSvc): ?>
                <li><a href="<?= e(rw_url('services/' . $rwNavSlug . '.php')) ?>"><i class="bi <?= e($rwNavSvc['icon']) ?>" aria-hidden="true"></i><?= e($rwNavSvc['nav']) ?></a></li>
              <?php endforeach; ?>
            </ul>
          </div>
        </li>

        <li class="rw-mobile__group">
          <div class="rw-mobile__row">
            <a href="<?= e(rw_url('specialties.php')) ?>">Specialties</a>
            <button type="button" class="rw-mobile__toggle" aria-expanded="false"
                    aria-label="Show all specialties">
              <i class="bi bi-chevron-down" aria-hidden="true"></i>
            </button>
          </div>
          <div class="rw-mobile__subwrap">
            <ul class="rw-mobile__sub">
              <?php foreach ($specialties as $rwNavSlug => $rwNavSpec): ?>
                <li><a href="<?= e(rw_url('specialties/' . $rwNavSlug . '.php')) ?>"><i class="bi <?= e($rwNavSpec['icon']) ?>" aria-hidden="true"></i><?= e($rwNavSpec['nav']) ?></a></li>
              <?php endforeach; ?>
            </ul>
          </div>
        </li>

        <li><a href="<?= e(rw_url('contact-us.php')) ?>">Contact</a></li>
      </ul>
    </nav>

    <div class="rw-mobile__foot">
      <a class="btn btn-rw-teal w-100" href="<?= e(rw_url('contact-us.php')) ?>">Get a Free Consultation</a>
      <a class="rw-mobile__call" href="tel:<?= e(rw_tel()) ?>">
        <i class="bi bi-telephone-fill" aria-hidden="true"></i> <?= e(BIZ_PHONE) ?>
      </a>
    </div>
  </div>
  <div class="rw-mobile__scrim" data-rw-close-mobile></div>
</div>

<main id="main">
