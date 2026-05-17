<?php

session_start();

require_once "../../path.php";
require_once ROOT_PATH . "app/config/database.php";
require_once ROOT_PATH . "app/middleware/auth.php";
require_once ROOT_PATH . "app/middleware/manager.php";
require_once ROOT_PATH . "app/helpers/months.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $workDate = $_POST['work_date'];
    $shiftStart = $_POST['shift_start'];
    $shiftEnd = $_POST['shift_end'];
    $requiredWorkers = $_POST['required_workers'];

    if ($shiftStart >= $shiftEnd) {
        $error = "Godzina zakończenia musi być późniejsza niż rozpoczęcia.";
    } else {

        $sqlCheck = "
        SELECT *
        FROM staffing_requirements
        WHERE work_date = ?
        AND (
            (? < shift_end)
            AND
            (? > shift_start)
        )
        ";

        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bind_param(
            "sss",
            $workDate,
            $shiftStart,
            $shiftEnd
        );

        $stmtCheck->execute();
        $checkResult = $stmtCheck->get_result();

        if ($checkResult->num_rows > 0) {
            $error = "Zmiana nachodzi na istniejącą zmianę.";
        } else {

            $sqlInsert = "
            INSERT INTO staffing_requirements (
            work_date, shift_start, shift_end, required_workers
            )
            VALUES (?, ?, ?, ?)
            ";

            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->bind_param(
                "sssi",
                $workDate,
                $shiftStart,
                $shiftEnd,
                $requiredWorkers
            );

            $stmtInsert->execute();
            $success = "Zmiana została dodana.";
        }
    }
}

$sqlRequirements = "
SELECT *
FROM staffing_requirements
ORDER BY work_date ASC
";

$resultRequirements = $conn->query($sqlRequirements);

$currentMonth = isset($_GET['month'])
    ? (int)$_GET['month']
    : (int)date("m");

$currentYear = isset($_GET['year'])
    ? (int)$_GET['year']
    : (int)date("Y");
;


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

$daysInMonth = cal_days_in_month(
    CAL_GREGORIAN,
    $currentMonth,
    $currentYear
);

$monthName = $months[$currentMonth];
$calendarShifts = [];

while($requirement = $resultRequirements->fetch_assoc()) {
    $shiftMonth = date(
        "n", strtotime($requirement['work_date'])
    );

    $shiftYear = date(
        "Y", strtotime($requirement['work_date'])
    );

    if (
        $shiftMonth == $currentMonth && $shiftYear == $currentYear
    ) {

        $day = date(
            "j", strtotime($requirement['work_date'])
        );
        $calendarShifts[$day][] = $requirement;
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
<?php require_once ROOT_PATH . "public/includes/head.php"; ?>
<title>Wymagania kadrowe</title>
</head>

<body>
<div class="container">
    <section class="left-panel">
        <div class="page-content">
            <h1>FLEXI WORK</h1>
            <h2>Wymagania kadrowe</h2>
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

            <form method="POST"
                  class="manager-form">
                <label>Data zmiany</label>
                <input type="date"
                       name="work_date"
                       required>

                <label>Godzina rozpoczęcia</label>
                <input type="time"
                       name="shift_start"
                       required>

                <label>Godzina zakończenia</label>
                <input type="time"
                       name="shift_end"
                       required>

                <label>Liczba wymaganych pracowników</label>
                <input type="number"
                       name="required_workers"
                       min="1"
                       required>

                <button type="submit">
                    Dodaj zmianę
                </button>
            </form>

<div class="calendar-wrapper">
<div class="calendar">
<div class="calendar-top">
    <a class="calendar-arrow"
       href="?month=<?= $prevMonth; ?>&year=<?= $prevYear; ?>">
        ←
    </a>

    <div class="calendar-title">
        Podgląd grafiku —
        <?= $monthName; ?>
        <?= $currentYear; ?>
    </div>

    <a class="calendar-arrow"
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
                <?php if(isset($calendarShifts[$day])): ?>
                    <?php foreach($calendarShifts[$day] as $shift): ?>

                        <div class="requirement-shift">
                            <div class="shift-hours">
                                <?= substr($shift['shift_start'], 0, 5); ?>
                                -
                                <?= substr($shift['shift_end'], 0, 5); ?>
                            </div>

                            <div class="workers-count">
                                <?= $shift['required_workers']; ?>
                                osób
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
        <?php require_once ROOT_PATH . "public/headers/header.php"; ?>
    </section>
</div>
</body>
</html>