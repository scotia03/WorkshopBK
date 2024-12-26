<?php
session_start();
require 'db.php'; // Memasukkan file koneksi database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek apakah username ada di database
    $stmt = $pdo->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Login berhasil
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header('Location: admin.php'); // Ganti dengan halaman dashboard admin
        exit;
    } else {
        // Login gagal
        $_SESSION['error'] = 'Username atau password salah!';
        header('Location: loginAdmin.php');
        exit;
    }
}
?>