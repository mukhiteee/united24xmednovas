# United 24 X MedNovas — Payment Intake Portal

Minimal, secure PHP + MySQL portal that does exactly one job: collect registration +
proof-of-payment submissions and store them safely. No admin dashboard, no ticket
generation, no QR codes — your team is handling that separately against this same database.

## What's included

```
├── index.php              Landing page (United 24 X MedNovas branding)
├── payment-info.php       Bank details screen
├── submit.php             Registration form + secure upload + DB insert
├── config/
│   ├── config.php          All settings — EDIT THIS (DB creds, bank info, branding)
│   └── database.php        PDO connection (blocked from direct HTTP access)
├── includes/
│   ├── session.php          Hardened session bootstrap
│   ├── csrf.php              CSRF token helpers
│   ├── functions.php        Output escaping + secure file upload validation
│   └── footer.php            Credit + social links, shown on every page
├── uploads/                 Passport photos & payment proofs (script execution disabled)
└── database/schema.sql      Run this first — single `submissions` table
```

## What gets stored, per submission

`reg_number`, `full_name`, `email`, `phone`, `department`, the two uploaded image
filenames, a `status` column (defaults to `'pending'`, ENUM `pending`/`accepted`/`rejected`
for your other system to update), and `created_at`.

## Deploy (phone-only, no CLI, no Composer)

**1. Database** — In your host's control panel, create a MySQL database + user.
Open **phpMyAdmin** → select the database → **Import** → upload `database/schema.sql` → Go.

**2. Upload the app** — Open **File Manager** → `public_html` → **Upload** the zip →
right-click → **Extract**. Move the contents up so `index.php` sits at the root.

**3. Configure** — Right-click `config/config.php` → **Edit**. Fill in `DB_HOST`,
`DB_NAME`, `DB_USER`, `DB_PASS`, and your bank details.

**4. Test** — Visit your domain, go through payment → submit a test registration →
check phpMyAdmin to confirm the row and both image files landed correctly.

That's it — there's no admin login or setup step in this build, since review happens
in whatever system your team is pointing at this database.

## Handing off to your review/ticketing system

Point it at the same `united24_mednovas` database. Useful things to know:
- `status` starts at `'pending'` — your system can `UPDATE submissions SET status = ...`
- Passport photos live in `uploads/passports/<random-filename>.jpg|png`
- Payment proofs live in `uploads/proofs/<random-filename>.jpg|png`
- Filenames are cryptographically random (not the original upload name) — the DB row
  is the only way to know which file belongs to which submission

## Security notes

- All queries use PDO prepared statements
- Uploads are MIME-verified (not just extension-checked), size-capped at 5MB, renamed
  to random strings, and the `uploads/` folder has script execution disabled via `.htaccess`
- CSRF token on the submission form
- Sessions are hardened (secure cookie flags, periodic ID rotation)
- `display_errors` is off — check host error logs if something goes wrong silently

## Before going live

- [ ] Real HTTPS certificate on your domain
- [ ] `.htaccess` files in `config/`, `includes/`, `uploads/` are actually being respected
      (ask your host to confirm `AllowOverride All`; Nginx needs the equivalent as server config)
- [ ] Database user has only the privileges this app needs (SELECT/INSERT on its own database)
- [ ] `uploads/` folder confirmed non-executable — try uploading a `.php` renamed `.jpg` and
      confirm it can't run
