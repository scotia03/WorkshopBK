<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Koneksi database
include 'db.php';

// Proses tambah pasien
if (isset($_POST['tambah_pasien'])) {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp']; // Menambahkan no_hp
    $no_rm = $_POST['no_rm']; // Menambahkan no_rm

    try {
        $query = "INSERT INTO pasien (nama, alamat, no_hp, no_rm) VALUES ('$nama', '$alamat', '$no_hp', '$no_rm')";
        $pdo->exec($query);
        $message = "Pasien berhasil ditambahkan!";
    } catch (Exception $e) {
        $message = "Pasien tidak berhasil ditambahkan: " . $e->getMessage();
    }
}

// Query untuk mengambil data pasien
$query_pasien = "SELECT * FROM pasien";
$result_pasien = $pdo->query($query_pasien);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mengelola Pasien</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
            justify-content: space-around;
            align-items: center;
            z-index: 1000;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            font-size: 1em;
            transition: background-color 0.3s ease, transform 0.2s ease;
            border-radius: 5px;
        }
        .navbar a i {
            margin-right: 8px;
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
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="number"] {
            width: 400px;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .submit-container {
            display: flex;
            justify-content: flex-end;
            width: 100%;
        }
        input[type="submit"] {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        table {
            width: 95%;
            border-collapse: collapse;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 5px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #007BFF;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        table tr:hover {
            background-color: #ddd;
        }
    </style>
    <script>
        // Menampilkan alert jika ada pesan dari PHP
        window.onload = function() {
            <?php if (isset($message)) { ?>
                alert("<?php echo $message; ?>");
            <?php } ?>
        };
    </script>
</head>
<body>
<header>
    <div class="navbar">
        <a href="admin.php"><i class="fa fa-home" aria-hidden="true"></i>Beranda</a>
        <a href="mengelolaDokter.php"><i class="fas fa-user-md"></i> Mengelola Dokter</a>
        <a href="mengelolaPasien.php"><i class="fas fa-user-injured"></i> Mengelola Pasien</a>
        <a href="mengelolaPoli.php"><i class="fas fa-hospital"></i> Mengelola Poli</a>
        <a href="mengelolaObat.php"><i class="fas fa-pills"></i> Mengelola Obat</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</header>

<h1>Mengelola Pasien</h1>

<form action="" method="post" class="form-container">
    <div>
        <label>Nama Pasien:</label>
        <input type="text" name="nama" required>
        
        <label>Alamat:</label>
        <input type="text" name="alamat" required>
        
        <label>Nomor HP:</label>
        <input type="text" name="no_hp" required> <!-- Menambahkan input untuk No HP -->
        
        <label>Nomor RM:</label>
        <input type="text" name="no_rm" required> <!-- Menambahkan input untuk No RM -->
    </div>
        <div class="submit-container">
        <input type="submit" name="tambah_pasien" value="Tambah pasien">
    </div>
</form>

<h2>Data Pasien</h2>
<table>
    <tr>
        <th>No</th>
        <th>Nama Pasien</th>
        <th>Alamat</th>
        <th>Nomor HP</th> <!-- Menambahkan kolom untuk No HP -->
        <th>Nomor RM</th> <!-- Menambahkan kolom untuk No RM -->
    </tr>
    <?php
    $no = 1;
    foreach ($result_pasien as $row) {
        echo '<tr>';
        echo '<td>' . $no . '</td>';
        echo '<td>' . htmlspecialchars($row['nama']) . '</td>'; // Menggunakan htmlspecialchars untuk keamanan
        echo '<td>' . htmlspecialchars($row['alamat']) . '</td>'; // Menggunakan htmlspecialchars untuk keamanan
        echo '<td>' . htmlspecialchars($row['no_hp']) . '</td>'; // Menampilkan No HP
        echo '<td>' . htmlspecialchars($row['no_rm']) . '</td>'; // Menampilkan No RM
        echo '</tr>';
        $no++;
    }
    ?>
</table>

</body>
</html>