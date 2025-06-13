<?php
include 'db.php';
$bolum_id = $_GET['bolum_id'];
$query = "SELECT * FROM dersler WHERE bolum_id = $bolum_id";
$result = $conn->query($query);
$dersler = [];
while ($row = $result->fetch_assoc()) {
    $dersler[] = $row;
}
echo json_encode($dersler);
?>
