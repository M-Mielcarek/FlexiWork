<?php

session_start();

require_once "../path.php";
require_once ROOT_PATH . "app/config/database.php";

if (!isset($_SESSION['user_id'])) {

    header("Location: " . BASE_URL . "views/auth/login.php");
    exit;
}

$userId = $_SESSION['user_id'];

$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
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

        <div class="profile-box">

            <h1>FLEXI WORK</h1>

            <div class="user-data">

                <h2>
                    <?= $user['name']; ?>
                    <?= $user['surname']; ?>
                </h2>

                <h3>
                    <?php
                    if ($user['role'] === 'manager') {
                          echo "Manager";
                    } else {
                        echo "Pracownik";
                    }
                    ?>
                </h3>

                <p>
                    <strong>Mail:</strong>
                    <?= $user['email']; ?>
                </p>

                <p>
                    <strong>Telefon:</strong>
                    <?= $user['phone']; ?>
                </p>

            </div>

        </div>

    </section>


    <section class="right-panel">
        <?php require_once ROOT_PATH . "public/headers/header.php"; ?>
    </section>

</div>

</body>
</html>



