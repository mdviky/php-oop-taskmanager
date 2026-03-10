# Sample PHP MVC Task Manager - Technical Documentation

Audience: Development team

This document explains the architecture, runtime flow, configuration, database schema, routing, tests, coverage, and operational guidance for the Sample PHP MVC Task Manager application.

**Table Of Contents**
1. Overview
2. Architecture
3. Folder Structure
4. Runtime Request Flow
5. Configuration And Environment
6. Database Schema And Migrations
7. Data Access Layer
8. Service Layer
9. Controller Layer
10. View Layer
11. Routing
12. Testing Strategy
13. Code Coverage
14. Running Locally
15. Troubleshooting
16. Extending The Application

**1. Overview**
This project is a minimal PHP 8.1+ MVC application implementing a Task Manager. It uses a custom lightweight framework composed of core classes for routing, HTTP request and response handling, dependency injection, and view rendering. Persistence is provided by MySQL via PDO. Tests are written in PHPUnit.

Primary capabilities:
- List tasks
- Create tasks
- View task details
- Edit tasks
- Delete tasks

**2. Architecture**
The architecture follows a standard MVC approach with a thin framework in `app/Core`. It is intentionally minimal for readability. There is a clear separation between:
- Controllers: HTTP orchestration
- Services: Business logic and validation
- Repositories: Database access via PDO
- Models: Domain data shape
- Views: PHP templates

The `public/index.php` front controller bootstraps the app, loads routes, then dispatches the request through the router. Controllers delegate to services, services call repositories, and views render output.

**3. Folder Structure**
- `public/` Front controller and static assets
- `app/Core/` Framework core: router, request, response, DI, config, env
- `app/Controllers/` Controllers for HTTP actions
- `app/Services/` Business logic
- `app/Repositories/` Database access
- `app/Models/` Data models
- `app/Views/` Templates
- `config/` Application and DB config
- `database/` Migrations and seeds
- `tests/` PHPUnit tests

**4. Runtime Request Flow**
1. `public/index.php` loads autoloader, config, routes.
2. `Request::fromGlobals()` builds a request from `$_SERVER`, `$_GET`, and `$_POST`.
3. `Router::dispatch()` matches the request and invokes a controller method.
4. Controller calls a service method.
5. Service validates input and calls repository methods.
6. Repository executes SQL via PDO and returns models.
7. Controller returns HTML or a redirect response.
8. `Response::send()` outputs headers and body.

**5. Configuration And Environment**
Configuration is loaded through `App/Core/Config.php` and environment variables are read using `App/Core/Env.php`.

- `.env` is loaded by scripts and `public/index.php` (if used there)
- `config/db.php` reads environment variables and builds DB config

Typical `.env` keys:
- `DB_HOST`
- `DB_NAME`
- `DB_USER`
- `DB_PASS`
- `DB_CHARSET`

For test isolation, the test harness reads `DB_TEST_NAME` if present and uses that DB instead of `DB_NAME`.

**6. Database Schema And Migrations**
Migrations are plain SQL files in `database/migrations/`.

Current migration:
- `001_create_tasks_table.sql`

Schema summary:
- `tasks`
  - `id` INT, auto increment
  - `title` VARCHAR(255), required
  - `description` TEXT, nullable
  - `is_completed` TINYINT(1), default 0
  - `created_at` DATETIME
  - `updated_at` DATETIME

Migration runner:
- `database/migrate.php`
  - Creates `migrations` table if missing
  - Executes new migration files in order

Seeder:
- `database/seed.php`
  - Loads SQL from `database/seeds/tasks.sql`

**7. Data Access Layer**
Repository: `app/Repositories/TaskRepository.php`
- `all()` returns all tasks ordered by `created_at` DESC
- `find($id)` returns a `Task` or null
- `create($data)` inserts a task and returns the new ID
- `update($id, $data)` updates a task
- `delete($id)` deletes a task

Repository uses PDO with prepared statements and a fixed schema. Timestamps are generated in PHP using `DateTimeImmutable`.

**8. Service Layer**
Service: `app/Services/TaskService.php`
- `listTasks()` delegates to repository `all()`
- `getTask($id)` returns array data for views
- `createTask($data)` validates, then creates
- `updateTask($id, $data)` validates, then updates
- `deleteTask($id)` deletes

Validation rules:
- Title is required and must be non-empty after trimming

Services return arrays with either:
- `['id' => $id]` on success
- `['errors' => [...]]` on validation failure

**9. Controller Layer**
Controller: `app/Controllers/TaskController.php`

Actions:
- `index()` loads tasks and renders `tasks/index`
- `show($id)` renders `tasks/show` or returns 404 response
- `create()` renders `tasks/create`
- `store()` validates and saves, or re-renders create with errors
- `edit($id)` renders `tasks/edit` or returns 404
- `update($id)` validates and saves, or re-renders edit with errors
- `delete($id)` deletes and redirects

Redirects are done via `Controller::redirect()` which sets status 302 and `Location` header.

**10. View Layer**
Views are plain PHP templates located in `app/Views/`.

Layout:
- `app/Views/layout.php` wraps content in a shared HTML layout

Task views:
- `app/Views/tasks/index.php`
- `app/Views/tasks/show.php`
- `app/Views/tasks/create.php`
- `app/Views/tasks/edit.php`

View rendering:
- `View::render($template, $data, $layout)`
- `layout` defaults to `layout` but can be disabled by passing empty string
- Missing views or layouts throw a `RuntimeException`

**11. Routing**
Routes are defined in `config/routes.php`:
- `GET /tasks` -> `TaskController@index`
- `GET /tasks/create` -> `TaskController@create`
- `POST /tasks` -> `TaskController@store`
- `GET /tasks/{id}` -> `TaskController@show`
- `GET /tasks/{id}/edit` -> `TaskController@edit`
- `POST /tasks/{id}` -> `TaskController@update`
- `POST /tasks/{id}/delete` -> `TaskController@delete`

No route is defined for `/`. If desired, add a redirect in routes:
- `GET /` -> redirect to `/tasks`

**12. Testing Strategy**
Tests are in `tests/Unit`. They cover core framework, model, repository, service, and controller flows. Tests use MySQL with a dedicated test database.

Test categories:
- Core framework
  - `RequestTest`, `ResponseTest`, `ViewTest`, `ConfigTest`, `EnvTest`, `RouterTest`
- Model
  - `TaskModelTest`
- Repository
  - `TaskRepositoryTest`
- Service
  - `TaskServiceTest`
- Controller
  - `TaskControllerTest`

Database test base:
- `tests/Support/DatabaseTestCase.php`
  - Loads `.env` and `config/db.php`
  - Uses `DB_TEST_NAME` if set
  - Runs migration SQL
  - Clears `tasks` table before each test

Important note:
- Tests will delete rows from `tasks` table in the test DB.

**13. Code Coverage**
Coverage is configured in `phpunit.xml` to include `app/`.

To run coverage in text:
```bash
vendor/bin/phpunit --coverage-text
```

To generate HTML report:
```bash
vendor/bin/phpunit --coverage-html coverage
```

Requirements:
- Xdebug or PCOV must be installed and enabled

Xdebug example in `php.ini`:
```ini
zend_extension=xdebug
xdebug.mode=coverage
```

**14. Running Locally**
**14.1 Developer Setup (Workstation)**
Prerequisites:
- PHP 8.1+ CLI
- Composer
- MySQL 8.x (or compatible)
- Optional for coverage: Xdebug or PCOV

Recommended Windows setup (example):
1. Install PHP and add it to PATH
2. Install Composer and verify `composer` works in PowerShell
3. Install MySQL and ensure it is running as a service

Quick verification:
```bash
php -v
composer --version
mysql --version
```

Local database setup:
1. Create a database for development:
```sql
CREATE DATABASE task_manager;
```
2. Create a separate test database:
```sql
CREATE DATABASE task_manager_test;
```
3. Configure `.env`:
```
DB_HOST=127.0.0.1
DB_NAME=task_manager
DB_USER=root
DB_PASS=your_password
DB_CHARSET=utf8mb4
DB_TEST_NAME=task_manager_test
```

Dependency install:
```bash
composer install
```

Run migrations and seed data:
```bash
php database/migrate.php
php database/seed.php
```

Start the local server:
```bash
php -S localhost:8000 -t public
```

Open in browser:
- `http://localhost:8000/tasks`

Steps:
1. Install dependencies
```bash
composer install
```

2. Configure `.env` with DB credentials

3. Create database in MySQL

4. Run migrations
```bash
php database/migrate.php
```

5. Optional seed
```bash
php database/seed.php
```

6. Start server
```bash
php -S localhost:8000 -t public
```

7. Open in browser
- `http://localhost:8000/tasks`

**15. Troubleshooting**
Common issues:
- 404 at `/`
  - Only `/tasks` is defined. Go to `/tasks` or add a route for `/`.

- PHPUnit using XAMPP PEAR version
  - Use `vendor/bin/phpunit` or `composer test`.

- No coverage driver
  - Install and enable Xdebug or PCOV.

- Database connection errors
  - Check `.env` values for host, user, password, database.
  - Ensure MySQL is running and DB exists.

- Tests wiping dev data
  - Set `DB_TEST_NAME` to a dedicated test DB.

**16. Extending The Application**
Add a new feature by following these steps:
1. Add a route in `config/routes.php`.
2. Add a controller method in `app/Controllers/`.
3. Add service logic in `app/Services/`.
4. Add repository methods if data access is required.
5. Add view templates in `app/Views/`.
6. Write or update tests in `tests/Unit/`.

Recommendations:
- Keep services free of HTTP concerns.
- Keep controllers thin.
- Use repositories for all SQL.
- Add validation in services.
- Prefer new tests alongside new features.

End of document.
