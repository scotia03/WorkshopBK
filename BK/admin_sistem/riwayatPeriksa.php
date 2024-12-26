<?php
session_start();
require 'db.php'; // Menggunakan koneksi PDO

$riwayatPemeriksaan = getRiwayatPemeriksaan($pdo);

// Fungsi untuk mendapatkan riwayat pemeriksaan
function getRiwayatPemeriksaan($pdo) {
    $sql = "SELECT dp.tanggal AS tanggal_pendaftaran, p.nama, pr.tgl_periksa, pr.catatan, pr.biaya_periksa, pr.id AS id_periksa
            FROM periksa pr
            JOIN daftar_poli dp ON pr.id_daftar_poli = dp.id
            JOIN pasien p ON dp.id_pasien = p.id
            ORDER BY pr.tgl_periksa DESC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

// Fungsi untuk menghapus riwayat pemeriksaan
function hapusRiwayat($pdo, $id_periksa) {
    try {
        // Hapus data terkait di tabel detail_periksa
        $sqlDetail = "DELETE FROM detail_periksa WHERE id_periksa = :id_periksa";
        $stmtDetail = $pdo->prepare($sqlDetail);
        $stmtDetail->execute([':id_periksa' => $id_periksa]);

        // Hapus data di tabel periksa
        $sqlPeriksa = "DELETE FROM periksa WHERE id = :id_periksa";
        $stmtPeriksa = $pdo->prepare($sqlPeriksa);
        $stmtPeriksa->execute([':id_periksa' => $id_periksa]);

        return true; // Jika berhasil
    } catch (Exception $e) {
        return false; // Jika terjadi kesalahan
    }
}


// Proses penghapusan riwayat
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_riwayat'])) {
    $id_periksa = $_POST['id_periksa'] ?? null;

    if ($id_periksa) {
        try {
            if (hapusRiwayat($pdo, $id_periksa)) {
                echo "<script>alert('Riwayat pemeriksaan berhasil dihapus.');</script>";
            } else {
                echo "<script>alert('Gagal menghapus riwayat pemeriksaan.');</script>";
            }
            header("Location: " . $_SERVER['PHP_SELF']); // Refresh halaman
            exit;
        } catch (Exception $e) {
            echo "<script>alert('Terjadi kesalahan: " . $e->getMessage() . "');</script>";
        }
    } else {
        echo "<script>alert('ID pemeriksaan tidak ditemukan.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pemeriksaan</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .navbar {
            background-color: #007bff;
            padding: 20px 20px;
            display: flex;
            justify-content: space-around;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 20px;
            transition: background-color 0.3s ease;
            display: flex;
            align-items: center;
        }

        .navbar a i {
            margin-right: 8px;
        }

        .navbar a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            padding: 20px;
        }

        .form-container, .table-container {
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 20px;
            flex: 1;
            margin: 10px;
            min-width: 300px;
        }

        form label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        form select, form textarea, form input[type="date"] {
            width: 80%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        form button {
            margin-top: 15px;
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #218838;
        }

        form button i {
            margin-right: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        table th {
            background-color: #007bff;
            color: white;
        }

        table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .delete-button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .delete-button:hover {
            background-color: #c82333;
        }

        .delete-button i {
            margin-right: 5px;
        }
    </style>
</head>
<body>
<div class="navbar">
    <a href="dashbordDokter.php"><i class="fa fa-home" aria-hidden="true"></i>Beranda</a>
    <a href="updateDokter.php"><i class="fas fa-user-md"></i> pembaharui data Dokter</a>
    <a href="jadwalPeriksa.php"><i class="fas fa-user-injured"></i> input jadwal periksa</a>
    <a href="catatan_kesehatan.php"><i class="fas fa-hospital"></i> memeriksa pasien</a>
    <a href="riwayatPeriksa.php"><i class="fas fa-pills"></i> riwayat pasien</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>
<div style="display: flex; flex-wrap: wrap; justify-content: space-between;">
    <div style="flex: 1; min-width: 300px;">
        <h2>Riwayat Pemeriksaan</h2>
        <table border="1" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Tanggal Pendaftaran</th>
                    <th>Nama Pasien</th>
                    <th>Tanggal Periksa</th>
                    <th>Catatan</th>
                    <th>Biaya</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($riwayatPemeriksaan)): ?>
                    <?php foreach ($riwayatPemeriksaan as $riwayat): ?>
                        <tr>
                            <td><?= htmlspecialchars($riwayat['tanggal_pendaftaran']) ?></td>
                            <td><?= htmlspecialchars($riwayat['nama']) ?></td>
                            <td><?= htmlspecialchars($riwayat['tgl_periksa']) ?></td>
                            <td><?= htmlspecialchars($riwayat['catatan']) ?></td>
                            <td>Rp<?= number_format($riwayat['biaya_periksa'], 0, ',', '.') ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id_periksa" value="<?= htmlspecialchars($riwayat['id_periksa']) ?>">
                                    <button type="submit" name="hapus_riwayat" onclick="return confirm('Apakah Anda yakin ingin menghapus riwayat ini?');">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">Belum ada riwayat pemeriksaan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>