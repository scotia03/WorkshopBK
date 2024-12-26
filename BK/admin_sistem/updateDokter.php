<?php
// Mengimpor koneksi database PDO
require_once 'db.php';

// Variabel untuk menampilkan pesan
$error = '';
$success = '';

// Mengecek jika form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mendapatkan data dari form
    $nama = $_POST['nama'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Enkripsi password
    $id_poli = isset($_POST['id_poli']) ? $_POST['id_poli'] : null; // Menggunakan isset untuk memastikan id_poli ada

    // Validasi input
    if (empty($nama) || empty($password) || empty($id_poli)) {
        $error = 'Semua kolom harus diisi.';
    } else {
        try {
            // Menggunakan prepared statement untuk menghindari SQL injection
            $stmt = $pdo->prepare("UPDATE dokter SET nama = ?, password = ?, id_poli = ? WHERE nama = ?");
            $stmt->execute([$nama, $password, $id_poli, $nama]);

            $success = "Data berhasil diperbaharui!";
        } catch (PDOException $e) {
            $error = "Gagal memperbaharui data. Error: " . $e->getMessage();
        }
    }
}

// Mendapatkan daftar poli dari tabel poli
try {
    $stmt = $pdo->query("SELECT * FROM poli");
    $polies = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Gagal mengambil data poli. Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profil Dokter</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #e0f7fa, #80deea);
            margin: 0;
            padding: 0;
            color: #333;
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
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            color: #007BFF;
            margin-bottom: 30px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        form label {
            font-size: 1rem;
            font-weight: bold;
        }

        form input, form select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        form input:focus, form select:focus {
            border-color: #007BFF;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            outline: none;
        }

        form button {
            padding: 12px;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        form button:hover {
            background: #0056b3;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }

        .alert.error {
            background: #f8d7da;
            color: #721c24;
        }

        .alert.success {
            background: #d4edda;
            color: #155724;
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
<div class="container">
    <h1>Update Profil Dokter</h1>

    <!-- Pesan Error atau Sukses -->
    <?php if (!empty($error)): ?>
        <div class="alert error">
            <?= $error; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert success">
            <?= $success; ?>
        </div>
    <?php endif; ?>

    <!-- Formulir Pembaruan Profil -->
    <form method="POST" action="">
        <label for="nama">Nama Dokter:</label>
        <input type="text" id="nama" name="nama" value="<?= isset($dokter['nama']) ? htmlspecialchars($dokter['nama']) : ''; ?>" required>

        <label for="password">Password Baru:</label>
        <input type="password" id="password" name="password" required>

        <label for="id_poli">Poli:</label>
        <select name="id_poli" id="id_poli" required>
            <option value="">Pilih Poli</option>
            <?php foreach ($polies as $poli): ?>
                <option value="<?= $poli['id']; ?>" <?= isset($dokter['id_poli']) && $dokter['id_poli'] == $poli['id_poli'] ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($poli['nama_poli']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Update</button>
    </form>
</div>

</body>
</html>
