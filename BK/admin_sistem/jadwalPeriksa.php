<?php
// Mengimpor koneksi database PDO
require_once 'db.php';

// Variabel untuk menampilkan pesan
$error = '';
$success = '';

// Memulai sesi dan mengambil nama dokter
session_start();
$nama = $_SESSION['nama'];

// Ambil ID dokter berdasarkan nama
$stmt = $pdo->prepare("SELECT id FROM dokter WHERE nama = ?");
$stmt->execute([$nama]);
$dokter = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dokter) {
    die('Dokter tidak ditemukan.');
}

$id_dokter = $dokter['id'];

// Fungsi untuk mendapatkan jadwal periksa
function getSchedules($pdo, $id_dokter) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM jadwal_periksa WHERE id_dokter = ? ORDER BY hari, jam_mulai");
        $stmt->execute([$id_dokter]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

// Mendapatkan daftar jadwal periksa
$schedules = getSchedules($pdo, $id_dokter);

// Proses penambahan, pembaruan, atau penghapusan jadwal periksa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];
    $id = $_POST['id'] ?? null;
    $hari = $_POST['hari'] ?? null;
    $jam_mulai = $_POST['jam_mulai'] ?? null;
    $jam_selesai = $_POST['jam_selesai'] ?? null;
    $statues = $_POST['statues'] ?? null;

    try {
        if ($action == 'add' && $hari && $jam_mulai && $jam_selesai && isset($statues)) {
            // Jika status "Aktif", set jadwal lain menjadi "Non-Aktif"
            if ($statues == '1') {
                $stmt = $pdo->prepare("UPDATE jadwal_periksa SET statues = 0 WHERE id_dokter = ?");
                $stmt->execute([$id_dokter]);
            }

            $stmt = $pdo->prepare("INSERT INTO jadwal_periksa (id_dokter, hari, jam_mulai, jam_selesai, statues) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$id_dokter, $hari, $jam_mulai, $jam_selesai, $statues]);
            $success = 'Jadwal berhasil ditambahkan!';
        } elseif ($action == 'update' && $id && $hari && $jam_mulai && $jam_selesai && isset($statues)) {
            // Jika status "Aktif", set jadwal lain menjadi "Non-Aktif"
            if ($statues == '1') {
                $stmt = $pdo->prepare("UPDATE jadwal_periksa SET statues = 0 WHERE id_dokter = ? AND id != ?");
                $stmt->execute([$id_dokter, $id]);
            }

            $stmt = $pdo->prepare("UPDATE jadwal_periksa SET hari = ?, jam_mulai = ?, jam_selesai = ?, statues = ? WHERE id = ? AND id_dokter = ?");
            $stmt->execute([$hari, $jam_mulai, $jam_selesai, $statues, $id, $id_dokter]);
            $success = 'Jadwal berhasil diperbarui!';
        } elseif ($action == 'delete' && $id) {
            $stmt = $pdo->prepare("DELETE FROM jadwal_periksa WHERE id = ? AND id_dokter = ?");
            $stmt->execute([$id, $id_dokter]);
            $success = 'Jadwal berhasil dihapus!';
        } else {
            $error = 'Data tidak valid atau tidak lengkap.';
        }
    } catch (PDOException $e) {
        $error = 'Terjadi kesalahan: ' . $e->getMessage();
    }

    // Perbarui daftar jadwal setelah operasi
    $schedules = getSchedules($pdo, $id_dokter);
}

// Data untuk jadwal yang sedang diedit
$editSchedule = null;
if (isset($_GET['edit'])) {
    $editId = $_GET['edit'];
    foreach ($schedules as $schedule) {
        if ($schedule['id'] == $editId) {
            $editSchedule = $schedule;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Jadwal Periksa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
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
        .container {
            margin-top: 30px;
        }
        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .table th {
            background-color: #007BFF;
            color: white;
        }
        .btn-primary {
            background-color: #007BFF;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .form-control:focus {
            border-color: #007BFF;
            box-shadow: 0 0 4px rgba(0, 123, 255, 0.25);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <a href="dashbordDokter.php"><i class="fa fa-home" aria-hidden="true"></i>Beranda</a>
    <a href="updateDokter.php"><i class="fas fa-user-md"></i> pembaharui data Dokter</a>
    <a href="jadwalPeriksa.php"><i class="fas fa-user-injured"></i> input jadwal periksa</a>
    <a href="catatan_kesehatan.php"><i class="fas fa-hospital"></i> memeriksa pasien</a>
    <a href="riwayatPeriksa.php"><i class="fas fa-pills"></i> riwayat pasien</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</nav>

<div class="container">
    <div class="card">
        <div class="card-body">
            <h1 class="text-center">Pengaturan Jadwal Periksa</h1>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?= $error; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success" role="alert">
                    <?= $success; ?>
                </div>
            <?php endif; ?>

            <!-- Form Tambah/Update Jadwal -->
            <form method="POST" action="">
                <input type="hidden" name="action" value="<?= $editSchedule ? 'update' : 'add'; ?>">
                <?php if ($editSchedule): ?>
                    <input type="hidden" name="id" value="<?= $editSchedule['id']; ?>">
                <?php endif; ?>
                <div class="mb-3">
                    <label for="hari" class="form-label">Hari</label>
                    <input type="text" id="hari" name="hari" class="form-control" value="<?= $editSchedule['hari'] ?? ''; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="jam_mulai" class="form-label">Jam Mulai</label>
                    <input type="time" id="jam_mulai" name="jam_mulai" class="form-control" value="<?= $editSchedule['jam_mulai'] ?? ''; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="jam_selesai" class="form-label">Jam Selesai</label>
                    <input type="time" id="jam_selesai" name="jam_selesai" class="form-control" value="<?= $editSchedule['jam_selesai'] ?? ''; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="statues" class="form-label">Status</label>
                    <select id="statues" name="statues" class="form-select" required>
                        <option value="1" <?= isset($editSchedule) && $editSchedule['statues'] == 'Aktif' ? 'selected' : ''; ?>>Aktif</option>
                        <option value="0" <?= isset($editSchedule) && $editSchedule['statues'] == 'Non-Aktif' ? 'selected' : ''; ?>>Non-Aktif</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100"><?= $editSchedule ? 'Update Jadwal' : 'Tambahkan Jadwal'; ?></button>
            </form>

            <!-- Tabel Jadwal -->
            <table class="table table-hover mt-4">
                <thead>
                    <tr>
                        <th>Hari</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($schedules as $schedule): ?>
                        <tr>
                            <td><?= htmlspecialchars($schedule['hari']); ?></td>
                            <td><?= htmlspecialchars($schedule['jam_mulai']); ?></td>
                            <td><?= htmlspecialchars($schedule['jam_selesai']); ?></td>
                            <td><?= htmlspecialchars($schedule['statues']); ?></td>
                            <td>
                                <a href="?edit=<?= $schedule['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                <form method="POST" action="" style="display:inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $schedule['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
