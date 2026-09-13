<?php

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}

/**
 * Verify a submitted CSRF token using a timing-safe comparison.
 * Kills the request with a 403 on failure.
 */
function csrf_verify(?string $submitted): void
{
    if (empty($_SESSION['csrf_token']) || empty($submitted) || !hash_equals($_SESSION['csrf_token'], $submitted)) {
        http_response_code(403);
        die('Invalid or expired security token. Please refresh the page and try again.');
    }
}







