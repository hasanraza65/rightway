<?php
/**
 * About Us.
 */
require __DIR__ . '/includes/config.php';

$page_title       = 'About Right Way Medical Billing | Our Team & Values';
$meta_description = 'Meet the people behind Right Way Medical Billing: certified coders, a named account team, HIPAA and SOC 2 aligned controls, and a decade of revenue cycle work.';
$canonical        = 'about-us.php';
$body_class       = 'page-about';
$breadcrumbs = [
    ['label' => 'Home',     'url' => 'index.php'],
    ['label' => 'About Us', 'url' => 'about-us.php'],
];

include __DIR__ . '/includes/header.php';

rw_partial('page-header', [
    'ph_title'    => 'A billing company built by people who were tired of billing companies',
    'ph_eyebrow'  => 'About us',
    'ph_lead'     => 'We run the revenue cycle for physician practices across the United States, and we do it the way we always wished our own vendors had.',
    'breadcrumbs' => $breadcrumbs,
    'ph_image'    => 'about-story',
]);
?>

<!-- Story ------------------------------------------------------------------- -->
<section class="rw-lead section-pad">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-6" data-aos="fade-up">
        <div class="rw-lead__media">
          <?php rw_img('about-story', ['sizes' => '(max-width: 991px) 100vw, 48vw', 'eager' => true]); ?>
          <div class="rw-lead__chip">
            <span class="rw-lead__chip-value">Since <?= e((string) BIZ_FOUNDED) ?></span>
            <span class="rw-lead__chip-label">working revenue cycles for U.S. physician practices</span>
          </div>
        </div>
      </div>

      <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
        <p class="rw-eyebrow">Our story</p>
        <h2>We started because the same complaint kept coming up</h2>
        <p class="rw-lead__para">
          Our founders spent years inside practice management and hospital revenue
          departments, and they kept hearing one version of the same story. The billing
          company was attentive through the sales process, then went quiet. Reports
          arrived as spreadsheets nobody could interpret. Denials piled up in a queue
          that belonged to nobody. And when a practice finally asked what was going
          wrong, the answer was usually a shrug.
        </p>
        <p class="rw-lead__para">
          <?= e(BIZ_NAME) ?> was built to be the opposite of that. Named people rather
          than a shared inbox. Reporting written in English rather than acronyms.
          Denials classified to a root cause so the same failure stops repeating. And
          pricing tied to what we actually collect, so nobody has to wonder whether our
          interests line up with theirs.
        </p>
        <p class="rw-lead__para">
          <?= e(rw_years()) ?> years later, we still measure ourselves the same way:
          how much of what a practice earned actually reaches its bank account, and how
          long it takes to get there.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Mission, vision, values -------------------------------------------------- -->
<section class="rw-values section-pad bg-sky">
  <div class="container">
    <div class="rw-section-head" data-aos="fade-up">
      <p class="rw-eyebrow">What drives us</p>
      <h2 class="rw-section-head__title">Mission, vision and the values we actually use</h2>
      <p class="rw-section-head__lead">
        These are not wall posters. They are the tests we apply when a decision is
        genuinely difficult.
      </p>
    </div>

    <div class="row g-4">
      <?php
      $values = [
          ['bi-bullseye', 'Our mission',
           'To make sure every practice we work with collects what it has already earned — completely, quickly, and without having to chase us for an explanation.'],
          ['bi-binoculars', 'Our vision',
           'A market where outsourced billing is judged on transparency and measurable results rather than on the lowest quoted percentage, because the cheapest biller is rarely the least expensive one.'],
          ['bi-hand-thumbs-up', 'Our values',
           'Say what is actually happening, including when the news is bad. Fix causes rather than symptoms. Protect patient data as though it were our own. And never let a claim sit without an owner.'],
      ];
      foreach ($values as $i => $v): ?>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="<?= $i * 110 ?>">
          <div class="rw-value h-100">
            <span class="rw-value__icon" data-tone="<?= $i % 8 ?>"><i class="bi <?= e($v[0]) ?>" aria-hidden="true"></i></span>
            <h3 class="rw-value__title"><?= e($v[1]) ?></h3>
            <p class="rw-value__text"><?= e($v[2]) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Certified team and technology -------------------------------------------- -->
<section class="rw-certified section-pad">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-6" data-aos="fade-up">
        <p class="rw-eyebrow">Trained and certified</p>
        <h2>Credentials we keep current, not credentials we once earned</h2>
        <p class="rw-lead__para">
          Coding rules change every quarter. NCCI edits are revised, guidelines are
          rewritten, and payer policies shift without much announcement. So continuing
          education is a scheduled part of the job here rather than something people
          fit in when work is quiet.
        </p>

        <ul class="rw-checklist mt-4">
          <li><i class="bi bi-check-lg" aria-hidden="true"></i><span>AAPC and AHIMA certified coders, credentials verified annually</span></li>
          <li><i class="bi bi-check-lg" aria-hidden="true"></i><span>Coders assigned by specialty rather than rotated through a general pool</span></li>
          <li><i class="bi bi-check-lg" aria-hidden="true"></i><span>Quarterly internal coding audits with written feedback per coder</span></li>
          <li><i class="bi bi-check-lg" aria-hidden="true"></i><span>Annual HIPAA training and attestation for every member of staff</span></li>
          <li><i class="bi bi-check-lg" aria-hidden="true"></i><span>Dedicated A/R specialists who work denials rather than posting payments</span></li>
        </ul>
      </div>

      <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
        <div class="rw-lead__media mb-5">
          <?php rw_img('about-certified', ['sizes' => '(max-width: 991px) 100vw, 48vw']); ?>
        </div>

        <h3 class="h5 mb-3">Systems we already work inside</h3>
        <p class="rw-lead__para mb-3">
          We adapt to your stack, not the other way round. If your platform supports
          remote user access, we can operate in it.
        </p>
        <ul class="rw-pills rw-pills--left">
          <?php foreach (['Epic', 'Cerner', 'athenahealth', 'eClinicalWorks', 'Kareo', 'AdvancedMD', 'DrChrono', 'NextGen', 'Practice Fusion', 'Allscripts'] as $sys): ?>
            <li><span class="rw-pill rw-pill--static"><i class="bi bi-hdd-network" aria-hidden="true"></i><?= e($sys) ?></span></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- Security and compliance --------------------------------------------------- -->
<section class="rw-compliance section-pad bg-navy">
  <div class="container">
    <div class="rw-section-head rw-section-head--invert" data-aos="fade-up">
      <p class="rw-eyebrow rw-eyebrow--light">Security and compliance</p>
      <h2 class="rw-section-head__title">How we protect the data you trust us with</h2>
      <p class="rw-section-head__lead">
        We handle protected health information every day. These are the controls that
        sit behind that, described plainly rather than as a list of logos.
      </p>
    </div>

    <div class="row g-4">
      <?php
      $controls = [
          ['bi-shield-lock', 'HIPAA compliance',
           'A signed business associate agreement is in place before any access is granted. Staff are trained annually, access is role-based, and every record view is logged and auditable.'],
          ['bi-file-lock2', 'SOC 2 aligned controls',
           'We operate against the SOC 2 trust criteria for security, availability and confidentiality: documented change management, access reviews, incident response and vendor assessment.'],
          ['bi-lock', 'Encryption in transit and at rest',
           'Data moves over TLS and is encrypted at rest. Nothing containing patient information travels by unencrypted email, and file exchange happens through secured channels only.'],
          ['bi-person-check', 'Least-privilege access',
           'Staff see only the accounts they are assigned to. Access is reviewed when roles change and revoked the same day someone leaves. Multi-factor authentication is mandatory.'],
      ];
      foreach ($controls as $i => $c): ?>
        <div class="col-md-6" data-aos="fade-up" data-aos-delay="<?= ($i % 2) * 100 ?>">
          <div class="rw-control h-100">
            <span class="rw-control__icon"><i class="bi <?= e($c[0]) ?>" aria-hidden="true"></i></span>
            <div>
              <h3 class="rw-control__title"><?= e($c[1]) ?></h3>
              <p class="rw-control__text"><?= e($c[2]) ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php
rw_partial('stats', [
    'stats_variant' => 'light',
    'stats' => [
        ['value' => rw_years(), 'suffix' => '+',  'label' => 'Years in revenue cycle management'],
        ['value' => 540,        'suffix' => '+',  'label' => 'Providers currently billed for'],
        ['value' => 24,         'suffix' => '',   'label' => 'Specialties and services covered in house'],
        ['value' => 96,         'suffix' => '%',  'label' => 'Client retention, year over year'],
    ],
]);
?>

<!-- Team --------------------------------------------------------------------- -->
<section class="rw-team section-pad">
  <div class="container">
    <div class="rw-section-head" data-aos="fade-up">
      <p class="rw-eyebrow">The people on your account</p>
      <h2 class="rw-section-head__title">You will know who is working your claims</h2>
      <p class="rw-section-head__lead">
        Every practice gets a named team across these four functions. You get their
        direct contact details on day one.
      </p>
    </div>

    <div class="row g-4">
      <?php
      $team = [
          ['team-1', 'Director of Client Success', 'Owns the relationship, runs your monthly review and is the person you call when something needs to move faster.'],
          ['team-2', 'Lead Coding Specialist',     'Certified coder assigned to your specialty. Reviews code selection, runs audits and sends documentation feedback to your providers.'],
          ['team-3', 'Denial Management Supervisor','Owns the denial queue, writes the appeals and reports the root-cause breakdown that tells you what to fix upstream.'],
          ['team-4', 'Credentialing Manager',      'Tracks every payer application, revalidation and expiring credential so nobody on your roster quietly falls out of network.'],
      ];
      foreach ($team as $i => $t): ?>
        <div class="col-sm-6 col-lg-3" data-aos="fade-up" data-aos-delay="<?= $i * 90 ?>">
          <article class="rw-person h-100">
            <div class="rw-person__photo">
              <?php rw_img($t[0], ['alt' => 'Portrait representing the ' . $t[1] . ' role', 'sizes' => '(max-width: 767px) 100vw, 25vw']); ?>
            </div>
            <div class="rw-person__body">
              <h3 class="rw-person__name"><?= e($t[1]) ?></h3>
              <p class="rw-person__role">Assigned to every account</p>
              <p class="rw-person__text"><?= e($t[2]) ?></p>
            </div>
          </article>
        </div>
      <?php endforeach; ?>
    </div>

    <p class="rw-form__fine mt-4" data-aos="fade-up">
      <i class="bi bi-info-circle" aria-hidden="true"></i>
      These images are stock photography illustrating the roles on a standard account
      team. They will be replaced with photographs of the real team before launch.
    </p>
  </div>
</section>

<?php
rw_partial('why-choose', [
    'why_id'    => 'about-why',
    'why_intro' => 'The short version of what makes us different from the last billing company you tried.',
]);

rw_partial('cta-banner', [
    'cta_title' => 'Come and ask us the hard questions',
    'cta_text'  => 'Bring your worst denial category, your oldest A/R bucket or the report you have never been able to make sense of. Thirty minutes, no cost, and no obligation afterwards.',
    'cta_btn'   => 'Talk to Our Team',
]);

include __DIR__ . '/includes/footer.php';
