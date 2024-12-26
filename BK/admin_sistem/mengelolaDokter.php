<?php 
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Koneksi database
include 'db.php';

// Proses tambah dokter
if (isset($_POST['tambah_dokter'])) {
    $nama = $_POST['nama'];
    $id = $_POST['id'];
    $nip = $_POST['nip'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $password = $_POST['password'];
    $id_poli = $_POST['id_poli'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $query = "INSERT INTO dokter (id, nip, nama, alamat, no_hp, password, id_poli) VALUES ('$id', '$nip', '$nama', '$alamat', '$no_hp', '$hashed_password', '$id_poli')";
        $pdo->exec($query);
        $message = "Dokter berhasil ditambahkan!";
    } catch (Exception $e) {
        $message = "Dokter tidak berhasil ditambahkan: " . $e->getMessage();
    }
}

// Query untuk mengambil data dokter dan nama poli
$query_dokter = "
    SELECT dokter.*, poli.nama_poli 
    FROM dokter 
    JOIN poli ON dokter.id_poli = poli.id
";
$result_dokter = $pdo->query($query_dokter);

// Query untuk mengambil data poli
$query_poli = "SELECT * FROM poli";
$result_poli = $pdo->query($query_poli);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mengelola Dokter</title>
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
        input[type="text"], input[type="password"], select {
            width: 400px;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
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
            overflow: hidden;
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

<h1>Mengelola Dokter</h1>

<form action="" method="post">
    <label>ID:</label>
    <input type="text" name="id" required>
    
    <label>NIP:</label>
    <input type="text" name="nip" required>
    
    <label>Nama Dokter:</label>
    <input type="text" name="nama" required>
    
    <label>Alamat:</label>
    <input type="text" name="alamat" required>
    
    <label>Nomor HP:</label>
    <input type="text" name="no_hp" required>
    
    <label>Password:</label>
    <input type="password" name="password" required>
    
    <label>Poli:</label>
    <select name="id_poli" required>
        <?php
        foreach ($result_poli as $row) {
            echo '<option value="' . $row['id'] . '">' . $row['nama_poli'] . '</option>';
        }
        ?>
    </select>
    
    <input type="submit" name="tambah_dokter" value="Tambah Dokter">
</form>

<h2>Data Dokter</h2>
<table>
    <tr>
        <th>ID</th>
        <th>NIP</th>
        <th>Nama</th>
        <th>Alamat</th>
        <th>Nomor HP</th>
        <th>Nama Poli</th>
    </tr>
    <?php
    foreach ($result_dokter as $row) {
        echo '<tr>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . $row['nip'] . '</td>';
        echo '<td>' . $row['nama'] . '</td>';
        echo '<td>' . $row['alamat'] . '</td>';
        echo '<td>' . $row['no_hp'] . '</td>';
        echo '<td>' . $row['nama_poli'] . '</td>';
        echo '</tr>';
    }
    ?>
</table>

</body>
</html>
