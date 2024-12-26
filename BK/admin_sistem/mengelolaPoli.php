<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Koneksi database
include 'db.php';

$status_message = ""; // Variabel untuk menyimpan status pesan

// Proses tambah poli
if (isset($_POST['tambah_poli'])) {
    $nama_poli = $_POST['nama_poli'];
    $keterangan = $_POST['keterangan'];

    try {
        $query = "INSERT INTO poli (nama_poli, keterangan) VALUES ('$nama_poli', '$keterangan')";
        $pdo->exec($query);
        $status_message = "Data poli berhasil ditambahkan!";
    } catch (Exception $e) {
        $status_message = "Gagal menambahkan data poli: " . $e->getMessage();
    }
}
// Query untuk mengambil data poli
$query_poli = "SELECT * FROM poli";
$result_poli = $pdo->query($query_poli);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mengelola Poli</title>
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
        // Menampilkan notifikasi jika ada status message
        window.onload = function() {
            var statusMessage = "<?php echo addslashes($status_message); ?>";
            if (statusMessage) {
                alert(statusMessage);
            }
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

<h1>Mengelola Poli</h1>

<form action="" method="post">
    <label>Nama Poli:</label>
    <input type="text" name="nama_poli" required>
    
    <label>keterangan:</label>
    <input type="text" name="keterangan" required>
    
    <div class="submit-container">
        <input type="submit" name="tambah_poli" value="Tambah Poli">
    </div>
</form>

<h2>Data Poli</h2>
<table>
    <tr>
        <th>No</th>
        <th>Nama Poli</th>
        <th>keterangan</th>
    </tr>
    <?php
    $no = 1;
    foreach ($result_poli as $row) {
        echo '<tr>';
        echo '<td>' . $no . '</td>';
        echo '<td>' . htmlspecialchars($row['nama_poli']) . '</td>'; // Menggunakan htmlspecialchars untuk keamanan
        echo '<td>' . htmlspecialchars($row['keterangan']) . '</td>'; // Menggunakan htmlspecialchars untuk keamanan
        echo '</tr>';
        $no++;
    }
    ?>
</table>

</body>
</html>