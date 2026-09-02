<?php
/**
 * Site footer plus the three floating elements (quote modal, WhatsApp, back to
 * top) and all script tags. Included by every page.
 */
$services    = rw_services();
$specialties = rw_specialties();
?>
</main>

<footer class="rw-footer">
  <div class="rw-footer__top">
    <div class="container">
      <div class="row g-4 g-lg-5">

        <div class="col-lg-4">
          <a class="rw-footer__logo" href="<?= e(rw_url('index.php')) ?>">
            <?php [$rwFootLogoW, $rwFootLogoH] = rw_img_dims('assets/img/logo-light.png'); ?>
            <img src="<?= e(rw_url('assets/img/logo-light.png')) ?>"
                 alt="<?= e(BIZ_NAME) ?>" width="<?= $rwFootLogoW ?>" height="<?= $rwFootLogoH ?>" loading="lazy" decoding="async">
          </a>
          <p class="rw-footer__blurb">
            We run the revenue cycle for physician practices across the United States &mdash;
            billing, coding, credentialing and denial recovery handled by people who do
            nothing else. Fewer denials, faster payment, and a clear view of every dollar.
          </p>
          <ul class="rw-footer__badges">
            <li><i class="bi bi-shield-lock-fill" aria-hidden="true"></i> HIPAA compliant</li>
            <li><i class="bi bi-patch-check-fill" aria-hidden="true"></i> AAPC certified coders</li>
          </ul>
          <ul class="rw-footer__social" aria-label="Social media">
            <li><a href="<?= e(SOCIAL_LINKEDIN) ?>" aria-label="LinkedIn" rel="noopener" target="_blank"><i class="bi bi-linkedin" aria-hidden="true"></i></a></li>
            <li><a href="<?= e(SOCIAL_FACEBOOK) ?>" aria-label="Facebook" rel="noopener" target="_blank"><i class="bi bi-facebook" aria-hidden="true"></i></a></li>
            <li><a href="<?= e(SOCIAL_TWITTER) ?>" aria-label="X" rel="noopener" target="_blank"><i class="bi bi-twitter-x" aria-hidden="true"></i></a></li>
            <li><a href="<?= e(SOCIAL_INSTAGRAM) ?>" aria-label="Instagram" rel="noopener" target="_blank"><i class="bi bi-instagram" aria-hidden="true"></i></a></li>
          </ul>
        </div>

        <div class="col-6 col-lg-2">
          <h2 class="rw-footer__head">Company</h2>
          <ul class="rw-footer__links">
            <li><a href="<?= e(rw_url('index.php')) ?>">Home</a></li>
            <li><a href="<?= e(rw_url('about-us.php')) ?>">About Us</a></li>
            <li><a href="<?= e(rw_url('services.php')) ?>">Services</a></li>
            <li><a href="<?= e(rw_url('specialties.php')) ?>">Specialties</a></li>
            <li><a href="<?= e(rw_url('contact-us.php')) ?>">Contact Us</a></li>
            <li><a href="<?= e(rw_url('credits.php')) ?>">Photo Credits</a></li>
          </ul>
        </div>

        <div class="col-6 col-lg-3">
          <h2 class="rw-footer__head">Services</h2>
          <ul class="rw-footer__links">
            <?php foreach ($services as $rwFootSlug => $rwFootSvc): ?>
              <li><a href="<?= e(rw_url('services/' . $rwFootSlug . '.php')) ?>"><?= e($rwFootSvc['nav']) ?></a></li>
            <?php endforeach; ?>
          </ul>
        </div>

        <div class="col-lg-3">
          <h2 class="rw-footer__head">Get in touch</h2>
          <ul class="rw-footer__contact">
            <li>
              <i class="bi bi-geo-alt-fill" aria-hidden="true"></i>
              <span><?= e(BIZ_STREET) ?><br><?= e(BIZ_CITY . ', ' . BIZ_STATE . ' ' . BIZ_ZIP) ?></span>
            </li>
            <li>
              <i class="bi bi-telephone-fill" aria-hidden="true"></i>
              <a href="tel:<?= e(rw_tel()) ?>"><?= e(BIZ_PHONE) ?></a>
            </li>
            <li>
              <i class="bi bi-envelope-fill" aria-hidden="true"></i>
              <a href="mailto:<?= e(BIZ_EMAIL) ?>"><?= e(BIZ_EMAIL) ?></a>
            </li>
            <li>
              <i class="bi bi-clock-fill" aria-hidden="true"></i>
              <span><?= e(BIZ_HOURS_WEEK) ?><br><?= e(BIZ_HOURS_SAT) ?></span>
            </li>
          </ul>

          <h2 class="rw-footer__head rw-footer__head--tight">Revenue cycle notes</h2>
          <p class="rw-footer__note">A short monthly email on payer rule changes and denial trends. No sales pitches.</p>
          <form class="rw-newsletter" action="<?= e(rw_url('handlers/newsletter-handler.php')) ?>" method="post" novalidate data-rw-form="newsletter">
            <input type="hidden" name="rw_token" value="<?= e(rw_csrf_token()) ?>">
            <label class="visually-hidden" for="rw-news-email">Your work email address</label>
            <div class="rw-newsletter__row">
              <input class="form-control" type="email" id="rw-news-email" name="email"
                     placeholder="you@yourpractice.com" required autocomplete="email">
              <button class="btn btn-rw-teal" type="submit" aria-label="Subscribe to the newsletter">
                <i class="bi bi-arrow-right" aria-hidden="true"></i>
              </button>
            </div>
            <!-- Honeypot: a real person never fills this in -->
            <div class="rw-hp" aria-hidden="true">
              <label>Company website<input type="text" name="rw_website" tabindex="-1" autocomplete="off"></label>
            </div>
            <p class="rw-form__msg" data-rw-msg role="status" aria-live="polite"></p>
          </form>
        </div>

      </div>
    </div>
  </div>

  <div class="rw-footer__bottom">
    <div class="container">
      <div class="rw-footer__bar">
        <p>&copy; <?= date('Y') ?> <?= e(BIZ_NAME) ?>. All rights reserved.</p>
        <ul>
          <li><a href="<?= e(rw_url('privacy-policy.php')) ?>">Privacy Policy</a></li>
          <li><a href="<?= e(rw_url('terms-of-service.php')) ?>">Terms of Service</a></li>
          <li><a href="<?= e(rw_url('sitemap.xml')) ?>">Sitemap</a></li>
        </ul>
      </div>
    </div>
  </div>
</footer>

<!-- Quick quote modal ------------------------------------------------------ -->
<div class="rw-modal" id="rw-quote-modal" hidden>
  <div class="rw-modal__scrim" data-rw-close-modal></div>
  <div class="rw-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="rw-quote-title">
    <button type="button" class="rw-modal__close" aria-label="Close" data-rw-close-modal>
      <i class="bi bi-x-lg" aria-hidden="true"></i>
    </button>
    <p class="rw-eyebrow rw-eyebrow--light">No obligation</p>
    <h2 class="rw-modal__title" id="rw-quote-title">Get a free revenue assessment</h2>
    <p class="rw-modal__lead">
      Tell us where your practice is losing money and we will come back within one
      business day with a specific read on it &mdash; not a generic brochure.
    </p>
    <form action="<?= e(rw_url('handlers/contact-handler.php')) ?>" method="post" novalidate data-rw-form="quote">
      <input type="hidden" name="rw_token" value="<?= e(rw_csrf_token()) ?>">
      <input type="hidden" name="rw_source" value="quote-modal">
      <div class="row g-3">
        <div class="col-sm-6">
          <label class="form-label" for="q-name">Full name <span aria-hidden="true">*</span></label>
          <input class="form-control" id="q-name" name="name" type="text" required autocomplete="name">
          <p class="rw-field__err" data-rw-err></p>
        </div>
        <div class="col-sm-6">
          <label class="form-label" for="q-email">Work email <span aria-hidden="true">*</span></label>
          <input class="form-control" id="q-email" name="email" type="email" required autocomplete="email">
          <p class="rw-field__err" data-rw-err></p>
        </div>
        <div class="col-sm-6">
          <label class="form-label" for="q-phone">Phone <span aria-hidden="true">*</span></label>
          <input class="form-control" id="q-phone" name="phone" type="tel" required autocomplete="tel">
          <p class="rw-field__err" data-rw-err></p>
        </div>
        <div class="col-sm-6">
          <label class="form-label" for="q-specialty">Specialty</label>
          <select class="form-select" id="q-specialty" name="specialty">
            <option value="">Select one</option>
            <?php foreach ($specialties as $rwFootSpec): ?>
              <option value="<?= e($rwFootSpec['nav']) ?>"><?= e($rwFootSpec['nav']) ?></option>
            <?php endforeach; ?>
            <option value="Other">Other</option>
          </select>
        </div>
      </div>
      <div class="rw-hp" aria-hidden="true">
        <label>Company website<input type="text" name="rw_website" tabindex="-1" autocomplete="off"></label>
      </div>
      <button class="btn btn-rw-teal w-100 mt-3" type="submit">
        <span data-rw-label>Request my assessment</span>
        <span class="rw-spinner" data-rw-spinner hidden aria-hidden="true"></span>
      </button>
      <p class="rw-form__msg" data-rw-msg role="status" aria-live="polite"></p>
      <p class="rw-modal__fine">
        We use your details only to reply to this inquiry. No patient data is ever
        sent through this form.
      </p>
    </form>
  </div>
</div>

<!-- Floating actions ------------------------------------------------------- -->
<a class="rw-whatsapp"
   href="https://wa.me/<?= e(BIZ_WHATSAPP) ?>?text=<?= rawurlencode('Hi ' . BIZ_NAME . ', I would like to discuss billing for my practice.') ?>"
   target="_blank" rel="noopener" aria-label="Chat with us on WhatsApp">
  <i class="bi bi-whatsapp" aria-hidden="true"></i>
  <span class="rw-whatsapp__pulse" aria-hidden="true"></span>
</a>

<button class="rw-totop" type="button" aria-label="Back to top" hidden>
  <i class="bi bi-arrow-up" aria-hidden="true"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>
<script src="<?= e(rw_asset('assets/js/main.js')) ?>" defer></script>
<?php if (GA_MEASUREMENT_ID): ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= e(GA_MEASUREMENT_ID) ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '<?= e(GA_MEASUREMENT_ID) ?>');
</script>
<?php endif; ?>
</body>
</html>
