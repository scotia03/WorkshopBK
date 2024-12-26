<?php
require 'db.php';

$poli_id = $_GET['poli_id'];

$stmt = $conn->prepare("SELECT id, nama_dokter, jadwal FROM dokter WHERE poli_id = ?");
$stmt->bind_param("i", $poli_id);
$stmt->execute();
$result = $stmt->get_result();

$dokter = [];
while ($row = $result->fetch_assoc()) {
    $dokter[] = $row;
}

echo json_encode($dokter);
?>
