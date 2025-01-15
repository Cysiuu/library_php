<!DOCTYPE html>
<html lang="pl">

<?php
require_once '../config.php';
if (!$db) {
    die("Błąd połączenia: " . mysqli_connect_error());
}

$query_miasta = "SELECT m.id_miasta, m.miasto, 
                GROUP_CONCAT(k.kod_pocztowy ORDER BY k.kod_pocztowy SEPARATOR ', ') as kody_pocztowe
                FROM miasta m 
                LEFT JOIN kody_pocztowe k ON m.id_miasta = k.id_miasta 
                GROUP BY m.id_miasta, m.miasto
                ORDER BY m.miasto";
$result_miasta = mysqli_query($db, $query_miasta);
?>
<head>
    <title>Modyfikuj miasto</title>

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
        <h1 class="display-3 mb-4">Modyfikowanie danych miasta</h1>

        <form id="cityForm" class="pixel-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

            <div class="form-section">
                <div class="form-group">
                    <label for="select_miasto">Wybierz miasto:</label>
                    <select class="form-control" id="select_miasto" name="id_miasta" required>
                        <option value="">Wybierz miasto</option>
                        <?php
                        if (mysqli_num_rows($result_miasta) > 0) {
                            while ($row = mysqli_fetch_assoc($result_miasta)) {
                                echo "<option value='" . htmlspecialchars($row['id_miasta']) . "' 
                                        data-kod='" . htmlspecialchars($row['kod_pocztowy']) . "'
                                        data-miasto='" . htmlspecialchars($row['miasto']) . "'>" .
                                    htmlspecialchars($row['miasto']) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="kod_pocztowy">Kod Pocztowy:</label>
                    <input type="text" class="form-control" id="kod_pocztowy" name="kod_pocztowy" required>
                </div>
                <div class="form-group">
                    <label for="miasto">Miasto:</label>
                    <input type="text" class="form-control" id="miasto" name="miasto" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Aktualizuj miasto</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id_miasta = mysqli_real_escape_string($db, $_POST['id_miasta']);
            $kod_pocztowy = mysqli_real_escape_string($db, $_POST['kod_pocztowy']);
            $miasto = mysqli_real_escape_string($db, $_POST['miasto']);

            $update_city = "UPDATE miasta SET miasto = '$miasto' WHERE id_miasta = '$id_miasta'";
            if (!mysqli_query($db, $update_city)) {
                die("Błąd podczas aktualizacji miasta: " . mysqli_error($db));
            }

            $check_postal = "SELECT * FROM kody_pocztowe WHERE id_miasta = '$id_miasta'";
            $result_postal = mysqli_query($db, $check_postal);

            if (mysqli_num_rows($result_postal) > 0) {
                $update_postal = "UPDATE kody_pocztowe SET kod_pocztowy = '$kod_pocztowy' 
                                    WHERE id_miasta = '$id_miasta'";
                if (!mysqli_query($db, $update_postal)) {
                    die("Błąd podczas aktualizacji kodu pocztowego: " . mysqli_error($db));
                }
            } else {
                $insert_postal = "INSERT INTO kody_pocztowe (kod_pocztowy, id_miasta) 
                                    VALUES ('$kod_pocztowy', '$id_miasta')";
                if (!mysqli_query($db, $insert_postal)) {
                    die("Błąd podczas dodawania kodu pocztowego: " . mysqli_error($db));
                }
            }

            mysqli_close($db);

            header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
            exit();
        }
        ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
