<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About This App

This repository contains a recruitment task implemented with Laravel.  
The implementation time was limited to **6 hours of coding**.

The goal was to build a small Laravel application that acts as a client for the Swagger Petstore API, with a strong focus on backend logic and API communication rather than UI design.

## Task Description

The application integrates with the external Swagger Petstore API and exposes a simple web interface to manage pets.

## Installation & Running the App

The project is a standard Laravel application. Below is the setup to run it locally **with Vite** (recommended, since the layout uses `@vite('resources/css/app.css')`).

### 1. Clone the repository

```bash
git clone <REPO_URL>
cd laravel-api-pet-store-app
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Environment configuration

Copy the example environment file and generate the application key:

```bash
cp .env.example .env
php artisan key:generate
```

Then adjust .env if needed, for example:

```bash
APP_ENV=local
APP_DEBUG=true

PETSTORE_BASE_URL=https://petstore.swagger.io/v2
PETSTORE_API_KEY=****** 
```

Note: The application does not use a local database; it talks directly to the external Swagger Petstore API.

### 4. Install frontend dependencies (Vite)

```bashv
npm install
```

### 5. Run the app (development mode, with Tailwind styling)

In two separate terminals:

```bash
# Terminal 1 – Laravel dev server
php artisan serve
```

```bash
# Terminal 2 – Vite dev server (assets)
npm run dev
```
Then open the URL shown by php artisan serve (typically http://127.0.0.1:8000) and navigate to /pets.

Without npm run dev (or a production build), the application will still work, but without Tailwind styling, because the CSS is loaded via Vite.

### 6. Production-style build (optional)

If you prefer not to run the Vite dev server, you can build the assets once:

```bash
npm run build
php artisan serve
```
npm run build will generate the Vite manifest in public/build/, so @vite('resources/css/app.css') will work without a running Vite dev server.

### Functional Scope

The application implements full CRUD operations for the `pet` resource against the external Petstore API:

- **Create** – add a new pet via `POST /pet`
- **Read**
  - list pets by status via `GET /pet/findByStatus`
  - view a single pet via `GET /pet/{petId}`
- **Update** – edit an existing pet via `PUT /pet`
- **Delete** – remove a pet via `DELETE /pet/{petId}`

### User Interface

A very simple web interface (Blade templates) is provided, containing basic forms and views to:

- display a list of pets,
- create a new pet,
- edit an existing pet,
- view pet details,
- delete a pet.

The UI is intentionally minimal – the goal is to demonstrate backend logic and API integration, not front-end styling.

### Error Handling

The application handles common error scenarios, including:

- **Validation errors** for incorrect or missing form data (via Laravel Form Requests),
- **External API errors**, such as:
  - connectivity issues (no response from the Petstore API),
  - HTTP errors returned by the API (e.g. 4xx / 5xx),
  - missing resources (e.g. pet not found → 404).

User-friendly flash messages are displayed in the UI to indicate success or failure of operations.

### Evaluation Focus

As requested by the development team:

- The **visual layer is not a priority** and is not meant to be production-grade.
- The main focus is on:
  - the **backend implementation**,
  - the way the application **communicates with the Petstore API**,
  - the **structure and clarity of the application logic**,
  - and how **errors and edge cases** are handled.

### Tech Stack & Implementation Notes

- **Framework:** Laravel 13
- **PHP:** 8.4
- **Strict types:** `declare(strict_types=1);` enabled in controllers, form requests and client classes
- **HTTP client:** Laravel HTTP Client (`Illuminate\Support\Facades\Http`) used to communicate with the Petstore API
- **API integration:**
  - Dedicated `PetStoreClient` class wrapping all external API calls
  - Clear separation of concerns: controllers delegate API calls and mapping logic to the client
- **Validation:** Laravel Form Requests (`StorePetRequest`, `UpdatePetRequest`) used for input validation and error messages
- **Views:** Blade templates with minimal Tailwind‑style utility classes, focused on usability, not design
- **Error handling:**
  - API connectivity and HTTP errors mapped to domain‑level exceptions
  - User‑friendly error messages shown via session flash messages
- **Testing (basic):**
  - HTTP client calls tested with `Http::fake()` where applicable
  - Focus on verifying correct API interaction and exception behavior rather than exhaustive test coverage

