<?php
session_start();
require 'db.php'; // Memasukkan file koneksi database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi input
    if ($password !== $confirm_password) {
        $_SESSION['error'] = 'Password dan konfirmasi password tidak cocok!';
        header('Location: loginAdmin.php');
        exit;
    }

    // Cek apakah username sudah ada
    $stmt = $pdo->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->rowCount() > 0) {
        $_SESSION['error'] = 'Username sudah terdaftar!';
        header('Location: register.php');
        exit;
    }

    // Simpan pengguna baru
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO user (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $hashed_password]);

    // Redirect ke halaman login
    header('Location: login.php');
    exit;
}
?>