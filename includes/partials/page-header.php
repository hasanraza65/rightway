<?php
/**
 * Inner-page hero band with breadcrumb.
 *
 * @var string $ph_title     H1 text
 * @var string $ph_eyebrow   small label above the H1
 * @var string $ph_lead      optional one-paragraph summary
 * @var array  $breadcrumbs  [['label' => ..., 'url' => ...], ...] — last item is the current page
 * @var string $ph_image     optional image key used as a tinted backdrop
 */
$ph_eyebrow = $ph_eyebrow ?? '';
$ph_lead    = $ph_lead    ?? '';
$crumbs     = $breadcrumbs ?? [];
$bg         = !empty($ph_image) ? rw_image($ph_image) : null;
?>
<section class="rw-pagehead<?= $bg ? ' rw-pagehead--image' : '' ?>">
  <?php if ($bg): ?>
    <div class="rw-pagehead__bg" aria-hidden="true">
      <img src="<?= e($bg['src']) ?>" alt="" width="<?= e($bg['width']) ?>" height="<?= e($bg['height']) ?>"
           loading="eager" decoding="async" fetchpriority="high">
    </div>
  <?php endif; ?>
  <span class="rw-pagehead__blob rw-pagehead__blob--a" aria-hidden="true"></span>
  <span class="rw-pagehead__blob rw-pagehead__blob--b" aria-hidden="true"></span>

  <div class="container">
    <?php if ($crumbs): ?>
      <nav class="rw-crumbs" aria-label="Breadcrumb">
        <ol>
          <?php foreach ($crumbs as $i => $c):
            $last = $i === count($crumbs) - 1; ?>
            <li>
              <?php if ($last): ?>
                <span aria-current="page"><?= e($c['label']) ?></span>
              <?php else: ?>
                <a href="<?= e(rw_url($c['url'])) ?>"><?= e($c['label']) ?></a>
                <i class="bi bi-chevron-right" aria-hidden="true"></i>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ol>
      </nav>
    <?php endif; ?>

    <div class="rw-pagehead__body">
      <?php if ($ph_eyebrow): ?>
        <p class="rw-eyebrow rw-eyebrow--light"><?= e($ph_eyebrow) ?></p>
      <?php endif; ?>
      <h1 class="rw-pagehead__title"><?= e($ph_title) ?></h1>
      <?php if ($ph_lead): ?>
        <p class="rw-pagehead__lead"><?= e($ph_lead) ?></p>
      <?php endif; ?>
    </div>
  </div>
</section>
