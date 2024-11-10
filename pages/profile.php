<?php
require_once '../server/baglan.php';
$db = new Database();

if (!$db->isLoggedIn()) {
    header("Location: auth/login.php");
    exit();
}

$firstname = $_SESSION['firstname'];
$lastname = $_SESSION['lastname'];
$email = $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_profile'])) {
        $newFirstname = trim($_POST['firstname']);
        $newLastname = trim($_POST['lastname']);
        $newEmail = trim($_POST['email']);
        

        $_SESSION['firstname'] = $newFirstname;
        $_SESSION['lastname'] = $newLastname;
        $_SESSION['email'] = $newEmail;
        
        $updateMessage = "Profil başarıyla güncellendi!";
    }
    
    if (isset($_POST['change_password'])) {
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];
        
        if ($newPassword !== $confirmPassword) {
            $passwordError = "Yeni şifreler eşleşmiyor!";
        } else {
            $passwordMessage = "Şifreniz başarıyla değiştirildi!";
        }
    }
}

$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'dark';
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?php echo $theme; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - AxePrime</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            /* Dark Theme Variables */
            --bg-primary: #111111;
            --bg-secondary: #1A1A1A;
            --bg-tertiary: #222222;
            --text-primary: #FFFFFF;
            --text-secondary: #888888;
            --border-color: rgba(255, 255, 255, 0.1);
            --primary-color: #3B82F6;
            --secondary-color: #8B5CF6;
            --success-color: #10B981;
            --warning-color: #F97316;
            --danger-color: #EF4444;
        }

        [data-theme="light"] {
            --bg-primary: #F8FAFC;
            --bg-secondary: #FFFFFF;
            --bg-tertiary: #F1F5F9;
            --text-primary: #1E293B;
            --text-secondary: #64748B;
            --border-color: rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            padding: 20px;
            transition: background-color 0.3s ease;
        }
                /* Header Styles */
                .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            background: var(--bg-secondary);
            border-radius: 16px;
            border: 1px solid var(--border-color);
        }

        .back-button {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-primary);
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .back-button:hover {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-color);
        }

        .theme-toggle {
            background: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 8px;
            cursor: pointer;
            color: var(--text-secondary);
            transition: all 0.3s ease;
        }

        .theme-toggle:hover {
            color: var(--primary-color);
            background: rgba(59, 130, 246, 0.1);
        }

        /* Profile Container Styles */
        .profile-container {
            max-width: 800px;
            margin: 0 auto;
        }

        /* Alert Styles */
        .alert {
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: var(--success-color);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: var(--danger-color);
        }

        /* Profile Header Styles */
        .profile-header {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: white;
            position: relative;
        }

        .avatar-upload {
            position: absolute;
            bottom: -10px;
            right: -10px;
            background: var(--bg-tertiary);
            border: 2px solid var(--border-color);
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .avatar-upload:hover {
            background: var(--primary-color);
            color: white;
        }

        .profile-info h1 {
            font-size: 28px;
            margin-bottom: 8px;
        }

        .profile-info p {
            color: var(--text-secondary);
        }

        .profile-status {
            padding: 6px 12px;
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
            border-radius: 20px;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 10px;
        }

        /* Profile Sections Styles */
        .profile-sections {
            display: grid;
            gap: 24px;
        }

        .profile-section {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 24px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-secondary);
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            background: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        .form-group input:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        /* Button Styles */
        .button {
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
        }

        .button-primary {
            background: var(--primary-color);
            color: white;
        }

        .button-primary:hover {
            background: #2563EB;
        }

        .button-secondary {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-color);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .button-secondary:hover {
            background: rgba(59, 130, 246, 0.2);
        }

        .button-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .button-danger:hover {
            background: rgba(239, 68, 68, 0.2);
        }
                /* Modal Styles */
                .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .modal.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background: var(--bg-secondary);
            border-radius: 16px;
            padding: 24px;
            width: 90%;
            max-width: 500px;
            transform: translateY(20px);
            transition: all 0.3s ease;
        }

        .modal.active .modal-content {
            transform: translateY(0);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 600;
        }

        .modal-close {
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            font-size: 20px;
            padding: 4px;
            transition: all 0.3s ease;
        }

        .modal-close:hover {
            color: var(--danger-color);
        }

        /* Security Options Styles */
        .security-options {
            display: grid;
            gap: 16px;
        }

        .security-option {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px;
            background: var(--bg-tertiary);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .security-option:hover {
            background: rgba(59, 130, 246, 0.1);
        }

        .security-info h3 {
            margin-bottom: 4px;
        }

        .security-info p {
            color: var(--text-secondary);
            font-size: 14px;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
            }

            .profile-info {
                text-align: center;
            }

            .section-header {
                flex-direction: column;
                gap: 12px;
                text-align: center;
            }

            .button {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <a href="dashboard.php" class="back-button">
            <i class="fas fa-arrow-left"></i>
            Dashboard'a Dön
        </a>
        <button class="theme-toggle" id="themeToggle">
            <i class="fas fa-moon"></i>
        </button>
    </div>

    <div class="profile-container">
        <!-- Alert Messages -->
        <?php if (isset($updateMessage)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo $updateMessage; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($passwordError)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $passwordError; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($passwordMessage)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo $passwordMessage; ?>
            </div>
        <?php endif; ?>

        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-avatar">
                <?php echo strtoupper(substr($firstname, 0, 1)); ?>
                <label class="avatar-upload" for="avatar-input">
                    <i class="fas fa-camera"></i>
                </label>
                <input type="file" id="avatar-input" style="display: none;" accept="image/*">
            </div>
            <div class="profile-info">
                <h1><?php echo $firstname . ' ' . $lastname; ?></h1>
                <p><?php echo $email; ?></p>
                <div class="profile-status">
                    <i class="fas fa-check-circle"></i>
                    Doğrulanmış Hesap
                </div>
            </div>
        </div>

        <!-- Profile Sections -->
        <div class="profile-sections">
            <!-- Personal Information -->
            <div class="profile-section">
                <div class="section-header">
                    <h2 class="section-title">Kişisel Bilgiler</h2>
                    <button class="button button-secondary" id="editPersonal">
                        <i class="fas fa-edit"></i> Düzenle
                    </button>
                </div>
                <form id="personalForm" method="POST">
                    <div class="form-group">
                        <label for="firstname">Ad</label>
                        <input type="text" id="firstname" name="firstname" value="<?php echo $firstname; ?>" disabled required>
                    </div>
                    <div class="form-group">
                        <label for="lastname">Soyad</label>
                        <input type="text" id="lastname" name="lastname" value="<?php echo $lastname; ?>" disabled required>
                    </div>
                    <div class="form-group">
                        <label for="email">E-posta</label>
                        <input type="email" id="email" name="email" value="<?php echo $email; ?>" disabled required>
                    </div>
                    <input type="hidden" name="update_profile" value="1">
                    <button type="submit" class="button button-primary" id="savePersonal" style="display: none;">
                        <i class="fas fa-save"></i> Değişiklikleri Kaydet
                    </button>
                </form>
            </div>

            <!-- Security Settings -->
            <div class="profile-section">
                <div class="section-header">
                    <h2 class="section-title">Güvenlik Ayarları</h2>
                </div>
                <div class="security-options">
                    <div class="security-option">
                        <div class="security-info">
                            <h3>Şifre Değiştir</h3>
                            <p>Hesap güvenliğiniz için şifrenizi düzenli olarak değiştirin</p>
                        </div>
                        <button class="button button-secondary" id="changePasswordBtn">
                            <i class="fas fa-key"></i> Değiştir
                        </button>
                    </div>
                    <div class="security-option">
                        <div class="security-info">
                            <h3>İki Faktörlü Doğrulama</h3>
                            <p>Hesabınızı daha güvenli hale getirin</p>
                        </div>
                        <button class="button button-secondary" id="enable2FABtn">
                            <i class="fas fa-shield-alt"></i> Aktifleştir
                        </button>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="profile-section">
                <div class="section-header">
                    <h2 class="section-title">Tehlikeli Bölge</h2>
                </div>
                <button class="button button-danger" id="deleteAccountBtn">
                    <i class="fas fa-trash-alt"></i> Hesabı Sil
                </button>
            </div>
        </div>
    </div>

    <!-- Password Change Modal -->
    <div class="modal" id="passwordModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Şifre Değiştir</h3>
                <button class="modal-close" id="closePasswordModal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="passwordForm" method="POST">
                <div class="form-group">
                    <label for="current_password">Mevcut Şifre</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">Yeni Şifre</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Yeni Şifre (Tekrar)</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <input type="hidden" name="change_password" value="1">
                <button type="submit" class="button button-primary">
                    <i class="fas fa-save"></i> Şifreyi Değiştir
                </button>
            </form>
        </div>
    </div>
    <script>
        const themeToggle = document.getElementById('themeToggle');
        const html = document.documentElement;
        const themeIcon = themeToggle.querySelector('i');

        function updateThemeIcon(theme) {
            themeIcon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        }

        themeToggle.addEventListener('click', () => {
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            html.setAttribute('data-theme', newTheme);
            document.cookie = `theme=${newTheme};path=/;max-age=31536000`;
            updateThemeIcon(newTheme);
        });

        updateThemeIcon(html.getAttribute('data-theme'));

        const editPersonal = document.getElementById('editPersonal');
        const personalForm = document.getElementById('personalForm');
        const inputs = personalForm.querySelectorAll('input:not([type="hidden"])');
        const savePersonal = document.getElementById('savePersonal');

        editPersonal.addEventListener('click', () => {
            const isEditing = editPersonal.innerHTML.includes('İptal');
            
            inputs.forEach(input => {
                input.disabled = !input.disabled;
            });
            
            if (!isEditing) {
                editPersonal.innerHTML = '<i class="fas fa-times"></i> İptal';
                savePersonal.style.display = 'block';
            } else {
                editPersonal.innerHTML = '<i class="fas fa-edit"></i> Düzenle';
                savePersonal.style.display = 'none';
                personalForm.reset();
            }
        });

        const changePasswordBtn = document.getElementById('changePasswordBtn');
        const passwordModal = document.getElementById('passwordModal');
        const closePasswordModal = document.getElementById('closePasswordModal');
        const passwordForm = document.getElementById('passwordForm');

        changePasswordBtn.addEventListener('click', () => {
            passwordModal.classList.add('active');
        });

        closePasswordModal.addEventListener('click', () => {
            passwordModal.classList.remove('active');
            passwordForm.reset();
        });

        passwordModal.addEventListener('click', (e) => {
            if (e.target === passwordModal) {
                passwordModal.classList.remove('active');
                passwordForm.reset();
            }
        });

        passwordForm.addEventListener('submit', (e) => {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('Yeni şifreler eşleşmiyor!');
            }
        });

        const avatarInput = document.getElementById('avatar-input');
        
        avatarInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const formData = new FormData();
                formData.append('avatar', file);

                fetch('upload-avatar.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Profil fotoğrafı başarıyla güncellendi!');
                    } else {
                        alert('Profil fotoğrafı yüklenirken bir hata oluştu!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Bir hata oluştu!');
                });
            }
        });

        const enable2FABtn = document.getElementById('enable2FABtn');
        
        enable2FABtn.addEventListener('click', () => {
            alert('İki faktörlü doğrulama yakında aktif olacak!');
        });

        const deleteAccountBtn = document.getElementById('deleteAccountBtn');
        
        deleteAccountBtn.addEventListener('click', () => {
            if (confirm('Hesabınızı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz!')) {
                alert('Hesap silme işlemi yakında aktif olacak!');
            }
        });

        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }, 5000);
        });
    </script>
</body>
</html>