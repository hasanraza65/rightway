<?php
/**
 * Specialties hub.
 */
require __DIR__ . '/includes/config.php';

$page_title       = 'Specialty Medical Billing Services | Right Way Billing';
$meta_description = 'Specialty-specific medical billing across sixteen fields, from gastroenterology and OB/GYN to behavioral health, wound care, podiatry, dental and urgent care.';
$canonical        = 'specialties.php';
$body_class       = 'page-specialties';
$breadcrumbs = [
    ['label' => 'Home',        'url' => ''],
    ['label' => 'Specialties', 'url' => 'specialties.php'],
];

$specialties = rw_specialties();

include __DIR__ . '/includes/header.php';

rw_partial('page-header', [
    'ph_title'    => 'Specialty billing, because every specialty has its own rulebook',
    'ph_eyebrow'  => 'Specialties we serve',
    'ph_lead'     => 'A coder who reads gastroenterology notes all day sees things a generalist reads straight past. That is the entire argument for specialty assignment, and we take it seriously.',
    'breadcrumbs' => $breadcrumbs,
    'ph_image'    => 'specialties-hub',
]);
?>

<!-- Grid ---------------------------------------------------------------------- -->
<section class="rw-spec-grid section-pad">
  <div class="container">
    <div class="rw-section-head" data-aos="fade-up">
      <p class="rw-eyebrow">Sixteen specialties</p>
      <h2 class="rw-section-head__title">Find the rules that apply to your practice</h2>
      <p class="rw-section-head__lead">
        Each page below covers the coding focus areas, payer behavior and documentation
        traps specific to that field &mdash; not a generic billing page with the
        specialty name swapped in.
      </p>
    </div>

    <div class="row g-3">
      <?php foreach (array_keys($specialties) as $i => $slug): $sp = $specialties[$slug]; ?>
        <div class="col-sm-6 col-lg-4 col-xxl-3" data-aos="fade-up" data-aos-delay="<?= ($i % 4) * 70 ?>">
          <a class="rw-tile" href="<?= e(rw_url('specialties/' . $slug . '.php')) ?>">
            <span class="rw-tile__icon" data-tone="<?= $i % 8 ?>"><i class="bi <?= e($sp['icon']) ?>" aria-hidden="true"></i></span>
            <span>
              <span class="rw-tile__name"><?= e($sp['title']) ?></span>
              <span class="rw-tile__text"><?= e($sp['card_desc']) ?></span>
            </span>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Why specialty billing matters --------------------------------------------- -->
<section class="rw-whyspec section-pad bg-sky">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-5" data-aos="fade-up">
        <p class="rw-eyebrow">Why it matters</p>
        <h2>Generic billing is where specialty revenue goes to die</h2>
        <p class="rw-lead__para">
          A biller who handles everything handles nothing especially well. The rules
          that decide whether a claim gets paid are almost always specialty-specific,
          and they are rarely written down anywhere a generalist would encounter them.
        </p>
        <p class="rw-lead__para">
          A screening colonoscopy that becomes therapeutic, a global maternity package
          split by a mid-pregnancy insurance change, routine foot care that needs
          documented class findings, a therapy visit that crosses the annual
          threshold &mdash; each of these is invisible until you know to look for it.
        </p>
        <a class="btn btn-rw-navy mt-2" href="<?= e(rw_url('contact-us.php')) ?>">Talk to a specialty coder</a>
      </div>

      <div class="col-lg-7" data-aos="fade-up" data-aos-delay="100">
        <ul class="rw-checklist rw-checklist--panel">
          <li><i class="bi bi-check-lg" aria-hidden="true"></i><span><strong>Coders assigned by specialty.</strong> The person coding your notes reads notes like yours every working day, not once a quarter.</span></li>
          <li><i class="bi bi-check-lg" aria-hidden="true"></i><span><strong>Payer policies tracked per field.</strong> Coverage rules differ sharply between specialties, and they change without much warning.</span></li>
          <li><i class="bi bi-check-lg" aria-hidden="true"></i><span><strong>Documentation feedback that fits your workflow.</strong> Generic advice to document more thoroughly helps nobody. Specific gaps do.</span></li>
          <li><i class="bi bi-check-lg" aria-hidden="true"></i><span><strong>Benchmarks from comparable practices.</strong> Your denial rate only means something next to others in your specialty.</span></li>
          <li><i class="bi bi-check-lg" aria-hidden="true"></i><span><strong>Appeals that cite the right policy.</strong> Specialty-specific coverage criteria are what make an appeal persuasive rather than generic.</span></li>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- Quick pill index ---------------------------------------------------------- -->
<section class="rw-spec-index section-pad">
  <div class="container">
    <div class="rw-section-head" data-aos="fade-up">
      <p class="rw-eyebrow">Quick index</p>
      <h2 class="rw-section-head__title">Jump straight to your field</h2>
    </div>
    <ul class="rw-pills" data-aos="fade-up" data-aos-delay="60">
      <?php foreach ($specialties as $slug => $sp): ?>
        <li>
          <a class="rw-pill" href="<?= e(rw_url('specialties/' . $slug . '.php')) ?>">
            <i class="bi <?= e($sp['icon']) ?>" aria-hidden="true"></i><?= e($sp['nav']) ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<?php
rw_partial('why-choose', [
    'why_id'    => 'spec-why',
    'why_intro' => 'Specialty knowledge is the reason practices come to us. These are the reasons they stay.',
]);

rw_partial('cta-banner', [
    'cta_title' => 'Do not see your specialty listed?',
    'cta_text'  => 'These sixteen are the fields we work in most, not the limit of what we handle. Tell us what you practice and we will be straight with you about whether we are the right fit.',
    'cta_btn'   => 'Ask About Your Specialty',
]);

include __DIR__ . '/includes/footer.php';
