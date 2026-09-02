<?php
/**
 * Privacy policy.
 *
 * NOTE FOR THE SITE OWNER: this is a plain-language starting point written for
 * a medical billing company. Have counsel review it against your actual data
 * practices, your state law and your HIPAA obligations before launch.
 */
require __DIR__ . '/includes/config.php';

$page_title       = 'Privacy Policy | Right Way Medical Billing';
$meta_description = 'How Right Way Medical Billing collects, uses and protects information submitted through this website, and how protected health information is handled.';
$canonical        = 'privacy-policy.php';
$body_class       = 'page-legal';
$breadcrumbs = [
    ['label' => 'Home',           'url' => 'index.php'],
    ['label' => 'Privacy Policy', 'url' => 'privacy-policy.php'],
];

include __DIR__ . '/includes/header.php';

rw_partial('page-header', [
    'ph_title'    => 'Privacy policy',
    'ph_eyebrow'  => 'Legal',
    'ph_lead'     => 'What we collect through this website, why we collect it, and what we do with it.',
    'breadcrumbs' => $breadcrumbs,
]);
?>

<section class="section-pad">
  <div class="container">
    <div class="rw-prose">
      <p class="rw-prose__meta">Last updated: <?= e(date('F Y')) ?></p>

      <p>
        This policy covers the <?= e(BIZ_NAME) ?> website. It explains what information
        this site collects from visitors and how we handle it. It is separate from how
        we handle protected health information on behalf of client practices, which is
        governed by HIPAA and by the business associate agreement we sign with each
        client.
      </p>

      <h2>Information we collect</h2>
      <h3>Information you give us</h3>
      <p>
        When you submit the contact form, the quick quote form or the newsletter
        signup, we collect what you type into it: your name, practice name, email
        address, phone number, specialty, area of interest and your message.
      </p>
      <p>
        <strong>Please do not send patient information through this website.</strong>
        Our forms are not an appropriate channel for protected health information. If
        you need to share claims data with us, we will provide a secure channel and a
        signed business associate agreement first.
      </p>

      <h3>Information collected automatically</h3>
      <ul>
        <li>Standard server log data: IP address, browser type, referring page and timestamp</li>
        <li>A session cookie, which exists only to protect our forms against cross-site request forgery</li>
        <li>Analytics data, if analytics is enabled by the site owner, in aggregate form</li>
      </ul>

      <h2>How we use it</h2>
      <ul>
        <li>To reply to your inquiry and, if you ask for one, to prepare a revenue assessment</li>
        <li>To send the monthly newsletter, if you subscribed to it</li>
        <li>To protect the site against spam and abusive submissions</li>
        <li>To understand which pages are useful so we can improve them</li>
      </ul>
      <p>
        We do not sell your information. We do not share it with third parties for
        their own marketing. We do not use it for automated decision-making.
      </p>

      <h2>Cookies</h2>
      <p>
        This site sets one strictly necessary cookie to maintain your session and
        validate form submissions. If the site owner enables analytics, that service
        will set its own cookies, and you can opt out through your browser settings or
        the provider opt-out tools.
      </p>

      <h2>How long we keep it</h2>
      <p>
        Inquiry details are retained for as long as needed to respond and, where a
        relationship follows, for the duration of that relationship plus any period
        required by law or by our accounting obligations. Newsletter subscriptions are
        kept until you unsubscribe. Server logs are rotated regularly.
      </p>

      <h2>How we protect it</h2>
      <ul>
        <li>Data is transmitted over encrypted connections</li>
        <li>Access is limited to staff who need it for their role</li>
        <li>Accounts require multi-factor authentication</li>
        <li>All staff complete HIPAA and security training annually</li>
      </ul>

      <h2>Your choices</h2>
      <p>
        You can ask us what information we hold about you, ask us to correct it, or ask
        us to delete it. You can unsubscribe from the newsletter using the link in any
        issue. Depending on where you live, you may have additional rights under state
        privacy law. To exercise any of these, email
        <a href="mailto:<?= e(BIZ_EMAIL) ?>"><?= e(BIZ_EMAIL) ?></a>.
      </p>

      <h2>Protected health information</h2>
      <p>
        When we act as a business associate for a client practice, our handling of
        protected health information is governed by HIPAA, the HITECH Act and the
        business associate agreement in place with that practice, not by this website
        policy. Patients with questions about their own records should contact their
        provider directly.
      </p>

      <h2>Children</h2>
      <p>
        This site is aimed at healthcare businesses and is not directed to children. We
        do not knowingly collect information from anyone under 13.
      </p>

      <h2>Changes to this policy</h2>
      <p>
        If we change this policy we will update the date at the top of this page.
        Material changes will be noted prominently on the site.
      </p>

      <h2>Contact</h2>
      <p>
        <?= e(BIZ_LEGAL_NAME) ?><br>
        <?= e(BIZ_STREET) ?><br>
        <?= e(BIZ_CITY . ', ' . BIZ_STATE . ' ' . BIZ_ZIP) ?><br>
        Email: <a href="mailto:<?= e(BIZ_EMAIL) ?>"><?= e(BIZ_EMAIL) ?></a><br>
        Phone: <a href="tel:<?= e(rw_tel()) ?>"><?= e(BIZ_PHONE) ?></a>
      </p>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php';
