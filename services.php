<?php
/**
 * Services hub.
 */
require __DIR__ . '/includes/config.php';

$page_title       = 'Medical Billing & RCM Services | Right Way Billing';
$meta_description = 'Eight revenue cycle services under one roof: medical billing, coding, denial management, credentialing, eligibility, patient billing, out-of-network and MIPS.';
$canonical        = 'services.php';
$body_class       = 'page-services';
$breadcrumbs = [
    ['label' => 'Home',     'url' => ''],
    ['label' => 'Services', 'url' => 'services.php'],
];

$services = rw_services();

$hub_faqs = [
    ['Can we outsource only one part of the revenue cycle?',
     'Yes, and plenty of practices do. Credentialing, coding and denial recovery are all commonly taken on their own. The only thing we ask is a clear boundary about who owns what, because the expensive failures happen where two parties each assume the other is handling something.'],
    ['How is outsourced billing priced?',
     'Usually as a percentage of collections, most often between four and nine percent depending on specialty, claim volume and average claim value. Project work such as credentialing or legacy A/R cleanup is quoted separately, because it is finite rather than ongoing.'],
    ['Is outsourcing actually cheaper than an in-house biller?',
     'Compare total cost rather than salary. In-house billing carries salary, payroll taxes, benefits, software licenses, clearinghouse fees, training, and the coverage problem when your biller is on leave. The more important variable, though, is usually collection rate rather than cost.'],
    ['What happens if we are not happy?',
     'Our agreements run month to month after the initial term, and we hand back complete records and system access on request. A billing partner that needs a long lock-in to keep clients is telling you something about its results.'],
    ['Do you work with new practices that have no billing history?',
     'Yes. New practices are actually easier in one respect, because there is no legacy A/R to untangle. We usually start with credentialing and enrollment, since nothing can be billed until the payer contracts are effective.'],
    ['How quickly will we see a difference?',
     'Clean claim rate and submission lag move within the first month, because those are process changes. Collections and A/R days take one to three months to show clearly, since claims submitted before the switch are still working through the payer cycle.'],
];
$faq_schema = $hub_faqs;

include __DIR__ . '/includes/header.php';

rw_partial('page-header', [
    'ph_title'    => 'Revenue cycle services, from charge capture to final appeal',
    'ph_eyebrow'  => 'Our services',
    'ph_lead'     => 'Take the entire cycle or the single part that is hurting most. Either way you get the same named team, the same reporting, and the same accountability for the result.',
    'breadcrumbs' => $breadcrumbs,
    'ph_image'    => 'services-hub',
]);
?>

<!-- Intro + grid -------------------------------------------------------------- -->
<section class="rw-services section-pad">
  <div class="container">
    <div class="rw-section-head" data-aos="fade-up">
      <p class="rw-eyebrow">What we do</p>
      <h2 class="rw-section-head__title">Eight services that connect into one process</h2>
      <p class="rw-section-head__lead">
        Most revenue leaks happen in the handoffs between these functions, which is
        exactly why we run them together rather than as separate products.
      </p>
    </div>

    <div class="row g-4">
      <?php foreach (array_keys($services) as $i => $slug): $s = $services[$slug]; ?>
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?= ($i % 3) * 90 ?>">
          <a class="rw-card rw-card--link h-100" href="<?= e(rw_url('services/' . $slug . '.php')) ?>">
            <span class="rw-card__icon" data-tone="<?= $i % 8 ?>"><i class="bi <?= e($s['icon']) ?>" aria-hidden="true"></i></span>
            <h3 class="rw-card__title"><?= e($s['title']) ?></h3>
            <p class="rw-card__text"><?= e($s['card_desc']) ?></p>
            <span class="rw-card__more">Explore this service <i class="bi bi-arrow-right" aria-hidden="true"></i></span>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- In-house vs outsourced ---------------------------------------------------- -->
<section class="rw-compare-sec section-pad bg-sky">
  <div class="container">
    <div class="rw-section-head" data-aos="fade-up">
      <p class="rw-eyebrow">Making the call</p>
      <h2 class="rw-section-head__title">In-house billing versus outsourcing, honestly compared</h2>
      <p class="rw-section-head__lead">
        In-house is the right answer for some practices. Here is the comparison we
        would want if we were the ones deciding.
      </p>
    </div>

    <div class="rw-compare" data-aos="fade-up" data-aos-delay="80">
      <div class="rw-compare__col rw-compare__col--theirs">
        <div class="rw-compare__head">
          <h3 class="rw-compare__title">Keeping it in house</h3>
        </div>
        <ul class="rw-compare__list">
          <li><i class="bi bi-x-circle-fill" aria-hidden="true"></i><span>Total cost is salary plus payroll taxes, benefits, software licenses, clearinghouse fees and training</span></li>
          <li><i class="bi bi-x-circle-fill" aria-hidden="true"></i><span>One or two people carry all the institutional knowledge, and leave with it</span></li>
          <li><i class="bi bi-x-circle-fill" aria-hidden="true"></i><span>Vacation, sickness and turnover create gaps where nothing gets submitted</span></li>
          <li><i class="bi bi-x-circle-fill" aria-hidden="true"></i><span>Keeping current with quarterly coding and payer policy changes is on you</span></li>
          <li><i class="bi bi-x-circle-fill" aria-hidden="true"></i><span>Denials get worked only after the day-to-day posting is finished, which it never is</span></li>
          <li><i class="bi bi-check-circle-fill" aria-hidden="true"></i><span>Direct control, immediate physical access, and no vendor relationship to manage</span></li>
        </ul>
      </div>

      <div class="rw-compare__col rw-compare__col--ours">
        <div class="rw-compare__head">
          <h3 class="rw-compare__title">Working with <?= e(BIZ_NAME) ?></h3>
          <span class="rw-compare__tag">Our approach</span>
        </div>
        <ul class="rw-compare__list">
          <li><i class="bi bi-check-circle-fill" aria-hidden="true"></i><span>One percentage covers staff, software, clearinghouse and training, and scales with volume</span></li>
          <li><i class="bi bi-check-circle-fill" aria-hidden="true"></i><span>A team rather than an individual, so knowledge does not walk out of the door</span></li>
          <li><i class="bi bi-check-circle-fill" aria-hidden="true"></i><span>Coverage every business day regardless of who is on leave</span></li>
          <li><i class="bi bi-check-circle-fill" aria-hidden="true"></i><span>Staying current with coding and payer rules is our job, not an extra task for your team</span></li>
          <li><i class="bi bi-check-circle-fill" aria-hidden="true"></i><span>Dedicated A/R specialists whose only job is working denials and aged claims</span></li>
          <li><i class="bi bi-check-circle-fill" aria-hidden="true"></i><span>Monthly reporting that shows the numbers whether or not they flatter us</span></li>
        </ul>
      </div>
    </div>
  </div>
</section>

<?php
rw_partial('stats', [
    'stats' => [
        ['value' => 98.6, 'suffix' => '%',    'label' => 'First-pass clean claim rate', 'decimals' => 1],
        ['value' => 31,   'suffix' => ' days','label' => 'Median days in accounts receivable'],
        ['value' => 74,   'suffix' => '%',    'label' => 'Denials overturned on appeal'],
        ['value' => 22,   'suffix' => '%',    'label' => 'Average collections lift in year one'],
    ],
]);

rw_partial('process');
rw_partial('why-choose', ['why_id' => 'svc-why']);
rw_partial('faq', [
    'faqs'      => $hub_faqs,
    'faq_id'    => 'services-faq',
    'faq_title' => 'Before you outsource anything',
    'faq_lead'  => 'The questions worth asking any billing company, including us.',
]);
rw_partial('cta-banner', [
    'cta_title' => 'Not sure which service you actually need?',
    'cta_text'  => 'That is normal, and it is what the free assessment is for. We look at your claims and denials and tell you where the biggest recoverable number is, even if that turns out to be something you can fix yourself.',
    'cta_btn'   => 'Book a Free Assessment',
]);

include __DIR__ . '/includes/footer.php';
