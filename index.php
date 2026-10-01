<?php
/**
 * Home page.
 */
require __DIR__ . '/includes/config.php';

$page_title       = 'Medical Billing Company for U.S. Practices | Right Way';
$meta_description = 'Right Way Medical Billing runs the full revenue cycle for physician practices: billing, coding, credentialing and denial recovery. Fewer denials, faster payment.';
$canonical        = '';
$body_class       = 'page-home';
$breadcrumbs      = [['label' => 'Home', 'url' => '']];

$services    = rw_services();
$specialties = rw_specialties();

/* FAQ content is declared once and used for both the visible accordion and the
   FAQPage structured data, so the two can never drift apart. */
$home_faqs = [
    ['How much do medical billing services cost?',
     'Most engagements are priced as a percentage of what we actually collect for you, which typically lands between four and nine percent depending on specialty, claim volume and average claim value. Percentage pricing means we only earn when you get paid. Credentialing and legacy A/R cleanup are usually quoted separately because they are finite projects.'],
    ['Do we have to switch our EHR or practice management software?',
     'No. We work inside whatever you already use. Our staff has operated in Epic, Cerner, athenahealth, eClinicalWorks, Kareo, AdvancedMD, DrChrono, NextGen and others. Nothing about your clinical day changes, and your team learns no new software.'],
    ['How long does it take to switch billing companies?',
     'Two to four weeks for most practices. Week one is access provisioning and a baseline audit of your A/R, week two runs in parallel with your current process, and we take full ownership once a clean batch has posted successfully. Running in parallel is what prevents a cash flow gap during the handover.'],
    ['What happens to the accounts receivable we already have?',
     'We audit it and tell you plainly what is still recoverable. Anything inside timely filing gets worked alongside your current claims. Anything genuinely past the deadline gets documented so you stop carrying uncollectible balances on your books indefinitely.'],
    ['Are you HIPAA compliant, and how is our data protected?',
     'Yes. We sign a business associate agreement before any access is granted, use role-based permissions so staff see only the accounts they work, encrypt data in transit and at rest, log every access, and run HIPAA training for all staff annually.'],
    ['Will our patients know they are dealing with an outside company?',
     'Only if you want them to. We answer the phone in your practice name, follow your financial policies, and use your tone. To a patient it is simply your billing office, which is the experience most practices want.'],
];
$faq_schema = $home_faqs;

include __DIR__ . '/includes/header.php';
?>

<!-- Hero -------------------------------------------------------------------- -->
<section class="rw-hero">
  <!-- Decorative only: soft light-blue field plus the drifting glyphs that give
       the band depth without competing with the copy. -->
  <span class="rw-hero__wash" aria-hidden="true"></span>
  <span class="rw-hero__glyph rw-hero__glyph--a" aria-hidden="true"><i class="bi bi-plus-lg"></i></span>
  <span class="rw-hero__glyph rw-hero__glyph--b" aria-hidden="true"><i class="bi bi-clipboard2-pulse"></i></span>
  <span class="rw-hero__glyph rw-hero__glyph--c" aria-hidden="true"><i class="bi bi-heart-pulse"></i></span>

  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-6">
        <p class="rw-eyebrow">Revenue cycle management for U.S. practices</p>
        <h1 class="rw-hero__title">
          Get paid the <em>Right Way</em> for care you have already delivered
        </h1>
        <p class="rw-hero__lead">
          Most practices are not underpaid because they undercharge. They are underpaid
          because claims leak out of a process nobody owns end to end. We own it &mdash;
          coding, submission, follow-up and appeals &mdash; and we report back in numbers
          you can act on.
        </p>
        <div class="rw-hero__actions">
          <a class="btn btn-rw-teal btn-lg" href="<?= e(rw_url('contact-us.php')) ?>">Get a Free Consultation</a>
          <a class="btn btn-rw-outline btn-lg" href="tel:<?= e(rw_tel()) ?>">
            <i class="bi bi-telephone-fill" aria-hidden="true"></i> Call <?= e(BIZ_PHONE) ?>
          </a>
        </div>
        <ul class="rw-hero__badges">
          <li><i class="bi bi-shield-lock-fill" aria-hidden="true"></i> HIPAA compliant</li>
          <li><i class="bi bi-patch-check-fill" aria-hidden="true"></i> AAPC certified coders</li>
          <li><i class="bi bi-clipboard2-check-fill" aria-hidden="true"></i> SOC 2 aligned controls</li>
        </ul>
      </div>

      <div class="col-lg-6">
        <div class="rw-hero__media">
          <!-- Blurred clinic interior behind a navy/teal tint: present enough to
               read as a real setting, quiet enough not to fight the copy. -->
          <div class="rw-hero__stage" aria-hidden="true">
            <img src="<?= e(rw_url('assets/img/hero-backdrop.jpg')) ?>"
                 alt="" width="1000" height="1000" fetchpriority="high" decoding="async">
            <span class="rw-hero__stage-tint"></span>
          </div>
          <img class="rw-hero__cutout"
               src="<?= e(rw_url('assets/img/hero-dr.png')) ?>"
               alt="Physician holding a tablet, representing the practices Right Way Medical Billing serves"
               width="432" height="646" fetchpriority="high" decoding="sync">

          <div class="rw-hero__float rw-hero__float--a">
            <span class="rw-hero__float-icon"><i class="bi bi-graph-up-arrow" aria-hidden="true"></i></span>
            <span>
              <span class="rw-hero__float-value">98.6%</span>
              <span class="rw-hero__float-label">clean claims, first pass</span>
            </span>
          </div>

          <div class="rw-hero__float rw-hero__float--b">
            <span class="rw-hero__float-icon"><i class="bi bi-hourglass-split" aria-hidden="true"></i></span>
            <span>
              <span class="rw-hero__float-value">31 days</span>
              <span class="rw-hero__float-label">median days in A/R</span>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php rw_partial('trust-strip'); ?>

<!-- About teaser ------------------------------------------------------------ -->
<section class="rw-about-teaser section-pad">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-6" data-aos="fade-up">
        <div class="rw-lead__media">
          <?php rw_img('home-about', ['sizes' => '(max-width: 991px) 100vw, 48vw']); ?>
          <div class="rw-lead__chip">
            <span class="rw-lead__chip-value"><?= e(rw_years()) ?> years</span>
            <span class="rw-lead__chip-label">running revenue cycles for physician practices</span>
          </div>
        </div>
      </div>

      <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
        <p class="rw-eyebrow">About Right Way</p>
        <h2>A billing partner that behaves like part of your practice</h2>
        <p class="rw-lead__para">
          We started <?= e(BIZ_NAME) ?> because practices kept telling us the same thing:
          their billing company was responsive during the sales call and invisible
          afterwards. So we built the opposite. You get named people, a visible
          workflow and reporting that tells you the truth about your revenue.
        </p>
        <ul class="rw-checklist mt-4">
          <li><i class="bi bi-check-lg" aria-hidden="true"></i><span>A named account team you can call, not a shared inbox</span></li>
          <li><i class="bi bi-check-lg" aria-hidden="true"></i><span>Coders assigned by specialty, so they read notes like yours daily</span></li>
          <li><i class="bi bi-check-lg" aria-hidden="true"></i><span>Every denial classified to a root cause and reported back monthly</span></li>
          <li><i class="bi bi-check-lg" aria-hidden="true"></i><span>We work inside your existing EHR &mdash; no migration, no new software</span></li>
          <li><i class="bi bi-check-lg" aria-hidden="true"></i><span>Percentage-of-collections pricing, so our incentives match yours</span></li>
        </ul>
        <a class="btn btn-rw-navy mt-4" href="<?= e(rw_url('about-us.php')) ?>">More about how we work</a>
      </div>
    </div>
  </div>
</section>

<!-- Who we serve ------------------------------------------------------------ -->
<section class="rw-serve section-pad bg-sky">
  <div class="container">
    <div class="rw-section-head" data-aos="fade-up">
      <p class="rw-eyebrow">Who we serve</p>
      <h2 class="rw-section-head__title">Built for the way your organization actually bills</h2>
      <p class="rw-section-head__lead">
        A solo practice and a twelve-site group have almost nothing in common
        operationally. We staff and price accordingly.
      </p>
    </div>

    <ul class="rw-serve__grid">
      <?php
      $serve = [
          ['bi-house-door', 'Private practices',   'Solo and small group physician practices'],
          ['bi-buildings',  'Multi-site clinics',  'Groups billing across several locations'],
          ['bi-hospital',   'Hospital departments','Provider-based and outpatient departments'],
          ['bi-scissors',   'Surgery centers',     'Ambulatory surgery with facility and professional splits'],
          ['bi-person-badge','Specialty physicians','Procedural and cognitive specialties alike'],
          ['bi-clock-history','Ambulatory and urgent care','High-volume episodic care settings'],
      ];
      foreach ($serve as $i => $s): ?>
        <li class="rw-serve__item" data-aos="fade-up" data-aos-delay="<?= ($i % 3) * 80 ?>">
          <span class="rw-serve__icon" data-tone="<?= $i % 8 ?>"><i class="bi <?= e($s[0]) ?>" aria-hidden="true"></i></span>
          <h3 class="rw-serve__name"><?= e($s[1]) ?></h3>
          <p class="rw-serve__text"><?= e($s[2]) ?></p>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<?php
rw_partial('stats', [
    'stats' => [
        ['value' => rw_years(), 'suffix' => '+',  'label' => 'Years running revenue cycles'],
        ['value' => 540,        'suffix' => '+',  'label' => 'Providers billed for'],
        ['value' => 1.4,        'suffix' => 'M+', 'label' => 'Claims processed to date', 'decimals' => 1],
        ['value' => 22,         'suffix' => '%',  'label' => 'Average lift in collections in year one'],
    ],
]);

rw_partial('process');
?>

<!-- Services ---------------------------------------------------------------- -->
<section class="rw-services section-pad bg-sky">
  <div class="container">
    <div class="rw-section-head" data-aos="fade-up">
      <p class="rw-eyebrow">What we do</p>
      <h2 class="rw-section-head__title">Eight services that add up to one revenue cycle</h2>
      <p class="rw-section-head__lead">
        Take the whole cycle or the one part that is hurting. Either way you get the
        same team, the same reporting and the same accountability.
      </p>
    </div>

    <div class="row g-4">
      <?php foreach ($services as $slug => $s): ?>
        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="<?= (array_search($slug, array_keys($services), true) % 4) * 80 ?>">
          <a class="rw-card rw-card--link h-100" href="<?= e(rw_url('services/' . $slug . '.php')) ?>">
            <span class="rw-card__icon" data-tone="<?= array_search($slug, array_keys($services), true) % 8 ?>"><i class="bi <?= e($s['icon']) ?>" aria-hidden="true"></i></span>
            <h3 class="rw-card__title"><?= e($s['title']) ?></h3>
            <p class="rw-card__text"><?= e($s['card_desc']) ?></p>
            <span class="rw-card__more">Learn more <i class="bi bi-arrow-right" aria-hidden="true"></i></span>
          </a>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="text-center mt-5" data-aos="fade-up">
      <a class="btn btn-rw-navy btn-lg" href="<?= e(rw_url('services.php')) ?>">View All Services</a>
    </div>
  </div>
</section>

<!-- Specialties ------------------------------------------------------------- -->
<section class="rw-specialties section-pad rw-specband">
  <div class="container">
    <div class="rw-section-head" data-aos="fade-up">
      <p class="rw-eyebrow">Specialties</p>
      <h2 class="rw-section-head__title">Expertise across <?= count($specialties) ?> medical specialties</h2>
      <p class="rw-section-head__lead">
        Coders and A/R staff are assigned by specialty, because the person reading your
        notes should recognize them without having to look anything up.
      </p>
    </div>

    <ul class="rw-spec-grid" data-aos="fade-up" data-aos-delay="80">
      <?php
      /* Icon wells cycle through eight tints. Eight over sixteen means each tone
         is used exactly twice and never lands next to itself in a six-column
         row, so the variety reads as deliberate rather than random. */
      $toneCount = 8;
      foreach (array_keys($specialties) as $i => $slug):
        $sp = $specialties[$slug]; ?>
        <li>
          <a class="rw-spec-tile" href="<?= e(rw_url('specialties/' . $slug . '.php')) ?>">
            <span class="rw-spec-tile__icon" data-tone="<?= $i % $toneCount ?>">
              <i class="bi <?= e($sp['icon']) ?>" aria-hidden="true"></i>
            </span>
            <span class="rw-spec-tile__name"><?= e($sp['nav']) ?></span>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>

    <div class="text-center mt-5" data-aos="fade-up">
      <a class="btn btn-rw-outline btn-lg" href="<?= e(rw_url('specialties.php')) ?>">View All Specialties</a>
    </div>
  </div>
</section>

<?php rw_partial('why-choose'); ?>

<!-- Book a consultation ------------------------------------------------------ -->
<section class="rw-booking bg-navy">
  <div class="container">
    <div class="rw-booking__panel" data-aos="fade-up">

      <div class="rw-booking__aside">
        <?php rw_img('home-booking', ['sizes' => '(max-width: 991px) 100vw, 40vw']); ?>
        <span class="rw-booking__tint" aria-hidden="true"></span>
        <div class="rw-booking__aside-body">
          <span class="rw-booking__badge" aria-hidden="true"><i class="bi bi-calendar2-check"></i></span>
          <h2 class="rw-booking__title">Book a consultation and stop guessing at your revenue</h2>
          <p class="rw-booking__text">
            Pick a time that suits you. We will review a sample of your claims, denials
            and aged A/R beforehand, so the call starts with findings rather than
            introductions. No cost, and no obligation afterwards.
          </p>
          <ul class="rw-booking__points">
            <li><i class="bi bi-check-lg" aria-hidden="true"></i>Answered within one business day</li>
            <li><i class="bi bi-check-lg" aria-hidden="true"></i>Runs about 30 minutes</li>
            <li><i class="bi bi-check-lg" aria-hidden="true"></i>Findings are yours to keep</li>
          </ul>
        </div>
      </div>

      <div class="rw-booking__form">
        <form action="<?= e(rw_url('handlers/contact-handler.php')) ?>" method="post" novalidate data-rw-form="booking">
          <input type="hidden" name="rw_token" value="<?= e(rw_csrf_token()) ?>">
          <input type="hidden" name="rw_source" value="booking-form">

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label" for="bk-name">Your name <span class="rw-req" aria-hidden="true">*</span></label>
              <input class="form-control" type="text" id="bk-name" name="name" required
                     autocomplete="name" maxlength="120" placeholder="Dr. Jane Okafor">
              <p class="rw-field__err" data-rw-err></p>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="bk-email">Work email <span class="rw-req" aria-hidden="true">*</span></label>
              <input class="form-control" type="email" id="bk-email" name="email" required
                     autocomplete="email" maxlength="180" placeholder="jane@yourpractice.com">
              <p class="rw-field__err" data-rw-err></p>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="bk-phone">Phone <span class="rw-req" aria-hidden="true">*</span></label>
              <input class="form-control" type="tel" id="bk-phone" name="phone" required
                     autocomplete="tel" maxlength="40" placeholder="(555) 123-4567">
              <p class="rw-field__err" data-rw-err></p>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="bk-date">Preferred date</label>
              <input class="form-control" type="date" id="bk-date" name="preferred"
                     min="<?= e(date('Y-m-d')) ?>">
              <p class="rw-field__err" data-rw-err></p>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="bk-interest">What do you need?</label>
              <select class="form-select" id="bk-interest" name="interest">
                <option value="">Select a service</option>
                <?php foreach ($services as $bkSvc): ?>
                  <option value="<?= e($bkSvc['title']) ?>"><?= e($bkSvc['title']) ?></option>
                <?php endforeach; ?>
                <option value="Full revenue cycle">The whole revenue cycle</option>
                <option value="Not sure yet">Not sure yet</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label" for="bk-specialty">Your specialty</label>
              <select class="form-select" id="bk-specialty" name="specialty">
                <option value="">Select your specialty</option>
                <?php foreach ($specialties as $bkSpec): ?>
                  <option value="<?= e($bkSpec['nav']) ?>"><?= e($bkSpec['nav']) ?></option>
                <?php endforeach; ?>
                <option value="Other">Other / not listed</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label" for="bk-message">Anything else we should know?</label>
              <textarea class="form-control" id="bk-message" name="message" rows="3" maxlength="4000"
                        placeholder="Number of providers, current billing setup, or the problem you most want solved."></textarea>
              <p class="rw-form__hint">
                <i class="bi bi-shield-lock" aria-hidden="true"></i>
                Please do not include patient identifiers or protected health information.
              </p>
            </div>
            <div class="col-12">
              <div class="form-check rw-check">
                <input class="form-check-input" type="checkbox" id="bk-consent" name="consent" value="1" required>
                <label class="form-check-label" for="bk-consent">
                  I agree to be contacted about this request and have read the
                  <a href="<?= e(rw_url('privacy-policy.php')) ?>">privacy policy</a>.
                </label>
                <p class="rw-field__err" data-rw-err></p>
              </div>
            </div>
          </div>

          <!-- Honeypot: hidden from people, irresistible to bots -->
          <div class="rw-hp" aria-hidden="true">
            <label>Company website<input type="text" name="rw_website" tabindex="-1" autocomplete="off"></label>
          </div>

          <button class="btn btn-rw-teal btn-lg w-100 mt-4" type="submit">
            <span data-rw-label>Book My Consultation</span>
            <span class="rw-spinner" data-rw-spinner hidden aria-hidden="true"></span>
          </button>
          <p class="rw-form__msg" data-rw-msg role="status" aria-live="polite"></p>
        </form>
      </div>

    </div>
  </div>
</section>

<?php
rw_partial('testimonials');
rw_partial('faq', [
    'faqs'      => $home_faqs,
    'faq_id'    => 'home-faq',
    'faq_title' => 'Questions practices ask us first',
    'faq_lead'  => 'The six things that come up in almost every first conversation. Answered plainly, without the sales gloss.',
]);
rw_partial('cta-banner', [
    'cta_title' => 'Find out what your revenue cycle is actually costing you',
    'cta_text'  => 'We will review a sample of your claims, denials and aged A/R and give you a specific, numbers-first read on where the money is going. Free, and the findings are yours whatever you decide.',
]);

include __DIR__ . '/includes/footer.php';
