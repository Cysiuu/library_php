<!DOCTYPE html>
<html lang="pl">

<head>

    <title>Kasowanie danych</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="static/styles/navbar.css">
    <link rel="stylesheet" href="static/styles/preloader.css">
    <link rel="stylesheet" href="static/styles/style.css">
    <link rel="stylesheet" href="static/styles/pixel-table.css">
    <link rel="stylesheet" href="static/styles/side-menu.css">
    <link
            href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
            href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
            rel="stylesheet">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">

    <style>
        .delete-btn {
            background: none;
            border: none;
            color: #ff6b6b;
            cursor: pointer;
            padding: 5px 10px;
            transition: all 0.3s steps(3);
        }

        .delete-btn:hover {
            color: #ff4757;
            transform: scale(1.2);
        }

        .delete-btn i {
            font-size: 1.2rem;
        }

        .action-column {
            width: 50px;
            text-align: center;
        }

    </style>

</head>

<body>

<button class="menu-toggle" aria-label="Toggle menu"></button>
<nav class="pixel-side-menu">
    <ul>
        <li><a href="#section-klienci">Klienci</a></li>
        <li><a href="#section-autorzy">Autorzy</a></li>
        <li><a href="#section-historia-cen">Historia Cen</a></li>
        <li><a href="#section-kategorie">Kategorie</a></li>
        <li><a href="#section-klubowicze">Klubowicze</a></li>
        <li><a href="#section-kody-pocztowe">Kody Pocztowe</a></li>
        <li><a href="#section-miasta">Miasta</a></li>
        <li><a href="#section-oplaty-miesieczne">Opłaty</a></li>
        <li><a href="#section-produkty">Produkty</a></li>
        <li><a href="#section-statusy-zamowien">Statusy</a></li>
        <li><a href="#section-zakupy">Zakupy</a></li>
        <li><a href="#section-zamowienia">Zamówienia</a></li>
        <li><a href="#section-znizki">Zniżki</a></li>
    </ul>
</nav>

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
                <h1 class="display-3">Księgarnia</h1>
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
        <h1 class="display-3 mb-4">Kasowanie danych</h1>
        <div class="table-container">
            <table class="pixel-table">
                <h2 class="display-4 mb-4">Klienci</h2>
                <tr>
                    <th>Id</th>
                    <th>Imię</th>
                    <th>Nazwisko</th>
                    <th>Id miasta</th>
                    <th>Nr domu</th>
                    <th>Nr mieszkania</th>
                    <th>Telefon</th>
                    <th>Id lojalnościowe</th>
                    <th>Data rejestracji</th>
                    <th class='action-column'>Akcje</th>
                </tr>
                <?php
                require_once 'config.php';


                $get_clients = "SELECT k.idklient, k.imie, k.nazwisko, k.id_miasta, k.nr_domu, k.nr_mieszkania, k.telefon, k.id_lojalnosciowe, k.data_rejestracji 
                FROM klient k";
                $result_get_clients = mysqli_query($db, $get_clients);

                while ($row = mysqli_fetch_assoc($result_get_clients)) {
                    echo "<tr data-id='" . $row['idklient'] . "' data-table='klient'>
            <td>" . $row['idklient'] . "</td>
            <td>" . $row['imie'] . "</td>
            <td>" . $row['nazwisko'] . "</td>
            <td>" . $row['id_miasta'] . "</td>
            <td>" . $row['nr_domu'] . "</td>
            <td>" . ($row['nr_mieszkania'] ? $row['nr_mieszkania'] : '-') . "</td>
            <td>" . $row['telefon'] . "</td>
            <td>" . ($row['id_lojalnosciowe'] ? $row['id_lojalnosciowe'] : '-') . "</td>
            <td>" . $row['data_rejestracji'] . "</td>
            <td class='action-column'>
                <button type='button' class='delete-btn' 
                        onclick='deleteRecord(" . $row['idklient'] . ", \"klient\", \"idklient\")'
                        data-id='" . $row['idklient'] . "'>
                    <i class='bi bi-trash'></i>
                </button>
            </td>
        </tr>";
                }
                ?>
            </table>
        </div>

        <div class="table-container">
            <table class="pixel-table">
                <h2 class="display-4 mb-4">Autorzy</h2>
                <tr>
                    <th> Id</th>
                    <th> Imię</th>
                    <th> Nazwisko</th>
                    <th class='action-column'>Akcje</th>
                </tr>
                <?php

                $get_authors = "SELECT * FROM autorzy";
                $result_get_authors = mysqli_query($db, $get_authors);

                while ($row = mysqli_fetch_assoc($result_get_authors)) {
                    echo "<tr data-id='" . $row['idautora'] . "' data-table='autorzy'>
            <td>" . $row['idautora'] . "</td>
            <td>" . $row['imie'] . "</td>
            <td>" . $row['nazwisko'] . "</td>
            <td class='action-column'>
                <button type='button' class='delete-btn' onclick='deleteRecord(" . $row['idautora'] . ", \"autorzy\", \"idautora\")'>
                    <i class='bi bi-trash'></i>
                </button>
            </td>
        </tr>";
                }
                ?>
            </table>
        </div>

        <div class="table-container">
            <table class="pixel-table">
                <h2 class="display-4 mb-4">Historia Cen</h2>
                <tr>
                    <th> Id produktu</th>
                    <th> Cena</th>
                    <th> Data zmiany</th>
                    <th class='action-column'>Akcje</th>
                </tr>
                <?php

                $get_price_history = "SELECT id_historii, id_produktu, cena, data_zmiany FROM historia_cen";
                $result_get_price_history = mysqli_query($db, $get_price_history);

                while ($row = mysqli_fetch_assoc($result_get_price_history)) {
                    echo "<tr data-id='" . $row['id_historii'] . "' data-table='historia_cen'>
            <td>" . $row['id_produktu'] . "</td>
            <td>" . $row['cena'] . "</td>
            <td>" . $row['data_zmiany'] . "</td>
            <td class='action-column'>
                <button type='button' class='delete-btn' onclick='deleteRecord(" . $row['id_historii'] . ", \"historia_cen\", \"id_historii\")'>
                    <i class='bi bi-trash'></i>
                </button>
            </td>
        </tr>";
                }
                ?>
            </table>
        </div>

        <div class="table-container">
            <table class="pixel-table">
                <h2 class="display-4 mb-4">Kategorie</h2>
                <tr>
                    <th> Id</th>
                    <th> Kategoria</th>
                    <th class='action-column'>Akcje</th>
                </tr>
                <?php

                $get_categories = "SELECT * FROM kategorie";
                $result_get_categories = mysqli_query($db, $get_categories);

                while ($row = mysqli_fetch_assoc($result_get_categories)) {
                    echo "<tr data-id='" . $row['id_kategorii'] . "' data-table='kategorie'>
            <td>" . $row['id_kategorii'] . "</td>
            <td>" . $row['nazwa_kategorii'] . "</td>
            <td class='action-column'>
                <button type='button' class='delete-btn' onclick='deleteRecord(" . $row['id_kategorii'] . ", \"kategorie\", \"id_kategorii\")'>
                    <i class='bi bi-trash'></i>
                </button>
            </td>
        </tr>";
                }
                ?>
            </table>
        </div>

        <div class="table-container">
            <table class="pixel-table">
                <h2 class="display-4 mb-4">Klubowicze</h2>
                <tr>
                    <th> Id lojalnościowe</th>
                    <th> Id opłaty miesięcznej</th>
                    <th> Id zniżki</th>
                    <th> Data dołączenia</th>
                    <th class='action-column'>Akcje</th>
                </tr>
                <?php

                $get_club_members = "SELECT * FROM klubowicze";
                $result_get_club_members = mysqli_query($db, $get_club_members);

                while ($row = mysqli_fetch_assoc($result_get_club_members)) {
                    echo "<tr data-id='" . $row['id_lojalnosciowe'] . "' data-table='klubowicze'>
            <td>" . $row['id_lojalnosciowe'] . "</td>
            <td>" . $row['id_oplaty_miesiecznej'] . "</td>
            <td>" . $row['id_znizki'] . "</td>
            <td>" . $row['data_dolaczenia'] . "</td>
            <td class='action-column'>
                <button type='button' class='delete-btn' onclick='deleteRecord(" . $row['id_lojalnosciowe'] . ", \"klubowicze\", \"id_lojalnosciowe\")'>
                    <i class='bi bi-trash'></i>
                </button>
            </td>
        </tr>";
                }
                ?>
            </table>
        </div>

        <div class="table-container">
            <table class="pixel-table">
                <h2 class="display-4 mb-4">Kody pocztowe</h2>
                <tr>
                    <th> Kod pocztowy</th>
                    <th> Id miasta</th>
                    <th class='action-column'>Akcje</th>
                </tr>
                <?php

                $get_postal_codes = "SELECT * FROM kody_pocztowe";
                $result_get_postal_codes = mysqli_query($db, $get_postal_codes);

                while ($row = mysqli_fetch_assoc($result_get_postal_codes)) {
                    echo "<tr data-id='" . $row['kod_pocztowy'] . "' data-table='kody_pocztowe'>
            <td>" . $row['kod_pocztowy'] . "</td>
            <td>" . $row['id_miasta'] . "</td>
            <td class='action-column'>
                <button type='button' class='delete-btn' onclick='deleteRecord(\"" . $row['kod_pocztowy'] . "\", \"kody_pocztowe\", \"kod_pocztowy\")'>
                    <i class='bi bi-trash'></i>
                </button>
            </td>
        </tr>";
                }

                ?>
            </table>
        </div>

        <div class="table-container">
            <table class="pixel-table">
                <h2 class="display-4 mb-4">Miasta</h2>
                <tr>
                    <th>Id miasta</th>
                    <th>Miasto</th>
                    <th class='action-column'>Akcje</th>
                </tr>
                <?php
                $get_cities = "SELECT * FROM miasta";
                $result_get_cities = mysqli_query($db, $get_cities);

                while ($row = mysqli_fetch_assoc($result_get_cities)) {
                    echo "<tr data-id='" . $row['id_miasta'] . "' data-table='miasta'>
            <td>" . $row['id_miasta'] . "</td>
            <td>" . $row['miasto'] . "</td>
            <td class='action-column'>
                <button type='button' class='delete-btn' onclick='deleteRecord(" . $row['id_miasta'] . ", \"miasta\", \"id_miasta\")'>
                    <i class='bi bi-trash'></i>
                </button>
            </td>
        </tr>";
                }
                ?>
            </table>
        </div>

        <div class="table-container">
            <table class="pixel-table">
                <h2 class="display-4 mb-4">Opłaty miesięczne</h2>
                <tr>
                    <th>Id opłaty</th>
                    <th>Wielkość opłaty</th>
                    <th class='action-column'>Akcje</th>
                </tr>
                <?php
                $get_payments = "SELECT * FROM oplaty_miesieczne";
                $result_get_payments = mysqli_query($db, $get_payments);

                while ($row = mysqli_fetch_assoc($result_get_payments)) {
                    echo "<tr data-id='" . $row['id_oplaty_miesiecznej'] . "' data-table='oplaty_miesieczne'>
            <td>" . $row['id_oplaty_miesiecznej'] . "</td>
            <td>" . $row['wielkosc_oplaty'] . " zł</td>
            <td class='action-column'>
                <button type='button' class='delete-btn' onclick='deleteRecord(" . $row['id_oplaty_miesiecznej'] . ", \"oplaty_miesieczne\", \"id_oplaty_miesiecznej\")'>
                    <i class='bi bi-trash'></i>
                </button>
            </td>
        </tr>";
                }
                ?>
            </table>
        </div>

        <div class="table-container">
            <table class="pixel-table">
                <h2 class="display-4 mb-4">Produkty</h2>
                <tr>
                    <th>Id</th>
                    <th>Id autora</th>
                    <th>Id kategorii</th>
                    <th>Nazwa produktu</th>
                    <th>Cena</th>
                    <th>Kod produktu</th>
                    <th>Data dodania</th>
                    <th>Dostępność</th>
                    <th class='action-column'>Akcje</th>
                </tr>
                <?php
                $get_products = "SELECT * FROM produkt";
                $result_get_products = mysqli_query($db, $get_products);

                while ($row = mysqli_fetch_assoc($result_get_products)) {
                    echo "<tr data-id='" . $row['idprodukt'] . "' data-table='produkt'>
            <td>" . $row['idprodukt'] . "</td>
            <td>" . $row['id_autora'] . "</td>
            <td>" . $row['id_kategorii'] . "</td>
            <td>" . $row['nazwa_produktu'] . "</td>
            <td>" . $row['cena'] . " zł</td>
            <td>" . $row['kod_produktu'] . "</td>
            <td>" . $row['data_dodania'] . "</td>
            <td>" . ($row['dostepnosc'] ? 'Tak' : 'Nie') . "</td>
            <td class='action-column'>
                <button type='button' class='delete-btn' onclick='deleteRecord(" . $row['idprodukt'] . ", \"produkt\", \"idprodukt\")'>
                    <i class='bi bi-trash'></i>
                </button>
            </td>
        </tr>";
                }
                ?>
            </table>
        </div>

        <div class="table-container">
            <table class="pixel-table">
                <h2 class="display-4 mb-4">Statusy zamówień</h2>
                <tr>
                    <th>Id statusu</th>
                    <th>Nazwa statusu</th>
                    <th class='action-column'>Akcje</th>
                </tr>
                <?php
                $get_statuses = "SELECT * FROM statusy_zamowien";
                $result_get_statuses = mysqli_query($db, $get_statuses);

                while ($row = mysqli_fetch_assoc($result_get_statuses)) {
                    echo "<tr data-id='" . $row['id_statusu'] . "' data-table='statusy_zamowien'>
            <td>" . $row['id_statusu'] . "</td>
            <td>" . $row['nazwa_statusu'] . "</td>
            <td class='action-column'>
                <button type='button' class='delete-btn' onclick='deleteRecord(" . $row['id_statusu'] . ", \"statusy_zamowien\", \"id_statusu\")'>
                    <i class='bi bi-trash'></i>
                </button>
            </td>
        </tr>";
                }
                ?>
            </table>
        </div>

        <div class="table-container">
            <table class="pixel-table">
                <h2 class="display-4 mb-4">Zakupy</h2>
                <tr>
                    <th>Id zakupu</th>
                    <th>Id klienta</th>
                    <th>Id zamówienia</th>
                    <th>Data zakupu</th>
                    <th class='action-column'>Akcje</th>
                </tr>
                <?php
                $get_purchases = "SELECT * FROM zakupy";
                $result_get_purchases = mysqli_query($db, $get_purchases);

                while ($row = mysqli_fetch_assoc($result_get_purchases)) {
                    echo "<tr data-id='" . $row['id_zakupu'] . "' data-table='zakupy'>
            <td>" . $row['id_zakupu'] . "</td>
            <td>" . $row['id_klienta'] . "</td>
            <td>" . $row['id_zamowienia'] . "</td>
            <td>" . $row['data_zakupu'] . "</td>
            <td class='action-column'>
                <button type='button' class='delete-btn' onclick='deleteRecord(" . $row['id_zakupu'] . ", \"zakupy\", \"id_zakupu\")'>
                    <i class='bi bi-trash'></i>
                </button>
            </td>
        </tr>";
                }
                ?>
            </table>
        </div>

        <div class="table-container">
            <table class="pixel-table">
                <h2 class="display-4 mb-4">Zamówienia</h2>
                <tr>
                    <th>Id zamówienia</th>
                    <th>Id produktu</th>
                    <th>Id zniżki</th>
                    <th>Ilość</th>
                    <th>Id statusu</th>
                    <th>Data zamówienia</th>
                    <th class='action-column'>Akcje</th>
                </tr>
                <?php
                $get_orders = "SELECT * FROM zamowienia";
                $result_get_orders = mysqli_query($db, $get_orders);

                while ($row = mysqli_fetch_assoc($result_get_orders)) {
                    echo "<tr data-id='" . $row['id_zamowienia'] . "' data-table='zamowienia'>
            <td>" . $row['id_zamowienia'] . "</td>
            <td>" . $row['id_produktu'] . "</td>
            <td>" . ($row['id_znizki'] ? $row['id_znizki'] : '-') . "</td>
            <td>" . $row['ilosc'] . "</td>
            <td>" . $row['id_statusu'] . "</td>
            <td>" . $row['data_zamowienia'] . "</td>
            <td class='action-column'>
                <button type='button' class='delete-btn' onclick='deleteRecord(" . $row['id_zamowienia'] . ", \"zamowienia\", \"id_zamowienia\")'>
                    <i class='bi bi-trash'></i>
                </button>
            </td>
        </tr>";
                }
                ?>
            </table>
        </div>

        <div class="table-container">
            <table class="pixel-table">
                <h2 class="display-4 mb-4">Zniżki</h2>
                <tr>
                    <th>Id zniżki</th>
                    <th>Wielkość zniżki</th>
                    <th class='action-column'>Akcje</th>
                </tr>
                <?php
                $get_discounts = "SELECT * FROM znizki";
                $result_get_discounts = mysqli_query($db, $get_discounts);

                while ($row = mysqli_fetch_assoc($result_get_discounts)) {
                    echo "<tr data-id='" . $row['id_znizki'] . "' data-table='znizki'>
            <td>" . $row['id_znizki'] . "</td>
            <td>" . $row['wielkosc_znizki'] . "%</td>
            <td class='action-column'>
                <button type='button' class='delete-btn' onclick='deleteRecord(" . $row['id_znizki'] . ", \"znizki\", \"id_znizki\")'>
                    <i class='bi bi-trash'></i>
                </button>
            </td>
        </tr>";
                }
                mysqli_close($db);
                ?>
            </table>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <script src="static/scripts/side-menu.js"></script>

        <script>
            async function deleteRecord(id, table, id_column) {
                console.log('Próba usunięcia:', {id, table, id_column});

                try {
                    const formData = new FormData();
                    formData.append('id', id);
                    formData.append('table', table);
                    formData.append('id_column', id_column);

                    const response = await fetch('delete_record.php', {
                        method: 'POST',
                        body: formData
                    });

                    let result;
                    const responseText = await response.text();
                    console.log('Odpowiedź serwera:', responseText);

                    try {
                        result = JSON.parse(responseText);
                    } catch (e) {
                        throw new Error('Nieprawidłowa odpowiedź serwera: ' + responseText);
                    }

                    if (result.success) {
                        const row = document.querySelector(`tr[data-id="${id}"][data-table="${table}"]`);
                        if (row) {
                            row.remove();
                        }
                        alert('Rekord został pomyślnie usunięty');
                    } else {
                        throw new Error(result.error || 'Nieznany błąd podczas usuwania');
                    }
                } catch (error) {
                    console.error('Szczegóły błędu:', error);
                    alert('Wystąpił błąd podczas usuwania: ' + error.message);
                }
            }
        </script>


</body>
</html>
