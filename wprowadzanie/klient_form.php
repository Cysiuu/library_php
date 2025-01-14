<!DOCTYPE html>
<html lang="pl">

<?php
require_once '../config.php';
if (!$db) {
    die("Błąd połączenia: " . mysqli_connect_error());
}

$query_miasta = "SELECT id_miasta, miasto FROM miasta ORDER BY miasto";
$result_miasta = mysqli_query($db, $query_miasta);
?>

<head>
    <title>Dodaj klienta</title>

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
        <h1 class="display-3 mb-4">Wprowadzanie danych klienta</h1>

        <form id="clientForm" class="pixel-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

            <div class="form-section">
                <h2>Dane osobowe</h2>
                <div class="form-group">
                    <label for="imie">Imię:</label>
                    <input type="text" class="form-control" id="imie" name="imie" required maxlength="50">
                </div>
                <div class="form-group">
                    <label for="nazwisko">Nazwisko:</label>
                    <input type="text" class="form-control" id="nazwisko" name="nazwisko" required maxlength="50">
                </div>
                <div class="form-group">
                    <label for="telefon">Telefon:</label>
                    <input type="tel" class="form-control" id="telefon" name="telefon" required pattern="[0-9]{9}">
                </div>
            </div>

            <div class="form-section">
                <h2>Adres</h2>
                <div class="form-group">
                    <label for="miasto">Miasto:</label>
                    <select class="form-control" id="miasto" name="miasto" required>
                        <option value="">Wybierz miasto</option>
                        <?php
                        if (mysqli_num_rows($result_miasta) > 0) {
                            while ($row = mysqli_fetch_assoc($result_miasta)) {
                                echo "<option value='" . htmlspecialchars($row['id_miasta']) . "'>" .
                                    htmlspecialchars($row['miasto']) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="nr_domu">Numer domu:</label>
                    <input type="text" class="form-control" id="nr_domu" name="nr_domu" required maxlength="10">
                </div>
                <div class="form-group">
                    <label for="nr_mieszkania">Numer mieszkania:</label>
                    <input type="text" class="form-control" id="nr_mieszkania" name="nr_mieszkania" maxlength="10">
                </div>
            </div>


            <div class="checkbox-group">
                <label>
                    <input type="checkbox" id="isClubMember" name="isClubMember">
                    Czy klient ma zostać klubowiczem?
                </label>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Dodaj klienta</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $imie = $_POST['imie'];
            $nazwisko = $_POST['nazwisko'];
            $telefon = $_POST['telefon'];
            $id_miasta = $_POST['miasto'];
            $nr_domu = $_POST['nr_domu'];
            $nr_mieszkania = $_POST['nr_mieszkania'] ?: NULL;

            if (isset($_POST['isClubMember'])) {
                $query_max_id = "SELECT MAX(id_lojalnosciowe) as max_id FROM klient WHERE id_lojalnosciowe IS NOT NULL";
                $result_max_id = mysqli_query($db, $query_max_id);
                $row = mysqli_fetch_assoc($result_max_id);
                $id_lojalnosciowe = $row['max_id'] + 1;
            } else {
                $id_lojalnosciowe = "NULL";
            }

            $data_rejestracji = date('Y-m-d');

            $sql = "INSERT INTO klient (imie, nazwisko, telefon, id_miasta, nr_domu, nr_mieszkania, id_lojalnosciowe, data_rejestracji) 
            VALUES ('$imie', '$nazwisko', '$telefon', $id_miasta, '$nr_domu', " .
                ($nr_mieszkania ? "'$nr_mieszkania'" : "NULL") . ", " .
                $id_lojalnosciowe . ", '$data_rejestracji')";

            if (mysqli_query($db, $sql)) {
                header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
            } else {
                header("Location: " . $_SERVER['PHP_SELF'] . "?error=" . mysqli_error($db));
            }

            mysqli_close($db);
            exit();
        }
        ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
