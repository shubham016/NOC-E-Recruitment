# NOC E-Recruitment System

Laravel-based recruitment management system for vacancy publishing, candidate registration, online applications, reviewer/approver workflows, payments, SMS notifications, admit cards, reports, and audit logs.

This file is the main project guide. Keep it when cleaning the project for transfer to another PC.

## Verified Project Stack

- Laravel: 12.55.1
- PHP: 8.2 or newer
- Database: MySQL/MariaDB
- Frontend build: Vite
- Node/NPM: Node 22 LTS or newer recommended
- PDF: DomPDF/mPDF with Devanagari font files in `storage/fonts`
- Payments: eSewa, Khalti, ConnectIPS
- SMS: Sparrow SMS
- Mail: SMTP

The project was checked with:

```powershell
php artisan about
php artisan route:list
php artisan migrate:status
php artisan test
```

At the time of this README update, the test suite passed: 6 tests, 19 assertions.

## Main Folders

```text
app/                  Controllers, models, middleware, services, helpers
bootstrap/            Laravel bootstrap files
config/               Laravel and service configuration
database/             Migrations, seeders, factories
lang/                 English and Nepali translation files
public/               Web root, CSS/JS/images, index.php
resources/            Blade views, source CSS/JS
routes/               Route definitions
storage/app/public/   Uploaded candidate/application files
storage/fonts/        PDF fonts and font cache
tests/                Automated tests
```

## Important Runtime Files

These files are required to install or recover the project:

```text
artisan
composer.json
composer.lock
package.json
package-lock.json
phpunit.xml
vite.config.js
.env.example
```

For an existing live/local system, also keep or back up:

```text
.env
storage/app/public/
storage/app/connectips/
storage/fonts/
database dump file, for example recruitment_system.sql
```

## Files And Folders Not To Copy

These are generated, local, temporary, or unsafe to transfer as source:

```text
vendor/
node_modules/
public/storage
public/build
public/hot
storage/logs/*.log
.phpunit.result.cache
.rnd
.claude/
.vscode/
public/phpinfo.php
temp_*.txt
posted_by)
posted_by_type
C*events_full.json
C*issue_events.json
```

Notes:

- `vendor/` is recreated by `composer install`.
- `node_modules/` is recreated by `npm.cmd install`.
- `public/storage` is a symlink and should be recreated with `php artisan storage:link`.
- `public/phpinfo.php` should be deleted because it exposes PHP/server configuration.
- `.claude/` is local assistant metadata and is not needed by Laravel.

## Required Software On A New Windows PC

Install these first:

1. XAMPP, WAMP, Laragon, or separate PHP + MySQL/MariaDB.
2. PHP 8.2 or newer.
3. Composer.
4. Node.js 22 LTS or newer.
5. Git, optional but recommended.

Required PHP extensions:

```text
bcmath
curl
dom
fileinfo
gd
json
mbstring
mysqli
openssl
pdo_mysql
pdo_sqlite
tokenizer
xml
xmlreader
xmlwriter
zip
```

On Windows PowerShell, use `npm.cmd` if plain `npm` is blocked by execution policy.

## Environment Configuration

Copy `.env.example` to `.env`, then adjust the values.

Minimum local settings:

```env
APP_NAME="NOC E-Recruitment"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_TIMEZONE=Asia/Kathmandu

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=recruitment_system
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file
SESSION_LIFETIME=480
CACHE_STORE=file
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=local

MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

Service keys used by the app:

```env
ESEWA_MERCHANT_ID=
ESEWA_SECRET_KEY=
ESEWA_BASE_URL=
ESEWA_SUCCESS_URL=
ESEWA_FAILURE_URL=

KHALTI_SECRET_KEY=
KHALTI_BASE_URL=

CONNECTIPS_MERCHANT_ID=
CONNECTIPS_APP_ID=
CONNECTIPS_APP_NAME=
CONNECTIPS_APP_PASSWORD=
CONNECTIPS_PFX_PASSWORD=
CONNECTIPS_TXN_URL=
CONNECTIPS_VALIDATE_URL=
CONNECTIPS_DETAIL_URL=
CONNECTIPS_PFX_PATH=
CONNECTIPS_PRIVATE_KEY_PATH=

SPARROW_SMS_TOKEN=
SPARROW_SMS_FROM=
SPARROW_SMS_BASE_URL=http://api.sparrowsms.com/v2
```

If you are moving the existing database to a new PC, keep the old `APP_KEY`. Changing `APP_KEY` can break encrypted data, sessions, and stored tokens.

## New PC Setup With Existing Data

Use this when you want the new PC to have the same applications, users, uploaded documents, and records.

### 1. Export Database On Old PC

```powershell
mysqldump -u root -p recruitment_system > recruitment_system.sql
```

If MySQL is from XAMPP and not in PATH, run from the XAMPP MySQL bin folder or use the full path:

```powershell
C:\xampp\mysql\bin\mysqldump.exe -u root -p recruitment_system > recruitment_system.sql
```

### 2. Copy Project Files

Copy the project folder to the new PC, excluding generated/temp folders listed above.

Make sure these data folders are included if you need existing uploads and PDFs:

```text
storage/app/public/
storage/app/connectips/
storage/fonts/
```

### 3. Create And Import Database On New PC

```powershell
mysql -u root -p -e "CREATE DATABASE recruitment_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p recruitment_system < recruitment_system.sql
```

With XAMPP full paths:

```powershell
C:\xampp\mysql\bin\mysql.exe -u root -p -e "CREATE DATABASE recruitment_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
C:\xampp\mysql\bin\mysql.exe -u root -p recruitment_system < recruitment_system.sql
```

### 4. Install Dependencies

From the project folder:

```powershell
composer install
npm.cmd install
npm.cmd run build
```

### 5. Configure `.env`

Copy the old `.env` securely or create a new one from `.env.example`.

If using the existing imported database, use the same `APP_KEY` from the old PC.

If this is a fresh empty setup only, generate a new key:

```powershell
php artisan key:generate
```

### 6. Clear Cache And Link Storage

```powershell
php artisan optimize:clear
php artisan storage:link
composer dump-autoload
```

### 7. Verify

```powershell
php artisan about
php artisan migrate:status
php artisan route:list
php artisan test
```

### 8. Run

```powershell
php artisan serve --host=127.0.0.1 --port=8000
```

Open:

```text
http://localhost:8000
```

## Fresh Setup Without Existing Data

Use this only if you do not need old users, applications, uploaded documents, payments, SMS logs, or audit logs.

```powershell
composer install
copy .env.example .env
php artisan key:generate
npm.cmd install
npm.cmd run build
php artisan migrate
php artisan db:seed
php artisan storage:link
php artisan serve --host=127.0.0.1 --port=8000
```

For this project, importing the existing database is safer than rebuilding from zero because the migration history includes table renames, table recreations, enum changes, and historical data transformations.

## Common Commands

```powershell
php artisan serve --host=127.0.0.1 --port=8000
php artisan optimize:clear
php artisan route:list
php artisan migrate:status
php artisan test
npm.cmd run dev
npm.cmd run build
```

If Vite dev server is needed during frontend work:

```powershell
npm.cmd run dev
```

In another terminal:

```powershell
php artisan serve --host=127.0.0.1 --port=8000
```

## Login Routes

```text
/admin/login
/reviewer/login
/approver/login
/candidate/login
```

The generic `/login` route redirects users toward the portal login flow.

## Main Functional Areas

- Admin dashboard and management
- Candidate registration/login with OTP/email flows
- Vacancy/job posting management
- Candidate application form with document uploads
- Reviewer application review workflow
- Approver final approval/rejection workflow
- Candidate notifications
- SMS logs and candidate SMS notifications
- Payment flows for eSewa, Khalti, and ConnectIPS
- Admit card assignment, preview, view, and download
- Reports and exports
- Audit logs
- Bilingual UI strings under `lang/en` and `lang/ne`

## Notes Before Cleaning Extra Docs

This README is the main documentation file to keep. Other root Markdown files that are not required to run the project can be archived or deleted after confirming they are no longer needed:

```text
BEFORE_AFTER_COMPARISON.md
CHECKBOX_MIGRATION_GUIDE.md
CHECKBOX_QUICK_REFERENCE.md
CHECKBOX_VALIDATION_INDEX.md
CLEAN_DESIGN_SUMMARY.md
LARAVEL_12_CHECKBOX_VALIDATION.md
README_CHECKBOX_VALIDATION.md
```

Do not delete source files, environment files, migrations, uploaded storage, or the database dump unless you have a backup.

## Known Maintenance Items

- Remove `public/phpinfo.php`.
- Review and clean temporary root files before copying to a new PC.
- Keep `storage/app/public` backed up together with the database, because database records reference uploaded file paths.
