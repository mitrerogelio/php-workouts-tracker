# PHP Workout Tracker

A small full-stack workout tracker built in **pure PHP 8.5** with no framework — a
learning project focused on object-oriented design, SOLID principles, and a
.NET-MVC-inspired layered architecture (PascalCase classes, `I`-prefixed
interfaces, repository pattern, dependency injection).

Users can register an account, log in, create workout sessions, and record the
exercises and sets they performed.

## Tech Stack

| Concern            | Choice                                              |
| ------------------ | --------------------------------------------------- |
| Language           | PHP 8.5 (property hooks, `enum`, `never`, promoted constructor properties) |
| Database access    | PDO (MySQL), prepared statements                    |
| Autoloading        | Composer PSR-4 (`App\` → `src/`)                     |
| Static analysis    | PHPStan **level max**                               |
| Auth               | `password_hash` / `password_verify`, PHP sessions   |
| Views              | Plain PHP templates                                 |

## Architecture

The app follows a layered, dependency-inverted design. Each layer depends only on
the abstraction (interface) of the layer beneath it:

```
HTTP request
    │
    ▼
public/index.php  ── front controller / composition root
    │                 (loads .env, builds the dependency graph, defines routes)
    ▼
Http\Router  ──►  Controllers  ──►  Services  ──►  Repositories  ──►  Database (PDO)
                       │              (AuthService)   (IProfileRepository…)   │
                       ▼                                                      ▼
                  Http\View  ──►  Views/*.php                          MySQL (fit_db)
```

- **Controllers** translate HTTP in/out. They never touch raw `mixed` superglobals
  directly — `Http\Request`, `Http\Session`, and `Http\Redirect` provide typed access.
- **Services** hold application logic. `AuthService` coordinates the Profile + Cred
  repositories and is deliberately free of superglobals so it stays unit-testable.
- **Repositories** are the only place that knows SQL. Each has an interface
  (`IProfileRepository`, `IWorkoutRepository`, …) so callers depend on the contract,
  not the implementation.
- **Models** are typed domain objects. A static `fromArray()` factory maps a raw DB
  row (`array<string, mixed>`) to a strongly-typed object — the job an ORM's entity
  mapper would normally do. Read-only access is exposed via **property hooks**.
- **`DataCaster`** is a type guard used by every `fromArray()`. It validates that a
  `mixed` DB value really holds the expected type (and throws if not) rather than
  silently coercing — bad data surfaces instead of hiding.

## Project Structure

```
public/
  index.php          Front controller (routing + composition root)
  styles/main.css
src/
  Config/            LoadEnv — reads .env into $_ENV
  Database/          IDatabase + Database (PDO wrapper)
  Models/            Profile, Cred, WorkoutSession, Exercise, ExerciseSet, Gender
  Repositories/      I*Repository interfaces + implementations
  Services/          DatabaseFactory, DataCaster, AuthService
  Http/              Router, View, Request, Session, Redirect
  Controllers/       AuthController, DashboardController, WorkoutController
  Views/             home, register, login, dashboard, error, _header, _footer
phpstan.neon
composer.json
```

## Getting Started

### Prerequisites

- PHP 8.5+
- MySQL (the schema targets a `fit_db` database)
- Composer

### 1. Install dependencies

```bash
composer install
```

### 2. Configure the database

Create a `.env` file in the project root (it is git-ignored):

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_USER=your_user
DB_PASS=your_password
DB_NAME=fit_db
```

Create the schema (tables: `profiles`, `creds`, `workout_sessions`, `exercises`,
`exercise_sets`, `weight_logs`). Note that `exercise_sets.session_id` references
`workout_sessions` with **`ON DELETE CASCADE`**, so deleting a profile cleanly
removes its sessions and sets.

### 3. Seed some exercises

The "add set" dropdown needs exercises to choose from:

```sql
INSERT INTO exercises (name, description, weight_required) VALUES
  ('Bench Press', 'Barbell chest press',     1),
  ('Squat',       'Barbell back squat',      1),
  ('Deadlift',    'Conventional deadlift',   1),
  ('Pull-up',     'Bodyweight vertical pull', 0),
  ('Plank',       'Core isometric hold',     0),
  ('Running',     'Steady-state cardio',     0);
```

### 4. Run the app

```bash
php -S 127.0.0.1:8000 -t public public/index.php
```

Open <http://127.0.0.1:8000>, register an account, and start logging workouts.

## Routes

| Method | Path               | Action                          |
| ------ | ------------------ | ------------------------------- |
| GET    | `/`                | Home (redirects to dashboard if logged in) |
| GET    | `/register`        | Registration form               |
| POST   | `/register`        | Create account + log in         |
| GET    | `/login`           | Login form                      |
| POST   | `/login`           | Authenticate                    |
| GET    | `/logout`          | End session                     |
| GET    | `/dashboard`       | List the user's sessions + sets |
| POST   | `/workouts/create` | Create a workout session        |
| POST   | `/sets/create`     | Add a set to a session          |

## Code Quality

Static analysis runs at the strictest level:

```bash
composer stan
```

View templates are excluded from analysis (inline-PHP templating fights max-level
rules); everything else is clean at level max. Every file declares
`strict_types=1` (except the two HTML-first view partials, where a `declare` cannot
legally precede output).

## Known Limitations

This is a learning project, not production code. Notably:

- **No CSRF protection** on the POST forms.
- Routing is exact-match only (parameters come via query string).
- Minimal validation/flash-messaging.
```
