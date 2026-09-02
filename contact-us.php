<?php
/**
 * Contact Us.
 */
require __DIR__ . '/includes/config.php';

$page_title       = 'Contact Right Way Medical Billing | Free Assessment';
$meta_description = 'Talk to Right Way Medical Billing about your practice revenue cycle. Call, email or request a free claims assessment and we will reply within one business day.';
$canonical        = 'contact-us.php';
$body_class       = 'page-contact';
$breadcrumbs = [
    ['label' => 'Home',       'url' => 'index.php'],
    ['label' => 'Contact Us', 'url' => 'contact-us.php'],
];

include __DIR__ . '/includes/header.php';

rw_partial('page-header', [
    'ph_title'    => 'Tell us what is going wrong with your billing',
    'ph_eyebrow'  => 'Contact us',
    'ph_lead'     => 'Whether you want a full revenue assessment or just a second opinion on one stubborn denial category, start here. Every inquiry gets a reply within one business day.',
    'breadcrumbs' => $breadcrumbs,
    'ph_image'    => 'contact-hero',
]);
?>

<!-- Contact methods ----------------------------------------------------------- -->
<section class="rw-contact-methods section-pad">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-4" data-aos="fade-up">
        <div class="rw-contact-card h-100">
          <span class="rw-contact-card__icon" data-tone="0"><i class="bi bi-telephone-fill" aria-hidden="true"></i></span>
          <h2 class="rw-contact-card__title">Call us</h2>
          <p class="rw-contact-card__text">
            Fastest route to a real answer. Ask for the new client team and you will
            speak to someone who can actually assess your situation.
          </p>
          <a href="tel:<?= e(rw_tel()) ?>"><?= e(BIZ_PHONE) ?></a>
        </div>
      </div>

      <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
        <div class="rw-contact-card h-100">
          <span class="rw-contact-card__icon" data-tone="1"><i class="bi bi-envelope-fill" aria-hidden="true"></i></span>
          <h2 class="rw-contact-card__title">Email us</h2>
          <p class="rw-contact-card__text">
            Good for detailed questions. Please do not attach anything containing
            patient identifiers &mdash; we will send you a secure link instead.
          </p>
          <a href="mailto:<?= e(BIZ_EMAIL) ?>"><?= e(BIZ_EMAIL) ?></a><br>
          <a href="mailto:<?= e(BIZ_EMAIL_SALES) ?>"><?= e(BIZ_EMAIL_SALES) ?></a>
        </div>
      </div>

      <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
        <div class="rw-contact-card h-100">
          <span class="rw-contact-card__icon" data-tone="2"><i class="bi bi-geo-alt-fill" aria-hidden="true"></i></span>
          <h2 class="rw-contact-card__title">Our office</h2>
          <p class="rw-contact-card__text">
            <?= e(BIZ_STREET) ?><br>
            <?= e(BIZ_CITY . ', ' . BIZ_STATE . ' ' . BIZ_ZIP) ?><br>
            United States
          </p>
          <a href="https://www.google.com/maps/search/?api=1&amp;query=<?= rawurlencode(BIZ_ADDRESS) ?>"
             target="_blank" rel="noopener">Open in Google Maps</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Form + sidebar ------------------------------------------------------------ -->
<section class="rw-contact-form section-pad bg-sky">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-7" data-aos="fade-up">
        <p class="rw-eyebrow">Free assessment</p>
        <h2 class="mb-2">Request a revenue cycle review</h2>
        <p class="rw-section-head__lead mb-4">
          Give us enough detail to be useful and we will come back with something
          specific rather than a brochure. There is no cost and no obligation, and
          whatever we find is yours to keep.
        </p>
        <?php rw_partial('contact-form', ['form_source' => 'contact-page']); ?>
      </div>

      <div class="col-lg-5" data-aos="fade-up" data-aos-delay="100">
        <div class="rw-contact-card mb-4">
          <span class="rw-contact-card__icon" data-tone="3"><i class="bi bi-clock-fill" aria-hidden="true"></i></span>
          <h2 class="rw-contact-card__title">Working hours</h2>
          <ul class="rw-hours mt-3">
            <li><span>Monday &ndash; Friday</span><strong>24 hours</strong></li>
            <li><span>Saturday &ndash; Sunday</span><strong>24 hours</strong></li>
            <li><span>Public holidays</span><strong>24 hours</strong></li>
          </ul>
          <p class="rw-contact-card__text mt-3 mb-0">
            Our team runs around the clock, so nothing waits on an office reopening.
            Payer portals still keep their own hours, but claim work, denials and
            appeal deadlines are covered every day of the week.
          </p>
        </div>

        <div class="rw-contact-card mb-4">
          <span class="rw-contact-card__icon" data-tone="4"><i class="bi bi-lightning-fill" aria-hidden="true"></i></span>
          <h2 class="rw-contact-card__title">What happens next</h2>
          <ul class="rw-nextsteps mt-3">
            <li><i class="bi bi-1-circle" aria-hidden="true"></i><span>We reply within one business day to arrange a 30 minute call.</span></li>
            <li><i class="bi bi-2-circle" aria-hidden="true"></i><span>You share read-only access or a claims sample, under a signed BAA.</span></li>
            <li><i class="bi bi-3-circle" aria-hidden="true"></i><span>We come back with written findings and a number attached to each one.</span></li>
          </ul>
        </div>

        <div class="rw-contact-card">
          <span class="rw-contact-card__icon" data-tone="5"><i class="bi bi-share-fill" aria-hidden="true"></i></span>
          <h2 class="rw-contact-card__title">Follow along</h2>
          <p class="rw-contact-card__text">
            We post payer rule changes and denial trend notes rather than marketing.
          </p>
          <ul class="rw-footer__social rw-footer__social--light">
            <li><a href="<?= e(SOCIAL_LINKEDIN) ?>" aria-label="LinkedIn" rel="noopener" target="_blank"><i class="bi bi-linkedin" aria-hidden="true"></i></a></li>
            <li><a href="<?= e(SOCIAL_FACEBOOK) ?>" aria-label="Facebook" rel="noopener" target="_blank"><i class="bi bi-facebook" aria-hidden="true"></i></a></li>
            <li><a href="<?= e(SOCIAL_TWITTER) ?>" aria-label="X" rel="noopener" target="_blank"><i class="bi bi-twitter-x" aria-hidden="true"></i></a></li>
            <li><a href="<?= e(SOCIAL_INSTAGRAM) ?>" aria-label="Instagram" rel="noopener" target="_blank"><i class="bi bi-instagram" aria-hidden="true"></i></a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<?php if (SHOW_MAP): ?>
<!-- Map ----------------------------------------------------------------------- -->
<section class="rw-map-sec section-pad">
  <div class="container">
    <div class="rw-section-head" data-aos="fade-up">
      <p class="rw-eyebrow">Find us</p>
      <h2 class="rw-section-head__title">Where we are</h2>
      <p class="rw-section-head__lead">
        We work with practices nationwide and almost everything happens remotely, but
        the door is open if you would rather talk in person.
      </p>
    </div>
    <div class="rw-map" data-aos="fade-up" data-aos-delay="80">
      <iframe
        title="Map showing the location of <?= e(BIZ_NAME) ?>"
        src="https://maps.google.com/maps?q=<?= rawurlencode(BIZ_ADDRESS) ?>&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=&amp;output=embed"
        loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
  </div>
</section>
<?php endif; ?>

<?php
include __DIR__ . '/includes/footer.php';
