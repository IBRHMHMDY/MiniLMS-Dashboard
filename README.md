# Mini LMS - Backend API & Dashboard

A robust, scalable backend system built with **Laravel** for a Mini Learning Management System (LMS). This project serves as the core infrastructure, providing a secure RESTful API for mobile/web clients and a powerful Admin Dashboard for management.

## 🚀 Tech Stack

* **Framework:** Laravel 12 (PHP)
* **Authentication:** Laravel Sanctum (API), Session (Dashboard)
* **Authorization:** Spatie Roles & Permissions
* **Admin Dashboard:** Filament PHP v4
* **Database:** MySQL
* **Architecture:** Service Layer Pattern, FormRequests Validation, API Resources.

## 🏗️ Architectural Highlights

* **Clean Controllers:** Business logic is abstracted into `Service` classes (e.g., `CourseService`, `PasswordResetService`) adhering to the Single Responsibility Principle.
* **Standardized JSON Responses:** A unified `ApiResponseTrait` ensures all API endpoints return a consistent structure for success and error handling.
* **Data Isolation:** Instructors can only view and manage their own courses and lessons within the Filament Dashboard.

## ⚙️ Installation & Setup

Follow these steps to get the backend running locally:

1. **Clone the repository:**

```bash
git clone <your-repository-url>
cd mini-lms-backend
```
2. **Install dependencies:**
```bash
composer install
```
3. **Environment Setup:**
```bash
cp .env.example .env
php artisan key:generate
Update your .env file with your database credentials (e.g., DB_DATABASE=mini_lms).
```
4. **Run Migrations & Seeders:**
This will create the database tables, default roles, and a Super Admin account.

```bash
php artisan migrate --seed
```
5. **Run Server:**
```bash
php artisan serve
```
## Default Credentials (Filament Dashboard)
6. * **You can access the admin panel at http://127.0.0.1:8000/admin.**

**Login Super Admin: Dashboard Only**

``` admin@minilms.com ```

``` password ```

**Login Instructor: Dashboard Only**

``` instructor@minilms.com ```

``` password ```

## API Endpoints (v1)
* All API routes are prefixed with /api/v1/.

* Auth: POST /auth/login, POST /auth/register, POST /auth/logout

* Password: POST /auth/forgot-password, POST /auth/reset-password

* Profile: GET /profile, PUT /profile

* Public: GET /categories, GET /courses, GET /courses/{id}

* Learning: POST /courses/{id}/enroll, GET /courses/{id}/lessons