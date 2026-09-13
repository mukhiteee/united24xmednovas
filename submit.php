<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/functions.php';

secure_session_start();

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify($_POST['csrf_token'] ?? null);

    $regNumber   = clean_input($_POST['reg_number'] ?? '');
    $fullName    = clean_input($_POST['full_name'] ?? '');
    $email       = clean_input($_POST['email'] ?? '');
    $phone       = clean_input($_POST['phone'] ?? '');
    $department  = clean_input($_POST['department'] ?? '');

    if ($regNumber === '' || strlen($regNumber) > 50) {
        $errors[] = 'A valid Registration Number is required.';
    }
    if ($fullName === '' || strlen($fullName) > 150) {
        $errors[] = 'A valid Full Name is required.';
    }
    if (!validate_email($email)) {
        $errors[] = 'A valid Email Address is required.';
    }
    if ($phone === '' || !preg_match('/^[0-9+\-\s()]{7,20}$/', $phone)) {
        $errors[] = 'A valid Phone Number is required.';
    }
    if ($department === '' || strlen($department) > 150) {
        $errors[] = 'A valid Department is required.';
    }

    $passportFilename = null;
    $proofFilename = null;

    if (empty($errors)) {
        try {
            $passportFilename = handle_secure_upload($_FILES['passport_photo'], UPLOAD_PATH_PASSPORTS);
            $proofFilename    = handle_secure_upload($_FILES['payment_proof'], UPLOAD_PATH_PROOFS);
        } catch (RuntimeException $e) {
            $errors[] = $e->getMessage();
        }
    }

    if (empty($errors)) {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare(
                'INSERT INTO submissions
                    (reg_number, full_name, email, phone, department, passport_photo, payment_proof)
                 VALUES (:reg, :name, :email, :phone, :dept, :passport, :proof)'
            );
            $stmt->execute([
                'reg'      => $regNumber,
                'name'     => $fullName,
                'email'    => $email,
                'phone'    => $phone,
                'dept'     => $department,
                'passport' => $passportFilename,
                'proof'    => $proofFilename,
            ]);
            $success = true;
        } catch (PDOException $e) {
            error_log('Submission insert failed: ' . $e->getMessage());
            $errors[] = 'A system error occurred while saving your submission. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Submit Verification Details</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="screen">
    <div class="card">
      <?php if ($success): ?>
        <h2>&#9989; Submission Received</h2>
        <div class="alert success">
          Your registration and proof of payment have been submitted successfully and are on file for review.
        </div>
        <a href="index.php" class="btn secondary">Back to Home</a>
      <?php else: ?>
        <a href="payment-info.php" class="back-pill">&larr; Back</a>

        <h2 style="margin-top:16px;">Submit Verification Details</h2>
        <p class="subtitle">Fill out the authentic details required to securely process your entry.</p>

        <?php if (!empty($errors)): ?>
          <div class="alert error">
            <?php foreach ($errors as $err): ?>
              <div><?= e($err) ?></div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" novalidate>
          <?= csrf_field() ?>

          <div class="field">
            <label for="reg_number">Registration Number</label>
            <input type="text" id="reg_number" name="reg_number" required maxlength="50"
                   placeholder="e.g., U24CO1001" value="<?= e($_POST['reg_number'] ?? '') ?>">
          </div>

          <div class="field">
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" required maxlength="150"
                   placeholder="John Doe" value="<?= e($_POST['full_name'] ?? '') ?>">
          </div>

          <div class="field">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required maxlength="150"
                   placeholder="johndoe@example.com" value="<?= e($_POST['email'] ?? '') ?>">
          </div>

          <div class="field">
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" required maxlength="20"
                   placeholder="e.g., 07000000000" value="<?= e($_POST['phone'] ?? '') ?>">
          </div>

          <div class="field">
            <label for="department">Department</label>
            <input type="text" id="department" name="department" required maxlength="150"
                   placeholder="e.g., Computer Engineering" value="<?= e($_POST['department'] ?? '') ?>">
          </div>

          <div class="field">
            <label>Passport Photograph <span class="hint">(Will be used for verification at the venue)</span></label>
            <label class="upload-box" id="passportBox" for="passport_photo">
              <div>&#128247; Click or Drag Passport Image Here</div>
              <div class="filename" id="passportFilename"></div>
            </label>
            <input type="file" id="passport_photo" name="passport_photo" accept="image/jpeg,image/png" required>
          </div>

          <div class="field">
            <label>Proof of Payment <span class="hint">(Transaction Screenshot)</span></label>
            <label class="upload-box" id="proofBox" for="payment_proof">
              <div>&#128247; Click or Drag Screenshot Here</div>
              <div class="filename" id="proofFilename"></div>
            </label>
            <input type="file" id="payment_proof" name="payment_proof" accept="image/jpeg,image/png" required>
          </div>

          <button type="submit" class="btn">Submit Registration</button>
        </form>
      <?php endif; ?>

      <?php include __DIR__ . '/includes/footer.php'; ?>
    </div>
  </div>

  <script>
    function wireUpload(inputId, boxId, filenameId) {
      const input = document.getElementById(inputId);
      const box = document.getElementById(boxId);
      const filenameEl = document.getElementById(filenameId);

      input.addEventListener('change', () => {
        if (input.files && input.files[0]) {
          filenameEl.textContent = input.files[0].name;
        }
      });

      ['dragover', 'dragenter'].forEach(evt => {
        box.addEventListener(evt, (e) => { e.preventDefault(); box.classList.add('dragover'); });
      });
      ['dragleave', 'drop'].forEach(evt => {
        box.addEventListener(evt, (e) => { e.preventDefault(); box.classList.remove('dragover'); });
      });
      box.addEventListener('drop', (e) => {
        if (e.dataTransfer.files && e.dataTransfer.files[0]) {
          input.files = e.dataTransfer.files;
          filenameEl.textContent = e.dataTransfer.files[0].name;
        }
      });
    }
    wireUpload('passport_photo', 'passportBox', 'passportFilename');
    wireUpload('payment_proof', 'proofBox', 'proofFilename');
  </script>
</body>
</html>
