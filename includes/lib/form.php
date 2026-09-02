<?php
/**
 * Shared plumbing for the form handlers: validation helpers, rate limiting,
 * mail delivery with a file-log fallback, and the JSON/redirect response pair.
 */

/** Common setup for every handler. */
function rw_form_bootstrap(): void {
    rw_session();
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: same-origin');
}

/** True when the request came from our fetch() call rather than a plain post. */
function rw_is_ajax(): bool {
    return strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest';
}

/** Trim, strip control characters and cap the length of a submitted value. */
function rw_clean($value, int $max): string {
    if (!is_string($value)) return '';
    $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $value);
    return mb_substr(trim($value), 0, $max);
}

/** Best-effort client IP, used only for rate limiting and the audit log. */
function rw_client_ip(): string {
    foreach (['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'] as $key) {
        if (!empty($_SERVER[$key])) {
            $ip = trim(explode(',', $_SERVER[$key])[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP)) return $ip;
        }
    }
    return '0.0.0.0';
}

/**
 * Simple file-backed rate limit: at most $limit submissions per $window
 * seconds, per IP per form. Good enough without a database.
 */
function rw_rate_ok(string $bucket, int $limit, int $window): bool {
    $dir = dirname(__DIR__, 2) . '/cache/rate';
    if (!is_dir($dir) && !@mkdir($dir, 0775, true)) return true;  // fail open

    $file = $dir . '/' . $bucket . '-' . md5(rw_client_ip()) . '.json';
    $now  = time();
    $hits = [];

    if (is_file($file)) {
        $decoded = json_decode((string) @file_get_contents($file), true);
        if (is_array($decoded)) $hits = $decoded;
    }
    $hits = array_values(array_filter($hits, fn($t) => ($now - $t) < $window));

    if (count($hits) >= $limit) return false;

    $hits[] = $now;
    @file_put_contents($file, json_encode($hits), LOCK_EX);
    return true;
}

/**
 * Send a plain-text notification. Returns false if PHP has no working mailer,
 * which is normal on a local dev machine — the submission is still logged.
 */
function rw_send_mail(string $to, string $subject, string $body, string $replyTo, string $replyName): bool {
    if (!function_exists('mail')) return false;

    $replyName = preg_replace('/[\r\n]/', '', $replyName);
    $replyTo   = filter_var($replyTo, FILTER_VALIDATE_EMAIL) ? $replyTo : FORM_FROM_EMAIL;

    $headers = [
        'From: ' . BIZ_NAME . ' Website <' . FORM_FROM_EMAIL . '>',
        'Reply-To: ' . ($replyName ? '"' . $replyName . '" <' . $replyTo . '>' : $replyTo),
        'Content-Type: text/plain; charset=UTF-8',
        'X-Mailer: PHP/' . phpversion(),
    ];

    return @mail(
        $to,
        '=?UTF-8?B?' . base64_encode($subject) . '?=',
        wordwrap($body, 78, "\n", true),
        implode("\r\n", $headers)
    );
}

/**
 * Append every submission to a dated log. This is the safety net: if mail
 * delivery is not configured, nothing is silently lost.
 */
function rw_log_submission(string $type, array $data, bool $mailed): void {
    $dir = FORM_LOG_DIR;
    if (!is_dir($dir) && !@mkdir($dir, 0775, true)) return;

    // Keep the log out of the web root if the site is served from this folder.
    if (!is_file($dir . '/.htaccess')) {
        @file_put_contents($dir . '/.htaccess', "Require all denied\n");
    }

    $record = [
        'at'     => date('c'),
        'type'   => $type,
        'mailed' => $mailed,
        'ip'     => rw_client_ip(),
        'data'   => $data,
    ];
    @file_put_contents(
        $dir . '/' . date('Y-m') . '.log',
        json_encode($record, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n",
        FILE_APPEND | LOCK_EX
    );
}

/** Success: JSON for XHR, redirect for a plain form post. */
function rw_form_done(bool $isAjax, string $message, string $redirect): never {
    if ($isAjax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => true, 'message' => $message, 'redirect' => $redirect]);
        exit;
    }
    header('Location: ' . $redirect);
    exit;
}

/** Failure: JSON for XHR, or bounce back to the referring page with a flag. */
function rw_form_fail(bool $isAjax, string $message, array $errors = [], int $status = 400): never {
    http_response_code($status);
    if ($isAjax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => false, 'message' => $message, 'errors' => $errors]);
        exit;
    }
    $back = $_SERVER['HTTP_REFERER'] ?? rw_url('contact-us.php');
    $back = strtok($back, '#');
    header('Location: ' . $back . (str_contains($back, '?') ? '&' : '?') . 'form=error');
    exit;
}
