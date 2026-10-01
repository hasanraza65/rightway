<?php
/**
 * Shared renderer for the eight service detail pages.
 *
 * The page file sets $slug and includes this. All of the copy comes from
 * includes/data/services.php, so each page is genuinely distinct content while
 * the layout stays in one place.
 */
if (empty($slug) || !($svc = rw_service($slug))) {
    http_response_code(404);
    require dirname(__DIR__, 2) . '/404.php';
    exit;
}

$page_title       = $svc['meta_title'];
$meta_description = $svc['meta_desc'];
$canonical        = 'services/' . $slug . '.php';
$body_class       = 'page-service';
$breadcrumbs = [
    ['label' => 'Home',     'url' => ''],
    ['label' => 'Services', 'url' => 'services.php'],
    ['label' => $svc['title'], 'url' => $canonical],
];
$service_schema = ['name' => $svc['title'], 'description' => $svc['meta_desc']];
$faq_schema     = $svc['faqs'];

$rwPageSlug = $slug;
include dirname(__DIR__) . '/header.php';

// Re-resolve after the include, for the same reason as the specialty template.
$slug = $rwPageSlug;
$svc  = rw_service($slug);

rw_partial('page-header', [
    'ph_title'   => $svc['h1'],
    'ph_eyebrow' => $svc['eyebrow'],
    'ph_lead'    => $svc['card_desc'],
    'breadcrumbs'=> $breadcrumbs,
]);
?>

<!-- Intro ------------------------------------------------------------------ -->
<section class="rw-lead section-pad">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-6" data-aos="fade-up">
        <div class="rw-lead__media">
          <?php rw_img($svc['image'], ['sizes' => '(max-width: 991px) 100vw, 48vw', 'eager' => true]); ?>
          <div class="rw-lead__chip">
            <span class="rw-lead__chip-value"><?= e($svc['stat']['value']) ?></span>
            <span class="rw-lead__chip-label"><?= e($svc['stat']['label']) ?></span>
          </div>
        </div>
      </div>
      <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
        <p class="rw-eyebrow">The short version</p>
        <h2 class="rw-section-head__title mb-3"><?= e($svc['title']) ?> without the guesswork</h2>
        <?php foreach ($svc['intro'] as $p): ?>
          <p class="rw-lead__para"><?= e($p) ?></p>
        <?php endforeach; ?>
        <div class="rw-lead__actions">
          <a class="btn btn-rw-navy" href="<?= e(rw_url('contact-us.php')) ?>">Talk to a specialist</a>
          <a class="btn btn-rw-outline" href="<?= e(rw_url('services.php')) ?>">All services</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Why this matters -------------------------------------------------------- -->
<section class="rw-pains section-pad bg-sky">
  <div class="container">
    <div class="rw-section-head" data-aos="fade-up">
      <p class="rw-eyebrow">Why this matters</p>
      <h2 class="rw-section-head__title"><?= e($svc['pain_head']) ?></h2>
      <p class="rw-section-head__lead"><?= e($svc['pain_intro']) ?></p>
    </div>

    <ul class="rw-pains__list">
      <?php foreach ($svc['pains'] as $i => $p): ?>
        <li class="rw-pains__item" data-aos="fade-up" data-aos-delay="<?= $i * 70 ?>">
          <span class="rw-pains__mark" aria-hidden="true"><i class="bi bi-exclamation-lg"></i></span>
          <div>
            <h3 class="rw-pains__title"><?= e($p[0]) ?></h3>
            <p class="rw-pains__text"><?= e($p[1]) ?></p>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<!-- What is included -------------------------------------------------------- -->
<section class="rw-includes section-pad">
  <div class="container">
    <div class="rw-section-head" data-aos="fade-up">
      <p class="rw-eyebrow">Scope of work</p>
      <h2 class="rw-section-head__title"><?= e($svc['inc_head']) ?></h2>
      <p class="rw-section-head__lead">Everything below is in scope from day one. Nothing here is an upsell later.</p>
    </div>

    <div class="row g-4">
      <?php foreach ($svc['includes'] as $i => $inc): ?>
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?= ($i % 3) * 90 ?>">
          <div class="rw-card h-100">
            <span class="rw-card__icon" data-tone="<?= $i % 8 ?>"><i class="bi <?= e($inc[0]) ?>" aria-hidden="true"></i></span>
            <h3 class="rw-card__title"><?= e($inc[1]) ?></h3>
            <p class="rw-card__text"><?= e($inc[2]) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Our process ------------------------------------------------------------- -->
<section class="rw-steps section-pad bg-navy">
  <div class="container">
    <div class="rw-section-head rw-section-head--invert" data-aos="fade-up">
      <p class="rw-eyebrow rw-eyebrow--light">Workflow</p>
      <h2 class="rw-section-head__title"><?= e($svc['proc_head']) ?></h2>
      <p class="rw-section-head__lead">The same sequence runs every day, which is what makes the output predictable.</p>
    </div>

    <ol class="rw-steps__list">
      <?php foreach ($svc['process'] as $i => $st): ?>
        <li class="rw-steps__item" data-aos="fade-up" data-aos-delay="<?= $i * 60 ?>">
          <span class="rw-steps__num"><?= $i + 1 ?></span>
          <div>
            <h3 class="rw-steps__title"><?= e($st[0]) ?></h3>
            <p class="rw-steps__text"><?= e($st[1]) ?></p>
          </div>
        </li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>

<?php
rw_partial('faq', [
    'faqs'      => $svc['faqs'],
    'faq_id'    => 'svc-' . $slug,
    'faq_title' => 'About ' . $svc['title'],
    'faq_lead'  => 'What practices usually want to know before handing this part of the revenue cycle over.',
]);

rw_partial('related', [
    'rel_kind'  => 'specialties',
    'rel_slugs' => $svc['related_specialties'],
    'rel_title' => 'Specialties where this makes the biggest difference',
    'rel_lead'  => 'The coding and payer nuances differ by specialty. These three see the largest swing from getting this right.',
]);

rw_partial('process');

rw_partial('cta-banner', [
    'cta_title' => 'Want a read on your ' . $svc['prose'] . '?',
    'cta_text'  => 'We will review a sample of your claims and come back with what we found. No cost, no commitment, and the findings are yours regardless of what you decide.',
    'cta_btn'   => 'Get My Free Assessment',
]);

include dirname(__DIR__) . '/footer.php';
