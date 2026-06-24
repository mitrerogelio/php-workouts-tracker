<?php

declare(strict_types=1);

/** @var string|null $title */
/** @var string|null $message */
$title = $title ?? 'Something went wrong';
$message = $message ?? '';
include __DIR__ . '/_header.php';
?>

<h1><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1>
<p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
<p><a href="/">Back to home</a></p>

<?php include __DIR__ . '/_footer.php'; ?>
