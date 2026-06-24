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

$esc = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

include __DIR__ . '/_header.php';
?>

<h1>Welcome back<?= $username !== null ? ', ' . $esc($username) : '' ?></h1>

<section class="card">
    <h2>Start a new session</h2>
    <form method="post" action="/workouts/create">
        <label>Workout Session Name
            <input type="text" name="notes" placeholder="e.g. Leg day">
        </label>
        <button type="submit" class="btn">Create workout session</button>
    </form>
</section>

<h2>Your workout sessions</h2>

<?php if ($sessions === []): ?>
    <p class="empty-note">No sessions yet — create your first one above.</p>
<?php endif; ?>

<?php foreach ($sessions as $entry): ?>
    <?php $session = $entry['session']; ?>
    <?php $sets = $entry['sets']; ?>
    <section class="card">
        <div class="session-header">
            <div>
                <h3 class="session-title"><?= ($session->notes !== null && $session->notes !== '') ? $esc($session->notes) : 'Untitled workout' ?></h3>
                <p class="session-meta">Session #<?= $session->id ?> &mdash; <?= $esc($session->createdAt->format('M j, Y g:i a')) ?></p>
            </div>
            <form method="post" action="/workouts/delete" class="inline-form"
                  onsubmit="return confirm('Delete this workout session and all of its sets?');">
                <input type="hidden" name="session_id" value="<?= $session->id ?>">
                <button type="submit" class="btn btn-danger btn-sm">Delete session</button>
            </form>
        </div>

        <form method="post" action="/workouts/update" class="rename-form">
            <input type="hidden" name="session_id" value="<?= $session->id ?>">
            <label class="field">Workout Session Name
                <input type="text" name="notes" value="<?= $esc($session->notes ?? '') ?>" placeholder="e.g. Leg day">
            </label>
            <button type="submit" class="btn btn-secondary btn-sm">Save name</button>
        </form>

        <h4 class="sets-heading">Sets</h4>
        <?php if ($sets === []): ?>
            <p class="empty-note">No sets recorded yet.</p>
        <?php endif; ?>

        <?php foreach ($sets as $set): ?>
            <form method="post" action="/sets/update" class="set-form set-edit">
                <input type="hidden" name="set_id" value="<?= $set->id ?>">
                <label class="field">Set #
                    <input type="number" name="set_number" value="<?= $set->setNumber ?>" min="1">
                </label>
                <label class="field field-exercise">Exercise
                    <select name="exercise_id" required>
                        <?php foreach ($exercises as $exercise): ?>
                            <option value="<?= $exercise->id ?>" <?= $exercise->id === $set->exerciseId ? 'selected' : '' ?>><?= $esc($exercise->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label class="field">Reps
                    <input type="number" name="reps" value="<?= $set->reps ?? '' ?>" min="0">
                </label>
                <label class="field">Weight
                    <input type="number" name="weight" value="<?= $set->weight !== null ? $esc(number_format($set->weight, 2, '.', '')) : '' ?>" step="0.01" min="0">
                </label>
                <label class="field">Time (s)
                    <input type="number" name="duration" value="<?= $set->duration ?? '' ?>" min="0">
                </label>
                <div class="set-actions">
                    <button type="submit" class="btn btn-secondary btn-sm">Save</button>
                    <button type="submit" formaction="/sets/delete" formnovalidate class="btn btn-danger btn-sm"
                            onclick="return confirm('Delete this set?');">Delete</button>
                </div>
            </form>
        <?php endforeach; ?>

        <h4 class="add-set-heading">Add a set</h4>
        <form method="post" action="/sets/create" class="set-form">
            <input type="hidden" name="session_id" value="<?= $session->id ?>">
            <label class="field field-exercise">Exercise
                <select name="exercise_id" required>
                    <option value="">Select…</option>
                    <?php foreach ($exercises as $exercise): ?>
                        <option value="<?= $exercise->id ?>"><?= $esc($exercise->name) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label class="field">Set #
                <input type="number" name="set_number" value="<?= count($sets) + 1 ?>" min="1">
            </label>
            <label class="field">Reps (for this set)
                <input type="number" name="reps" min="0">
            </label>
            <label class="field">Weight
                <input type="number" name="weight" step="0.01" min="0">
            </label>
            <label class="field toggle">
                <input type="checkbox" class="toggle-timed"> Timed exercise
            </label>
            <label class="field field-duration" hidden>Time (seconds)
                <input type="number" name="duration" min="0">
            </label>
            <button type="submit" class="btn">Add set</button>
        </form>
    </section>
<?php endforeach; ?>

<script>
    document.querySelectorAll('.set-form').forEach(function (form) {
        var toggle = form.querySelector('.toggle-timed');
        var durationField = form.querySelector('.field-duration');
        if (!toggle || !durationField) {
            return;
        }
        toggle.addEventListener('change', function () {
            durationField.hidden = !toggle.checked;
        });
    });
</script>

<?php include __DIR__ . '/_footer.php'; ?>
