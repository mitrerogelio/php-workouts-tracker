<?php

declare(strict_types=1);

use App\Config\LoadEnv;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\WorkoutController;
use App\Http\Router;
use App\Http\View;
use App\Repositories\CredRepository;
use App\Repositories\ExerciseRepository;
use App\Repositories\ExerciseSetRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\WorkoutRepository;
use App\Services\AuthService;
use App\Services\DatabaseFactory;

require __DIR__ . '/../vendor/autoload.php';

// Let the PHP built-in server serve existing static files (CSS, images) directly.
if (PHP_SAPI === 'cli-server') {
    $requested = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    if (is_string($requested) && $requested !== '/' && is_file(__DIR__ . $requested)) {
        return false;
    }
}

(new LoadEnv(__DIR__ . '/../.env'))->load();
session_start();

// --- Composition root: build the dependency graph once ------------------------
$db = (new DatabaseFactory())->createDatabase();

$profiles = new ProfileRepository($db);
$creds = new CredRepository($db);
$workouts = new WorkoutRepository($db);
$exercises = new ExerciseRepository($db);
$sets = new ExerciseSetRepository($db);

$auth = new AuthService($profiles, $creds);

$authController = new AuthController($auth);
$dashboardController = new DashboardController($workouts, $sets, $exercises);
$workoutController = new WorkoutController($workouts, $sets);

// --- Routes -------------------------------------------------------------------
$router = new Router();
$router->add('GET', '/', static fn () => $dashboardController->home());
$router->add('GET', '/register', static fn () => $authController->showRegister());
$router->add('POST', '/register', static fn () => $authController->register());
$router->add('GET', '/login', static fn () => $authController->showLogin());
$router->add('POST', '/login', static fn () => $authController->login());
$router->add('GET', '/logout', static fn () => $authController->logout());
$router->add('GET', '/dashboard', static fn () => $dashboardController->index());
$router->add('POST', '/workouts/create', static fn () => $workoutController->create());
$router->add('POST', '/sets/create', static fn () => $workoutController->addSet());

// --- Dispatch -----------------------------------------------------------------
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

try {
    $router->dispatch(
        is_string($method) ? $method : 'GET',
        is_string($path) ? $path : '/'
    );
} catch (Throwable $e) {
    http_response_code(500);
    View::render('error', [
        'title' => 'Server error',
        'message' => $e->getMessage(),
    ]);
}
