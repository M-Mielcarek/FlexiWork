<?php

session_start();

require_once "../../path.php";
require_once ROOT_PATH . "app/config/database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $workDate = $_POST['work_date'];
    $startTime = $_POST['start_time'];
    $endTime = $_POST['end_time'];
    $sqlRequirement = "
    SELECT required_workers
    FROM staffing_requirements
    WHERE work_date = ?
    AND shift_start = ?
    AND shift_end = ?
    LIMIT 1
    ";

    $stmtRequirement = $conn->prepare($sqlRequirement);

    $stmtRequirement->bind_param(
        "sss",
        $workDate,
        $startTime,
        $endTime
    );

    $stmtRequirement->execute();

    $requirementResult =
        $stmtRequirement->get_result();

    if ($requirementResult->num_rows > 0) {

        $requirement =
            $requirementResult->fetch_assoc();

        $requiredWorkers =
            $requirement['required_workers'];

        $sqlCount = "
        SELECT COUNT(*) AS workers_count
        FROM schedules
        WHERE work_date = ?
        AND start_time = ?
        AND end_time = ?
        ";

        $stmtCount = $conn->prepare($sqlCount);

        $stmtCount->bind_param(
            "sss",
            $workDate,
            $startTime,
            $endTime
        );

        $stmtCount->execute();

        $countResult = $stmtCount->get_result();

        $countData = $countResult->fetch_assoc();

        if ($countData['workers_count'] >= $requiredWorkers) {

            $error =
                "Osiągnięto maksymalną liczbę pracowników dla tej zmiany.";

        } else {

            $sqlInsert = "
            INSERT INTO schedules (

                user_id,
                work_date,
                start_time,
                end_time

            )

            VALUES (?, ?, ?, ?)
            ";

            $stmtInsert = $conn->prepare($sqlInsert);

            $stmtInsert->bind_param(
                "isss",
                $userId,
                $workDate,
                $startTime,
                $endTime
            );

            $stmtInsert->execute();

            $success =
                "Zmiana została dodana.";
        }

    } else {

        $error =
            "Najpierw ustaw wymagania kadrowe dla tej zmiany.";
    }
}

$sql = "
SELECT *
FROM users
WHERE role = 'user'
ORDER BY surname ASC
";

$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="pl">
<head>
<?php require_once ROOT_PATH . "public/includes/head.php"; ?>
<title>Ustalanie grafiku</title>
</head>

<body>

<div class="container">

    <section class="left-panel">

        <div class="page-content">

            <h1>FLEXI WORK</h1>

            <h2>Ustalanie grafiku</h2>

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

                <select name="user_id" required>

                    <option value="">
                        Wybierz pracownika
                    </option>

                    <?php while($user = $result->fetch_assoc()): ?>

                        <option value="<?= $user['id']; ?>">

                            <?= $user['name']; ?>
                            <?= $user['surname']; ?>

                        </option>

                    <?php endwhile; ?>

                </select>

                <input type="date"
                       name="work_date"
                       required>

                <label>
                    Godzina rozpoczęcia:
                </label>

                <input type="time"
                       name="start_time"
                       required>


                <label>
                    Godzina zakończenia:
                </label>

                <input type="time"
                       name="end_time"
                       required>

                <button type="submit">
                    Dodaj zmianę
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