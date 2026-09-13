<?php require_once __DIR__ . '/includes/functions.php'; require_once __DIR__ . '/config/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e(APP_NAME) ?> Payment Portal</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    .hero-screen {
      min-height: 100vh;
      display: flex;
      align-items: flex-end;
      padding: 0;
      background: linear-gradient(180deg, rgba(20,22,26,.15) 0%, rgba(20,22,26,.35) 55%, rgba(242,102,27,.55) 100%),
                  radial-gradient(circle at 30% 20%, #7dd3fc 0%, #38bdf8 35%, #0ea5e9 60%, #14161A 100%);
      background-size: cover;
      position: relative;
    }
    .hero-logo {
      position: absolute;
      top: 24px;
      left: 20px;
      color: #fff;
      font-weight: 800;
      letter-spacing: .04em;
      font-size: .95rem;
      opacity: .95;
    }
    .hero-card {
      width: 100%;
      padding: 40px 22px 44px;
      background: linear-gradient(180deg, rgba(20,22,26,0) 0%, rgba(20,22,26,.55) 40%, rgba(20,22,26,.85) 100%);
    }
    .hero-card h1 {
      color: #fff;
      font-size: 1.7rem;
      font-weight: 800;
      margin: 0;
      line-height: 1.2;
    }
    .hero-card .accent { color: var(--primary); }
    .hero-card p.subtitle { color: #E5E7EB; margin: 10px 0 26px; }
    .hero-card .btn { max-width: 420px; margin: 0 auto; }
    .hero-footer { max-width: 420px; margin: 18px auto 0; }
    .hero-footer.site-footer, .hero-footer .credit-name { color: #E5E7EB; }
    .hero-footer.site-footer .social-links a { color: #D1D5DB; }
  </style>
</head>
<body>
  <div class="hero-screen">
    <div class="hero-logo"><?= e(APP_NAME) ?></div>
    <div class="hero-card">
      <h1>UNITED 24 <span class="accent">X MEDNOVAS</span><br>PAYMENT PORTAL</h1>
      <p class="subtitle"><?= e(APP_TAGLINE) ?></p>
      <a href="payment-info.php" class="btn">Proceed To Payment &rarr;</a>
      <div class="hero-footer">
        <?php include __DIR__ . '/includes/footer.php'; ?>
      </div>
    </div>
  </div>
</body>
</html>
