<?php

declare(strict_types=1);

use App\Models\Exercise;
use App\Models\ExerciseSet;
use App\Models\WorkoutSession;

/** @var string|null $username */
/** @var array<int, array{session: WorkoutSession, sets: array<int, ExerciseSet>}> $sessions */
/** @var array<int, Exercise> $exercises */

$username = $username ?? null;
$sessions = $sessions ?? [];
$exercises = $exercises ?? [];

// Lookup table so sets can show the exercise name from its id.
$exerciseNames = [];
foreach ($exercises as $exercise) {
    $exerciseNames[$exercise->id] = $exercise->name;
}

$esc = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

include __DIR__ . '/_header.php';
?>

<h1>Welcome back<?= $username !== null ? ', ' . $esc($username) : '' ?></h1>

<section class="card">
    <h2>Start a new session</h2>
    <form method="post" action="/workouts/create">
        <label>Notes (optional)
            <input type="text" name="notes" placeholder="e.g. Leg day">
        </label>
        <button type="submit" class="btn">Create session</button>
    </form>
</section>

<h2>Your sessions</h2>

<?php if ($sessions === []): ?>
    <p>No sessions yet — create your first one above.</p>
<?php endif; ?>

<?php foreach ($sessions as $entry): ?>
    <?php $session = $entry['session']; ?>
    <?php $sets = $entry['sets']; ?>
    <section class="card">
        <h3>Session #<?= $session->id ?> &mdash; <?= $esc($session->createdAt->format('M j, Y g:i a')) ?></h3>
        <?php if ($session->notes !== null): ?>
            <p><em><?= $esc($session->notes) ?></em></p>
        <?php endif; ?>

        <?php if ($sets === []): ?>
            <p>No sets recorded yet.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr><th>#</th><th>Exercise</th><th>Reps</th><th>Weight</th><th>Duration (s)</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($sets as $set): ?>
                        <tr>
                            <td><?= $set->setNumber ?></td>
                            <td><?= $esc($exerciseNames[$set->exerciseId] ?? ('#' . $set->exerciseId)) ?></td>
                            <td><?= $set->reps ?? '&mdash;' ?></td>
                            <td><?= $set->weight !== null ? $esc(number_format($set->weight, 2)) : '&mdash;' ?></td>
                            <td><?= $set->duration ?? '&mdash;' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <form method="post" action="/sets/create" class="set-form">
            <input type="hidden" name="session_id" value="<?= $session->id ?>">
            <select name="exercise_id" required>
                <option value="">Exercise…</option>
                <?php foreach ($exercises as $exercise): ?>
                    <option value="<?= $exercise->id ?>"><?= $esc($exercise->name) ?></option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="set_number" value="<?= count($sets) + 1 ?>" min="1" title="Set number">
            <input type="number" name="reps" placeholder="Reps" min="0">
            <input type="number" name="weight" placeholder="Weight" step="0.01" min="0">
            <input type="number" name="duration" placeholder="Seconds" min="0">
            <button type="submit" class="btn">Add set</button>
        </form>
    </section>
<?php endforeach; ?>

<?php include __DIR__ . '/_footer.php'; ?>
