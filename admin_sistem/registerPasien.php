<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register pasien</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .register-container {
            background: white;
            padding: 50px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .register-container h2 {
            margin-bottom: 20px;
        }
        .register-container input {
            width: 80%;
            padding: 10px;
            margin: 5px 0;
        }
        .register-container button {
            padding: 10px;
            background-color: #5cb85c;
            color: white;
            border: none;
            cursor: pointer;
        }
        .register-container button:hover {
            background-color: #4cae4c;
        }
        /* Tambahkan CSS untuk error message */
        .error {
            color: red;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="register-container">
    <h2>Register Pasien</h2>

    <!-- Menampilkan pesan error jika ada -->
    <?php
    if (isset($_SESSION['error'])) {
        echo '<div class="error">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
    ?>
    
    <form action="prosesRegispasien.php" method="POST">
        <label for="nama">Nama:</label><br>
        <input type="text" id="nama" name="nama" required><br>

        <label for="no_ktp">No KTP:</label><br>
        <input type="text" id="no_ktp" name="no_ktp" required><br>

        <label for="alamat">Alamat:</label><br>
        <input type="text" id="alamat" name="alamat" required><br><br>

        <label for="no_hp">No HP:</label><br>
        <input type="text" id="no_hp" name="no_hp" required><br><br>

        <button type="submit">Daftar</button>
    </form>
    <p>Sudah punya akun? <a href="loginPasien.php">Login di sini</a></p>
</div>

</body>
</html>