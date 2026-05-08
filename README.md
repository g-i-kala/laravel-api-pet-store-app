<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About This App

It's a reqritment task - limited to 6h of coding - that should: 

## Task Description

This project is a small Laravel application that acts as a client for the Swagger Petstore API and focuses on backend logic and API communication rather than UI design.

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


