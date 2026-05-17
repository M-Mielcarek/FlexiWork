<?php

session_start();

require_once "../../path.php";
require_once ROOT_PATH . "app/config/database.php";
require_once ROOT_PATH . "app/middleware/auth.php";

if (!isset($_GET['id'])) {
    die("Brak ID pracownika");
}

$employeeId = $_GET['id'];

$sql = "
SELECT *
FROM users
WHERE id = ?
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $employeeId
);

$stmt->execute();

$result = $stmt->get_result();

$employee = $result->fetch_assoc();

if (!$employee) {
    die("Nie znaleziono pracownika");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dayRate = $_POST['day_rate'];
    $nightRate = $_POST['night_rate'];
    $sqlUpdate = "
    UPDATE users
    SET
        day_rate = ?,
        night_rate = ?
    WHERE id = ?
    ";

    $stmtUpdate = $conn->prepare($sqlUpdate);

    $stmtUpdate->bind_param(
        "ddi",
        $dayRate,
        $nightRate,
        $employeeId
    );

    $stmtUpdate->execute();

    header(
        "Location: " . BASE_URL . "public/lista_uzytkownikow.php"
    );

    exit;
}

?>
<!DOCTYPE html>
<html lang="pl">
<head>
<?php require_once ROOT_PATH . "public/includes/head.php"; ?>
<title>Ustalanie stawki</title>
</head>

<body>
<div class="container">
<section class="left-panel">
<div class="page-content">
<h1>FLEXI WORK</h1>

<h2>
<?= $employee['name']; ?>
<?= $employee['surname']; ?>
</h2>

<form method="POST"
      class="manager-form">

<label>Stawka dzienna</label>

<input type="number"
       step="0.01"
       min="0"
       name="day_rate"
       value="<?= $employee['day_rate']; ?>"
       required>

<label>Stawka nocna</label>

<input type="number"
       step="0.01"
       min="0"
       name="night_rate"
       value="<?= $employee['night_rate']; ?>"
       required>

<button type="submit">
    Zapisz stawki
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