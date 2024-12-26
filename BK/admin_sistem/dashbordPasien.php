<?php  
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pasien</title>
    <link rel="stylesheet" href="style.css"> <!-- Link ke CSS eksternal -->
    <!-- Tambahkan link untuk Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background: linear-gradient(135deg, #007BFF, #0056b3);
            padding: 15px 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            display: flex;
            justify-content: flex-end; /* Menempatkan menu di kanan */
            align-items: center;
            z-index: 1000;
        }
        .navbar h2 {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            font-size: 1em;
            margin-right: 70%; /* Memberikan jarak antar tautan */
        }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            font-size: 1em;
            transition: background-color 0.3s ease, transform 0.2s ease;
            border-radius: 5px;
            margin-left: 10px; /* Memberikan jarak antar tautan */
        }
        .navbar a i {
            margin-right: 8px; /* Memberikan jarak antara ikon dan teks */
        }
        .navbar a:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
        .container {
            max-width: 1200px;
            margin: 40px auto;
            text-align: center;
            padding: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 50%;
            font-size: 14px;
            color: #777;
        }
        .welcome-message {
            margin-top: 20px;
            font-size: 20px;
            text-align: center;
            color: #333;
            background-color: #007BFF;
            color: white;
            padding: 20px;
            border-radius: 30px; /* Membuat bentuk gelembung */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Efek bayangan untuk memberikan kesan 3D */
            display: inline-block;
            transform: scale(1); /* Menambahkan sedikit efek scale */
            transition: transform 0.3s ease-in-out; /* Efek zoom saat hover */
        }
        .welcome-message:hover {
            transform: scale(1.1); /* Efek pembesaran saat hover */
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h2>Menu Dashboard Pasien</h2>
        <!-- Menu berada di kanan -->
        <a href="daftarPoli.php"><i class="fas fa-id-card"></i>Daftar poli</a>
        <a href="riwayatPoli.php"><i class="fas fa-id-card"></i>riwayat poli</a>
        <a href="logoutPasien.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
    </div>
    
    <div class="container">
        <div class="content">
            <div class="welcome-message">
                <h1>Selamat datang, <?php echo $_SESSION['nama']; ?>!</h1>
            </div>
        </div>
        <div class="footer">
            <p>&copy; 2024 Sistem Poliklinik. All rights reserved.</p>
        </div>
    </div>

</body>
</html>
