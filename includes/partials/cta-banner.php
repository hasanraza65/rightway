<?php
/**
 * The recurring conversion banner. Sits near the bottom of every page, with
 * per-page copy so it does not read as the same block twenty-nine times.
 *
 * @var string $cta_title
 * @var string $cta_text
 * @var string $cta_btn    optional primary button label
 */
$cta_title = $cta_title ?? 'Ready to see what your practice is leaving behind?';
$cta_text  = $cta_text  ?? 'A free revenue assessment takes about thirty minutes and gives you a specific, numbers-first read on where your revenue cycle is leaking.';
$cta_btn   = $cta_btn   ?? 'Book a Free Assessment';
?>
<section class="rw-cta" data-aos="fade-up">
  <div class="container">
    <div class="rw-cta__panel">
      <span class="rw-cta__glow" aria-hidden="true"></span>
      <div class="rw-cta__content">
        <p class="rw-eyebrow rw-eyebrow--light">Free consultation</p>
        <h2 class="rw-cta__title"><?= e($cta_title) ?></h2>
        <p class="rw-cta__text"><?= e($cta_text) ?></p>
      </div>
      <div class="rw-cta__actions">
        <a class="btn btn-rw-teal btn-lg" href="<?= e(rw_url('contact-us.php')) ?>"><?= e($cta_btn) ?></a>
        <a class="btn btn-rw-ghost btn-lg" href="tel:<?= e(rw_tel()) ?>">
          <i class="bi bi-telephone-fill" aria-hidden="true"></i> <?= e(BIZ_PHONE) ?>
        </a>
      </div>
    </div>
  </div>
</section>
