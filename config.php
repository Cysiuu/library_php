<?php
$db = mysqli_connect("localhost", "root", "", "ksiegarnia");
if (mysqli_connect_errno()) {
    error_log("Błąd połączenia z bazą: " . mysqli_connect_error());
    die("Przepraszamy, wystąpił błąd połączenia z bazą danych.");
}
mysqli_set_charset($db, "utf8");
?>
