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
<?php require_once ROOT_PATH . "public/includes/head.php"; ?>
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

                <input type="email"
                       name="email"
                       placeholder="Email"
                       required>

                <input type="text"
                       name="phone"
                       placeholder="Telefon"
                       required>

                <input type="password"
                    name="password"
                    placeholder="Hasło"
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