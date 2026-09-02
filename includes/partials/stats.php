<?php
/**
 * Animated counter band. Counts up once when scrolled into view.
 *
 * @var array  $stats   [['value' => 12, 'suffix' => '+', 'prefix' => '', 'label' => '...'], ...]
 * @var string $stats_variant  'dark' (default) or 'light'
 */
$stats = $stats ?? [];
$variant = $stats_variant ?? 'dark';
?>
<section class="rw-stats rw-stats--<?= e($variant) ?>">
  <div class="container">
    <div class="rw-stats__grid">
      <?php foreach ($stats as $i => $s): ?>
        <div class="rw-stats__item" data-aos="fade-up" data-aos-delay="<?= $i * 90 ?>">
          <p class="rw-stats__value">
            <span data-rw-count="<?= e((string) $s['value']) ?>"
                  data-rw-decimals="<?= e((string) ($s['decimals'] ?? 0)) ?>"><?= e((string) ($s['prefix'] ?? '')) ?>0</span><span class="rw-stats__suffix"><?= e($s['suffix'] ?? '') ?></span>
          </p>
          <p class="rw-stats__label"><?= e($s['label']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
