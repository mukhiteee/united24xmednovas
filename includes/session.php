<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Start a hardened session. Call this at the top of every page that
 * needs CSRF protection (i.e. the submission form).
 */
function secure_session_start(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

    session_set_cookie_params([
        'lifetime' => SESSION_LIFETIME,
        'path'     => '/',
        'secure'   => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();

    if (isset($_SESSION['created_at']) && (time() - $_SESSION['created_at']) > SESSION_LIFETIME) {
        session_unset();
        session_destroy();
        session_start();
        $_SESSION['created_at'] = time();
    } elseif (!isset($_SESSION['created_at'])) {
        $_SESSION['created_at'] = time();
    }

    if (empty($_SESSION['last_regen']) || (time() - $_SESSION['last_regen']) > 300) {
        session_regenerate_id(true);
        $_SESSION['last_regen'] = time();
    }
}
