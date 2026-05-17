<?php

session_start();
require_once "../../path.php";
require_once ROOT_PATH . "app/config/database.php";
require_once ROOT_PATH . "app/middleware/auth.php";

$userId = $_SESSION['user_id'];

if (isset($_GET['delete'])) {
    $announcementId = $_GET['delete'];
    $deleteSql = "
    DELETE FROM announcements
    WHERE id = ?
    AND user_id = ?
    ";

    $deleteStmt = $conn->prepare($deleteSql);

    $deleteStmt->bind_param(
        "ii",
        $announcementId,
        $userId
    );

    $deleteStmt->execute();
    header("Location: tablica_ogloszen.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message']);

    if (!empty($message) && strlen($message) <= 400) {
        $sqlInsert = "
        INSERT INTO announcements (
            user_id, message
        )
        VALUES (?, ?)
        ";

        $stmt = $conn->prepare($sqlInsert);

        $stmt->bind_param(
            "is",
            $userId,
            $message
        );

        $stmt->execute();
    }
}

$sql = "
SELECT
    announcements.id, announcements.user_id, announcements.message, announcements.created_at, users.name, users.surname
FROM announcements
JOIN users
ON users.id = announcements.user_id
ORDER BY announcements.created_at DESC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
<?php require_once ROOT_PATH . "public/includes/head.php"; ?>
<title>Tablica ogłoszeń</title>
</head>

<body>
<div class="container">
    <section class="left-panel">
        <div class="announcements-container">

            <h1>FLEXI WORK</h1>
            <h2>Tablica ogłoszeń</h2>

            <form method="POST"
                  class="announcement-form">

                <textarea
                    name="message"
                    maxlength="400"
                    placeholder="Napisz ogłoszenie..."
                    required></textarea>

                <button type="submit">
                    Dodaj ogłoszenie
                </button>
            </form>

 <div class="announcements-list">
    <?php while($announcement = $result->fetch_assoc()): ?>

        <div class="announcement-box">
            <div class="announcement-top">

                <div class="announcement-author">
                    <i class="fa-solid fa-user"></i>
                    <?= $announcement['name']; ?>
                    <?= $announcement['surname']; ?>
                </div>

                <?php if($announcement['user_id'] == $userId): ?>
                    <a class="delete-announcement" href="?delete=<?= $announcement['id']; ?>"
                       onclick="return confirm('Usunąć ogłoszenie?')">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                <?php endif; ?>
            </div>

            <div class="announcement-date">
                <?= date("d.m.Y H:i", strtotime($announcement['created_at'])
                ); ?>

            </div>

            <div class="announcement-message">
                <?= nl2br(htmlspecialchars($announcement['message'])
                ); ?>
            </div>
        </div>
    <?php endwhile; ?>
</div>
</section>

    <section class="right-panel">
        <?php require_once ROOT_PATH . "public/headers/header.php"; ?>
    </section>
</div>
</body>
</html>