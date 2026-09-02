<?php
/**
 * "Why choose us" accordion with a supporting image and progress bars.
 * One panel open at a time; fully keyboard operable via native <details>-style
 * buttons wired up in main.js.
 *
 * @var string $why_intro   optional replacement lead paragraph
 * @var string $why_id      unique id prefix when the component appears twice on a page
 * @var bool   $why_media   show the image + progress column (default true)
 */
$why_id    = $why_id    ?? 'why';
$why_intro = $why_intro ?? 'We are not the cheapest option and we do not try to be. What we offer is a billing operation that behaves like part of your practice rather than a vendor you chase.';
$why_media = $why_media ?? true;

$items = [
    ['bi-person-workspace', 'A named team, not a ticket queue',
     'You get specific people who know your payer mix, your providers and your quirks. They answer the phone, and they are the same people next quarter.'],
    ['bi-eye', 'Reporting you can actually read',
     'Monthly reporting in plain English covering collections, A/R aging, denial causes and payer performance, with the trend that matters called out rather than buried.'],
    ['bi-diagram-3', 'We fix causes, not just claims',
     'Every denial is classified to a root cause and reported back. Reworking a claim is worth something; stopping the next fifty is worth considerably more.'],
    ['bi-shield-lock', 'Security treated as a first-class concern',
     'Role-based access, encryption in transit and at rest, audited logins, signed business associate agreements and annual staff HIPAA training.'],
    ['bi-hdd-stack', 'We work in your systems',
     'No migration, no new software for your staff to learn. We operate inside the EHR and clearinghouse you already use, so nothing about your clinical day changes.'],
    ['bi-currency-dollar', 'Pricing that lines up with your results',
     'Percentage-of-collections pricing on most engagements, so we do better when you collect more. No charges for claims that never get paid.'],
    ['bi-arrow-repeat', 'A transition that does not drop revenue',
     'We run in parallel with your current process before taking over, and we work your legacy A/R rather than letting it age out during the handover.'],
];
?>
<section class="rw-why section-pad">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-<?= $why_media ? '7' : '12' ?>" data-aos="fade-up">
        <div class="rw-section-head rw-section-head--left">
          <p class="rw-eyebrow">Why Right Way</p>
          <h2 class="rw-section-head__title">Reasons practices stay with us</h2>
          <p class="rw-section-head__lead"><?= e($why_intro) ?></p>
        </div>

        <div class="rw-accordion" data-rw-accordion>
          <?php foreach ($items as $i => $it): ?>
            <div class="rw-accordion__item<?= $i === 0 ? ' is-open' : '' ?>">
              <h3 class="rw-accordion__heading">
                <button type="button" class="rw-accordion__btn"
                        aria-expanded="<?= $i === 0 ? 'true' : 'false' ?>"
                        aria-controls="<?= e($why_id) ?>-panel-<?= $i ?>"
                        id="<?= e($why_id) ?>-btn-<?= $i ?>">
                  <span class="rw-accordion__icon" data-tone="<?= $i % 8 ?>"><i class="bi <?= e($it[0]) ?>" aria-hidden="true"></i></span>
                  <span class="rw-accordion__label"><?= e($it[1]) ?></span>
                  <span class="rw-accordion__chev" aria-hidden="true"><i class="bi bi-plus-lg"></i></span>
                </button>
              </h3>
              <div class="rw-accordion__panel" id="<?= e($why_id) ?>-panel-<?= $i ?>"
                   role="region" aria-labelledby="<?= e($why_id) ?>-btn-<?= $i ?>"<?= $i === 0 ? '' : ' hidden' ?>>
                <div class="rw-accordion__inner"><p><?= e($it[2]) ?></p></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <?php if ($why_media): ?>
        <div class="col-lg-5" data-aos="fade-up" data-aos-delay="120">
          <div class="rw-why__media">
            <div class="rw-why__img">
              <?php rw_img('home-why', ['sizes' => '(max-width: 991px) 100vw, 40vw']); ?>
            </div>
            <div class="rw-why__metrics">
              <?php
              $bars = [
                  ['Clean claim rate on first pass', 98],
                  ['Client retention, year over year', 96],
                  ['Denials overturned on appeal', 74],
              ];
              foreach ($bars as $b): ?>
                <div class="rw-meter">
                  <div class="rw-meter__row">
                    <span class="rw-meter__label"><?= e($b[0]) ?></span>
                    <span class="rw-meter__val"><?= e((string) $b[1]) ?>%</span>
                  </div>
                  <div class="rw-meter__track" role="img"
                       aria-label="<?= e($b[0] . ': ' . $b[1] . ' percent') ?>">
                    <span class="rw-meter__fill" data-rw-meter="<?= e((string) $b[1]) ?>"></span>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
