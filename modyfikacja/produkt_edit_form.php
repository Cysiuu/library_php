<!DOCTYPE html>
<html lang="pl">

<?php
require_once '../config.php';

$query_autorzy = "SELECT idautora, imie, nazwisko FROM autorzy ORDER BY nazwisko";
$result_autorzy = mysqli_query($db, $query_autorzy);

$query_kategorie = "SELECT id_kategorii, nazwa_kategorii FROM kategorie ORDER BY nazwa_kategorii";
$result_kategorie = mysqli_query($db, $query_kategorie);

$query_produkty = "SELECT p.idprodukt, p.nazwa_produktu, p.id_autora, p.id_kategorii, 
                         p.cena, p.kod_produktu, p.dostepnosc,
                         a.imie, a.nazwisko, k.nazwa_kategorii 
                  FROM produkt p 
                  JOIN autorzy a ON p.id_autora = a.idautora 
                  JOIN kategorie k ON p.id_kategorii = k.id_kategorii 
                  ORDER BY p.nazwa_produktu";
$result_produkty = mysqli_query($db, $query_produkty);
?>
<head>
    <title>Modyfikuj produkt</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../static/styles/navbar.css">
    <link rel="stylesheet" href="../static/styles/preloader.css">
    <link rel="stylesheet" href="../static/styles/style.css">
    <link rel="stylesheet" href="../static/styles/pixel-form.css">
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
<script src="../static/scripts/preloader-script.js"></script>


<nav class="navbar navbar-expand-lg navbar-light bg-light justify-content-between p-3 mb-5">
    <div class="container-fluid">

        <a href="../index.html" class="navbar-brand">
            <img src="../static/images/library-logo.png" width="100" height="100" alt="logo">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="offcanvas offcanvas-start align-middle" tabindex="-1" id="navbarNav" aria-labelledby="Title">
            <div class="offcanvas-header">
                <h1 class="display-3">Księgarnia</h1>
                <img src="../static/images/library-logo.png" width="100" height="100" alt="logo">
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
            </div>
            <ol class="navbar-nav py-2 px-3 gap-5">
                <li class="nav-item">
                    <a href="../struktura_bazy.html"><label class="nav-link">Struktura bazy</label></a>
                </li>
                <li class="nav-item">
                    <a href="../wprowadzanie_danych.html"><label class="nav-link">Wprowadzanie danych</label></a>
                </li>
                <li class="nav-item">
                    <a href="../wyswietlanie_danych.php"><label class="nav-link">Wyswietlanie danych</label></a>
                </li>
                <li class="nav-item">
                    <a href="../kasowanie_danych.php"><label class="nav-link">Kasowanie danych</label></a>
                </li>
                <li class="nav-item">
                    <a href="../modyfikowanie_danych.html"><label class="nav-link">Modyfikowanie danych</label></a>
                </li>
                <li class="nav-item">
                    <a href="../raport.php"><label class="nav-link">Raport</label></a>
                </li>
            </ol>
        </div>
    </div>
</nav>

<div class="container-fluid d-flex align-items-center justify-content-center">
    <div class="container form-container">
        <h1 class="display-3 mb-4">Modyfikowanie produktu</h1>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                Produkt został pomyślnie zaktualizowany.
            </div>
        <?php endif; ?>

        <form id="productForm" class="pixel-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="form-section">
                <div class="form-group">
                    <label for="select_produkt">Wybierz produkt do edycji:</label>
                    <select class="form-control" id="select_produkt" name="idprodukt" required>
                        <option value="">Wybierz produkt</option>
                        <?php while($produkt = mysqli_fetch_assoc($result_produkty)): ?>
                            <option value="<?php echo htmlspecialchars($produkt['idprodukt']); ?>"
                                    data-nazwa="<?php echo htmlspecialchars($produkt['nazwa_produktu']); ?>"
                                    data-autor="<?php echo htmlspecialchars($produkt['id_autora']); ?>"
                                    data-kategoria="<?php echo htmlspecialchars($produkt['id_kategorii']); ?>"
                                    data-cena="<?php echo htmlspecialchars($produkt['cena']); ?>"
                                    data-kod="<?php echo htmlspecialchars($produkt['kod_produktu']); ?>"
                                    data-dostepnosc="<?php echo htmlspecialchars($produkt['dostepnosc']); ?>">
                                <?php echo htmlspecialchars($produkt['nazwa_produktu']) . ' - ' .
                                    htmlspecialchars($produkt['imie'] . ' ' . $produkt['nazwisko']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="nazwa_produktu">Nazwa produktu:</label>
                    <input type="text" class="form-control" id="nazwa_produktu" name="nazwa_produktu" required>
                </div>

                <div class="form-group">
                    <label for="id_autora">Autor:</label>
                    <select class="form-control" id="id_autora" name="id_autora" required>
                        <option value="">Wybierz autora</option>
                        <?php
                        mysqli_data_seek($result_autorzy, 0);
                        while($autor = mysqli_fetch_assoc($result_autorzy)):
                            ?>
                            <option value="<?php echo htmlspecialchars($autor['idautora']); ?>">
                                <?php echo htmlspecialchars($autor['imie'] . ' ' . $autor['nazwisko']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="id_kategorii">Kategoria:</label>
                    <select class="form-control" id="id_kategorii" name="id_kategorii" required>
                        <option value="">Wybierz kategorię</option>
                        <?php
                        mysqli_data_seek($result_kategorie, 0);
                        while($kategoria = mysqli_fetch_assoc($result_kategorie)):
                            ?>
                            <option value="<?php echo htmlspecialchars($kategoria['id_kategorii']); ?>">
                                <?php echo htmlspecialchars($kategoria['nazwa_kategorii']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="cena">Cena:</label>
                    <input type="number" step="0.01" class="form-control" id="cena" name="cena" required>
                </div>

                <div class="form-group">
                    <label for="kod_produktu">Kod produktu:</label>
                    <input type="text" class="form-control" id="kod_produktu" name="kod_produktu" required>
                </div>

                <div class="form-group">
                    <label for="dostepnosc">Dostępność:</label>
                    <input type="number" step="1" class="form-control" id="dostepnosc" name="dostepnosc" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Aktualizuj produkt</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id_produkt = (int)$_POST['idprodukt'];
            $nazwa_produktu = mysqli_real_escape_string($db, $_POST['nazwa_produktu']);
            $id_autora = (int)$_POST['id_autora'];
            $id_kategorii = (int)$_POST['id_kategorii'];
            $cena = floatval($_POST['cena']);
            $kod_produktu = mysqli_real_escape_string($db, $_POST['kod_produktu']);
            $dostepnosc = (int)$_POST['dostepnosc'];

            if (!$result_produkty || !$result_autorzy || !$result_kategorie) {
                die("Błąd zapytania: " . mysqli_error($db));
            }

            $sql = "UPDATE produkt SET 
                    id_autora = $id_autora,
                    id_kategorii = $id_kategorii,
                    nazwa_produktu = '$nazwa_produktu',
                    cena = $cena,
                    kod_produktu = '$kod_produktu',
                    dostepnosc = $dostepnosc
                    WHERE idprodukt = $id_produkt";

            if (mysqli_query($db, $sql)) {
                echo "<div class='alert alert-success'>Historia została zaktualizowana!</div>";
            } else {
                echo "<div class='alert alert-danger'>Błąd: " . mysqli_error($db) . "</div>";
            }
        }
        ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
