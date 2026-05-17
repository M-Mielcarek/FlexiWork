<?php

session_start();

require_once "../../path.php";
require_once ROOT_PATH . "app/config/database.php";
require_once ROOT_PATH . "app/middleware/auth.php";
require_once ROOT_PATH . "app/helpers/months.php";

if (!isset($_GET['id'])) {
    die("Brak ID pracownika");
}

$employeeId = $_GET['id'];
$sqlEmployee = "
SELECT *
FROM users
WHERE id = ?
";

$stmtEmployee = $conn->prepare($sqlEmployee);
$stmtEmployee->bind_param(
    "i",
    $employeeId
);

$stmtEmployee->execute();
$resultEmployee =
    $stmtEmployee->get_result();

$employee =
    $resultEmployee->fetch_assoc();

if (!$employee) {
    die("Nie znaleziono pracownika");
};

$currentMonth = isset($_GET['month'])
    ? (int)$_GET['month']
    : (int)date("m");

$currentYear = isset($_GET['year'])
    ? (int)$_GET['year']
    : (int)date("Y");

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

$sql = "
SELECT *
FROM schedules
WHERE user_id = ?
AND MONTH(work_date) = ?
AND YEAR(work_date) = ?
ORDER BY work_date ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "iii",
    $employeeId,
    $currentMonth,
    $currentYear
);

$stmt->execute();
$result = $stmt->get_result();
$schedules = [];
while($row = $result->fetch_assoc()) {
    $day = date(
        "j",
        strtotime($row['work_date'])
    );
    $schedules[$day][] = $row;
}

$daysInMonth = cal_days_in_month(
    CAL_GREGORIAN,
    $currentMonth,
    $currentYear
);
$monthName = $months[$currentMonth];
?>

<!DOCTYPE html>
<html lang="pl">
<head>
<?php require_once ROOT_PATH . "public/includes/head.php"; ?>
<title>Grafik pracownika</title>
</head>

<body>
<div class="container">
<section class="left-panel">
<div class="page-content">
<h1>FLEXI WORK</h1>

<h2> Grafik:
<?= $employee['name']; ?>
<?= $employee['surname']; ?>
</h2>

<div class="calendar-wrapper">
<div class="calendar">
<div class="calendar-top">

<a class="calendar-arrow"
href="?id=<?= $employeeId; ?>&month=<?= $prevMonth; ?>&year=<?= $prevYear; ?>">
←
</a>

<div class="calendar-title">
<?= $monthName; ?>
<?= $currentYear; ?>
</div>

<a class="calendar-arrow"
href="?id=<?= $employeeId; ?>&month=<?= $nextMonth; ?>&year=<?= $nextYear; ?>">
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

<?php if(isset($schedules[$day])): ?>
<?php foreach($schedules[$day] as $shift): ?>

<div class="shift-box">
<div>
<?= substr($shift['start_time'], 0, 5); ?>
-
<?= substr( $shift['end_time'], 0, 5); ?>
</div>
</div>
<?php endforeach; ?>
<?php endif; ?>
</div>
<?php endfor; ?>
</div>
</div>
</div>
</div>
</section>

<section class="right-panel">
<?php require_once ROOT_PATH ."public/headers/header.php";?>
</section>

</div>

</body>
</html>