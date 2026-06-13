<?php

// TODO: Load environment variables from .env
// (new \App\Config\LoadEnv(__DIR__ . '/../.env'))->load();

// TODO: Create database instance using DatabaseFactory
// $db = (new \App\Services\DatabaseFactory())->createDatabase();

// TODO: Test database connection
// var_dump($db);

?>

<?php include __DIR__ . '/../src/Views/_header.php'; ?>

<h1>Welcome to Your Workout Application</h1>
<p>This application will help you stay on track with your daily workout routines. Soon, you'll be able to create an account, customize your plan, and receive email notifications.</p>

<section class="card">
    <h2>Today's Reading</h2>
    <p>Loading your plan...</p>
</section>

<?php include __DIR__ . '/../src/Views/_footer.php'; ?>
