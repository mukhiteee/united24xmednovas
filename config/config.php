<?php
/**
 * Central configuration. Edit the values below for your environment.
 */

// ---- Database ----
define('DB_HOST', 'localhost');
define('DB_NAME', 'united24_mednovas');
define('DB_USER', 'your_db_user');
define('DB_PASS', 'your_db_password');

// ---- Paths ----
define('BASE_PATH', dirname(__DIR__));
define('UPLOAD_PATH_PASSPORTS', BASE_PATH . '/uploads/passports/');
define('UPLOAD_PATH_PROOFS', BASE_PATH . '/uploads/proofs/');

// ---- Upload limits ----
define('MAX_UPLOAD_BYTES', 5 * 1024 * 1024); // 5MB
define('ALLOWED_MIME_TYPES', ['image/jpeg', 'image/png']);

// ---- Branding ----
define('APP_NAME', 'UNITED 24 X MEDNOVAS');
define('APP_TAGLINE', 'Secure. Seamless. Instant.');

// ---- Payment info shown to submitters ----
define('BANK_NAME', 'Example Bank Plc');
define('ACCOUNT_NAME', 'Association Financial Secretary');
define('ACCOUNT_NUMBER', '0123456789');

// ---- Credit / social links (shown in the site footer) ----
define('CREDIT_NAME', 'Mukhiteee');
define('CREDIT_X_URL', 'https://x.com/mukhiteee');
define('CREDIT_INSTAGRAM_URL', 'https://instagram.com/mukhiteee');
define('CREDIT_LINKEDIN_URL', 'https://www.linkedin.com/in/mukhtar-onoruoiza-abdulhamid-787ba2426');

// ---- Session / security ----
define('SESSION_LIFETIME', 60 * 60); // 1 hour

// ---- Error reporting (turn OFF display_errors in production) ----
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

date_default_timezone_set('Africa/Lagos');







