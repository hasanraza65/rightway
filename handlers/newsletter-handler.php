<?php
/**
 * Newsletter signup handler. Same protections as the contact form, minus the
 * fields we do not collect.
 */
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/lib/form.php';

rw_form_bootstrap();
$isAjax = rw_is_ajax();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    rw_form_fail($isAjax, 'This endpoint only accepts form submissions.', [], 405);
}

if (!empty($_POST['rw_website'])) {
    rw_form_done($isAjax, 'You are subscribed. Watch for the next issue.', rw_url('thank-you.php?ref=newsletter'));
}

if (!rw_csrf_valid($_POST['rw_token'] ?? null)) {
    rw_form_fail($isAjax, 'Your session expired. Please refresh the page and try again.', [], 419);
}

if (!rw_rate_ok('newsletter', 4, 900)) {
    rw_form_fail($isAjax, 'Too many attempts. Please try again in a few minutes.', [], 429);
}

$email = rw_clean($_POST['email'] ?? '', 180);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    rw_form_fail($isAjax, 'Please enter a valid email address.', ['email' => 'That does not look like a valid address.'], 422);
}

$subject = 'Newsletter signup: ' . $email;
$body    = implode("\n", [
    'A new subscriber signed up on the ' . BIZ_NAME . ' website.',
    '',
    'Email:     ' . $email,
    'Submitted: ' . date('Y-m-d H:i:s T'),
    'IP:        ' . rw_client_ip(),
    'Page:      ' . rw_clean($_SERVER['HTTP_REFERER'] ?? '(unknown)', 300),
]);

$sent = rw_send_mail(FORM_TO_EMAIL, $subject, $body, $email, '');
rw_log_submission('newsletter', ['email' => $email], $sent);

rw_form_done($isAjax, 'You are on the list. The next issue goes out at the start of the month.', rw_url('thank-you.php?ref=newsletter'));
