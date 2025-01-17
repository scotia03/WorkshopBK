<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #007BFF, #6A11CB);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #333;
        }
        .login-container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 100%;
            max-width: 400px;
            transition: transform 0.3s ease;
        }
        .login-container:hover {
            transform: translateY(-5px);
        }
        .login-container h2 {
            margin-bottom: 20px;
            font-size: 1.8em;
            color: #007BFF;
        }
        .login-container .icon {
            font-size: 3em;
            color: #007BFF;
            margin-bottom: 20px;
        }
        .login-container input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
            box-sizing: border-box;
        }
        .login-container button {
            width: 100%;
            padding: 12px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .login-container button:hover {
            background-color: #0056b3;
        }
        .login-container .error {
            color: red;
            margin-bottom: 15px;
        }
        .login-container .register-link, .role-switch {
            margin-top: 15px;
            font-size: 0.9em;
        }
        .login-container a {
            color: #007BFF;
            text-decoration: none;
        }
        .login-container a:hover {
            text-decoration: underline;
        }
        .role-switch {
            display: flex;
            justify-content: space-around;
            margin-bottom: 15px;
        }
        .role-switch button {
            background-color: #28a745;
            padding: 10px;
            font-size: 0.9em;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .role-switch button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="icon">
        <i class="fas fa-user-shield"></i>
    </div>
    <h2>Login</h2>

    <?php
    if (isset($_SESSION['error'])) {
        echo '<div class="error">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
    ?>

    <form action="process_login.php" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

    <div class="role-switch">
        <a href="loginDokter.php"><button type="button"><i class="fas fa-user-md"></i> Login sebagai Dokter</button></a>
    </div>

    <div class="register-link">
        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
</div>

</body>
</html>
