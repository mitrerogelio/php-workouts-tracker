<?php

declare(strict_types=1);

/** @var string|null $error */
$error = $error ?? null;
include __DIR__ . '/_header.php';
?>

<h1>Create Account</h1>

<?php if ($error !== null): ?>
    <p class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>

<form method="post" action="/register" class="card">
    <label>First name
        <input type="text" name="first_name" required>
    </label>
    <label>Last name
        <input type="text" name="last_name" required>
    </label>
    <label>Username
        <input type="text" name="username" required>
    </label>
    <label>Email
        <input type="email" name="email" required>
    </label>
    <label>Password
        <input type="password" name="password" required>
    </label>
    <label>Gender
        <select name="gender">
            <option value="">Prefer not to say</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
        </select>
    </label>
    <button type="submit" class="btn">Register</button>
</form>

<p>Already have an account? <a href="/login">Log in</a></p>

<?php include __DIR__ . '/_footer.php'; ?>
