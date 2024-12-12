<?php  
session_start();
require 'db.php'; // Memasukkan file koneksi database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $no_hp = $_POST['no_hp'];

    // Cek apakah pasien ada di database berdasarkan nama
    $stmt = $pdo->prepare("SELECT * FROM pasien WHERE nama = ?");
    $stmt->execute([$nama]);
    $pasien = $stmt->fetch(PDO::FETCH_ASSOC);

    // Jika pasien ditemukan dan no_hp yang dimasukkan cocok
    if ($pasien && $no_hp === $pasien['no_hp']) {
        // Login berhasil
        $_SESSION['loggedin'] = true;
        $_SESSION['nama'] = $pasien['nama']; // Menyimpan nama pasien dalam session
        $_SESSION['no_hp'] = $pasien['no_hp']; // Menyimpan no_hp pasien dalam session
        header('Location: dashbordPasien.php'); // Ganti dengan halaman dashboard pasien
        exit;
    } else {
        // Login gagal
        $_SESSION['error'] = 'Nama atau nomor HP salah!';
        header('Location: loginPasien.php');
        exit;
    }
}
?>