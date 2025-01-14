<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';
header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Nieprawidłowa metoda żądania');
    }

    if (!isset($_POST['id']) || !isset($_POST['table']) || !isset($_POST['id_column'])) {
        throw new Exception('Brak wymaganych danych');
    }

    $id = mysqli_real_escape_string($db, $_POST['id']);
    $table = mysqli_real_escape_string($db, $_POST['table']);
    $id_column = mysqli_real_escape_string($db, $_POST['id_column']);

    $allowed_tables = [
        'klient' => 'idklient',
        'autorzy' => 'idautora',
        'historia_cen' => 'id_historii',
        'kategorie' => 'id_kategorii',
        'klubowicze' => 'id_lojalnosciowe',
        'kody_pocztowe' => 'kod_pocztowy',
        'miasta' => 'id_miasta',
        'oplaty_miesieczne' => 'id_oplaty_miesiecznej',
        'produkt' => 'idprodukt',
        'statusy_zamowien' => 'id_statusu',
        'zakupy' => 'id_zakupu',
        'zamowienia' => 'id_zamowienia',
        'znizki' => 'id_znizki'
    ];

    if (!array_key_exists($table, $allowed_tables)) {
        throw new Exception('Niedozwolona tabela');
    }

    if ($allowed_tables[$table] !== $id_column) {
        throw new Exception('Nieprawidłowa kolumna ID dla wybranej tabeli');
    }

    mysqli_begin_transaction($db);

    switch ($table) {
        case 'klient':
            $query = "DELETE FROM zakupy WHERE id_klienta = '$id'";
            if (!mysqli_query($db, $query)) {
                throw new Exception("Błąd podczas usuwania zakupów klienta: " . mysqli_error($db));
            }
            $query = "UPDATE klient SET id_lojalnosciowe = NULL WHERE idklient = '$id'";
            if (!mysqli_query($db, $query)) {
                throw new Exception("Błąd podczas aktualizacji powiązania z klubowiczami: " . mysqli_error($db));
            }
            break;

        case 'autorzy':
            $query = "DELETE FROM historia_cen WHERE id_produktu IN (SELECT idprodukt FROM produkt WHERE id_autora = '$id')";
            if (!mysqli_query($db, $query)) {
                throw new Exception("Błąd podczas usuwania historii cen: " . mysqli_error($db));
            }
            $query = "DELETE FROM zakupy WHERE id_zamowienia IN (SELECT id_zamowienia FROM zamowienia WHERE id_produktu IN (SELECT idprodukt FROM produkt WHERE id_autora = '$id'))";
            if (!mysqli_query($db, $query)) {
                throw new Exception("Błąd podczas usuwania zakupów: " . mysqli_error($db));
            }
            $query = "DELETE FROM zamowienia WHERE id_produktu IN (SELECT idprodukt FROM produkt WHERE id_autora = '$id')";
            if (!mysqli_query($db, $query)) {
                throw new Exception("Błąd podczas usuwania zamówień: " . mysqli_error($db));
            }
            $query = "DELETE FROM produkt WHERE id_autora = '$id'";
            if (!mysqli_query($db, $query)) {
                throw new Exception("Błąd podczas usuwania produktów: " . mysqli_error($db));
            }
            break;

        case 'kategorie':
            $query = "DELETE FROM historia_cen WHERE id_produktu IN (SELECT idprodukt FROM produkt WHERE id_kategorii = '$id')";
            if (!mysqli_query($db, $query)) {
                throw new Exception("Błąd podczas usuwania historii cen: " . mysqli_error($db));
            }
            $query = "DELETE FROM zakupy WHERE id_zamowienia IN (SELECT id_zamowienia FROM zamowienia WHERE id_produktu IN (SELECT idprodukt FROM produkt WHERE id_kategorii = '$id'))";
            if (!mysqli_query($db, $query)) {
                throw new Exception("Błąd podczas usuwania zakupów: " . mysqli_error($db));
            }
            $query = "DELETE FROM zamowienia WHERE id_produktu IN (SELECT idprodukt FROM produkt WHERE id_kategorii = '$id')";
            if (!mysqli_query($db, $query)) {
                throw new Exception("Błąd podczas usuwania zamówień: " . mysqli_error($db));
            }
            $query = "DELETE FROM produkt WHERE id_kategorii = '$id'";
            if (!mysqli_query($db, $query)) {
                throw new Exception("Błąd podczas usuwania produktów: " . mysqli_error($db));
            }
            break;

        case 'produkt':
            $query = "DELETE FROM historia_cen WHERE id_produktu = '$id'";
            if (!mysqli_query($db, $query)) {
                throw new Exception("Błąd podczas usuwania historii cen: " . mysqli_error($db));
            }
            $query = "DELETE FROM zakupy WHERE id_zamowienia IN (SELECT id_zamowienia FROM zamowienia WHERE id_produktu = '$id')";
            if (!mysqli_query($db, $query)) {
                throw new Exception("Błąd podczas usuwania zakupów: " . mysqli_error($db));
            }
            $query = "DELETE FROM zamowienia WHERE id_produktu = '$id'";
            if (!mysqli_query($db, $query)) {
                throw new Exception("Błąd podczas usuwania zamówień: " . mysqli_error($db));
            }
            break;

        case 'miasta':
            $query = "DELETE FROM kody_pocztowe WHERE id_miasta = '$id'";
            if (!mysqli_query($db, $query)) {
                throw new Exception("Błąd podczas usuwania kodów pocztowych: " . mysqli_error($db));
            }
            $query = "DELETE FROM zakupy WHERE id_klienta IN (SELECT idklient FROM klient WHERE id_miasta = '$id')";
            if (!mysqli_query($db, $query)) {
                throw new Exception("Błąd podczas usuwania zakupów: " . mysqli_error($db));
            }
            $query = "DELETE FROM klient WHERE id_miasta = '$id'";
            if (!mysqli_query($db, $query)) {
                throw new Exception("Błąd podczas usuwania klientów: " . mysqli_error($db));
            }
            break;

        case 'klubowicze':
            $query = "UPDATE klient SET id_lojalnosciowe = NULL WHERE id_lojalnosciowe = '$id'";
            if (!mysqli_query($db, $query)) {
                throw new Exception("Błąd podczas aktualizacji klientów: " . mysqli_error($db));
            }
            break;

        case 'zamowienia':
            $query = "DELETE FROM zakupy WHERE id_zamowienia = '$id'";
            if (!mysqli_query($db, $query)) {
                throw new Exception("Błąd podczas usuwania zakupów: " . mysqli_error($db));
            }
            break;

        case 'znizki':
            $query = "UPDATE klubowicze SET id_znizki = NULL WHERE id_znizki = '$id'";
            if (!mysqli_query($db, $query)) {
                throw new Exception("Błąd podczas aktualizacji klubowiczów: " . mysqli_error($db));
            }

            $query = "UPDATE zamowienia SET id_znizki = NULL WHERE id_znizki = '$id'";
            if (!mysqli_query($db, $query)) {
                throw new Exception("Błąd podczas aktualizacji zamówień: " . mysqli_error($db));
            }

            break;

        case 'oplaty_miesieczne':
            $query = "UPDATE klubowicze SET id_oplaty_miesiecznej = NULL WHERE id_oplaty_miesiecznej = '$id'";
            if (!mysqli_query($db, $query)) {
                throw new Exception("Błąd podczas aktualizacji klubowiczów: " . mysqli_error($db));
            }
            break;

        case 'statusy_zamowien':
            $query = "UPDATE zamowienia SET id_statusu = 1 WHERE id_statusu = '$id'";
            if (!mysqli_query($db, $query)) {
                throw new Exception("Błąd podczas aktualizacji statusów zamówień: " . mysqli_error($db));
            }
            break;
    }

    $query = "DELETE FROM `$table` WHERE `$id_column` = '$id'";
    if (!mysqli_query($db, $query)) {
        throw new Exception("Błąd podczas usuwania rekordu: " . mysqli_error($db));
    }

    mysqli_commit($db);

    echo json_encode([
        'success' => true,
        'message' => 'Rekord został pomyślnie usunięty'
    ]);

} catch (Exception $e) {
    if (isset($db)) {
        mysqli_rollback($db);
    }

    error_log("Błąd w delete_record.php: " . $e->getMessage());

    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

if (isset($db)) {
    mysqli_close($db);
}
?>
