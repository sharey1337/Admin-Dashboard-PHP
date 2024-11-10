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

$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'dark';
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?php echo $theme; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - AxePrime</title>
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
            display: flex;
            transition: background-color 0.3s ease;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background: var(--bg-secondary);
            backdrop-filter: blur(10px);
            border-right: 1px solid var(--border-color);
            padding: 20px;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px;
            margin-bottom: 30px;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .logo-icon::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.2));
            transform: translateX(-100%);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% { transform: translateX(-100%); }
            20% { transform: translateX(100%); }
            100% { transform: translateX(100%); }
        }

        .brand-text {
            font-size: 24px;
            font-weight: 700;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 0.5px;
        }

        .nav-menu {
            list-style: none;
            padding: 0 10px;
        }

        .nav-item {
            margin-bottom: 8px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: 12px;
            font-weight: 500;
            letter-spacing: 0.3px;
            border: 1px solid transparent;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background: rgba(59, 130, 246, 0.1);
            border-color: rgba(59, 130, 246, 0.2);
            color: var(--primary-color);
        }

        .nav-link.active {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(139, 92, 246, 0.1));
            border: 1px solid rgba(59, 130, 246, 0.2);
            color: var(--primary-color);
        }

        .nav-link i {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            margin-right: 12px;
            transition: all 0.3s ease;
        }

        .nav-link:hover i,
        .nav-link.active i {
            background: rgba(59, 130, 246, 0.2);
            color: var(--primary-color);
        }
            .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 20px;
            transition: all 0.3s ease;
        }

/* Header Styles */
.header {
    background: var(--bg-secondary);
    backdrop-filter: blur(10px);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    position: relative;
    z-index: 1000; /* Header için de z-index ekledik */
}

        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 16px;
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

        /* User Dropdown Styles */
        .user-dropdown {
            position: relative;
            z-index: 9999;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            background: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .user-profile:hover {
            background: rgba(59, 130, 246, 0.1);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: white;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            color: var(--text-primary);
        }

        .user-email {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .dropdown-icon {
            color: var(--text-secondary);
            font-size: 12px;
            transition: transform 0.3s ease;
        }

        .user-dropdown.active .dropdown-icon {
            transform: rotate(180deg);
        }

        .dropdown-menu {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 220px;
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 8px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.3s ease;
            z-index: 9999; /* Z-index değerini artırdık */
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .user-dropdown.active .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: var(--text-primary);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background: rgba(59, 130, 246, 0.1);
        }

        .dropdown-item i {
            font-size: 16px;
            color: var(--text-secondary);
        }

        .dropdown-divider {
            height: 1px;
            background: var(--border-color);
            margin: 8px 0;
        }

        .text-danger {
            color: var(--danger-color) !important;
        }

        .text-danger i {
            color: var(--danger-color) !important;
        }

        .text-danger:hover {
            background: rgba(239, 68, 68, 0.1) !important;
        }
                /* Stats Grid */
                .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 24px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 16px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 14px;
            color: var(--text-secondary);
        }

        .stat-trend {
            position: absolute;
            top: 24px;
            right: 24px;
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 14px;
            font-weight: 500;
        }

        .trend-up {
            color: var(--success-color);
        }

        .trend-down {
            color: var(--danger-color);
        }

        /* Charts Grid */
        .charts-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .chart-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 24px;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .chart-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .chart-actions {
            display: flex;
            gap: 8px;
        }

        .chart-button {
            padding: 8px 16px;
            border-radius: 8px;
            background: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .chart-button:hover,
        .chart-button.active {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .charts-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-menu {
                display: block;
            }

            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
        }

        /* Mobile Menu Button */
        .mobile-menu {
            display: none;
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 24px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="brand-logo">
            <div class="logo-icon">
                <i class="fas fa-bolt"></i>
            </div>
            <span class="brand-text">AxePrime</span>
        </div>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="#" class="nav-link active">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-chart-bar"></i>
                    <span>İstatistikler</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-users"></i>
                    <span>Kullanıcılar</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Siparişler</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-box"></i>
                    <span>Ürünler</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-cog"></i>
                    <span>Ayarlar</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <button class="mobile-menu" id="mobileMenu">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title">Dashboard</h1>
            </div>
            <div class="header-actions">
                <button class="theme-toggle" id="themeToggle">
                    <i class="fas fa-moon"></i>
                </button>
                <div class="user-dropdown" id="userDropdown">
                    <div class="user-profile">
                        <div class="user-avatar">
                            <?php echo strtoupper(substr($firstname, 0, 1)); ?>
                        </div>
                        <div class="user-info">
                            <div class="user-name"><?php echo $firstname . ' ' . $lastname; ?></div>
                            <div class="user-email"><?php echo $email; ?></div>
                        </div>
                        <i class="fas fa-chevron-down dropdown-icon"></i>
                    </div>
                    <div class="dropdown-menu">
                        <a href="profile.php" class="dropdown-item">
                            <i class="fas fa-user"></i>
                            Profil
                        </a>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-cog"></i>
                            Ayarlar
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="auth/logout.php" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt"></i>
                            Çıkış Yap
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: var(--primary-color);">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-value">1,234</div>
                <div class="stat-label">Toplam Kullanıcı</div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-arrow-up"></i> 12%
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success-color);">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-value">$45.2K</div>
                <div class="stat-label">Toplam Gelir</div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-arrow-up"></i> 8%
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(249, 115, 22, 0.1); color: var(--warning-color);">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-value">856</div>
                <div class="stat-label">Siparişler</div>
                <div class="stat-trend trend-down">
                    <i class="fas fa-arrow-down"></i> 3%
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(139, 92, 246, 0.1); color: var(--secondary-color);">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-value">4.8</div>
                <div class="stat-label">Müşteri Puanı</div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-arrow-up"></i> 2%
                </div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="charts-grid">
            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-title">Gelir Grafiği</div>
                    <div class="chart-actions">
                        <button class="chart-button active">Günlük</button>
                        <button class="chart-button">Haftalık</button>
                        <button class="chart-button">Aylık</button>
                    </div>
                </div>
                <div id="revenueChart"></div>
            </div>
            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-title">Kullanıcı Dağılımı</div>
                </div>
                <div id="userDistributionChart"></div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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

        const mobileMenu = document.getElementById('mobileMenu');
        const sidebar = document.getElementById('sidebar');

        mobileMenu.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

        const userDropdown = document.getElementById('userDropdown');

        userDropdown.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('active');
        });

        document.addEventListener('click', (e) => {
            if (!userDropdown.contains(e.target)) {
                userDropdown.classList.remove('active');
            }
        });

        const revenueOptions = {
            series: [{
                name: 'Gelir',
                data: [31000, 40000, 28000, 51000, 42000, 109000, 100000]
            }],
            chart: {
                type: 'area',
                height: 350,
                toolbar: {
                    show: false
                },
                background: 'transparent'
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            colors: ['#3B82F6'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.2,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: ['Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt', 'Paz'],
                labels: {
                    style: {
                        colors: '#888888'
                    }
                },
                axisBorder: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#888888'
                    },
                    formatter: function (value) {
                        return '$' + value.toLocaleString();
                    }
                }
            },
            grid: {
                borderColor: 'rgba(255, 255, 255, 0.1)',
                strokeDashArray: 5,
                xaxis: {
                    lines: {
                        show: true
                    }
                },
                yaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            tooltip: {
                theme: 'dark',
                y: {
                    formatter: function (value) {
                        return '$' + value.toLocaleString();
                    }
                }
            }
        };

        const revenueChart = new ApexCharts(document.querySelector("#revenueChart"), revenueOptions);
        revenueChart.render();

        const userDistributionOptions = {
            series: [44, 55, 13, 43],
            chart: {
                type: 'donut',
                height: 350,
                background: 'transparent'
            },
            labels: ['Premium', 'Standard', 'Basic', 'Trial'],
            colors: ['#3B82F6', '#10B981', '#F97316', '#8B5CF6'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Toplam',
                                formatter: function (w) {
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                position: 'bottom',
                labels: {
                    colors: '#888888'
                }
            },
            tooltip: {
                theme: 'dark'
            }
        };

        const userDistributionChart = new ApexCharts(document.querySelector("#userDistributionChart"), userDistributionOptions);
        userDistributionChart.render();

        const chartButtons = document.querySelectorAll('.chart-button');
        chartButtons.forEach(button => {
            button.addEventListener('click', () => {
                chartButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
            });
        });

        const updateChartsTheme = (theme) => {
            const textColor = theme === 'dark' ? '#888888' : '#64748B';
            const gridColor = theme === 'dark' ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';

            revenueChart.updateOptions({
                xaxis: {
                    labels: {
                        style: {
                            colors: textColor
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: textColor
                        }
                    }
                },
                grid: {
                    borderColor: gridColor
                }
            });

            userDistributionChart.updateOptions({
                legend: {
                    labels: {
                        colors: textColor
                    }
                }
            });
        };

        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.attributeName === 'data-theme') {
                    updateChartsTheme(html.getAttribute('data-theme'));
                }
            });
        });

        observer.observe(html, {
            attributes: true
        });
    </script>
</body>
</html>