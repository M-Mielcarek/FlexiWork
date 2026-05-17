<?php

session_start();

require_once "../path.php";
require_once ROOT_PATH . "app/config/database.php";
require_once ROOT_PATH . "app/middleware/auth.php";

$userId = $_SESSION['user_id'];

$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($currentPassword !== $user['password']) {
        $error = "Aktualne hasło jest nieprawidłowe.";
    } elseif ($newPassword !== $confirmPassword) {
        $error = "Nowe hasła nie są takie same.";
    } else {
        $sqlUpdate = "
        UPDATE users
        SET password = ?
        WHERE id = ?
        ";

        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param(
            "si",
            $newPassword,
            $userId
        );
        $stmtUpdate->execute();
        $success = "Hasło zostało zmienione.";
    }
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

<div class="change-password-box">
    <h3>Zmień hasło</h3>
    <?php if(isset($success)): ?>

        <div class="success-message">
            <?= $success; ?>
        </div>
    <?php endif; ?>

    <?php if(isset($error)): ?>
        <div class="error-message">
            <?= $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="password-form">

        <input type="password"
               name="current_password"
               placeholder="Aktualne hasło"
               required>

        <input type="password"
               name="new_password"
               placeholder="Nowe hasło"
               required>

        <input type="password"
               name="confirm_password"
               placeholder="Powtórz nowe hasło"
               required>

        <button type="submit">
            Zmień hasło
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



