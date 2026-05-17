<?php

session_start();

require_once "../path.php";
require_once ROOT_PATH . "app/config/database.php";
require_once ROOT_PATH . "app/middleware/auth.php";
require_once ROOT_PATH . "app/helpers/months.php";
require_once ROOT_PATH . "app/helpers/current_user.php";

$selectedMonth =
    isset($_GET['month']) ? (int)$_GET['month'] : (int)date("m");

$selectedYear =
    isset($_GET['year']) ? (int)$_GET['year'] : (int)date("Y");

$selectedUserId = $userId;

if (
    $currentUser['role'] === 'manager' && isset($_GET['employee_id'])
) {
    $selectedUserId = (int)$_GET['employee_id'];
}

$sqlEmployee = "
SELECT *
FROM users
WHERE id = ?
";

$stmtEmployee = $conn->prepare($sqlEmployee);
$stmtEmployee->bind_param(
    "i",
    $selectedUserId
);

$stmtEmployee->execute();
$resultEmployee = $stmtEmployee->get_result();

$employee = $resultEmployee->fetch_assoc();

$employeesResult = null;

if ($currentUser['role'] === 'manager') {
    $sqlEmployees = "
    SELECT id, name, surname
    FROM users
    ORDER BY surname ASC
    ";
    $employeesResult =
        $conn->query($sqlEmployees);
}

$sqlSchedules = "
SELECT *
FROM schedules
WHERE user_id = ?
AND MONTH(work_date) = ?
AND YEAR(work_date) = ?
ORDER BY work_date ASC
";

$stmtSchedules = $conn->prepare($sqlSchedules);
$stmtSchedules->bind_param(
    "iii",
    $selectedUserId,
    $selectedMonth,
    $selectedYear
);

$stmtSchedules->execute();
$resultSchedules =
    $stmtSchedules->get_result();

$totalHours = 0;
$totalSalary = 0;
$reportData = [];

while($schedule = $resultSchedules->fetch_assoc()) {
    $startTimestamp =
        strtotime($schedule['start_time']);
    $endTimestamp =
        strtotime($schedule['end_time']);
    $workedHours =
        ($endTimestamp - $startTimestamp)
        / 3600;

    $nightHours = 0;
    $currentHour = $startTimestamp;
    while ($currentHour < $endTimestamp) {
        $hour = date(
            "G", $currentHour
        );

        if ($hour >= 22 || $hour < 6) {
            $nightHours++;
        }
        $currentHour += 3600;
    }
    $dayHours = $workedHours - $nightHours;

    $salary = ($dayHours * $employee['day_rate']) + ($nightHours * $employee['night_rate']);

    $totalHours += $workedHours;
    $totalSalary += $salary;
    $reportData[] = [
        'date' => $schedule['work_date'],
        'start' => substr( $schedule['start_time'], 0, 5),
        'end' => substr( $schedule['end_time'], 0, 5),
        'hours' => $workedHours,
        'salary' => $salary
    ];
};
?>

<!DOCTYPE html>
<html lang="pl">
<head>
<?php require_once ROOT_PATH . "public/includes/head.php"; ?>
<title>Raport czasu pracy</title>
</head>

<body>
<div class="container">
<section class="left-panel">
<div class="page-content">
<h1>FLEXI WORK</h1>

<h2>Raport czasu pracy</h2>
<form method="GET" class="manager-form">

<label>Miesiąc</label>
<select name="month">
<?php for($m = 1; $m <= 12; $m++): ?>
<option value="<?= $m; ?>"

<?= $selectedMonth == $m ? 'selected' : ''; ?>>
<?= $months[$m]; ?>
</option>
<?php endfor; ?>
</select>

<label>Rok</label>
<select name="year">
<?php for($y = 2024; $y <= 2030; $y++): ?>

<option value="<?= $y; ?>"
<?= $selectedYear == $y ? 'selected' : ''; ?>>
<?= $y; ?>
</option>
<?php endfor; ?>
</select>

<?php if($currentUser['role'] === 'manager'): ?>
<label>Pracownik</label>
<select name="employee_id">

<?php while($emp = $employeesResult->fetch_assoc()): ?>

<option value="<?= $emp['id']; ?>"

<?= $selectedUserId == $emp['id']
? 'selected'
: ''; ?>>
<?= $emp['name']; ?>
<?= " "; ?>
<?= $emp['surname']; ?>

</option>
<?php endwhile; ?>
</select>
<?php endif; ?>

<button type="submit">
Generuj raport
</button>
</form>

<table class="employees-table">
<tr>
<th>Data</th>
<th>Godziny</th>
<th>Ilość godzin</th>
<th>Wynagrodzenie</th>
</tr>

<?php foreach($reportData as $report): ?>
<tr>
<td>
<?= date("d.m.Y", strtotime($report['date'])); ?>
</td>

<td>
<?= $report['start']; ?>
-
<?= $report['end']; ?>
</td>

<td>
<?= number_format($report['hours'], 1); ?> h
</td>

<td>
<?= number_format($report['salary'], 2); ?> zł
</td>
</tr>
<?php endforeach; ?>

<tr class="summary-row">
<td colspan="2">
<strong>SUMA</strong>
</td>

<td>
<strong>
<?= number_format($totalHours, 1); ?> h
</strong>
</td>

<td>
<strong>
<?= number_format($totalSalary, 2); ?> zł
</strong>
</td>
</tr>
</table>
</div>
</section>

<section class="right-panel">
<?php require_once ROOT_PATH . "public/headers/header.php"; ?>
</section>

</div>

</body>
</html>