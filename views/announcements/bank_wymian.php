<?php

session_start();

require_once "../../path.php";
require_once ROOT_PATH . "app/config/database.php";
require_once ROOT_PATH . "app/middleware/auth.php";

$userId = $_SESSION['user_id'];

if (isset($_GET['take'])) {
    $scheduleId = $_GET['take'];
    $sqlUpdate = "
    UPDATE schedules
    SET
        user_id = ?,
        status = 'active'
    WHERE id = ?
    ";

    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param(
        "ii",
        $userId,
        $scheduleId
    );

    $stmtUpdate->execute();

    $sqlDelete = "
    DELETE FROM shift_exchange
    WHERE schedule_id = ?
    ";

    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bind_param(
        "i",
        $scheduleId
    );

    $stmtDelete->execute();
    header("Location: bank_wymian.php");
    exit;
}

$sql = "
SELECT
    shift_exchange.id, schedules.id AS schedule_id, schedules.work_date, schedules.start_time, schedules.end_time,
    users.name, users.surname
FROM shift_exchange
JOIN schedules
ON schedules.id = shift_exchange.schedule_id
JOIN users
ON users.id = shift_exchange.user_id
ORDER BY schedules.work_date ASC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
<?php require_once ROOT_PATH . "public/includes/head.php"; ?>
<title>Bank wymian</title>
</head>

<body>
<div class="container">
<section class="left-panel">
    <div class="page-content">
    <h1>FLEXI WORK</h1>
    <h2>Bank wymian</h2>
    <div class="exchange-list">
        <?php while($shift = $result->fetch_assoc()): ?>

        <div class="exchange-box">
        <h3>
            <?= $shift['name']; ?>
            <?= $shift['surname']; ?>
        </h3>

        <p>
            <?= date("d.m.Y", strtotime($shift['work_date'])); ?>
        </p>

        <p>
            <?= substr($shift['start_time'], 0, 5); ?>
            -
            <?= substr($shift['end_time'], 0, 5); ?>
        </p>

        <a class="take-shift-btn" href="?take=<?= $shift['schedule_id']; ?>"
           onclick="return confirm('Przejąć zmianę?')">
            Przejmij zmianę
        </a>
    </div>
<?php endwhile; ?>
</div>
</div>
</section>

    <section class="right-panel">
        <?php require_once ROOT_PATH . "public/headers/header.php"; ?>
    </section>
</div>
</body>
</html>