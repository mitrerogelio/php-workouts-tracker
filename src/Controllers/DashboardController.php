<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Redirect;
use App\Http\Session;
use App\Http\View;
use App\Repositories\IExerciseRepository;
use App\Repositories\IExerciseSetRepository;
use App\Repositories\IWorkoutRepository;

class DashboardController
{
    public function __construct(
        private IWorkoutRepository $workouts,
        private IExerciseSetRepository $sets,
        private IExerciseRepository $exercises,
    ) {}

    public function home(): void
    {
        if (Session::userId() !== null) {
            Redirect::to('/dashboard');
        }

        View::render('home');
    }

    public function index(): void
    {
        $userId = Session::userId();
        if ($userId === null) {
            Redirect::to('/login');
        }

        $sessions = [];
        foreach ($this->workouts->findByUserId($userId) as $session) {
            $sessions[] = [
                'session' => $session,
                'sets' => $this->sets->findBySessionId($session->id),
            ];
        }

        View::render('dashboard', [
            'username' => Session::username(),
            'sessions' => $sessions,
            'exercises' => $this->exercises->findAll(),
        ]);
    }
}
