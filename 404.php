<?php
/**
 * Custom 404. Also used as the fallback when a service or specialty slug does
 * not resolve, which is why it tolerates being included rather than requested.
 */
if (!defined('BIZ_NAME')) {
    require __DIR__ . '/includes/config.php';
}
if (!headers_sent()) {
    http_response_code(404);
}

$page_title       = 'Page Not Found | Right Way Medical Billing';
$meta_description = 'That page does not exist at Right Way Medical Billing. Use the links here to reach our services, specialty billing pages, about page or contact form.';
$canonical        = '404.php';
$body_class       = 'page-404';
$noindex          = true;
$breadcrumbs      = [['label' => 'Home', 'url' => '']];

include __DIR__ . '/includes/header.php';
?>

<section class="section-pad">
  <div class="container">
    <div class="rw-message" data-aos="fade-up">
      <p class="rw-message__code">4<span>0</span>4</p>
      <h1>This claim came back unpayable</h1>
      <p class="rw-section-head__lead">
        The page you were looking for is not here. It may have moved, or the link that
        brought you here may have a typo in it. Either way, these should get you back
        on track.
      </p>

      <div class="rw-message__actions">
        <a class="btn btn-rw-teal btn-lg" href="<?= e(rw_url('')) ?>">Back to Home</a>
        <a class="btn btn-rw-outline btn-lg" href="<?= e(rw_url('contact-us.php')) ?>">Contact Us</a>
      </div>

      <ul class="rw-nextsteps">
        <li>
          <i class="bi bi-briefcase" aria-hidden="true"></i>
          <span><a href="<?= e(rw_url('services.php')) ?>">All services</a> &mdash; billing, coding,
          credentialing, denial recovery, eligibility, patient billing, out-of-network and MIPS.</span>
        </li>
        <li>
          <i class="bi bi-heart-pulse" aria-hidden="true"></i>
          <span><a href="<?= e(rw_url('specialties.php')) ?>">All specialties</a> &mdash; sixteen
          fields, each with the coding rules that actually apply to it.</span>
        </li>
        <li>
          <i class="bi bi-people" aria-hidden="true"></i>
          <span><a href="<?= e(rw_url('about-us.php')) ?>">About us</a> &mdash; who we are and how
          we work with practices.</span>
        </li>
        <li>
          <i class="bi bi-telephone" aria-hidden="true"></i>
          <span>Or just call <a href="tel:<?= e(rw_tel()) ?>"><?= e(BIZ_PHONE) ?></a> and we will
          point you at the right page.</span>
        </li>
      </ul>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php';
