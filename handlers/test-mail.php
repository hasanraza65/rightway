<?php
/**
 * Standalone SMTP connectivity test.
 *
 * Usage (command line):
 *   php test-smtp.php you@example.com
 *
 * Usage (browser):
 *   https://yoursite.com/test-smtp.php?to=you@example.com
 *
 * IMPORTANT: this script prints the full SMTP conversation, which is
 * useful for you right now and useful to nobody else. Delete it from the
 * server as soon as you're done testing — don't leave it sitting in a
 * public folder.
 */

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../includes/config.php'; // must define SMTP_HOST, SMTP_PORT, SMTP_USER, SMTP_PASS, FORM_TO_EMAIL

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

define('SMTP_HOST', 'rightwayrcm.com');
define('SMTP_PORT', 465);
define('SMTP_USER', 'info@rightwayrcm.com');
define('SMTP_PASS', 'y76QCLNGLXjwcEY');
define('FORM_TO_EMAIL', 'info@rightwayrcm.com');

header('Content-Type: text/plain');

$to = $_GET['to'] ?? ($argv[1] ?? (defined('FORM_TO_EMAIL') ? FORM_TO_EMAIL : null));

if (!$to || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
    die("Please provide a valid destination email, e.g. ?to=you@example.com\n");
}

foreach (['SMTP_HOST', 'SMTP_PORT', 'SMTP_USER', 'SMTP_PASS'] as $const) {
    if (!defined($const)) {
        die("Missing config: $const is not defined in includes/config.php\n");
    }
}

echo "Testing SMTP send\n";
echo "Host: " . SMTP_HOST . "\n";
echo "Port: " . SMTP_PORT . "\n";
echo "User: " . SMTP_USER . "\n";
echo "To:   $to\n";
echo str_repeat('-', 50) . "\n\n";

$mail = new PHPMailer(true);

// Capture the SMTP debug conversation instead of letting PHPMailer dump it
// raw, so we can print it in a readable block after the result.
$debugLog = '';
$mail->SMTPDebug   = SMTP::DEBUG_SERVER; // shows client <-> server exchange
$mail->Debugoutput = function ($str, $level) use (&$debugLog) {
    $debugLog .= $str . "\n";
};

// Don't let a misconfigured port/encryption pair hang for minutes —
// fail fast so testing is quick.
$mail->Timeout      = 12;
$mail->SMTPKeepAlive = false;

try {
    $mail->isSMTP();
    $mail->Host     = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USER;
    $mail->Password = SMTP_PASS;
    $mail->Port     = (int) SMTP_PORT;

    // Port 465 = implicit TLS (SMTPS) from the first byte.
    // Port 587 (or 25 with opportunistic TLS) = STARTTLS.
    // Getting this pairing wrong is the #1 cause of "hangs, then fails".
    if ((int) SMTP_PORT === 465) {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    } else {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    }

    $mail->setFrom(SMTP_USER, defined('BIZ_NAME') ? BIZ_NAME : 'Website');
    $mail->addAddress($to);
    $mail->Subject = 'SMTP test — ' . date('Y-m-d H:i:s');
    $mail->Body    = "This is a test message confirming SMTP delivery is working.\n\nSent: " . date('Y-m-d H:i:s T');
    $mail->isHTML(false);

    $ok = $mail->send();

    if ($ok) {
        echo "\n✔ SUCCESS — message was accepted for delivery.\n";
        echo "Check the inbox (and spam folder) for $to.\n\n";
    } else {
        echo "\n✘ FAILED — send() returned false without throwing.\n";
        echo "PHPMailer ErrorInfo: " . $mail->ErrorInfo . "\n\n";
    }
} catch (PHPMailerException $e) {
    echo "\n✘ FAILED — an exception was thrown.\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "PHPMailer ErrorInfo: " . $mail->ErrorInfo . "\n\n";
} catch (\Throwable $e) {
    // Catches autoload/config problems etc. so you always get a readable
    // message instead of a blank page.
    echo "\n✘ FAILED — unexpected error.\n";
    echo "Message: " . $e->getMessage() . "\n\n";
}

echo str_repeat('-', 50) . "\n";
echo "Full SMTP conversation log:\n\n";
echo $debugLog ?: "(no debug output captured — the connection likely never opened; check host, port, and that your host's firewall allows outbound SMTP.)\n";