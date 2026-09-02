<?php
/**
 * Contact / consultation form handler.
 *
 * Protections, in order of cheapness:
 *   1. POST only
 *   2. Honeypot field (rw_website) — bots fill it, people cannot see it
 *   3. Minimum time-on-page is not used; instead a per-IP rate limit
 *   4. CSRF token tied to the session
 *   5. Full server-side validation, independent of the client-side checks
 *
 * Responds with JSON to XHR and with a redirect to a normal form post, so the
 * form works with JavaScript disabled.
 */
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/lib/form.php';

rw_form_bootstrap();

$isAjax = rw_is_ajax();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    rw_form_fail($isAjax, 'This endpoint only accepts form submissions.', [], 405);
}

/* Honeypot — respond as though everything is fine so bots learn nothing. */
if (!empty($_POST['rw_website'])) {
    rw_form_done($isAjax, 'Thanks — we will be in touch shortly.', rw_url('thank-you.php'));
}

if (!rw_csrf_valid($_POST['rw_token'] ?? null)) {
    rw_form_fail($isAjax, 'Your session expired. Please refresh the page and try again.', [], 419);
}

if (!rw_rate_ok('contact', 5, 900)) {
    rw_form_fail($isAjax, 'That is a few too many submissions. Please wait a few minutes or call us directly.', [], 429);
}

/* ------------------------------------------------------------- validate */
$in = [
    'name'      => rw_clean($_POST['name']      ?? '', 120),
    'practice'  => rw_clean($_POST['practice']  ?? '', 160),
    'email'     => rw_clean($_POST['email']     ?? '', 180),
    'phone'     => rw_clean($_POST['phone']     ?? '', 40),
    'specialty' => rw_clean($_POST['specialty'] ?? '', 80),
    'interest'  => rw_clean($_POST['interest']  ?? '', 80),
    'preferred' => rw_clean($_POST['preferred'] ?? '', 40),
    'message'   => rw_clean($_POST['message']   ?? '', 4000),
    'source'    => rw_clean($_POST['rw_source'] ?? 'unknown', 60),
];

/* The quick modal and the booking block are deliberately short forms: they ask
   for enough to make contact and nothing more. Only the full contact form
   requires a written message. The modal has no consent checkbox to enforce;
   the other two do. */
$requiresMessage = !in_array($in['source'], ['quote-modal', 'booking-form'], true);
$requiresConsent = $in['source'] !== 'quote-modal';

$errors = [];

if (mb_strlen($in['name']) < 2) {
    $errors['name'] = 'Please enter your full name.';
}
if (!filter_var($in['email'], FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Please enter a valid email address.';
}
if (strlen(preg_replace('/[^0-9]/', '', $in['phone'])) < 10) {
    $errors['phone'] = 'Please enter a phone number with at least 10 digits.';
}
if ($requiresMessage && mb_strlen($in['message']) < 15) {
    $errors['message'] = 'Please tell us a little more about what you need.';
}
if ($requiresConsent && empty($_POST['consent'])) {
    $errors['consent'] = 'Please confirm you agree to be contacted.';
}
/* Reject a preferred date in the past — a typo there costs a callback. */
if ($in['preferred'] !== '') {
    $d = DateTime::createFromFormat('Y-m-d', $in['preferred']);
    if (!$d || $d->format('Y-m-d') !== $in['preferred']) {
        $errors['preferred'] = 'Please choose a valid date.';
    } elseif ($d < new DateTime('today')) {
        $errors['preferred'] = 'Please choose today or a later date.';
    }
}
// Header-injection attempt in a name or email field.
if (preg_match('/[\r\n]/', $in['name'] . $in['email'])) {
    $errors['name'] = 'That name contains characters we cannot accept.';
}

if ($errors) {
    rw_form_fail($isAjax, 'Please correct the highlighted fields and try again.', $errors, 422);
}

/* --------------------------------------------------------------- deliver */
$subject = ($in['source'] === 'booking-form' ? 'Consultation request from ' : 'New inquiry from ') . $in['name']
         . ($in['specialty'] ? ' (' . $in['specialty'] . ')' : '');

$lines = [
    'A new inquiry came in from the ' . BIZ_NAME . ' website.',
    '',
    'Name:       ' . $in['name'],
    'Practice:   ' . ($in['practice'] ?: '(not given)'),
    'Email:      ' . $in['email'],
    'Phone:      ' . $in['phone'],
    'Specialty:  ' . ($in['specialty'] ?: '(not given)'),
    'Interest:   ' . ($in['interest'] ?: '(not given)'),
    'Preferred:  ' . ($in['preferred'] ?: '(no date given)'),
    '',
    'Message:',
    $in['message'] ?: '(no message — submitted from the quick quote modal)',
    '',
    str_repeat('-', 52),
    'Form:       ' . $in['source'],
    'Submitted:  ' . date('Y-m-d H:i:s T'),
    'IP:         ' . rw_client_ip(),
    'Page:       ' . rw_clean($_SERVER['HTTP_REFERER'] ?? '(unknown)', 300),
];

$sent = rw_send_mail(FORM_TO_EMAIL, $subject, implode("\n", $lines), $in['email'], $in['name']);
rw_log_submission('contact', $in, $sent);

rw_form_done(
    $isAjax,
    'Thank you. Your inquiry is with our team and we will reply within one business day.',
    rw_url('thank-you.php')
);
