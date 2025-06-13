<?php
// Veritabanı bağlantısı
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "muafiyet";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

$universiteler_sorgu = "SELECT id, universite_ad FROM universiteler";
$universiteler = $conn->query($universiteler_sorgu);

?>