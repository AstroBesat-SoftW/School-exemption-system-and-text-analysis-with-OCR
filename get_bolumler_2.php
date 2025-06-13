<?php
include 'db.php';
$universite_id = $_GET['universite_id'];
$query = "SELECT id, bolum_ad FROM bolumler WHERE universite_id = $universite_id";
$result = $conn->query($query);
$bolumler = [];
while ($row = $result->fetch_assoc()) {
    $bolumler[] = $row;
}
echo json_encode($bolumler);
?>
