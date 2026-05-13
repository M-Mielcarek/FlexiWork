<?php
session_start();

require_once "../../path.php";
?>

<!DOCTYPE html>
<html lang="pl">
<head>

<meta charset="UTF-8">

<title>Wymagania kadrowe</title>

<link rel="stylesheet"
      href="<?= BASE_URL ?>public/assets/style.css">

</head>

<body>

<div class="container">

<section class="left-panel">

<div class="page-content">

<h1>FLEXI WORK</h1>

<h2>Wymagania kadrowe</h2>

<form class="manager-form">

    <input type="date"
           name="date">

    <input type="text"
           name="shift_hours"
           placeholder="Godziny zmiany">

    <input type="number"
           name="workers_needed"
           placeholder="Liczba pracowników">

    <button type="submit">
        Zapisz wymagania
    </button>

</form>

</div>

</section>

    <section class="right-panel">
        <?php require_once ROOT_PATH . "public/headers/header.php"; ?>
    </section>
</div>

</body>
</html>