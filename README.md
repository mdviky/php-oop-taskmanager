# Sample PHP MVC Task Manager

This project demonstrates a simple custom MVC framework in PHP 8.1 with MySQL persistence and PHPUnit tests. It is intentionally minimal and easy to read.

## Architecture Overview

Request flow:
1. `public/index.php` boots the app.
2. Router matches the request to a controller action.
3. Controller delegates business logic to a service.
4. Service uses repository for data access.
5. View renders the response.

## Folder Structure

- `public/` - Front controller
- `app/Core/` - Framework core (Router, Request, Response, etc.)
- `app/Controllers/` - Controllers
- `app/Services/` - Business logic
- `app/Repositories/` - Data access
- `app/Models/` - Simple data models
- `app/Views/` - PHP templates
- `config/` - App and DB config
- `database/` - SQL schema, migrations, seeds
- `tests/` - PHPUnit tests

## Setup

1. Copy `.env.example` to `.env` and update DB values.
2. Create the database in MySQL.
3. Install dependencies:
   - `composer install`
4. Run migrations:
   - `php database/migrate.php`
5. Optional seed:
   - `php database/seed.php`
6. Run the app using the PHP built-in server:
   - `php -S localhost:8000 -t public`

## Running Tests

- `composer test`

Note: The repository unit test uses SQLite in-memory to keep tests fast and isolated.

## Extending

To add a new feature:
1. Add routes in `config/routes.php`.
2. Create controller methods and views.
3. Add service methods and repository calls as needed.
4. Write tests for service logic and routing behavior.
