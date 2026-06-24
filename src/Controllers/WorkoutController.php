<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Redirect;
use App\Http\Request;
use App\Http\Session;
use App\Models\ExerciseSet;
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
        $userId = $this->requireUserId();

        $notes = Request::postString('notes');
        $this->workouts->create($userId, $notes === '' ? null : $notes);

        Redirect::to('/dashboard');
    }

    public function update(): void
    {
        $userId = $this->requireUserId();
        $sessionId = Request::postInt('session_id');

        if ($this->userOwnsSession($sessionId, $userId)) {
            $notes = Request::postString('notes');
            $this->workouts->update($sessionId, $notes === '' ? null : $notes);
        }

        Redirect::to('/dashboard');
    }

    public function delete(): void
    {
        $userId = $this->requireUserId();
        $sessionId = Request::postInt('session_id');

        if ($this->userOwnsSession($sessionId, $userId)) {
            $this->workouts->delete($sessionId);
        }

        Redirect::to('/dashboard');
    }

    public function addSet(): void
    {
        $userId = $this->requireUserId();
        $sessionId = Request::postInt('session_id');

        if ($this->userOwnsSession($sessionId, $userId)) {
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

    public function updateSet(): void
    {
        $userId = $this->requireUserId();
        $set = $this->ownedSet(Request::postInt('set_id'), $userId);

        if ($set !== null) {
            $this->sets->update(
                $set->id,
                Request::postInt('exercise_id'),
                Request::postNullableInt('reps'),
                Request::postNullableFloat('weight'),
                Request::postNullableInt('duration'),
                Request::postInt('set_number', 1),
            );
        }

        Redirect::to('/dashboard');
    }

    public function deleteSet(): void
    {
        $userId = $this->requireUserId();
        $set = $this->ownedSet(Request::postInt('set_id'), $userId);

        if ($set !== null) {
            $this->sets->delete($set->id);
        }

        Redirect::to('/dashboard');
    }

    private function requireUserId(): int
    {
        $userId = Session::userId();
        if ($userId === null) {
            Redirect::to('/login');
        }
        return $userId;
    }

    private function userOwnsSession(int $sessionId, int $userId): bool
    {
        $session = $this->workouts->findById($sessionId);
        return $session !== null && $session->userId === $userId;
    }

    /**
     * Return the set only if it belongs to a session owned by the user.
     */
    private function ownedSet(int $setId, int $userId): ?ExerciseSet
    {
        $set = $this->sets->findById($setId);
        if ($set === null) {
            return null;
        }
        return $this->userOwnsSession($set->sessionId, $userId) ? $set : null;
    }
}
