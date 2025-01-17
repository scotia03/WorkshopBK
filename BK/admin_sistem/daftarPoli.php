<?php
session_start();
require_once 'db.php';

// Inisialisasi variabel
$id_poli = isset($_POST['id_poli']) ? $_POST['id_poli'] : '';
$id_dokter = isset($_POST['id_dokter']) ? $_POST['id_dokter'] : '';
$id_jadwal = isset($_POST['id_jadwal']) ? $_POST['id_jadwal'] : '';
$keluhan = isset($_POST['keluhan']) ? $_POST['keluhan'] : '';

// Ambil data Poli
$query_poli = $pdo->query("SELECT * FROM poli");
$poli_list = $query_poli->fetchAll(PDO::FETCH_ASSOC);

// Ambil data dokter berdasarkan poli yang dipilih
if ($id_poli) {
    $query_dokter = $pdo->prepare("SELECT * FROM dokter WHERE id_poli = :id_poli");
    $query_dokter->execute([':id_poli' => $id_poli]);
    $dokter_list = $query_dokter->fetchAll(PDO::FETCH_ASSOC);
} else {
    $dokter_list = [];
}

// Ambil jadwal dokter berdasarkan dokter yang dipilih
if ($id_dokter) {
    $query_jadwal = $pdo->prepare("SELECT * FROM jadwal_periksa WHERE id_dokter = :id_dokter AND statues = '1'");
    $query_jadwal->execute([':id_dokter' => $id_dokter]);
    $jadwal_list = $query_jadwal->fetchAll(PDO::FETCH_ASSOC);
} else {
    $jadwal_list = [];
}

// Proses formulir pendaftaran poli
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $id_poli && $id_dokter && $id_jadwal && $keluhan) {
    try {
        // Insert data pendaftaran ke database
        $sql = "INSERT INTO daftar_poli (id_pasien, id_jadwal, keluhan, no_antrian, tanggal) 
                VALUES (:id_pasien, :id_jadwal, :keluhan, :no_antrian, NOW())";
        $stmt = $pdo->prepare($sql);

        // Menambahkan nomor antrian otomatis
        $no_antrian = rand(1000, 9999); // Angka acak untuk nomor antrian

        // Menambahkan data pendaftaran pasien
        $stmt->execute([
            ':id_pasien' => 12,  // Ganti dengan ID pasien yang sesungguhnya
            ':id_jadwal' => $id_jadwal,
            ':keluhan' => $keluhan,
            ':no_antrian' => $no_antrian
        ]);

        echo "<div class='alert alert-success'>Pendaftaran berhasil! Nomor antrian Anda: $no_antrian</div>";
    } catch (PDOException $e) {
        echo "<div class='alert alert-error'>Terjadi kesalahan: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Poli</title>

    <style>
        /* Reset beberapa default styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            color: #333;
            padding: bold;
        }

        /* Navbar */
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
        .navbar h1 {
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


        /* Form Container */
        .form-container {
            background-color: #fff;
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        select, textarea {
            margin-bottom: 15px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: #28a745;
            color: white;
            font-size: 16px;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        /* Alert messages */
        .alert {
            padding: 15px;
            margin-top: 20px;
            text-align: center;
            border-radius: 5px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>

</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <h1>Poliklinik</h1>
        <!-- Menu berada di kanan -->
            <a href="dashbordPasien.php">home</a>
            <a href="daftarPoli.php">Daftar Poli</a>
            <a href="riwayatPoli.php">Riwayat Poli</a>
            <a href="logoutPasien.php">Logout</a>
    </div>

    <div class="form-container">
        <h2>Form Pendaftaran Poli</h2>
        <form method="POST">
            <label for="id_poli">Pilih Poli:</label>
            <select id="id_poli" name="id_poli" onchange="this.form.submit()">
                <option value="">Pilih Poli</option>
                <?php foreach ($poli_list as $poli): ?>
                    <option value="<?= $poli['id'] ?>" <?= $poli['id'] == $id_poli ? 'selected' : '' ?>><?= $poli['nama_poli'] ?></option>
                <?php endforeach; ?>
            </select>

            <?php if ($id_poli): ?>
                <label for="id_dokter">Pilih Dokter:</label>
                <select id="id_dokter" name="id_dokter" onchange="this.form.submit()">
                    <option value="">Pilih Dokter</option>
                    <?php foreach ($dokter_list as $dokter): ?>
                        <option value="<?= $dokter['id'] ?>" <?= $dokter['id'] == $id_dokter ? 'selected' : '' ?>><?= $dokter['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>

            <?php if ($id_dokter): ?>
                <label for="id_jadwal">Pilih Jadwal:</label>
                <select id="id_jadwal" name="id_jadwal">
                    <option value="">Pilih Jadwal</option>
                    <?php foreach ($jadwal_list as $jadwal): ?>
                        <option value="<?= $jadwal['id'] ?>" <?= $jadwal['id'] == $id_jadwal ? 'selected' : '' ?>>
                            <?= $jadwal['hari'] ?> - <?= $jadwal['jam_mulai'] ?> - <?= $jadwal['jam_selesai'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>

            <label for="keluhan">Keluhan:</label>
            <textarea id="keluhan" name="keluhan" required><?= $keluhan ?></textarea>

            <button type="submit">Daftar</button>
        </form>
    </div>

</body>
</html>
