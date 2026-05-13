<?php
require_once "../../path.php";
?>

<!DOCTYPE html>
<html lang="pl">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Logowanie - Flexi Work</title>

    <link rel="stylesheet"
          href="<?= BASE_URL ?>public/assets/login.css">

</head>

<body>

<div class="login-container">

    <div class="login-left">

        <div class="left-content">

            <h1>FLEXI WORK</h1>

            <p>
                System zarządzania grafikiem pracowników
            </p>

            <img src="<?= BASE_URL ?>public/assets/team.png"
                 alt="Login Image">

        </div>

    </div>


     <div class="login-right">

        <form action="<?= BASE_URL ?>views/auth/login_form.php"
              method="POST"
              class="login-form">

            <h2>Logowanie</h2>

            <?php if(isset($_SESSION['error'])): ?>

                <div class="error-message">
                    <?= $_SESSION['error']; ?>
                </div>

                <?php unset($_SESSION['error']); ?>

            <?php endif; ?>

            <input type="email"
                   name="email"
                   placeholder="Email"
                   required>

            <input type="password"
                   name="password"
                   placeholder="Hasło"
                   required>

            <button type="submit">
                Zaloguj się
            </button>

        </form>

    </div>

</div>

</body>
</html>