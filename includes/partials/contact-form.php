<?php
/**
 * The full consultation form. Used on the contact page and anywhere else a
 * complete inquiry form is needed.
 *
 * @var string $form_source  hidden value recording which page produced the lead
 * @var string $form_title   optional heading rendered above the fields
 */
$form_source = $form_source ?? 'contact-page';
$specialties = rw_specialties();
$services    = rw_services();
?>
<form class="rw-form" action="<?= e(rw_url('handlers/contact-handler.php')) ?>" method="post" novalidate data-rw-form="contact">
  <input type="hidden" name="rw_token" value="<?= e(rw_csrf_token()) ?>">
  <input type="hidden" name="rw_source" value="<?= e($form_source) ?>">

  <?php if (!empty($form_title)): ?>
    <h2 class="rw-form__title"><?= e($form_title) ?></h2>
  <?php endif; ?>

  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label" for="cf-name">Full name <span class="rw-req" aria-hidden="true">*</span></label>
      <input class="form-control" type="text" id="cf-name" name="name" required
             autocomplete="name" maxlength="120" placeholder="Dr. Jane Okafor">
      <p class="rw-field__err" data-rw-err></p>
    </div>

    <div class="col-md-6">
      <label class="form-label" for="cf-practice">Practice name</label>
      <input class="form-control" type="text" id="cf-practice" name="practice"
             autocomplete="organization" maxlength="160" placeholder="Northside Family Health">
      <p class="rw-field__err" data-rw-err></p>
    </div>

    <div class="col-md-6">
      <label class="form-label" for="cf-email">Work email <span class="rw-req" aria-hidden="true">*</span></label>
      <input class="form-control" type="email" id="cf-email" name="email" required
             autocomplete="email" maxlength="180" placeholder="jane@northsidehealth.com">
      <p class="rw-field__err" data-rw-err></p>
    </div>

    <div class="col-md-6">
      <label class="form-label" for="cf-phone">Phone <span class="rw-req" aria-hidden="true">*</span></label>
      <input class="form-control" type="tel" id="cf-phone" name="phone" required
             autocomplete="tel" maxlength="40" placeholder="(555) 123-4567">
      <p class="rw-field__err" data-rw-err></p>
    </div>

    <div class="col-md-6">
      <label class="form-label" for="cf-specialty">Specialty</label>
      <select class="form-select" id="cf-specialty" name="specialty">
        <option value="">Select your specialty</option>
        <?php foreach ($specialties as $sp): ?>
          <option value="<?= e($sp['nav']) ?>"><?= e($sp['nav']) ?></option>
        <?php endforeach; ?>
        <option value="Other">Other / not listed</option>
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label" for="cf-interest">What do you need help with?</label>
      <select class="form-select" id="cf-interest" name="interest">
        <option value="">Select a service</option>
        <?php foreach ($services as $s): ?>
          <option value="<?= e($s['title']) ?>"><?= e($s['title']) ?></option>
        <?php endforeach; ?>
        <option value="Full revenue cycle">The whole revenue cycle</option>
        <option value="Not sure yet">Not sure yet</option>
      </select>
    </div>

    <div class="col-12">
      <label class="form-label" for="cf-message">Tell us what is going wrong <span class="rw-req" aria-hidden="true">*</span></label>
      <textarea class="form-control" id="cf-message" name="message" rows="5" required
                maxlength="4000" placeholder="Number of providers, current billing setup, and the problem you most want solved."></textarea>
      <p class="rw-field__err" data-rw-err></p>
      <p class="rw-form__hint">
        <i class="bi bi-shield-lock" aria-hidden="true"></i>
        Please do not include any patient identifiers or protected health information in this form.
      </p>
    </div>

    <div class="col-12">
      <div class="form-check rw-check">
        <input class="form-check-input" type="checkbox" id="cf-consent" name="consent" value="1" required>
        <label class="form-check-label" for="cf-consent">
          I agree to be contacted about this inquiry and have read the
          <a href="<?= e(rw_url('privacy-policy.php')) ?>">privacy policy</a>.
        </label>
        <p class="rw-field__err" data-rw-err></p>
      </div>
    </div>
  </div>

  <!-- Honeypot: hidden from people, irresistible to bots -->
  <div class="rw-hp" aria-hidden="true">
    <label>Company website<input type="text" name="rw_website" tabindex="-1" autocomplete="off"></label>
  </div>

  <button class="btn btn-rw-teal btn-lg w-100 mt-4" type="submit">
    <span data-rw-label>Request My Free Assessment</span>
    <span class="rw-spinner" data-rw-spinner hidden aria-hidden="true"></span>
  </button>

  <p class="rw-form__msg" data-rw-msg role="status" aria-live="polite"></p>

  <p class="rw-form__fine">
    We reply to every inquiry within one business day. If it is urgent, call
    <a href="tel:<?= e(rw_tel()) ?>"><?= e(BIZ_PHONE) ?></a>.
  </p>
</form>
