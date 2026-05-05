# NOC E-Recruitment System

A comprehensive web-based recruitment management system built with **Laravel 12**, replicating and enhancing the [Nepal Oil Corporation's E-Recruitment Portal](https://erecruitment.nepaloil.org.np). The system streamlines the entire hiring process — from vacancy posting to final approval — with a 5-role authentication hierarchy, Nepal-specific payment integration, Nepali (BS) date support, and bilingual capabilities.

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

---

## Screenshots

### Candidate Portal

| Login Page | Registration |
|:---:|:---:|
| ![Login](screenshots/candidate-login.png) | ![Register](screenshots/candidate-register.png) |

| Browse Vacancies | Application Form |
|:---:|:---:|
| ![Vacancies](screenshots/browse-vacancies.png) | ![Application](screenshots/application-form.png) |

| Candidate Dashboard | Application Status |
|:---:|:---:|
| ![Dashboard](screenshots/candidate-dashboard.png) | ![Status](screenshots/application-status.png) |

### Admin Panel

| Super Admin Dashboard | Vacancy Management |
|:---:|:---:|
| ![Admin Dashboard](screenshots/admin-dashboard.png) | ![Manage Vacancies](screenshots/vacancy-management.png) |

| Application Review | Manage Reviewers |
|:---:|:---:|
| ![Review](screenshots/application-review.png) | ![Reviewers](screenshots/manage-reviewers.png) |

| Manage Approvers | Manage HR Administrators |
|:---:|:---:|
| ![Approvers](screenshots/manage-approvers.png) | ![HR Admins](screenshots/manage-hr-admins.png) |

### Reviewer & Approver Panels

| Reviewer Dashboard | Approver Dashboard |
|:---:|:---:|
| ![Reviewer](screenshots/reviewer-dashboard.png) | ![Approver](screenshots/approver-dashboard.png) |

### Other

| Admit Card (PDF) | Payment Integration |
|:---:|:---:|
| ![Admit Card](screenshots/admit-card.png) | ![Payment](screenshots/payment.png) |

> **To add screenshots:** Create a `screenshots/` folder in your repo root, take screenshots from your running local app, and save them with the filenames shown above.

---

## Features

### 5-Role Authentication System

The system implements a custom multi-guard authentication architecture (`config/auth.php`) with five distinct user roles, each with a dedicated guard, model, middleware, and dashboard:

| Role | Guard | Responsibilities |
|------|-------|-----------------|
| **Super Admin** | `admin` | Full system control — manages all users (HR Admins, Reviewers, Approvers, Candidates), creates/publishes vacancies, assigns reviewers & approvers, views all applications, makes final decisions |
| **HR Administrator** | `hr_administrator` | Manages vacancies and applications within their department, screens candidates, coordinates recruitment workflow |
| **Approver** | `approver` | Reviews applications assigned by Admin, provides final approve/reject decisions with notes, receives notifications for assigned applications |
| **Reviewer** | `reviewer` | Evaluates assigned applications, provides review scores and recommendations, adds reviewer notes |
| **Candidate** | `candidate` | Registers with OTP email verification, browses vacancies, submits applications, uploads documents, makes payments, tracks application status, downloads admit cards, views results |

### Vacancy Management

- Create, edit, publish, and close vacancies with detailed position information
- Fields include: title, level, department/service group, category, required qualifications, age limits, education requirements
- Application deadlines with normal and double-fee (दोब्बर दस्तुर) periods
- Internal vacancy type and category classification
- Advertisement number tracking
- Nepali (BS) date support for deadlines via custom `adToBS()` helper

### Application System

- Multi-step application form with comprehensive fields (personal info, education, work experience, documents)
- Document uploads: citizenship, certificates, photo, signature
- Application status workflow: `draft` → `submitted` → `reviewed` → `approved` / `rejected`
- Reviewer assignment with notes and review timestamp
- Approver assignment with notes and approval timestamp
- PDF admit card generation with Devanagari (Nepali) font rendering
- Exam result publishing and candidate result viewing

### Payment Integration

Nepal-specific payment gateways integrated:

- **eSewa** — Most widely used digital wallet in Nepal
- **Khalti** — Popular mobile payment platform
- **ConnectIPS** — Bank-linked payment system

Each with dedicated Blade views (`resources/views/payment/`) and controller logic for transaction reference tracking.

### Notification System

- In-app notification system for all user roles
- Notification types: application status updates, reviewer/approver assignments, vacancy alerts
- Mark as read, mark all as read, and delete functionality
- Dedicated `NotificationController` per role

### Additional Features

- AD to BS (Bikram Sambat) Nepali date conversion helper
- PDF generation using DomPDF & mPDF with DejaVu Sans font for Devanagari script
- Email notifications via SMTP (Mailtrap for development)
- OTP-based email verification for candidates
- Custom 404 error page
- Responsive design across all panels
- Eloquent ORM with proper relationships and 40+ migrations
- Database seeders for Admin, Vacancies, Reviewers, and Results

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Framework | Laravel 12 |
| Language | PHP 8.2+ |
| Database | MySQL (InnoDB) |
| Frontend | Blade Templates, Bootstrap 5, JavaScript, Vite |
| PDF Generation | DomPDF, mPDF, Laravel Snappy |
| Mail | SMTP (Mailtrap for development) |
| Payments | eSewa, Khalti, ConnectIPS APIs |
| Date Conversion | Custom AD ↔ BS helper (`app/Helpers/helpers.php`) |
| Database Toolkit | Doctrine DBAL |
| Dev Environment | XAMPP, VS Code |

---

## Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- MySQL 5.7+ or MariaDB
- Node.js & npm (for Vite frontend assets)
- XAMPP / WAMP / Laravel Valet (local server)

### Setup

```bash
# Clone the repository
git clone https://github.com/shubham016/NOC-E-Recruitment.git
cd NOC-E-Recruitment

# Install PHP dependencies
composer install

# Install frontend dependencies
npm install && npm run build

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your database in .env
# DB_DATABASE=recruitment_system
# DB_USERNAME=root
# DB_PASSWORD=

# Run migrations
php artisan migrate

# Seed default data (Admin, Vacancies, Reviewers, Results)
php artisan db:seed

# Start the development server
php artisan serve
```

Or use the built-in composer script:

```bash
composer setup
```

### Environment Configuration

Key `.env` variables to configure:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=recruitment_system
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_FROM_ADDRESS=noreply@erecruitment.com
MAIL_FROM_NAME="Recruitment Portal"
```

---

## Project Structure

```
NOC-E-Recruitment/
├── app/
│   ├── Helpers/
│   │   └── helpers.php                    # AD↔BS date conversion
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/                     # Super Admin controllers
│   │   │   │   ├── AdminDashboardController.php
│   │   │   │   ├── VacancyManagementController.php
│   │   │   │   ├── AdminApplicationController.php
│   │   │   │   ├── ReviewerController.php
│   │   │   │   ├── ApproverController.php
│   │   │   │   ├── HRAdministratorController.php
│   │   │   │   ├── CandidateManagementController.php
│   │   │   │   └── NotificationController.php
│   │   │   ├── Approver/                  # Approver controllers
│   │   │   │   ├── ApproverAuthController.php
│   │   │   │   ├── AssignedToMeController.php
│   │   │   │   └── NotificationController.php
│   │   │   ├── Auth/                      # Auth controllers per role
│   │   │   │   ├── AdminAuthController.php
│   │   │   │   ├── CandidateAuthController.php
│   │   │   │   ├── HRAdministratorAuthController.php
│   │   │   │   └── ReviewerAuthController.php
│   │   │   ├── Candidate/                 # Candidate controllers
│   │   │   │   ├── CandidateDashboardController.php
│   │   │   │   ├── ApplicationFormController.php
│   │   │   │   ├── VacancyBrowsingController.php
│   │   │   │   ├── PaymentController.php
│   │   │   │   ├── AdmitCardController.php
│   │   │   │   ├── CandidateResultController.php
│   │   │   │   ├── ProfileController.php
│   │   │   │   ├── SettingsController.php
│   │   │   │   └── NotificationController.php
│   │   │   ├── HRAdministrator/           # HR Admin controllers
│   │   │   │   ├── HRAdministratorDashboardController.php
│   │   │   │   ├── HRVacancyController.php
│   │   │   │   ├── HRApplicationController.php
│   │   │   │   ├── HRCandidateController.php
│   │   │   │   ├── HRReviewerController.php
│   │   │   │   └── NotificationController.php
│   │   │   └── Reviewer/                  # Reviewer controllers
│   │   │       ├── ReviewerDashboardController.php
│   │   │       ├── ApplicationReviewController.php
│   │   │       └── NotificationController.php
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php
│   │       ├── ApproverMiddleware.php
│   │       ├── CandidateMiddleware.php
│   │       ├── CandidateSessionMiddleware.php
│   │       ├── HRAdministratorMiddleware.php
│   │       ├── RedirectIfNotApprover.php
│   │       └── ReviewerMiddleware.php
│   ├── Mail/
│   └── Models/
│       ├── Admin.php
│       ├── Approver.php
│       ├── Candidate.php
│       ├── CandidateOtp.php
│       ├── HRAdministrator.php
│       ├── Reviewer.php
│       ├── JobPosting.php                 # Vacancy model
│       ├── Application.php
│       ├── ApplicationForm.php
│       ├── Notification.php
│       ├── Payment.php
│       ├── Result.php
│       └── RegistrationForm.php
├── config/
│   ├── auth.php                           # 5 guards + providers
│   └── dompdf.php                         # PDF configuration
├── database/
│   ├── migrations/                        # 40+ migration files
│   ├── seeders/
│   │   ├── AdminSeeder.php
│   │   ├── JobPostingSeeder.php
│   │   ├── ReviewerSeeder.php
│   │   └── ResultSeeder.php
│   └── factories/
├── resources/views/
│   ├── admin/                             # Admin panel views
│   │   ├── dashboard.blade.php
│   │   ├── jobs/                          # Vacancy CRUD views
│   │   ├── applications/
│   │   ├── reviewers/
│   │   ├── approvers/
│   │   ├── hr-administrators/
│   │   └── candidates/
│   ├── approver/                          # Approver views
│   │   ├── dashboard.blade.php
│   │   ├── login.blade.php
│   │   ├── assignedtome.blade.php
│   │   ├── show.blade.php
│   │   └── notifications/
│   ├── auth/                              # Login/register per role
│   │   ├── admin/
│   │   ├── approver/
│   │   ├── candidate/
│   │   ├── hr-administrator/
│   │   └── reviewer/
│   ├── candidate/                         # Candidate portal views
│   │   ├── dashboard.blade.php
│   │   ├── login.blade.php
│   │   ├── register.blade.php
│   │   ├── applications/
│   │   ├── vacancies/
│   │   ├── payment/
│   │   ├── profile/
│   │   ├── settings/
│   │   ├── admit-card.blade.php
│   │   ├── admit-card-pdf.blade.php
│   │   └── view-result.blade.php
│   ├── hr-administrator/                  # HR Admin views
│   │   ├── dashboard.blade.php
│   │   └── vacancies/
│   ├── reviewer/                          # Reviewer views
│   │   ├── dashboard.blade.php
│   │   ├── applications/
│   │   └── notifications/
│   ├── layouts/                           # Shared Blade layouts
│   │   ├── app.blade.php
│   │   ├── apps.blade.php
│   │   └── dashboard.blade.php
│   ├── payment/                           # Payment gateway views
│   │   ├── esewa.blade.php
│   │   ├── khalti.blade.php
│   │   └── connectips.blade.php
│   ├── errors/
│   │   └── 404.blade.php
│   └── welcome.blade.php                  # Landing page
├── public/
│   ├── css/
│   ├── js/
│   └── images/                            # NOC logos, payment logos
├── routes/
│   └── web.php                            # All route definitions
├── storage/
│   └── fonts/                             # DejaVu Sans for PDF
└── composer.json
```

---

## Role Hierarchy & Workflow

```
┌─────────────────────────────────────────┐
│            SUPER ADMIN                  │
│   Full system control & final authority │
│   Manages all users & vacancies         │
└──────────────────┬──────────────────────┘
                   │
    ┌──────────────┼──────────────┐
    │              │              │
┌───▼────────┐ ┌───▼────────┐ ┌──▼───────────┐
│ HR ADMIN   │ │ APPROVER   │ │  REVIEWER    │
│ Dept. mgmt │ │ Final call │ │ Evaluates    │
│ & screening│ │ approve/   │ │ applications │
│            │ │ reject     │ │ & scores     │
└───┬────────┘ └────────────┘ └──────────────┘
    │
    └──────────────┐
                   │
            ┌──────▼──────┐
            │  CANDIDATE  │
            │  Registers, │
            │  applies &  │
            │  tracks     │
            └─────────────┘
```

### Application Workflow

```
Candidate submits application
        │
        ▼
  Admin assigns Reviewer
        │
        ▼
  Reviewer evaluates & adds notes
        │
        ▼
  Admin assigns Approver
        │
        ▼
  Approver makes final decision
  (approve / reject with notes)
        │
        ▼
  Candidate receives notification
```

---

## Default Login Routes

| Role | Login URL |
|------|----------|
| Super Admin | `/admin/login` |
| HR Administrator | `/hr-administrator/login` |
| Approver | `/approver/login` |
| Reviewer | `/reviewer/login` |
| Candidate | `/candidate/login` |

---

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/new-feature`)
3. Commit your changes (`git commit -m 'Add new feature'`)
4. Push to the branch (`git push origin feature/new-feature`)
5. Open a Pull Request

---

## License

This project is open-sourced under the [MIT License](LICENSE).

---

## Acknowledgements

- Inspired by [Nepal Oil Corporation E-Recruitment Portal](https://erecruitment.nepaloil.org.np)
- Built with [Laravel](https://laravel.com)
- PDF rendering powered by [DomPDF](https://github.com/barryvdh/laravel-dompdf) & [mPDF](https://mpdf.github.io/)
