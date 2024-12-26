<?php
session_start();
require 'db.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['nama'])) {
    echo "Anda harus login terlebih dahulu.";
    exit();
}

// Ambil nama pengguna dari session
$nama_pengguna = $_SESSION['nama'];

// Fungsi untuk mendapatkan riwayat pemeriksaan
function getRiwayatPemeriksaan($pdo, $nama_pengguna) {
    $sql = "SELECT dp.tanggal AS tanggal_pendaftaran, p.nama, pr.tgl_periksa, pr.catatan, pr.biaya_periksa, pr.id AS id_periksa
            FROM periksa pr
            JOIN daftar_poli dp ON pr.id_daftar_poli = dp.id
            JOIN pasien p ON dp.id_pasien = p.id
            WHERE p.nama = :nama_pengguna
            ORDER BY pr.tgl_periksa DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':nama_pengguna' => $nama_pengguna]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Ambil riwayat pemeriksaan untuk pengguna yang login
$riwayat_list = getRiwayatPemeriksaan($pdo, $nama_pengguna);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pemeriksaan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background: linear-gradient(135deg, #007BFF, #0056b3);
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        .navbar h1 {
            font-size: 1.5em;
            margin: 0;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
            padding: 8px 12px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .navbar a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #007BFF;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            text-align: center;
            padding: 10px;
        }

        table th {
            background-color: #007BFF;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .no-data {
            text-align: center;
            color: #777;
            margin: 20px 0;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #555;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Poliklinik</h1>
        <div>
            <a href="dashbordPasien.php">home</a>
            <a href="daftarPoli.php">Daftar Poli</a>
            <a href="riwayatPoli.php">Riwayat Poli</a>
            <a href="logoutPasien.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <h2>Halo, <?= htmlspecialchars($nama_pengguna) ?>!</h2>
        <p>Berikut adalah riwayat pemeriksaan Anda:</p>

        <?php if (!empty($riwayat_list)): ?>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Pendaftaran</th>
                        <th>Nama</th>
                        <th>Tanggal Periksa</th>
                        <th>Catatan</th>
                        <th>Biaya</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($riwayat_list as $index => $riwayat): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($riwayat['tanggal_pendaftaran']) ?></td>
                            <td><?= htmlspecialchars($riwayat['nama']) ?></td>
                            <td><?= htmlspecialchars($riwayat['tgl_periksa']) ?></td>
                            <td><?= htmlspecialchars($riwayat['catatan']) ?></td>
                            <td>Rp<?= number_format($riwayat['biaya_periksa'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">Tidak ada riwayat pemeriksaan.</p>
        <?php endif; ?>
    </div>

    <div class="footer">
        &copy; <?= date('Y') ?> Sistem Informasi Klinik. Semua hak cipta dilindungi.
    </div>
</body>
</html>
