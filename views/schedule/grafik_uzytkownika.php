<?php
session_start();

require_once "../../path.php";
require_once ROOT_PATH . "app/config/database.php";
require_once ROOT_PATH . "app/controllers/ScheduleController.php";

$schedules = ScheduleController::userSchedule();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <script src="https://kit.fontawesome.com/f45c1e3753.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@200..900&display=swap" rel="stylesheet">

    <title>FlexiWork</title>
</head>
<body>
    
<div class="container">

    <section class="left-panel">

        <div class="page-content">

            <h1>FLEXI WORK</h1>

            <h2>Grafik</h2>

            <div class="calendar">

            <div class="calendar-header">
                Kwiecień
            </div>

            <div class="calendar-grid">

            <?php foreach($schedules as $schedule): ?>

            <div class="shift">
                <?= $schedule['hours']; ?>
            </div>

            <?php endforeach; ?>

</div>

</div>

</div>

</section>

    <section class="right-panel">
        <?php require_once ROOT_PATH . "public/headers/header.php"; ?>
    </section>
</div>

</body>
</html>