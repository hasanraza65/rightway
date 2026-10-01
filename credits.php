<?php
/**
 * Photo credits.
 *
 * The Pexels license does not require attribution, but crediting photographers
 * is requested and is the decent thing to do. This page is generated from the
 * image manifest so it stays accurate whenever the photography changes.
 */
require __DIR__ . '/includes/config.php';

$page_title       = 'Photo Credits | Right Way Medical Billing';
$meta_description = 'Photography used across the Right Way Medical Billing website, with credit to each photographer and a link to their work on Pexels.';
$canonical        = 'credits.php';
$body_class       = 'page-credits';
$breadcrumbs = [
    ['label' => 'Home',          'url' => ''],
    ['label' => 'Photo Credits', 'url' => 'credits.php'],
];

$manifest = rw_image_manifest();
$map      = rw_image_map();

include __DIR__ . '/includes/header.php';

rw_partial('page-header', [
    'ph_title'    => 'Photo credits',
    'ph_eyebrow'  => 'Attribution',
    'ph_lead'     => 'Every photograph on this site comes from Pexels. Credit to the photographers whose work makes these pages what they are.',
    'breadcrumbs' => $breadcrumbs,
]);
?>

<section class="section-pad">
  <div class="container">
    <?php if (!$manifest): ?>
      <p class="rw-section-head__lead">
        No cached photography is currently in place, so the site is showing its bundled
        placeholder artwork. Run <code>php tools/fetch-images.php</code> to populate it.
      </p>
    <?php else: ?>
      <p class="rw-prose__meta mb-4">
        <?= count($manifest) ?> photographs, sourced through the Pexels API and cached
        locally so pages load quickly. Photographer names link to their Pexels profiles.
      </p>
      <div class="rw-credits__wrap" data-aos="fade-up">
        <table class="rw-credits">
          <caption class="visually-hidden">Photographers credited for images used on this website</caption>
          <thead>
            <tr>
              <th scope="col">Used for</th>
              <th scope="col">Photographer</th>
              <th scope="col">Description</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($manifest as $key => $m): ?>
              <tr>
                <th scope="row"><?= e($key) ?></th>
                <td>
                  <?php if (!empty($m['photographer_url'])): ?>
                    <a href="<?= e($m['photographer_url']) ?>" target="_blank" rel="noopener nofollow"><?= e($m['photographer']) ?></a>
                  <?php else: ?>
                    <?= e($m['photographer'] ?: 'Unknown') ?>
                  <?php endif; ?>
                </td>
                <td><?= e($map[$key]['alt'] ?? $m['alt'] ?? '') ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <p class="rw-prose__meta mt-4">
        Photographs are used under the
        <a href="https://www.pexels.com/license/" target="_blank" rel="noopener nofollow">Pexels license</a>.
        The <?= e(BIZ_NAME) ?> logo and brand marks are not covered by that license and
        remain the property of <?= e(BIZ_LEGAL_NAME) ?>.
      </p>
    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php';
