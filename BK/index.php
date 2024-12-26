<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Temu Janji Pasien - Dokter untuk Bimbingan Karir 2023 Bidang Web">
    <meta name="keywords" content="Poliklinik, Temu Janji, Pasien, Dokter, Bimbingan Karir">
    <title>Poliklinik - Sistem Temu Janji</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="dist/css/custom.css"> <!-- Tambahkan file CSS kustom jika diperlukan -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
        }
        .header {
            background-color: #00488b;
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        .section {
            padding: 40px 20px;
            text-align: center;
        }
        .card {
            border: none;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-10px);
        }
        .icon {
            font-size: 50px;
            color: #007bff;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <h1>Sistem Temu Janji<br>Pasien - Dokter</h1>
        <p>Bimbingan Karir 2023 Bidang Web</p>
    </div>

    <!-- Section Pilihan -->
    <div class="section container">
        <div class="row">
            <!-- Registrasi Pasien -->
            <div class="col-md-6 mb-4">
                <div class="card p-4">
                    <div class="icon">
                        <i class="fas fa-user-plus" aria-hidden="true"></i>
                    </div>
                    <h3><strong>Registrasi Sebagai Pasien</strong></h3>
                    <p>Apabila Anda adalah seorang Pasien, silahkan Registrasi terlebih dahulu untuk melakukan pendaftaran sebagai Pasien!</p>
                    <a href="admin_sistem/loginPasien.php" class="btn btn-primary">Klik Link Berikut →</a>
                </div>
            </div>

            <!-- Login Dokter -->
            <div class="col-md-6 mb-4">
                <div class="card p-4">
                    <div class="icon">
                        <i class="fas fa-user-md" aria-hidden="true"></i>
                    </div>
                    <h3><strong>Login Sebagai Dokter</strong></h3>
                    <p>Apabila Anda adalah seorang Dokter, silahkan Login terlebih dahulu untuk memulai melayani Pasien!</p>
                    <a href="admin_sistem/loginAdmin.php" class="btn btn-primary">Klik Link Berikut →</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>