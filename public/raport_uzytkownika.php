<?php
require_once "../path.php";
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <script src="https://kit.fontawesome.com/f45c1e3753.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@200..900&display=swap" rel="stylesheet">

    <title>FlexiWork</title>
</head>

<body>

    <div class="container">

    <!-- LEWA STRONA -->
    <section class="left-panel">

        <h1>FLEXI WORK</h1>


    </section>

<header class="right-panel">
    <nav>
        <ul>

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
                    Tablica Ogłoszeń
                </a>
            </li>

            <li>
                <a href="<?= BASE_URL ?>views/announcements/bank_wymian.php">
                    Bank Wymian
                </a>
            </li>

            <li>
                <a href="<?= BASE_URL ?>public/lista_uzytkownikow.php">
                    Lista użytkowników
                </a>
            </li>

            <li>
                <a href="<?= BASE_URL ?>views/manager/dodaj_pracownika.php">
                    Dodaj pracownika
                </a>
            </li>

            <li>
                <a href="<?= BASE_URL ?>views/schedule/ustalanie_grafiku.php">
                    Ustalanie grafiku
                </a>
            </li>

            <li>
                <a href="<?= BASE_URL ?>views/auth/logout.php">
                    Wyloguj
                </a>
            </li>

        </ul>
    </nav>

</header>

</div>

</body>
</html>
