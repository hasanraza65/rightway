<?php
/**
 * Terms of service.
 *
 * NOTE FOR THE SITE OWNER: a plain-language starting point covering use of this
 * website. Your actual client engagements are governed by your service
 * agreement and BAA, not by this page. Have counsel review before launch.
 */
require __DIR__ . '/includes/config.php';

$page_title       = 'Terms of Service | Right Way Medical Billing';
$meta_description = 'The terms that apply to visitors using the Right Way Medical Billing website, including acceptable use, intellectual property and limitations of liability.';
$canonical        = 'terms-of-service.php';
$body_class       = 'page-legal';
$breadcrumbs = [
    ['label' => 'Home',             'url' => ''],
    ['label' => 'Terms of Service', 'url' => 'terms-of-service.php'],
];

include __DIR__ . '/includes/header.php';

rw_partial('page-header', [
    'ph_title'    => 'Terms of service',
    'ph_eyebrow'  => 'Legal',
    'ph_lead'     => 'The rules that apply when you use this website. Client engagements are governed separately by a signed service agreement.',
    'breadcrumbs' => $breadcrumbs,
]);
?>

<section class="section-pad">
  <div class="container">
    <div class="rw-prose">
      <p class="rw-prose__meta">Last updated: <?= e(date('F Y')) ?></p>

      <h2>1. Acceptance</h2>
      <p>
        By using this website you agree to these terms. If you do not agree with them,
        please do not use the site.
      </p>

      <h2>2. What this site is</h2>
      <p>
        This website describes the services offered by <?= e(BIZ_LEGAL_NAME) ?>. It is
        informational. Nothing on it is a binding offer, and nothing on it constitutes
        legal, medical, coding, compliance or financial advice.
      </p>
      <p>
        Descriptions of coding rules, payer policies and regulatory requirements are
        given in general terms to explain what we do. Rules change frequently and vary
        by payer and by state. Do not rely on this website in place of current official
        guidance or professional advice specific to your situation.
      </p>

      <h2>3. No client relationship is created here</h2>
      <p>
        Submitting a form, subscribing to the newsletter or calling us does not create
        a service relationship. A relationship begins only when both parties sign a
        written service agreement and, where protected health information is involved,
        a business associate agreement.
      </p>

      <h2>4. Statistics and results</h2>
      <p>
        Performance figures shown on this site reflect aggregate results across our
        client base and are provided for illustration. Results vary considerably by
        specialty, payer mix, documentation quality and the state a practice starts
        from. Nothing here is a guarantee of any particular outcome.
      </p>

      <h2>5. Acceptable use</h2>
      <ul>
        <li>Do not submit protected health information or patient identifiers through this website</li>
        <li>Do not attempt to gain unauthorized access to any part of the site or its infrastructure</li>
        <li>Do not use automated tools to scrape, overload or disrupt the site</li>
        <li>Do not submit false information, spam or malicious content through our forms</li>
        <li>Do not use our content or brand marks to imply an endorsement or partnership that does not exist</li>
      </ul>

      <h2>6. Intellectual property</h2>
      <p>
        The text, layout, code, logo and brand marks on this site are the property of
        <?= e(BIZ_LEGAL_NAME) ?>. You may view and print pages for your own evaluation.
        You may not republish, resell or systematically copy the content without written
        permission.
      </p>
      <p>
        Photography is licensed from Pexels and remains subject to the Pexels license.
        Photographers are credited on our <a href="<?= e(rw_url('credits.php')) ?>">photo
        credits</a> page. Third-party names such as EHR platforms are the trademarks of
        their respective owners and are referenced only to describe compatibility.
      </p>

      <h2>7. Third-party links</h2>
      <p>
        Where we link to other sites, we do so for convenience. We do not control them
        and are not responsible for their content or their privacy practices.
      </p>

      <h2>8. Availability</h2>
      <p>
        We aim to keep this site available and accurate, but we do not guarantee
        uninterrupted access. We may change, suspend or withdraw any part of it without
        notice.
      </p>

      <h2>9. Limitation of liability</h2>
      <p>
        To the fullest extent permitted by law, <?= e(BIZ_LEGAL_NAME) ?> is not liable
        for indirect, incidental or consequential losses arising from your use of this
        website, including lost revenue or lost data. Nothing in these terms limits
        liability that cannot lawfully be limited.
      </p>

      <h2>10. Privacy</h2>
      <p>
        Our handling of information submitted through this site is described in the
        <a href="<?= e(rw_url('privacy-policy.php')) ?>">privacy policy</a>, which forms
        part of these terms.
      </p>

      <h2>11. Governing law</h2>
      <p>
        These terms are governed by the laws of the State of <?= e(BIZ_STATE) ?>, United
        States, without regard to its conflict of laws rules.
      </p>

      <h2>12. Contact</h2>
      <p>
        Questions about these terms can be sent to
        <a href="mailto:<?= e(BIZ_EMAIL) ?>"><?= e(BIZ_EMAIL) ?></a> or
        <a href="tel:<?= e(rw_tel()) ?>"><?= e(BIZ_PHONE) ?></a>.
      </p>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php';
