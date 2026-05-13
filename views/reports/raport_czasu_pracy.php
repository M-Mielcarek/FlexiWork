<?php
session_start();

require_once "../../path.php";
require_once ROOT_PATH . "app/config/database.php";

$sql = "
SELECT
    users.name,
    users.surname,
    schedules.date,
    schedules.hours
FROM schedules

JOIN users
ON users.id = schedules.user_id
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pl">
<head>

<meta charset="UTF-8">

<title>Raport czasu pracy</title>

<link rel="stylesheet"
      href="<?= BASE_URL ?>public/assets/style.css">

</head>

<body>

    <div class="container">

    <section class="left-panel">

    <div class="page-content">

    <h1>FLEXI WORK</h1>

    <h2>Raport czasu pracy</h2>

    <table class="report-table">

        <tr>
            <th>Pracownik</th>
            <th>Data</th>
            <th>Godziny</th>
        </tr>

    <?php while($row = $result->fetch_assoc()): ?>

    <tr>

        <td>
            <?= $row['name']; ?>
            <?= $row['surname']; ?>
        </td>

        <td>
            <?= $row['date']; ?>
        </td>

        <td>
            <?= $row['hours']; ?>
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