<?php
/**
 * FAQ accordion. The page is responsible for also passing the same pairs to
 * $faq_schema before header.php, so the visible content and the FAQPage
 * structured data always match.
 *
 * @var array  $faqs      [[question, answer], ...]
 * @var string $faq_id    unique prefix
 * @var string $faq_title
 * @var string $faq_lead
 */
$faqs   = $faqs ?? [];
$faq_id = $faq_id ?? 'faq';
if (!$faqs) return;
?>
<section class="rw-faq section-pad">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-4" data-aos="fade-up">
        <div class="rw-faq__aside">
          <p class="rw-eyebrow">Questions</p>
          <h2 class="rw-section-head__title"><?= e($faq_title ?? 'Straight answers') ?></h2>
          <p class="rw-section-head__lead"><?= e($faq_lead ?? 'The questions practices ask before they switch. If yours is not here, ask us directly.') ?></p>
          <a class="btn btn-rw-outline mt-2" href="<?= e(rw_url('contact-us.php')) ?>">Ask us something else</a>
        </div>
      </div>

      <div class="col-lg-8" data-aos="fade-up" data-aos-delay="100">
        <div class="rw-accordion rw-accordion--plain" data-rw-accordion>
          <?php foreach ($faqs as $i => $f): ?>
            <div class="rw-accordion__item<?= $i === 0 ? ' is-open' : '' ?>">
              <h3 class="rw-accordion__heading">
                <button type="button" class="rw-accordion__btn"
                        aria-expanded="<?= $i === 0 ? 'true' : 'false' ?>"
                        aria-controls="<?= e($faq_id) ?>-p-<?= $i ?>"
                        id="<?= e($faq_id) ?>-b-<?= $i ?>">
                  <span class="rw-accordion__label"><?= e($f[0]) ?></span>
                  <span class="rw-accordion__chev" aria-hidden="true"><i class="bi bi-plus-lg"></i></span>
                </button>
              </h3>
              <div class="rw-accordion__panel" id="<?= e($faq_id) ?>-p-<?= $i ?>"
                   role="region" aria-labelledby="<?= e($faq_id) ?>-b-<?= $i ?>"<?= $i === 0 ? '' : ' hidden' ?>>
                <div class="rw-accordion__inner"><p><?= e($f[1]) ?></p></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>
