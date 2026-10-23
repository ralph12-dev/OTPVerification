# OTP Verification System

**Final Laboratory Activity 1 — Email OTP Verification**  
Laravel 12 application for requesting an email verification code and protecting a page until the code is verified.

## Features implemented

- Generates a six-digit OTP using PHP's `random_int()`.
- Emails the OTP using Laravel Mail and the SMTP account configured on the machine running the app.
- Stores the OTP as a hash and expires it after five minutes.
- Rejects incorrect, expired, malformed, and reused codes with validation or error messages.
- Deletes a code after successful verification and regenerates the session.
- Protects the home page until verification succeeds.
- Limits OTP requests and verification attempts; invalidates a code after five incorrect attempts.

SMS delivery is not included. The activity identifies SMS as optional or an advanced task; the submitted implementation is email-based.

## Requirements

- PHP 8.2 or newer
- Composer
- SQLite (the default database configuration) or another database supported by Laravel
- SMTP credentials for real email delivery

## Install and run (Windows PowerShell)

Run these commands from the project directory:

```powershell
composer install
Copy-Item .env.example .env
if (-not (Test-Path database\database.sqlite)) {
    New-Item -ItemType File database\database.sqlite
}
php artisan key:generate
php artisan migrate
```

Configure mail in `.env` as described below, then clear cached configuration and start the server:

```powershell
php artisan config:clear
php artisan serve
```

Open <http://127.0.0.1:8000> in a browser.

## Configure real email delivery

The `.env.example` file contains placeholder values. To send real email, set the mail variables in your local `.env` to credentials for an SMTP provider. For Gmail, use a Google App Password (not your regular account password):

```dotenv
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your.address@gmail.com
MAIL_PASSWORD=your_google_app_password
MAIL_FROM_ADDRESS=your.address@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

Use a sender address authorized by your provider. After changing `.env`, run `php artisan config:clear`. Do not share or submit `.env`, SMTP passwords, App Passwords, or real credentials. `.env` is excluded by `.gitignore`. An instructor who runs the project and wants to test real delivery must configure their own SMTP credentials in their local `.env`.

## Application flow

1. Visit `/request-otp` and enter an email address.
2. The app generates and emails a six-digit code.
3. Visit `/verify-otp` and enter the code received by email.
4. A valid code redirects to `/home`; access to that page requires successful verification.

## Verification performed

- The OTP feature test suite passes: `php artisan test --filter=OtpVerificationTest`.
- Manual end-to-end testing confirmed that a code was delivered by email and a valid code unlocked the protected home page.
- Incorrect and expired codes were rejected with `The OTP is incorrect or has expired.`
- Empty and non-six-digit submissions displayed their required validation messages.

Automated tests use Laravel's `Mail::fake()` and do not send real email. Real delivery must be tested with valid SMTP settings.

## Run the tests

```powershell
php artisan test --filter=OtpVerificationTest
```

## Submission checklist

- Submit the Laravel project source, including application code, migrations, tests, and this README.
- Include screenshots of the request page, verification page, successful verification/protected home page, and relevant validation or error messages, as requested by the activity.
- Do not include `.env` or expose SMTP credentials in screenshots, source code, or submission notes.
- SMS integration is only needed if it was separately assigned by the instructor.
