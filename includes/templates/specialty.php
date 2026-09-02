<?php
/**
 * Shared renderer for the sixteen specialty detail pages.
 *
 * The page file sets $slug and includes this. All copy lives in
 * includes/data/specialties.php.
 */
if (empty($slug) || !($sp = rw_specialty($slug))) {
    http_response_code(404);
    require dirname(__DIR__, 2) . '/404.php';
    exit;
}

$page_title       = $sp['meta_title'];
$meta_description = $sp['meta_desc'];
$canonical        = 'specialties/' . $slug . '.php';
$body_class       = 'page-specialty';
$breadcrumbs = [
    ['label' => 'Home',        'url' => 'index.php'],
    ['label' => 'Specialties', 'url' => 'specialties.php'],
    ['label' => $sp['title'],  'url' => $canonical],
];
$faq_schema = $sp['faqs'];

$rwPageSlug = $slug;
include dirname(__DIR__) . '/header.php';

// Re-resolve after the include: header.php renders the nav from the same data,
// and a stray loop variable there must never be able to change what this page shows.
$slug = $rwPageSlug;
$sp   = rw_specialty($slug);

rw_partial('page-header', [
    'ph_title'    => $sp['h1'],
    'ph_eyebrow'  => $sp['eyebrow'],
    'ph_lead'     => $sp['card_desc'],
    'breadcrumbs' => $breadcrumbs,
]);
?>

<!-- Intro ------------------------------------------------------------------- -->
<section class="rw-lead section-pad">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-6 order-lg-2" data-aos="fade-up">
        <div class="rw-lead__media">
          <?php rw_img($sp['image'], ['sizes' => '(max-width: 991px) 100vw, 48vw', 'eager' => true]); ?>
          <div class="rw-lead__chip">
            <span class="rw-lead__chip-value"><?= e($sp['stat']['value']) ?></span>
            <span class="rw-lead__chip-label"><?= e($sp['stat']['label']) ?></span>
          </div>
        </div>
      </div>
      <div class="col-lg-6 order-lg-1" data-aos="fade-up" data-aos-delay="100">
        <p class="rw-eyebrow">The challenge</p>
        <h2 class="rw-section-head__title mb-3">What makes <?= e($sp['prose']) ?> billing different</h2>
        <?php foreach ($sp['intro'] as $p): ?>
          <p class="rw-lead__para"><?= e($p) ?></p>
        <?php endforeach; ?>
        <div class="rw-lead__actions">
          <a class="btn btn-rw-navy" href="<?= e(rw_url('contact-us.php')) ?>">Discuss my practice</a>
          <a class="btn btn-rw-outline" href="<?= e(rw_url('specialties.php')) ?>">All specialties</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Why choose us ----------------------------------------------------------- -->
<section class="rw-whyspec section-pad bg-sky">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-5" data-aos="fade-up">
        <p class="rw-eyebrow">Why us</p>
        <h2 class="rw-section-head__title"><?= e($sp['why_head']) ?></h2>
        <p class="rw-section-head__lead">
          Specialty billing is not a marketing label for us. Coders and A/R staff are
          assigned by specialty, so the person on your account reads notes like yours
          every day.
        </p>
        <a class="btn btn-rw-navy mt-2" href="<?= e(rw_url('contact-us.php')) ?>">Get a free assessment</a>
      </div>
      <div class="col-lg-7" data-aos="fade-up" data-aos-delay="100">
        <ul class="rw-checklist rw-checklist--panel">
          <?php foreach ($sp['why'] as $w): ?>
            <li><i class="bi bi-check-lg" aria-hidden="true"></i><span><?= e($w) ?></span></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- What we handle ---------------------------------------------------------- -->
<section class="rw-includes section-pad">
  <div class="container">
    <div class="rw-section-head" data-aos="fade-up">
      <p class="rw-eyebrow">Scope of work</p>
      <h2 class="rw-section-head__title"><?= e($sp['handle_head']) ?></h2>
      <p class="rw-section-head__lead">The work below is specific to this specialty, not a generic billing checklist with the name swapped out.</p>
    </div>

    <div class="row g-4">
      <?php foreach ($sp['handles'] as $i => $h): ?>
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?= ($i % 3) * 90 ?>">
          <div class="rw-card h-100">
            <span class="rw-card__icon" data-tone="<?= $i % 8 ?>"><i class="bi <?= e($h[0]) ?>" aria-hidden="true"></i></span>
            <h3 class="rw-card__title"><?= e($h[1]) ?></h3>
            <p class="rw-card__text"><?= e($h[2]) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- The difference ---------------------------------------------------------- -->
<section class="rw-diff section-pad bg-navy">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-5" data-aos="fade-up">
        <p class="rw-eyebrow rw-eyebrow--light">The Right Way difference</p>
        <h2 class="rw-section-head__title rw-section-head__title--invert"><?= e($sp['diff_head']) ?></h2>
        <p class="rw-diff__lead">
          These are the details that separate a paid claim from an appealed one in
          <?= e($sp['prose']) ?>. They are checked on every claim, not sampled.
        </p>
      </div>
      <div class="col-lg-7" data-aos="fade-up" data-aos-delay="100">
        <ul class="rw-diff__list">
          <?php foreach ($sp['diffs'] as $i => $d): ?>
            <li>
              <span class="rw-diff__num"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
              <span class="rw-diff__text"><?= e($d) ?></span>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>
</section>

<?php
rw_partial('stats', [
    'stats_variant' => 'light',
    'stats' => [
        ['value' => rw_years(), 'suffix' => '+',  'label' => 'Years running revenue cycles for physician practices'],
        ['value' => 540,        'suffix' => '+',  'label' => 'Providers billed for across every specialty we serve'],
        ['value' => 98.6,       'suffix' => '%',  'label' => 'First-pass clean claim rate', 'decimals' => 1],
        ['value' => 31,         'suffix' => ' days', 'label' => 'Median days in accounts receivable'],
    ],
]);

rw_partial('faq', [
    'faqs'      => $sp['faqs'],
    'faq_id'    => 'spec-' . $slug,
    'faq_title' => $sp['nav'] . ' billing questions',
    'faq_lead'  => 'What practices in this specialty ask us most often before making a change.',
]);

rw_partial('related', [
    'rel_kind'  => 'services',
    'rel_slugs' => $sp['related_services'],
    'rel_title' => 'The services that move the needle here',
    'rel_lead'  => 'These three parts of the revenue cycle tend to matter most for practices in this specialty.',
]);

rw_partial('process', [
    'proc_title' => 'Getting started takes about a month',
    'proc_lead'  => 'The same four steps whichever specialty you are in. No system migration and no gap in your cash flow.',
]);

rw_partial('cta-banner', [
    'cta_title' => 'Let us look at your ' . $sp['prose'] . ' claims',
    'cta_text'  => 'Send us a sample and we will tell you specifically what is being coded, denied or under-paid — and what it is worth to fix. Free, and yours to keep.',
    'cta_btn'   => 'Request My Free Review',
]);

include dirname(__DIR__) . '/footer.php';
