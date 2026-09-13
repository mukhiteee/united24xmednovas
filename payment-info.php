<?php require_once __DIR__ . '/includes/functions.php'; require_once __DIR__ . '/config/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Make Your Payment</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="screen">
    <div class="card">
      <a href="index.php" class="back-pill">&larr; Back</a>

      <h2 style="margin-top:16px;">Make Your Payment</h2>
      <p class="subtitle">Transfer the exact amount to the details below</p>

      <div class="bank-row">
        <div>
          <div class="bank-label">Bank Name</div>
          <div class="bank-value"><?= e(BANK_NAME) ?></div>
        </div>
      </div>
      <div class="bank-row">
        <div>
          <div class="bank-label">Account Name</div>
          <div class="bank-value"><?= e(ACCOUNT_NAME) ?></div>
        </div>
      </div>
      <div class="bank-row">
        <div>
          <div class="bank-label">Account Number</div>
          <div class="bank-value" id="acctNumber"><?= e(ACCOUNT_NUMBER) ?></div>
        </div>
        <button class="copy-btn" onclick="copyAccount()">Copy</button>
      </div>

      <div style="height:22px;"></div>
      <a href="submit.php" class="btn green">I've Made Payment</a>

      <?php include __DIR__ . '/includes/footer.php'; ?>
    </div>
  </div>

  <script>
    function copyAccount() {
      const text = document.getElementById('acctNumber').innerText;
      navigator.clipboard.writeText(text).then(() => {
        const btn = document.querySelector('.copy-btn');
        const original = btn.innerText;
        btn.innerText = 'Copied!';
        setTimeout(() => { btn.innerText = original; }, 1500);
      });
    }
  </script>
</body>
</html>
