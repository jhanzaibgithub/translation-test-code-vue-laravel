Project Name: Translation Management Service

A full-featured Translation Management API and Admin Panel using Laravel (with Passport Auth), Vue 3, Bootstrap 5, Docker, Swagger, and scalable architecture. This system supports multi-locale translation records with tag-based filtering and JSON export for frontend consumption.


---

🛠 Part 1: Backend (Laravel 10 + Passport + Swagger)

✅ 1. Laravel Setup

Installed Laravel 10.

Set up .env file for database & app config.

Ran migrations using php artisan migrate.



---

✅ 2. Authentication

Installed and configured Laravel Passport for token-based authentication.

Added HasApiTokens to User model.

Secured API routes using auth:api middleware.

Implemented basic login and registration routes.



---

✅ 3. Translation CRUD API

Created a Translation model, migration, and controller.

Added support for:

key (string)

value (text)

locale (e.g., en, fr, es)

tag (e.g., mobile, web)




---

✅ 4. Tag Filtering, Search & Pagination

API allows:

Filtering by key, value, tag

Pagination (10/25/50/100/all)

JSON Export for all or paginated records




---

✅ 5. API Routes

Method	URI	Description

GET	/api/translations	Paginated + Filtered list
GET	/api/translations-show	Export all translations (JSON)
POST	/api/translations/create	Add a new translation
POST	/api/translations/update/{id}	Update a translation
POST	/api/translations/delete/{id}	Delete a translation
GET	/api/tags	Get list of unique tags



---

✅ 6. Factory + Seeder for Performance Testing

Used Laravel factories to generate 100,000+ dummy records.


php artisan db:seed --class=TranslationSeeder


---

✅ 7. Swagger/OpenAPI Docs

Installed l5-swagger.

Documented all API endpoints using @OA\... annotations.

Swagger JSON available at:

/api/documentation



---

✅ 8. Docker Setup

Dockerfile for Laravel PHP-FPM.

docker-compose.yml includes:

nginx (reverse proxy)

mysql (8.0)

app (Laravel)



Run with:

docker-compose up -d --build


✅ 9. Testing

Created:

Feature tests for CRUD

Unit test for model validation

Performance test (JSON export response under 500ms)



Run with:

php artisan test




🖼 Part 2: Frontend (Vue 3 + Bootstrap 5)

✅ 1. Setup

Vue 3 + Bootstrap 5 via Vite

Created views and router config for:

Login

Register

Dashboard (Translation Manager)






✅ 2. Translation Management UI

Vue page /translations shows:

Filters: key, value, tag

Pagination & export buttons

Create / Edit modals




✅ 3. File Structure (Frontend)

resources/js/
├── views/
│   ├── Login.vue
│   ├── Register.vue
│   ├── Dashboard.vue
├── components/
│   MainLayout.vue
├── router.js
├── app.js
└── bootstrap.js




✅ 4. Export Button

Exports filtered/paginated results as translations_page_1.json
