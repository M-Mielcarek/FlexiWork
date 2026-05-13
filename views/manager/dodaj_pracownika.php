<?php
session_start();

require_once "../../path.php";
require_once ROOT_PATH . "app/controllers/UserController.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    UserController::store();
}
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

        <h2>Dodaj pracownika</h2>

        <div class="form-container">

            <form method="POST" class="custom-form">

                <input type="text"
                       name="name"
                       placeholder="Imię"
                       required>

                <input type="text"
                       name="surname"
                       placeholder="Nazwisko"
                       required>

                <input type="text"
                       name="pesel"
                       placeholder="PESEL"
                       required>

                <input type="email"
                       name="email"
                       placeholder="Email"
                       required>

                <input type="text"
                       name="phone"
                       placeholder="Telefon"
                       required>

                <button type="submit">
                    Dodaj pracownika
                </button>

            </form>

        </div>

    </div>

</section>

    <section class="right-panel">
        <?php require_once ROOT_PATH . "public/headers/header.php"; ?>
    </section>
</div>

</body>
</html>