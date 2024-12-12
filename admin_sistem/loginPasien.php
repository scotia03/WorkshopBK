<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pasien</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #28a745;
        }
        .login-container input {
            width: 93%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .login-container button {
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .login-container button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login Pasien</h2>
    <form method="POST" action="prosesLoginpasien.php">
        <label for="nama">Nama:</label><br>
        <input type="text" id="nama" name="nama" required><br>

        <label for="no_hp">No HP:</label><br>
        <input type="text" id="no_hp" name="no_hp" required><br><br>

        <button type="submit">Login</button>
    </form>
    <p>Belum punya akun? <a href="registerPasien.php">Daftar di sini</a></p>
</div>

</body>
</html>
