<?php

session_start();
require_once "../app/config/database.php";
require_once "../path.php";

$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    header(
        "Location: " . BASE_URL ."views/auth/login.php"
    );
    exit;
}

require_once ROOT_PATH . "app/middleware/manager.php";

if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];

    if ($deleteId != $userId) {
        $deleteSql = "
        DELETE FROM users
        WHERE id = ?
        ";

        $deleteStmt = $conn->prepare($deleteSql);
        $deleteStmt->bind_param(
            "i",
            $deleteId
        );
        $deleteStmt->execute();
    }

    header(
        "Location: lista_uzytkownikow.php"
    );
    exit;
}

$sqlUsers = "
SELECT
    id, name, surname, email, phone, role, day_rate, night_rate
FROM users
ORDER BY surname ASC
";

$usersResult = $conn->query($sqlUsers);
?>

<!DOCTYPE html>
<html lang="pl">

<head>
<?php require_once ROOT_PATH . "public/includes/head.php"; ?>
<title>Lista pracowników</title>
</head>

<body>
<div class="container">
    <section class="left-panel">
        <div class="employees-box">
            <h1>FLEXI WORK</h1>
            <h2>Lista pracowników</h2>
            <table class="employees-table">
                <tr>
                    <th>Imię i nazwisko</th>
                    <th>Email</th>
                    <th>Telefon</th>
                    <th>Rola</th>
                    <th>Stawki</th>
                    <th>Usuń</th>
                </tr>

                <?php while($employee = $usersResult->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <a class="employee-link"
                               href="<?= BASE_URL ?>views/manager/grafik_pracownika.php?id=<?= $employee['id']; ?>">
                                <?= $employee['name']; ?>
                                <?= $employee['surname']; ?>
                            </a>
                        </td>

                        <td>
                            <?= $employee['email']; ?>
                        </td>

                        <td>
                            <?= $employee['phone']; ?>
                        </td>

                        <td>
                            <?php
                            if ($employee['role'] === 'manager') {
                                echo "Manager";
                            } else {
                                echo "Pracownik";
                            }
                            ?>
                        </td>

                        <td>
                            <div class="rates-box">
                                <div>
                                    Dzienna:
                                    <strong>
                                        <?= number_format($employee['day_rate'],2); ?>
                                        zł
                                    </strong>
                                </div>

                                <div>
                                    Nocna:
                                    <strong>
                                        <?= number_format($employee['night_rate'],2); ?>
                                        zł
                                    </strong>
                                </div>

                                <a class="rate-btn"
                                   href="<?= BASE_URL ?>views/manager/ustalanie_stawki.php?id=<?= $employee['id']; ?>">
                                    Ustaw stawki
                                </a>
                            </div>
                        </td>

                        <td>
                            <?php if($employee['id'] != $userId): ?>
                                <a class="delete-btn" href="?delete=<?= $employee['id']; ?>"
                                   onclick="return confirm('Czy na pewno usunąć pracownika?')">
                                    Usuń
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>

            <div class="add-employee-box">
                <a href="<?= BASE_URL ?>views/manager/dodaj_pracownika.php"
                   class="add-employee-btn">
                    Dodaj pracownika
                </a>
            </div>
        </div>
    </section>

    <section class="right-panel">
        <?php require_once ROOT_PATH . "public/headers/header.php"; ?>
    </section>
</div>
</body>
</html>