<?php

session_start();

require_once "../../path.php";
require_once ROOT_PATH . "app/config/database.php";
require_once ROOT_PATH . "app/controllers/ScheduleController.php";
require_once ROOT_PATH . "app/helpers/months.php";

$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {

    header(
        "Location: " .BASE_URL ."views/auth/login.php"
    );

    exit;
}


if (isset($_GET['exchange'])) {

    $scheduleId = $_GET['exchange'];

    $sqlCheck = "
    SELECT *
    FROM shift_exchange
    WHERE schedule_id = ?
    ";

    $stmtCheck = $conn->prepare($sqlCheck);

    $stmtCheck->bind_param(
        "i",
        $scheduleId
    );

    $stmtCheck->execute();

    $checkResult = $stmtCheck->get_result();

    if ($checkResult->num_rows === 0) {

        $sqlUpdate = "
        UPDATE schedules

        SET status = 'exchange'

        WHERE id = ?
        ";

        $stmtUpdate = $conn->prepare($sqlUpdate);

        $stmtUpdate->bind_param(
            "i",
            $scheduleId
        );

        $stmtUpdate->execute();


        $sqlInsert = "
        INSERT INTO shift_exchange (

            schedule_id,
            user_id

        )

        VALUES (?, ?)
        ";

        $stmtInsert = $conn->prepare($sqlInsert);

        $stmtInsert->bind_param(
            "ii",
            $scheduleId,
            $userId
        );

        $stmtInsert->execute();
    }

    header("Location: grafik_uzytkownika.php");

    exit;
};

$currentMonth =
    isset($_GET['month'])
    ? (int)$_GET['month']
    : (int)date("m");

$currentYear =
    isset($_GET['year'])
    ? (int)$_GET['year']
    : date("Y");

$prevMonth = $currentMonth - 1;
$nextMonth = $currentMonth + 1;

$prevYear = $currentYear;
$nextYear = $currentYear;

if ($prevMonth < 1) {

    $prevMonth = 12;
    $prevYear--;
}

if ($nextMonth > 12) {

    $nextMonth = 1;
    $nextYear++;
}

$schedules = ScheduleController::userScheduleByMonth(
    $conn,
    $userId,
    $currentMonth,
    $currentYear
);

$daysInMonth = cal_days_in_month(
    CAL_GREGORIAN,
    $currentMonth,
    $currentYear
);


$shiftDays = [];

foreach ($schedules as $schedule) {

    $day = date(
        "j",
        strtotime($schedule['work_date'])
    );

    $shiftDays[$day] = $schedule;
}

?>

<!DOCTYPE html>
<html lang="pl">

<head>
<?php require_once ROOT_PATH . "public/includes/head.php"; ?>
<title>Grafik</title>
</head>

<body>
<div class="container">
<section class="left-panel">
<div class="page-content">

<h1>FLEXI WORK</h1>
<h2>Twój grafik</h2>

<div class="calendar">
<div class="calendar-top">
<a class="calendar-nav"
href="?month=<?= $prevMonth; ?>&year=<?= $prevYear; ?>">
←
</a>

<div class="calendar-header">
<?= $months[$currentMonth]; ?>
<?= $currentYear; ?>
</div>

<a class="calendar-nav"
href="?month=<?= $nextMonth; ?>&year=<?= $nextYear; ?>">
→
</a>
</div>

<div class="calendar-days">
<div>Pn</div>
<div>Wt</div>
<div>Śr</div>
<div>Cz</div>
<div>Pt</div>
<div>Sb</div>
<div>Nd</div>
</div>

<div class="calendar-grid">
<?php for($day = 1; $day <= $daysInMonth; $day++): ?>
<div class="calendar-cell">
<div class="day-number">
<?= $day; ?>
</div>

<?php if(isset($shiftDays[$day])): ?>
<?php

$shift = $shiftDays[$day];
$start =
    substr($shift['start_time'], 0, 5);
$end =
    substr($shift['end_time'], 0, 5);

$startTimestamp =
    strtotime($shift['start_time']);

$endTimestamp =
    strtotime($shift['end_time']);

$totalHours =
    ($endTimestamp - $startTimestamp)
    / 3600;

$dayRate =
    $shift['day_rate'];

$nightRate =
    $shift['night_rate'];
$nightHours = 0;
$currentHour = $startTimestamp;

while ($currentHour < $endTimestamp) {
    $hour = date(
        "G",
        $currentHour
    );

    if ($hour >= 22 || $hour < 6) {
        $nightHours++;
    }
    $currentHour += 3600;
}

$dayHours =
    $totalHours - $nightHours;
$salary =
    ($dayHours * $dayRate)+($nightHours * $nightRate);
?>

<div class="shift-box
<?= $shift['status'] === 'exchange'
    ? 'exchange-shift'
    : ''; ?>">

<?= $start; ?>
-
<?= $end; ?>

<div class="shift-details">
<p>
<strong>Dzienne:</strong>
<?= $dayHours; ?>h
</p>
<p>

<strong>Nocne:</strong>
<?= $nightHours; ?>h
</p>
<p>

<strong>Stawka dzienna:</strong>
<?= $dayRate; ?> zł
</p>

<p>
<strong>Stawka nocna:</strong>
<?= $nightRate; ?> zł
</p>

<p>
<strong>Wynagrodzenie:</strong>
<?= number_format(
    $salary,
    2
); ?>
zł
</p>

<?php if(
    $shift['status'] !== 'exchange'
): ?>

<a class="exchange-btn"
href="?exchange=<?= $shift['id']; ?>" onclick="return confirm( 'Oddać zmianę do banku wymian?')">
Oddaj zmianę
</a>

<?php else: ?>
<div class="exchange-info">
Zmiana w banku wymian
</div>
<?php endif; ?>
</div>
</div>
<?php endif; ?>
</div>
<?php endfor; ?>
</div>
</div>
</div>
</section>

<section class="right-panel">
<?php require_once ROOT_PATH . "public/headers/header.php";?>
</section>

</div>

</body>
</html>