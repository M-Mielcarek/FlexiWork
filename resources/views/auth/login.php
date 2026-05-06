<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <script src="https://kit.fontawesome.com/f45c1e3753.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/scale.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@200..900&display=swap" rel="stylesheet">
    <title>Rejestracja</title>
</head>

<body>

    <!-- header -->
    <?php include(ROOT_PATH ."app/includes/header.php"); ?>
    <!-- header -->

    <div class="auth-content">
        <form action="login.html" method="post">
            <h2 class="form-title">Logowanie</h2>

            <!-- <?php include(ROOT_PATH . "app/helpers/formErrors.php"); ?> -->

            <div>
                <label>Nazwa użytkownika</label>
                <input type="text" name="username" value="<?php echo $username;?>" class="text-input">
            </div>
            <div>
                <label>Hasło</label>
                <input type="password" name="password" value="<?php echo $password;?>" class="text-input">
            </div>
            <div>
                <button type="submit" name="login-btn" class="btn btn-big">Zaloguj</button>
            </div>

        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>