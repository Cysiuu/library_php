<!DOCTYPE html>
<html lang="pl">

<head>

    <title>Dodaj użytkownika</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="static/styles/navbar.css">
    <link rel="stylesheet" href="static/styles/preloader.css">
    <link rel="stylesheet" href="static/styles/style.css">
    <link rel="stylesheet" href="static/styles/pixel-table.css">
    <link
            href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
            href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
            rel="stylesheet">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">

</head>

<body>

<div class="preloader-wrapper">
    <div class="spinner-border text-warning" role="status">
        <span class="visually-hidden">Ładowanie...</span>
    </div>
</div>
<script src="static/scripts/preloader-script.js"></script>


<nav class="navbar navbar-expand-lg navbar-light bg-light justify-content-between p-3 mb-5">
    <div class="container-fluid">

        <a href="index.html" class="navbar-brand">
            <img src="static/images/library-logo.png" width="100" height="100" alt="logo">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="offcanvas offcanvas-start align-middle" tabindex="-1" id="navbarNav" aria-labelledby="Title">
            <div class="offcanvas-header">
                <h12 class="display-3">Księgarnia</h12>
                    <img src="static/images/library-logo.png" width="100" height="100" alt="logo">
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
            </div>
            <ol class="navbar-nav py-2 px-3 gap-5">
                <li class="nav-item">
                    <a href="struktura_bazy.html"><label class="nav-link">Struktura bazy</label></a>
                </li>
                <li class="nav-item">
                    <a href="wprowadzanie_danych.html"><label class="nav-link">Wprowadzanie danych</label></a>
                </li>
                <li class="nav-item">
                    <a href="wyswietlanie_danych.php"><label class="nav-link">Wyswietlanie danych</label></a>
                </li>
                <li class="nav-item">
                    <a href="kasowanie_danych.php"><label class="nav-link">Kasowanie danych</label></a>
                </li>
                <li class="nav-item">
                    <a href="modyfikowanie_danych.html"><label class="nav-link">Modyfikowanie danych</label></a>
                </li>
                <li class="nav-item">
                    <a href="raport.php"><label class="nav-link">Raport</label></a>
                </li>
            </ol>
        </div>
    </div>
</nav>

<div class="container-fluid d-flex align-items-center justify-content-center">
    <div class="container form-container">
        <h1 class="display-3 mb-4">Raporty księgarni</h1>

        <!-- Top 5 najczęściej kupowanych książek -->
        <div class="table-container">
            <table class="pixel-table">
                <h2 class="display-4 mb-4">Top 5 bestsellerów</h2>
                <tr>
                    <th>Tytuł</th>
                    <th>Autor</th>
                    <th>Liczba sprzedanych</th>
                    <th>Łączna wartość</th>
                </tr>
                <?php
                require_once 'config.php';

                $top_books = "SELECT 
                                p.nazwa_produktu,
                                CONCAT(a.imie, ' ', a.nazwisko) as autor,
                                COUNT(z.id_zamowienia) as liczba_sprzedanych,
                                SUM(p.cena * zam.ilosc) as laczna_wartosc
                            FROM produkt p
                            JOIN autorzy a ON p.id_autora = a.idautora
                            JOIN zamowienia zam ON p.idprodukt = zam.id_produktu
                            JOIN zakupy z ON zam.id_zamowienia = z.id_zamowienia
                            GROUP BY p.idprodukt
                            ORDER BY liczba_sprzedanych DESC
                            LIMIT 5";

                $result = mysqli_query($db, $top_books);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['nazwa_produktu']}</td>
                            <td>{$row['autor']}</td>
                            <td>{$row['liczba_sprzedanych']}</td>
                            <td>{$row['laczna_wartosc']} zł</td>
                        </tr>";
                }
                ?>
            </table>
        </div>

        <!-- Statystyki klubowiczów -->
        <div class="table-container">
            <table class="pixel-table">
                <h2 class="display-4 mb-4">Statystyki członków klubu</h2>
                <tr>
                    <th>Status</th>
                    <th>Liczba</th>
                </tr>
                <?php
                $club_stats = "SELECT 
                                COUNT(DISTINCT k.idklient) as total_clients,
                                SUM(CASE WHEN k.id_lojalnosciowe IS NOT NULL THEN 1 ELSE 0 END) as club_members
                            FROM klient k";

                $result = mysqli_query($db, $club_stats);
                $stats = mysqli_fetch_assoc($result);

                echo "<tr>
                        <td>Wszyscy klienci</td>
                        <td>{$stats['total_clients']}</td>
                    </tr>
                    <tr>
                        <td>Członkowie klubu</td>
                        <td>{$stats['club_members']}</td>
                    </tr>
                    <tr>
                        <td>Procent klubowiczów</td>
                        <td>" . round(($stats['club_members'] / $stats['total_clients']) * 100, 2) . "%</td>
                    </tr>";
                ?>
            </table>
        </div>

        <!-- Średnia wartość zamówienia w poszczególnych miesiącach -->
        <div class="table-container">
            <table class="pixel-table">
                <h2 class="display-4 mb-4">Średnia wartość zamówienia (ostatnie 6 miesięcy)</h2>
                <tr>
                    <th>Miesiąc</th>
                    <th>Liczba zamówień</th>
                    <th>Średnia wartość</th>
                </tr>
                <?php
                $monthly_avg = "SELECT 
                                DATE_FORMAT(z.data_zakupu, '%Y-%m') as miesiac,
                                COUNT(DISTINCT z.id_zakupu) as liczba_zamowien,
                                ROUND(AVG(p.cena * zam.ilosc), 2) as srednia_wartosc
                            FROM zakupy z
                            JOIN zamowienia zam ON z.id_zamowienia = zam.id_zamowienia
                            JOIN produkt p ON zam.id_produktu = p.idprodukt
                            GROUP BY miesiac
                            ORDER BY miesiac DESC
                            LIMIT 6";

                $result = mysqli_query($db, $monthly_avg);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['miesiac']}</td>
                            <td>{$row['liczba_zamowien']}</td>
                            <td>{$row['srednia_wartosc']} zł</td>
                        </tr>";
                }
                ?>
            </table>
        </div>

        <!-- Popularność kategorii -->
        <div class="table-container">
            <table class="pixel-table">
                <h2 class="display-4 mb-4">Popularność kategorii książek</h2>
                <tr>
                    <th>Kategoria</th>
                    <th>Liczba sprzedanych</th>
                    <th>Udział w sprzedaży</th>
                </tr>
                <?php
                $category_stats = "SELECT 
                                    k.nazwa_kategorii,
                                    COUNT(z.id_zakupu) as liczba_sprzedanych,
                                    ROUND(COUNT(z.id_zakupu) * 100.0 / (SELECT COUNT(*) FROM zakupy), 2) as udzial_procentowy
                                FROM kategorie k
                                JOIN produkt p ON k.id_kategorii = p.id_kategorii
                                JOIN zamowienia zam ON p.idprodukt = zam.id_produktu
                                JOIN zakupy z ON zam.id_zamowienia = z.id_zamowienia
                                GROUP BY k.id_kategorii
                                ORDER BY liczba_sprzedanych DESC";

                $result = mysqli_query($db, $category_stats);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['nazwa_kategorii']}</td>
                            <td>{$row['liczba_sprzedanych']}</td>
                            <td>{$row['udzial_procentowy']}%</td>
                        </tr>";
                }
                mysqli_close($db);
                ?>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>
