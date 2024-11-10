<?php
require_once '../../server/baglan.php';

$db = new Database();

if (!$db->isLoggedIn()) {
    header("Location: ../../auth/login.php");
    exit();
}

$db->logout();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Çıkış Yapılıyor - AxePrime</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #111111;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .background {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background: linear-gradient(45deg, #1a1a1a, #0A0A0A);
            z-index: -1;
        }

        .light-effect {
            position: absolute;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            animation: pulse 4s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.2); opacity: 0.3; }
            100% { transform: scale(1); opacity: 0.5; }
        }

        .logout-container {
            text-align: center;
            padding: 40px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            max-width: 400px;
            width: 90%;
            position: relative;
            z-index: 1;
        }

        .logout-icon {
            width: 80px;
            height: 80px;
            background: rgba(59, 130, 246, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: #3B82F6;
            font-size: 32px;
            animation: iconSpin 1s ease-in-out;
        }

        @keyframes iconSpin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        h1 {
            font-size: 24px;
            margin-bottom: 15px;
            color: #fff;
        }

        p {
            color: #888;
            margin-bottom: 25px;
            line-height: 1.6;
        }

        .loading {
            width: 40px;
            height: 40px;
            border: 3px solid rgba(59, 130, 246, 0.3);
            border-top: 3px solid #3B82F6;
            border-radius: 50%;
            margin: 0 auto 20px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .login-link {
            display: inline-block;
            padding: 12px 30px;
            background: #3B82F6;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeIn 0.5s ease forwards;
            animation-delay: 2s;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-link:hover {
            background: #2563EB;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="background"></div>
    <div class="light-effect" style="top: 20%; left: 30%;"></div>
    <div class="light-effect" style="bottom: 20%; right: 30%;"></div>
    
    <div class="logout-container">
        <div class="logout-icon">
            <i class="fas fa-sign-out-alt"></i>
        </div>
        <h1>Çıkış Yapılıyor</h1>
        <p>Oturumunuz güvenli bir şekilde sonlandırılıyor...</p>
        <div class="loading"></div>
        <a href="login.php" class="login-link">Giriş Yap</a>
    </div>

    <script>
        setTimeout(() => {
            window.location.href = '../../auth/login.php';
        }, 2000);
    </script>
</body>
</html>