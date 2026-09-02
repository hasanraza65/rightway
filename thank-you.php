<?php
/**
 * Post-submission confirmation.
 */
require __DIR__ . '/includes/config.php';

$isNewsletter = ($_GET['ref'] ?? '') === 'newsletter';

$page_title       = 'Thank You | Right Way Medical Billing';
$meta_description = 'Thanks for getting in touch with Right Way Medical Billing. We reply to every inquiry within one business day. Here is what happens next.';
$canonical        = 'thank-you.php';
$body_class       = 'page-thanks';
$noindex          = true;   // a confirmation page has no business in search results
$breadcrumbs = [
    ['label' => 'Home',      'url' => 'index.php'],
    ['label' => 'Thank You', 'url' => 'thank-you.php'],
];

include __DIR__ . '/includes/header.php';
?>

<section class="section-pad">
  <div class="container">
    <div class="rw-message" data-aos="fade-up">
      <span class="rw-message__icon"><i class="bi bi-check-lg" aria-hidden="true"></i></span>

      <?php if ($isNewsletter): ?>
        <h1>You are on the list</h1>
        <p class="rw-section-head__lead">
          Thanks for subscribing. Our revenue cycle notes go out at the start of each
          month: payer rule changes, denial trends worth watching, and nothing else.
          Unsubscribe from any issue in one click.
        </p>
      <?php else: ?>
        <h1>Thank you &mdash; your inquiry is with our team</h1>
        <p class="rw-section-head__lead">
          We have received your details and someone from the new client team will be in
          touch within one business day. If it is urgent, calling is always faster.
        </p>
      <?php endif; ?>

      <ul class="rw-nextsteps">
        <li>
          <i class="bi bi-1-circle" aria-hidden="true"></i>
          <span><strong>Within one business day</strong> &mdash; we reply by email to arrange a
          30 minute call at a time that suits your schedule.</span>
        </li>
        <li>
          <i class="bi bi-2-circle" aria-hidden="true"></i>
          <span><strong>On the call</strong> &mdash; we ask about your specialty, payer mix, current
          setup and the problem you most want solved. No slide deck.</span>
        </li>
        <li>
          <i class="bi bi-3-circle" aria-hidden="true"></i>
          <span><strong>After the call</strong> &mdash; with a signed business associate agreement in
          place, we review a claims sample and send written findings with a number
          attached to each one.</span>
        </li>
      </ul>

      <div class="rw-message__actions">
        <a class="btn btn-rw-teal btn-lg" href="<?= e(rw_url('index.php')) ?>">Back to Home</a>
        <a class="btn btn-rw-outline btn-lg" href="tel:<?= e(rw_tel()) ?>">
          <i class="bi bi-telephone-fill" aria-hidden="true"></i> <?= e(BIZ_PHONE) ?>
        </a>
      </div>

      <p class="rw-form__fine mt-4">
        While you wait, you might find the
        <a href="<?= e(rw_url('services.php')) ?>">services overview</a> or your
        <a href="<?= e(rw_url('specialties.php')) ?>">specialty page</a> useful.
      </p>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php';
