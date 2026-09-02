<?php
/**
 * The four-step onboarding walkthrough, reused on the home page and on every
 * service and specialty page.
 *
 * @var string $proc_title
 * @var string $proc_lead
 * @var array  $proc_steps  [['icon','title','text'], ...] — defaults to onboarding
 */
$proc_title = $proc_title ?? 'How working with us actually starts';
$proc_lead  = $proc_lead  ?? 'No long implementation project, no switching systems. Four steps, and most practices are live inside a month.';
$proc_steps = $proc_steps ?? [
    ['bi-search',        'Revenue assessment',   'We audit a sample of your claims, denials and aged A/R and show you what we found. Free, and yours to keep either way.'],
    ['bi-file-earmark-text', 'Scope and agreement', 'A written scope covering exactly which services we run, what it costs and what we are accountable for.'],
    ['bi-plugin',        'Access and onboarding', 'We set up inside your existing EHR and clearinghouse. Two to four weeks, run in parallel with your current process.'],
    ['bi-graph-up-arrow','Live and reporting',   'We take over submission and follow-up, and you get monthly reporting you can actually act on.'],
];
?>
<section class="rw-process section-pad">
  <div class="container">
    <div class="rw-section-head" data-aos="fade-up">
      <p class="rw-eyebrow">How it works</p>
      <h2 class="rw-section-head__title"><?= e($proc_title) ?></h2>
      <p class="rw-section-head__lead"><?= e($proc_lead) ?></p>
    </div>

    <ol class="rw-process__list">
      <?php foreach ($proc_steps as $i => $s): ?>
        <li class="rw-process__step" data-aos="fade-up" data-aos-delay="<?= $i * 110 ?>">
          <span class="rw-process__num"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
          <span class="rw-process__icon" data-tone="<?= $i % 8 ?>"><i class="bi <?= e($s[0]) ?>" aria-hidden="true"></i></span>
          <h3 class="rw-process__title"><?= e($s[1]) ?></h3>
          <p class="rw-process__text"><?= e($s[2]) ?></p>
        </li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>
