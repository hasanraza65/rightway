<?php
/**
 * The reassurance row that sits directly under a hero.
 *
 * @var array $trust_items  [['icon','title','text'], ...]
 */
$trust_items = $trust_items ?? [
    ['bi-shield-lock-fill', 'HIPAA compliant',      'Signed BAAs, encrypted transfer, audited access'],
    ['bi-patch-check-fill', '98.6% clean claims',   'First-pass acceptance across our whole book'],
    ['bi-lightning-fill',   '24 to 48 hour turnaround', 'Charges captured and submitted, not queued'],
    ['bi-hdd-network-fill', '50+ EHR integrations', 'We work inside the system you already use'],
];
?>
<section class="rw-trust">
  <div class="container">
    <ul class="rw-trust__list">
      <?php foreach ($trust_items as $i => $t): ?>
        <li class="rw-trust__item" data-aos="fade-up" data-aos-delay="<?= $i * 80 ?>">
          <span class="rw-trust__icon" data-tone="<?= $i % 8 ?>"><i class="bi <?= e($t[0]) ?>" aria-hidden="true"></i></span>
          <span class="rw-trust__body">
            <span class="rw-trust__title"><?= e($t[1]) ?></span>
            <span class="rw-trust__text"><?= e($t[2]) ?></span>
          </span>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
