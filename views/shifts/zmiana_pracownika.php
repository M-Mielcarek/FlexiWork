<?php
session_start();

require_once "../../path.php";
require_once ROOT_PATH . "app/config/database.php";
require_once ROOT_PATH . "app/controllers/ShiftController.php";

$shift = ShiftController::show($_GET['id']);
?>

<!DOCTYPE html>
<html lang="pl">
<head>

<meta charset="UTF-8">

<title>Zmiana</title>

<link rel="stylesheet" href="<?= BASE_URL ?>public/assets/pages.css">

</head>
<body>

<div class="container">

<section class="left-panel">

<div class="page-content">

<h1>FLEXI WORK</h1>

<h2>Dane o zmianie</h2>

<div class="shift-form">

<input type="text"
       value="<?= $shift['date']; ?>">

<input type="text"
       value="<?= $shift['hours']; ?>">

<input type="text"
       value="<?= $shift['salary']; ?>">
       <div class="button-row">

<button>
    Zaakceptuj
</button>

<button>
    Oddaj
</button>

</div>

</div>

</div>

</section>

<?php require_once ROOT_PATH . "public/headers/header.php"; ?>

</div>

</body>
</html>
