<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once ROOT_PATH . "app/config/database.php";

$userId = $_SESSION['user_id'] ?? null;

$role = 'user';

if ($userId) {

    $sql = "SELECT role FROM users WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $user = $result->fetch_assoc();

        $role = $user['role'];
    }
}
?>
    <nav>
        <ul>
            <?php if ($role === 'user'): ?>
            <li>
                <a href="<?= BASE_URL ?>public/profil_uzytkownika.php">
                    Profil użytkownika
                </a>
            </li>

            <li>
                <a href="<?= BASE_URL ?>views/schedule/grafik_uzytkownika.php">
                    Grafik
                </a>
            </li>

            <li>
                <a href="<?= BASE_URL ?>public/raport_uzytkownika.php">
                    Raport
                </a>
            </li>

            <li>
                <a href="<?= BASE_URL ?>views/announcements/tablica_ogloszen.php">
                    Tablica ogłoszeń
                </a>
            </li>

            <li>
                <a href="<?= BASE_URL ?>views/announcements/bank_wymian.php">
                    Bank wymian
                </a>
            </li>

            <li>
                <a href="<?= BASE_URL ?>views/auth/logout.php">
                    Wyloguj
                </a>
            </li>

            <?php endif; ?>

            <!-- DLA MANAGERA -->
            <?php if ($role === 'manager'): ?>

                <li>
                    <a href="<?= BASE_URL ?>public/profil_uzytkownika.php">
                        Profil użytkownika
                    </a>
                </li>

                <li>
                    <a href="<?= BASE_URL ?>public/lista_uzytkownikow.php">
                        Lista pracowników
                    </a>
                </li>

                <li>
                    <a href="<?= BASE_URL ?>views/schedule/ustalanie_grafiku.php">
                        Ustalanie grafiku
                    </a>
                </li>
        
                <li>
                    <a href="<?= BASE_URL ?>public/raport_uzytkownika.php">
                        Raporty użytkowników
                    </a>
                </li>

                <li>
                    <a href="<?= BASE_URL ?>views/manager/wymagania_kadrowe.php">
                        Wymagania kadrowe
                    </a>
                </li>


                <li>
                    <a href="<?= BASE_URL ?>views/announcements/tablica_ogloszen.php">
                        Tablica ogłoszeń
                    </a>
                </li>

                <li>
                    <a href="<?= BASE_URL ?>views/announcements/bank_wymian.php">
                        Bank wymian
                    </a>
                </li>

                <li>
                    <a href="<?= BASE_URL ?>views/auth/logout.php">
                        Wyloguj
                    </a>
                </li>

            <?php endif; ?>



        </ul>
    </nav>
