<?php

session_start();

require_once "../app/config/database.php";
require_once "../path.php";

/*
|--------------------------------------------------------------------------
| SPRAWDZENIE LOGOWANIA
|--------------------------------------------------------------------------
*/

$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {

    header("Location: " . BASE_URL . "views/auth/login_form.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| SPRAWDZENIE ROLI
|--------------------------------------------------------------------------
*/

$sql = "SELECT role FROM users WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $userId);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user['role'] !== 'manager') {

    die("Brak dostępu");
}

$sqlUsers = "
SELECT
    id,
    name,
    surname,
    email,
    phone,
    role
FROM users
ORDER BY surname ASC
";

$usersResult = $conn->query($sqlUsers);

?>

<!DOCTYPE html>
<html lang="pl">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta http-equiv="X-UA-Compatible"
          content="ie=edge">

    <script src="https://kit.fontawesome.com/f45c1e3753.js"
            crossorigin="anonymous"></script>

    <link rel="stylesheet"
          href="<?= BASE_URL ?>public/assets/style.css">

    <link rel="preconnect"
          href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@200..900&display=swap"
          rel="stylesheet">

    <title>Lista pracowników</title>

</head>

<body>

<div class="container">

    <section class="left-panel">

        <div class="employees-box">

            <h1>FLEXI WORK</h1>

            <h2>Lista pracowników</h2>

            <table class="employees-table">

                <tr>
                    <th>Imię i nazwisko</th>
                    <th>Email</th>
                    <th>Telefon</th>
                    <th>Rola</th>
                </tr>

                <?php while($employee = $usersResult->fetch_assoc()): ?>

                    <tr>

                        <td>
                            <?= $employee['name']; ?>
                            <?= $employee['surname']; ?>
                        </td>

                        <td>
                            <?= $employee['email']; ?>
                        </td>

                        <td>
                            <?= $employee['phone']; ?>
                        </td>

                        <td>

                            <?php
                                if ($employee['role'] === 'manager') {
                                    echo "Manager";
                                } else {
                                    echo "Pracownik";
                                }
                            ?>

                        </td>

                    </tr>

                <?php endwhile; ?>

            </table>

        </div>

    </section>

    <section class="right-panel">
        <?php require_once ROOT_PATH . "public/headers/header.php"; ?>
    </section>

</div>

</body>
</html>