<?php 
session_start();
require 'db.php'; // Memasukkan file koneksi database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $no_ktp = $_POST['no_ktp'];
    $no_hp = $_POST['no_hp'];
    $alamat = $_POST['alamat'];

    // Validasi input tidak boleh kosong
    if (empty($nama) || empty($no_ktp) || empty($no_hp) || empty($alamat)) {
        $_SESSION['error'] = 'Semua field wajib diisi!';
        header('Location: registerPasien.php');
        exit;
    }

    // Cek apakah nama sudah terdaftar
    $stmt = $pdo->prepare("SELECT * FROM pasien WHERE nama = ?");
    $stmt->execute([$nama]);
    if ($stmt->rowCount() > 0) {
        $_SESSION['error'] = 'Nama sudah terdaftar!';
        header('Location: registerPasien.php');
        exit;
    }

    // Ambil tahun dan bulan saat ini
    $tahun_bulan = date('Ym'); // Format: YYYYMM

    // Cek nomor urut pasien untuk bulan dan tahun ini
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM pasien WHERE no_rm LIKE ?");
    $stmt->execute([$tahun_bulan . '%']);
    $count = $stmt->fetchColumn();

    // Nomor urut pasien (misalnya, jika sudah ada 3 pasien, maka nomor urut berikutnya adalah 004)
    $nomor_urut = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

    // Buat no_rm dengan format YYYYMM-XXX
    $no_rm = $tahun_bulan . '-' . $nomor_urut;

    // Gunakan no_hp sebagai password dan hash password
    $hashed_password = password_hash($no_hp, PASSWORD_DEFAULT);

    // Simpan data pasien baru ke database
    $stmt = $pdo->prepare("INSERT INTO pasien (nama, no_ktp, no_hp, alamat, no_rm, password) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$nama, $no_ktp, $no_hp, $alamat, $no_rm, $hashed_password])) {
        $_SESSION['success'] = 'Registrasi berhasil! Silakan login.';
        header('Location: loginPasien.php');
        exit;
    } else {
        $_SESSION['error'] = 'Terjadi kesalahan saat registrasi.';
        header('Location: registerPasien.php');
        exit;
    }
}
?>
