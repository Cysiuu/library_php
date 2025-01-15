<!DOCTYPE html>
<html lang="pl">


<?php
require_once '../config.php';

$query_kategorie = "SELECT * FROM kategorie";
$result_kategorie = mysqli_query($db, $query_kategorie);
?>

<head>
    <title>Modyfikacja kategorii</title>

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
        <h1 class="display-3 mb-4">Modyfikacja kategorii</h1>

        <form id="kategoriaForm" class="pixel-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="form-section">
                <div class="form-group">
                    <label for="select_kategoria">Wybierz kategorię</label>
                    <select class="form-control" id="select_kategoria" name="id_kategorii" required>
                        <option value="">Wybierz kategorię</option>
                        <?php while ($kategoria = mysqli_fetch_assoc($result_kategorie)): ?>
                            <option value="<?php echo htmlspecialchars($kategoria['id_kategorii']); ?>">
                                <?php echo htmlspecialchars($kategoria['nazwa_kategorii']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="form-section">
                <div class="form-group">
                    <label for="nazwa_kategorii">Nazwa kategorii</label>
                    <input type="text" class="form-control" id="nazwa_kategorii" name="nazwa_kategorii" required>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Modyfikuj kategorię</button>
            </div>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nazwa_kategorii = mysqli_real_escape_string($db, $_POST['nazwa_kategorii']);
            $idkategorii = mysqli_real_escape_string($db, $_POST['id_kategorii']);

            $sql = "UPDATE kategorie 
                    SET nazwa_kategorii = '$nazwa_kategorii'
                    WHERE id_kategorii = $idkategorii";

            if (mysqli_query($db, $sql)) {
                header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Błąd: " . mysqli_error($db) . "</div>";
            }

            mysqli_close($db);
        }
        ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
