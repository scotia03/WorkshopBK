<?php
require_once 'db.php';

if (isset($_GET['dokter'])) {
    $id = $_GET['id'];

    try {
        // Query untuk mengambil jadwal dokter berdasarkan id dokter
        $sql = "SELECT * FROM jadwal_periksa WHERE id_dokter = : id"; // Pastikan kolom ini sesuai
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        // Cek apakah ada jadwal yang ditemukan
        $jadwal = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($jadwal) > 0) {
            // Mengembalikan hasil dalam format JSON
            echo json_encode($jadwal);
        } else {
            // Jika tidak ada jadwal ditemukan
            echo json_encode(['message' => 'Jadwal tidak tersedia']);
        }
    } catch (PDOException $e) {
        // Menampilkan error jika terjadi kesalahan dalam query
        echo json_encode(['error' => 'Error: ' . $e->getMessage()]);
    }
} else {
    // Menangani jika tidak ada parameter dokter_id yang dikirimkan
    echo json_encode(['error' => 'Dokter ID tidak ditemukan']);
}
?>
