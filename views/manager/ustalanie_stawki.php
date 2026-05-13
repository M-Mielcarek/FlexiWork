<?php
session_start();

require_once "../../path.php";
require_once ROOT_PATH . "app/config/database.php";

$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pl">
<head>

    <meta charset="UTF-8">

    <title>Ustalanie stawki</title>

    <link rel="stylesheet"
          href="<?= BASE_URL ?>public/assets/style.css">

</head>

<body>

<div class="container">

<section class="left-panel">

<div class="page-content">

<h1>FLEXI WORK</h1>

<h2>Ustalanie stawki godzinowej</h2>

<form action="" method="POST" class="manager-form">

    <select name="user_id">

        <?php while($user = $result->fetch_assoc()): ?>

            <option value="<?= $user['id']; ?>">

                <?= $user['name']; ?>
                <?= $user['surname']; ?>

            </option>

        <?php endwhile; ?>

    </select>

    <input type="number"
           step="0.01"
           name="hour_rate"
           placeholder="Stawka godzinowa">

    <button type="submit">
        Zapisz stawkę
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