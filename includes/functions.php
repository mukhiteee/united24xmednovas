<?php

/** Escape a value for safe HTML output. */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/** Trim and strip control characters from raw input (does not replace output escaping). */
function clean_input(?string $value): string
{
    $value = trim($value ?? '');
    return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $value);
}

function validate_email(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate and move an uploaded image file.
 *
 * @param array  $file    A single $_FILES[...] entry.
 * @param string $destDir Absolute path to the destination directory (with trailing slash).
 * @return string          The stored random filename on success.
 * @throws RuntimeException On any validation failure.
 */
function handle_secure_upload(array $file, string $destDir): string
{
    if (!isset($file['error']) || is_array($file['error'])) {
        throw new RuntimeException('Invalid file upload parameters.');
    }

    switch ($file['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No file was uploaded.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('The uploaded file is too large.');
        default:
            throw new RuntimeException('File upload failed. Please try again.');
    }

    if ($file['size'] <= 0 || $file['size'] > MAX_UPLOAD_BYTES) {
        throw new RuntimeException('File size must not exceed ' . (MAX_UPLOAD_BYTES / 1024 / 1024) . 'MB.');
    }

    if (!is_uploaded_file($file['tmp_name'])) {
        throw new RuntimeException('Invalid file upload.');
    }

    // Verify actual MIME type via finfo (does not trust the client-supplied type)
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, ALLOWED_MIME_TYPES, true)) {
        throw new RuntimeException('Only JPEG and PNG images are allowed.');
    }

    // Double-check it's a genuine, decodable image (blocks polyglot files)
    $imageInfo = @getimagesize($file['tmp_name']);
    if ($imageInfo === false) {
        throw new RuntimeException('The uploaded file is not a valid image.');
    }

    $extMap = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
    $ext = $extMap[$mime];

    // Cryptographically random filename — never trust the client filename
    $randomName = bin2hex(random_bytes(16)) . '.' . $ext;
    $destPath = rtrim($destDir, '/') . '/' . $randomName;

    if (!is_dir($destDir) && !mkdir($destDir, 0755, true) && !is_dir($destDir)) {
        throw new RuntimeException('Server storage error.');
    }

    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        throw new RuntimeException('Could not save the uploaded file.');
    }

    chmod($destPath, 0644);

    return $randomName;
}
