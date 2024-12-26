<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background: linear-gradient(135deg, #007BFF, #0056b3);
            padding: 20px 20px;
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
        h1 {
            color: #333;
            margin-bottom: 30px;
        }
        .box-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }
        .box {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 200px;
            text-align: center;
            margin: 10px;
        }
        .box h3 {
            margin: 0;
            font-size: 2em;
            color: #007BFF;
        }
        .box p {
            margin: 10px 0 0;
            font-weight: bold;
        }
        .chart-container {
            margin: 0 auto;
            width: 80%;
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
    <h1>Selamat Datang di Dashboard Admin</h1>

    <div class="box-container">
        <div class="box">
            <h3>50</h3>
            <p>Total Pasien</p>
        </div>
        <div class="box">
            <h3>100</h3>
            <p>Total Obat</p>
        </div>
    </div>

    <div class="chart-container">
        <canvas id="dataChart"></canvas>
    </div>
</div>

<script>
    // Dummy data untuk diagram
    const data = {
        labels: ['Pasien','Obat'],
        datasets: [{
            label: 'Jumlah Data',
            data: [10, 50, 5, 100],
            backgroundColor: ['#007BFF', '#28A745', '#FFC107', '#DC3545'],
            borderColor: ['#0056b3', '#1e7e34', '#d39e00', '#c82333'],
            borderWidth: 1
        }]
    };

    // Konfigurasi diagram
    const config = {
        type: 'bar',
        data: data,
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    };

    // Render diagram
    const ctx = document.getElementById('dataChart').getContext('2d');
    new Chart(ctx, config);
</script>

</body>
</html>
