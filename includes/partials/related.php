<?php
/**
 * Cross-link module. Service pages surface related specialties and specialty
 * pages surface related services, so every detail page links onward to three
 * genuinely relevant siblings rather than a random selection.
 *
 * @var string $rel_kind   'specialties' or 'services'
 * @var array  $rel_slugs  three slugs from the matching catalogue
 * @var string $rel_title
 * @var string $rel_lead
 */
$isSpec = ($rel_kind ?? 'services') === 'specialties';
$all    = $isSpec ? rw_specialties() : rw_services();
$items  = rw_pick($all, $rel_slugs ?? []);
$dir    = $isSpec ? 'specialties/' : 'services/';
if (!$items) return;
?>
<section class="rw-related section-pad">
  <div class="container">
    <div class="rw-section-head" data-aos="fade-up">
      <p class="rw-eyebrow"><?= $isSpec ? 'Related specialties' : 'Related services' ?></p>
      <h2 class="rw-section-head__title"><?= e($rel_title ?? 'Where this shows up next') ?></h2>
      <?php if (!empty($rel_lead)): ?>
        <p class="rw-section-head__lead"><?= e($rel_lead) ?></p>
      <?php endif; ?>
    </div>

    <div class="row g-4">
      <?php foreach (array_values($items) as $i => $it):
        $slug = array_keys($items)[$i]; ?>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="<?= $i * 100 ?>">
          <a class="rw-card rw-card--link h-100" href="<?= e(rw_url($dir . $slug . '.php')) ?>">
            <span class="rw-card__icon" data-tone="<?= $i % 8 ?>"><i class="bi <?= e($it['icon']) ?>" aria-hidden="true"></i></span>
            <h3 class="rw-card__title"><?= e($it['title']) ?></h3>
            <p class="rw-card__text"><?= e($it['card_desc']) ?></p>
            <span class="rw-card__more">Read more <i class="bi bi-arrow-right" aria-hidden="true"></i></span>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
