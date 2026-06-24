<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Redirect;
use App\Http\Request;
use App\Http\Session;
use App\Repositories\IExerciseSetRepository;
use App\Repositories\IWorkoutRepository;

class WorkoutController
{
    public function __construct(
        private IWorkoutRepository $workouts,
        private IExerciseSetRepository $sets,
    ) {}

    public function create(): void
    {
        $userId = Session::userId();
        if ($userId === null) {
            Redirect::to('/login');
        }

        $notes = Request::postString('notes');
        $this->workouts->create($userId, $notes === '' ? null : $notes);

        Redirect::to('/dashboard');
    }

    public function addSet(): void
    {
        $userId = Session::userId();
        if ($userId === null) {
            Redirect::to('/login');
        }

        $sessionId = Request::postInt('session_id');
        $session = $this->workouts->findById($sessionId);

        // Only allow adding sets to a session the current user owns.
        if ($session !== null && $session->userId === $userId) {
            $this->sets->create(
                $sessionId,
                Request::postInt('exercise_id'),
                Request::postNullableInt('reps'),
                Request::postNullableFloat('weight'),
                Request::postNullableInt('duration'),
                Request::postInt('set_number', 1),
            );
        }

        Redirect::to('/dashboard');
    }
}
