<?php
session_start();
require_once "path.php";
require_once ROOT_PATH . "app/models/User.php";
?>

<!DOCTYPE html>
<html lang="pl">
<head>
<?php require_once ROOT_PATH . "public/includes/head.php"; ?>
<title>FlexiWork</title>
</head>

<body>
<div class="container">
    <section class="left-panel">
        <h1>FLEXI WORK</h1>

    <p class="welcome">
        Witaj, <?= ($_SESSION['name'] ?? '') . ' ' . ($_SESSION['surname'] ?? '') ?>
    </p>

    <div class="image-box">
        <img src="<?= BASE_URL ?>public/assets/house.jpg" alt="Team">
    </div>
    </section>

    <section class="right-panel">
        <?php require_once ROOT_PATH . "public/headers/header.php"; ?>
    </section>
</div>

</body>
</html>
