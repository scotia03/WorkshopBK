<?php
require_once 'db.php';

// Ambil riwayat pendaftaran poli pasien
$id_pasien = ''; // Ganti dengan ID pasien yang sesungguhnya, bisa dari session atau input pengguna

$sql = "SELECT dp.id, p.nama_poli, d.nama AS nama_dokter, jp.hari, jp.jam_mulai, jp.jam_selesai, dp.no_antrian, jp.statues 
        FROM daftar_poli dp
        JOIN poli p ON dp.id = p.id
        JOIN jadwal_periksa jp ON dp.id_jadwal = jp.id
        JOIN dokter d ON jp.id_dokter = d.id
        WHERE dp.id_pasien = :id_pasien";

$stmt = $pdo->prepare($sql);
$stmt->execute([':id_pasien' => $id_pasien]);
$riwayat_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pendaftaran Poli</title>

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
        nav {
            background-color: #007bff;
            padding: 10px 20px;
            text-align: center;
        }

        nav ul {
            list-style-type: none;
        }

        nav ul li {
            display: inline;
            margin: 0 15px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
        }

        nav ul li a:hover {
            text-decoration: underline;
        }

        /* Form Container */
        .form-container {
            background-color: #fff;
            max-width: 1000px;
            margin: 40px auto;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Table Styles */
        .riwayat-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .riwayat-table th, .riwayat-table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .riwayat-table th {
            background-color: #007bff;
            color: #fff;
        }

        .riwayat-table td a {
            color: #007bff;
            text-decoration: none;
        }

        .riwayat-table td a:hover {
            text-decoration: underline;
        }

        /* Alert Messages */
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
    <nav>
        <ul>
            <li><a href="dashbordPasien.php">Home</a></li>
            <li><a href="daftarPoli.php">Pendaftaran Poli</a></li>
            <li><a href="riwayatPoli.php">Riwayat Pendaftaran</a></li>
        </ul>
    </nav>

    <div class="form-container">
        <h2>Riwayat Pendaftaran Poli</h2>

        <?php if ($riwayat_list): ?>
        <table class="riwayat-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Poli</th>
                    <th>Dokter</th>
                    <th>Hari</th>
                    <th>Mulai</th>
                    <th>Selesai</th>
                    <th>Antrian</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($riwayat_list as $index => $riwayat): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= $riwayat['nama_poli'] ?></td>
                    <td><?= $riwayat['nama_dokter'] ?></td>
                    <td><?= $riwayat['hari'] ?></td>
                    <td><?= $riwayat['jam_mulai'] ?></td>
                    <td><?= $riwayat['jam_selesai'] ?></td>
                    <td><?= $riwayat['no_antrian'] ?></td>
                    <td><?= $riwayat['statues'] ?></td>
                    <td><a href="#">Lihat Detail</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>Anda belum memiliki riwayat pendaftaran poli.</p>
        <?php endif; ?>
    </div>

</body>
</html>
