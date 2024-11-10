<?php
require_once '../server/baglan.php';

$db = new Database();
$conn = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($confirmPassword)) {
        echo "error_empty";
    } elseif ($password !== $confirmPassword) {
        echo "error_password_match";
    } elseif (strlen($password) < 6) {
        echo "error_password_length";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "error_email";
    } else {
        $result = $db->register($firstname, $lastname, $email, $password);
        if ($result === "success") {
            echo "success";
        } else {
            echo $result;
        }
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol - AxePrime</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background: #111111;
            color: #fff;
        }

        .split-screen {
            display: flex;
            width: 100%;
        }

        .left {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: linear-gradient(135deg, #1E1E1E 0%, #0A0A0A 100%);
            position: relative;
            overflow: hidden;
        }

        .left::before {
            content: '';
            position: absolute;
            width: 1000px;
            height: 1000px;
            background: radial-gradient(circle, #3B82F6 0%, transparent 70%);
            opacity: 0.1;
            top: -50%;
            left: -50%;
            animation: pulse 15s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        .left-content {
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .left-content h1 {
            font-size: 3.5em;
            font-weight: 700;
            margin-bottom: 20px;
            background: linear-gradient(45deg, #3B82F6, #8B5CF6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .left-content p {
            color: #888;
            font-size: 1.1em;
            line-height: 1.6;
            max-width: 400px;
        }

        .right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: #0A0A0A;
        }

        .register-container {
            width: 100%;
            max-width: 400px;
        }

        .register-header {
            margin-bottom: 40px;
        }

        .register-header h2 {
            font-size: 2em;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .register-header p {
            color: #666;
        }

        .input-group {
            margin-bottom: 25px;
            position: relative;
        }

        .input-row {
            display: flex;
            gap: 15px;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            color: #888;
            font-size: 0.9em;
        }

        .input-group input {
            width: 100%;
            padding: 15px;
            background: #1A1A1A;
            border: 2px solid #2A2A2A;
            border-radius: 8px;
            color: #fff;
            font-size: 1em;
            transition: all 0.3s ease;
        }

        .input-group input:focus {
            border-color: #3B82F6;
            background: #1E1E1E;
            outline: none;
        }

        .password-strength {
            margin-top: 8px;
            font-size: 0.85em;
            color: #666;
        }

        .strength-meter {
            height: 4px;
            background: #2A2A2A;
            border-radius: 2px;
            margin-top: 5px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .strength-meter div {
            height: 100%;
            width: 0;
            transition: all 0.3s ease;
        }

        .register-button {
            width: 100%;
            padding: 15px;
            background: #3B82F6;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .register-button:hover {
            background: #2563EB;
            transform: translateY(-2px);
        }

        .register-button.loading {
            background: #2563EB;
            pointer-events: none;
            color: transparent;
        }

        .register-button.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border: 2px solid #fff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .login-link {
            text-align: center;
            margin-top: 30px;
            color: #888;
        }

        .login-link a {
            color: #3B82F6;
            text-decoration: none;
            font-weight: 600;
        }

        /* Bildirim stilleri */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 20px;
            border-radius: 12px;
            background: rgba(0, 0, 0, 0.95);
            color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            gap: 15px;
            transform: translateX(150%);
            transition: transform 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            z-index: 1000;
            backdrop-filter: blur(10px);
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification .icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .notification.success {
            border-left: 4px solid #10B981;
        }

        .notification.success .icon {
            background: rgba(16, 185, 129, 0.2);
            color: #10B981;
        }

        .notification.error {
            border-left: 4px solid #EF4444;
        }

        .notification.error .icon {
            background: rgba(239, 68, 68, 0.2);
            color: #EF4444;
        }

        .notification .content {
            flex: 1;
        }

        .notification h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .notification p {
            font-size: 14px;
            color: #999;
            margin: 0;
        }

        /* Loading overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .loading-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #3B82F6;
            border-top: 4px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .split-screen {
                flex-direction: column;
            }

            .left {
                display: none;
            }

            .right {
                padding: 40px 20px;
            }

            .input-row {
                flex-direction: column;
                gap: 25px;
            }
        }
    </style>
</head>
<body>
    <div class="split-screen">
        <div class="left">
            <div class="left-content">
                <h1>AxePrime</h1>
                <p>Hemen kayıt olun ve dijital deneyiminize başlayın.</p>
            </div>
        </div>
        <div class="right">
            <div class="register-container">
                <div class="register-header">
                    <h2>Hesap Oluştur</h2>
                    <p>Bilgilerinizi girerek hemen kayıt olun</p>
                </div>
                <form method="POST" action="" id="registerForm">
                    <div class="input-row">
                        <div class="input-group">
                            <label for="firstname">Ad</label>
                            <input type="text" id="firstname" name="firstname" required>
                        </div>
                        <div class="input-group">
                            <label for="lastname">Soyad</label>
                            <input type="text" id="lastname" name="lastname" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <label for="email">E-posta</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="input-group">
                        <label for="password">Şifre</label>
                        <input type="password" id="password" name="password" required>
                        <div class="password-strength">
                            <div class="strength-meter">
                                <div id="strengthBar"></div>
                            </div>
                            <span id="strengthText">Şifre gücü</span>
                        </div>
                    </div>
                    <div class="input-group">
                        <label for="confirmPassword">Şifre Tekrar</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" required>
                    </div>
                    <button type="submit" class="register-button">Kayıt Ol</button>
                    <div class="login-link">
                        Zaten hesabınız var mı? <a href="login.php">Giriş Yap</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bildirim elementi -->
    <div class="notification" id="notification">
        <div class="icon">
            <i class="fas fa-check"></i>
        </div>
        <div class="content">
            <h3>Başlık</h3>
            <p>Mesaj içeriği</p>
        </div>
    </div>

    <!-- Loading overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <script>
        function showNotification(type, title, message) {
            const notification = document.getElementById('notification');
            const icon = notification.querySelector('.icon i');
            const titleEl = notification.querySelector('h3');
            const messageEl = notification.querySelector('p');

            if (type === 'success') {
                notification.className = 'notification success';
                icon.className = 'fas fa-check';
            } else {
                notification.className = 'notification error';
                icon.className = 'fas fa-times';
            }

            titleEl.textContent = title;
            messageEl.textContent = message;

            setTimeout(() => notification.classList.add('show'), 100);

            setTimeout(() => {
                notification.classList.remove('show');
            }, 5000);
        }

        document.getElementById('password').addEventListener('input', function(e) {
            const password = e.target.value;
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            
            let strength = 0;
            if(password.match(/[a-z]/)) strength++;
            if(password.match(/[A-Z]/)) strength++;
            if(password.match(/[0-9]/)) strength++;
            if(password.match(/[^a-zA-Z0-9]/)) strength++;
            if(password.length >= 8) strength++;

            const colors = ['#FF4B4B', '#FF4B4B', '#FFA53D', '#C1D82F', '#4BB543'];
            const texts = ['Çok zayıf', 'Zayıf', 'Orta', 'Güçlü', 'Çok güçlü'];

            strengthBar.style.width = `${(strength / 5) * 100}%`;
            strengthBar.style.background = colors[strength - 1] || '#2A2A2A';
            strengthText.textContent = `Şifre gücü: ${texts[strength - 1] || 'Çok zayıf'}`;
        });

        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const button = document.querySelector('.register-button');
            const loadingOverlay = document.getElementById('loadingOverlay');
            
            button.classList.add('loading');
            button.disabled = true;
            loadingOverlay.classList.add('show');

            const formData = new FormData(this);

            try {
                const response = await fetch('', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.text();

                setTimeout(() => {
                    button.classList.remove('loading');
                    button.disabled = false;
                    loadingOverlay.classList.remove('show');

                    if (result === 'success') {
                        showNotification(
                            'success',
                            'Kayıt Başarılı!',
                            'Giriş sayfasına yönlendiriliyorsunuz...'
                        );
                        setTimeout(() => {
                            window.location.href = 'login.php?registered=true';
                        }, 2000);
                    } else {
                        let errorMessage = 'Bir hata oluştu.';
                        
                        switch(result) {
                            case 'error_empty':
                                errorMessage = 'Lütfen tüm alanları doldurun.';
                                break;
                            case 'error_password_match':
                                errorMessage = 'Şifreler eşleşmiyor.';
                                break;
                            case 'error_password_length':
                                errorMessage = 'Şifre en az 6 karakter olmalıdır.';
                                break;
                            case 'error_email':
                                errorMessage = 'Geçerli bir e-posta adresi girin.';
                                break;
                            default:
                                errorMessage = result;
                        }

                        showNotification(
                            'error',
                            'Kayıt Başarısız!',
                            errorMessage
                        );
                    }
                }, 1500);

            } catch (error) {
                button.classList.remove('loading');
                button.disabled = false;
                loadingOverlay.classList.remove('show');
                
                showNotification(
                    'error',
                    'Bağlantı Hatası!',
                    'Lütfen internet bağlantınızı kontrol edin.'
                );
            }
        });

        const inputs = document.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.01)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>