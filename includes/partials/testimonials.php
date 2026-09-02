<?php
/**
 * Swiper testimonial carousel. Autoplays, pauses on hover and on focus, and
 * exposes real prev/next buttons for keyboard users.
 */
$quotes = [
    [
        'quote'   => 'We had two hundred thousand dollars sitting in aged A/R that our previous biller had quietly given up on. Right Way worked it claim by claim and recovered most of what was still inside filing. The monthly reporting alone changed how we run the practice.',
        'name'    => 'Dr. Amara Whitfield',
        'role'    => 'Owner, three-provider gastroenterology group',
        'img'     => 'testimonial-1',
        'stars'   => 5,
    ],
    [
        'quote'   => 'The part I did not expect was the coding feedback. Every month I get a short note about what my documentation is missing. My E/M distribution moved without me changing how I practice, because I was under-coding work I was already doing.',
        'name'    => 'Dr. Marcus Elwood',
        'role'    => 'Internal medicine, solo practice',
        'img'     => 'testimonial-2',
        'stars'   => 5,
    ],
    [
        'quote'   => 'Onboarding was the thing I dreaded and it turned out to be the easiest part. They worked inside our existing system, ran in parallel for two weeks, and our staff barely noticed the switch. No downtime, no gap in cash flow.',
        'name'    => 'Renata Salcedo',
        'role'    => 'Practice manager, multi-site urgent care',
        'img'     => 'testimonial-3',
        'stars'   => 5,
    ],
    [
        'quote'   => 'Credentialing four new providers used to take us most of a year and someone always slipped through a revalidation. They put every date on a calendar with owners. Nobody has billed out of network by accident since.',
        'name'    => 'Dr. Howard Brennan',
        'role'    => 'Medical director, rehabilitation clinic',
        'img'     => 'testimonial-4',
        'stars'   => 5,
    ],
    [
        'quote'   => 'Nobody had ever told us that a good share of our surgical extractions were payable by medical insurance. That single change added meaningful revenue without us treating a single extra patient.',
        'name'    => 'Dr. Priya Raghunathan',
        'role'    => 'Owner, general and cosmetic dental practice',
        'img'     => 'testimonial-5',
        'stars'   => 5,
    ],
];
?>
<section class="rw-quotes section-pad">
  <div class="container">
    <div class="rw-section-head" data-aos="fade-up">
      <p class="rw-eyebrow">Client results</p>
      <h2 class="rw-section-head__title">What practices say after the first year</h2>
      <p class="rw-section-head__lead">Names and details are shared with permission. Numbers vary by specialty, payer mix and where a practice started.</p>
    </div>

    <div class="rw-quotes__wrap" data-aos="fade-up" data-aos-delay="100">
      <div class="swiper rw-quotes__swiper" data-rw-quotes>
        <div class="swiper-wrapper">
          <?php foreach ($quotes as $q): ?>
            <div class="swiper-slide">
              <figure class="rw-quote">
                <div class="rw-quote__stars" role="img" aria-label="<?= e($q['stars']) ?> out of 5 stars">
                  <?php for ($i = 0; $i < $q['stars']; $i++): ?><i class="bi bi-star-fill" aria-hidden="true"></i><?php endfor; ?>
                </div>
                <i class="bi bi-quote rw-quote__mark" aria-hidden="true"></i>
                <blockquote><p><?= e($q['quote']) ?></p></blockquote>
                <figcaption class="rw-quote__by">
                  <span class="rw-quote__avatar"><?php rw_img($q['img'], ['alt' => 'Portrait of ' . $q['name'], 'sizes' => '56px']); ?></span>
                  <span>
                    <span class="rw-quote__name"><?= e($q['name']) ?></span>
                    <span class="rw-quote__role"><?= e($q['role']) ?></span>
                  </span>
                </figcaption>
              </figure>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="rw-quotes__nav">
        <button type="button" class="rw-quotes__btn" data-rw-quotes-prev aria-label="Previous testimonial">
          <i class="bi bi-arrow-left" aria-hidden="true"></i>
        </button>
        <div class="rw-quotes__dots" data-rw-quotes-pagination></div>
        <button type="button" class="rw-quotes__btn" data-rw-quotes-next aria-label="Next testimonial">
          <i class="bi bi-arrow-right" aria-hidden="true"></i>
        </button>
      </div>
    </div>
  </div>
</section>
