<?php
session_start();
require 'db.php'; // Menggunakan koneksi PDO

// Fungsi untuk mendapatkan daftar pasien
function getDaftarPasien($pdo) {
    $sql = "SELECT dp.id AS id_daftar_poli, p.nama, dp.no_antrian, dp.keluhan, dp.tanggal 
            FROM daftar_poli dp 
            JOIN pasien p ON dp.id_pasien = p.id 
            WHERE NOT EXISTS (
                SELECT * FROM periksa WHERE periksa.id_daftar_poli = dp.id
            )
            ORDER BY dp.tanggal ASC, dp.no_antrian ASC";

    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

// Fungsi untuk mendapatkan daftar obat
function getDaftarObat($pdo) {
    $sql = "SELECT id, nama_obat, harga FROM obat";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

// Fungsi untuk menyimpan pemeriksaan
function simpanPemeriksaan($pdo, $id_daftar_poli, $tgl_periksa, $catatan, $biaya_periksa) {
    $sql = "INSERT INTO periksa (id_daftar_poli, tgl_periksa, catatan, biaya_periksa) 
            VALUES (:id_daftar_poli, :tgl_periksa, :catatan, :biaya_periksa)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_daftar_poli' => $id_daftar_poli,
        ':tgl_periksa' => $tgl_periksa,
        ':catatan' => $catatan,
        ':biaya_periksa' => $biaya_periksa,
    ]);
    return $pdo->lastInsertId();
}

// Fungsi untuk menyimpan detail pemeriksaan
function simpanDetailPeriksa($pdo, $id_periksa, $id_obat) {
    $sql = "INSERT INTO detail_periksa (id_periksa, id_obat) VALUES (:id_periksa, :id_obat)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_periksa' => $id_periksa,
        ':id_obat' => $id_obat,
    ]);
}

// Logika untuk memproses input
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_daftar_poli = $_POST['id_daftar_poli'] ?? null;
    $tgl_periksa = $_POST['tgl_periksa'] ?? null;
    $catatan = $_POST['catatan'] ?? '';
    $obat_ids = $_POST['obat'] ?? [];

    if (!$id_daftar_poli || !$tgl_periksa || empty($obat_ids)) {
        echo "<script>alert('Data tidak lengkap. Pastikan semua field telah diisi.');</script>";
    } else {
        try {
            // Mulai transaksi
            $pdo->beginTransaction();

            // Hitung total harga obat
            $total_harga_obat = 0;
            foreach ($obat_ids as $id_obat) {
                $sql = "SELECT harga FROM obat WHERE id = :id_obat";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id_obat' => $id_obat]);
                $total_harga_obat += $stmt->fetchColumn();
            }

            $total_harga = $total_harga_obat + 100000;

            // Simpan data pemeriksaan
            $id_periksa = simpanPemeriksaan($pdo, $id_daftar_poli, $tgl_periksa, $catatan, $total_harga);

            // Simpan detail pemeriksaan
            foreach ($obat_ids as $id_obat) {
                simpanDetailPeriksa($pdo, $id_periksa, $id_obat);
            }

            // Commit transaksi
            $pdo->commit();
            echo "<script>alert('Data pemeriksaan berhasil disimpan.');</script>";
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "<script>alert('Gagal menyimpan data pemeriksaan: " . $e->getMessage() . "');</script>";
        }
    }
}

// Data pasien dan obat
$daftarPasien = getDaftarPasien($pdo);
$daftarObat = getDaftarObat($pdo);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pemeriksaan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .navbar {
            background-color: #007bff;
            padding: 20px 20px;
            display: flex;
            justify-content: space-around;
            width: 100%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            z-index: 1000;
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

        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding-top: 0px; /* Untuk menyesuaikan dengan tinggi navbar */
            width: 100%;
        }

        .form-box {
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 20px 30px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .form-box h1 {
            margin-bottom: 20px;
            color: #333;
        }

        form label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            text-align: left;
        }

        form input[type="date"],
        form select,
        form textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        form button {
            margin-top: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <a href="dashbordDokter.php"><i class="fas fa-home"></i> Beranda</a>
        <a href="updateDokter.php"><i class="fas fa-user-md"></i> Pembaharui Data Dokter</a>
        <a href="jadwalPeriksa.php"><i class="fas fa-calendar-alt"></i> Input Jadwal Periksa</a>
        <a href="catatan_kesehatan.php"><i class="fas fa-notes-medical"></i> Memeriksa Pasien</a>
        <a href="riwayatPeriksa.php"><i class="fas fa-pills"></i> Riwayat Pasien</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Form Container -->
    <div class="form-container">
        <div class="form-box">
            <h1>Form Pemeriksaan</h1>
            <form method="POST">
                <label for="id_daftar_poli">Pilih Pasien:</label>
                <select name="id_daftar_poli" id="id_daftar_poli" required>
                    <option value="">-- Pilih Pasien --</option>
                    <?php foreach ($daftarPasien as $pasien): ?>
                        <option value="<?= htmlspecialchars($pasien['id_daftar_poli']) ?>">
                            <?= htmlspecialchars($pasien['nama']) ?> - <?= htmlspecialchars($pasien['no_antrian']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="tgl_periksa">Tanggal Pemeriksaan:</label>
                <input type="date" name="tgl_periksa" id="tgl_periksa" required>

                <label for="catatan">Catatan:</label>
                <textarea name="catatan" id="catatan" rows="4" required></textarea>

                <label for="obat">Pilih Obat:</label>
                <select name="obat[]" id="obat" multiple required>
                    <?php foreach ($daftarObat as $obat): ?>
                        <option value="<?= htmlspecialchars($obat['id']) ?>">
                            <?= htmlspecialchars($obat['nama_obat']) ?> - Rp<?= number_format($obat['harga'], 0, ',', '.') ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit">Simpan Pemeriksaan</button>
            </form>
        </div>
    </div>
</body>
</html>
