<?php

declare(strict_types=1);

/** @var string|null $error */
$error = $error ?? null;
include __DIR__ . '/_header.php';
?>

<h1>Log In</h1>

<?php if ($error !== null): ?>
    <p class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>

<form method="post" action="/login" class="card">
    <label>Email
        <input type="email" name="email" required>
    </label>
    <label>Password
        <input type="password" name="password" required>
    </label>
    <button type="submit" class="btn">Log in</button>
</form>

<p>Need an account? <a href="/register">Register</a></p>

<?php include __DIR__ . '/_footer.php'; ?>
