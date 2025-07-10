<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

---

# 🚀 Project Name: Translation Management Service

A full-featured Translation Management API and Admin Panel using **Laravel (Passport Auth)**, **Vue 3**, **Bootstrap 5**, **Docker**, **Swagger**, and scalable architecture.  
Supports multi-locale translation records with tag-based filtering and JSON export for frontend use.

---

## 🛠 Part 1: Backend (Laravel 10 + Passport + Swagger)

### ✅ 1. Laravel Setup
- Installed Laravel 10
- Set up `.env` for database and app config
- Ran migrations via `php artisan migrate`

---

### ✅ 2. Authentication
- Configured Laravel Passport for token-based authentication
- Added `HasApiTokens` to `User` model
- Protected API routes with `auth:api` middleware
- Implemented login and registration routes

---

### ✅ 3. Translation CRUD API
- Created `Translation` model, migration, controller
- Fields:
  - `key` (string)
  - `value` (text)
  - `locale` (e.g., en, fr, es)
  - `tag` (e.g., mobile, web)

---

### ✅ 4. Tag Filtering, Search & Pagination
- API allows filtering by:
  - `key`, `value`, `tag`
- Pagination (10, 25, 50, 100, all)
- JSON Export for full or paginated results

---

### ✅ 5. API Routes

| Method | URI | Description |
|--------|-----|-------------|
| GET    | `/api/translations`        | Paginated + Filtered list |
| GET    | `/api/translations-show`   | Export all translations (JSON) |
| POST   | `/api/translations/create` | Add a new translation |
| POST   | `/api/translations/update/{id}` | Update a translation |
| POST   | `/api/translations/delete/{id}` | Delete a translation |
| GET    | `/api/tags`                | Get unique tags |

---

### ✅ 6. Factory + Seeder
- Generated 100,000+ dummy records:
```bash
php artisan db:seed --class=TranslationSeeder
