<?php

session_start();

require_once "config/Database.php";
require_once "classes/Service.php";

$database = new Database();
$pdo = $database->connect();

$serviceObj = new Service($pdo);
$services = $serviceObj->getPopular(8);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>FixHub - Home Services</title>

    <link rel="stylesheet" href="assets/style.css">

    <style>

        .user-welcome {
            color: #ffffff;
            font-weight: 600;
            font-size: 14px;
            margin-right: 15px;
        }

        .logout-btn {
            color: #ef4444;
            text-decoration: none;
            font-weight: 600;
            padding: 8px 16px;
            border: 1px solid rgba(239, 68, 68, 0.4);
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background-color: rgba(239, 68, 68, 0.1);
        }

        .service-card {
            cursor: pointer;
        }

    </style>

</head>

<body>

<header class="navbar">

    <a href="#home" class="logo">
        Fix<span>Hub</span>
    </a>

    <nav>
        <a href="#home">Home</a>
        <a href="#services">Services</a>
        <a href="#about">About</a>
        <a href="#contact">Contact</a>
    </nav>

    <div class="nav-buttons">

        <?php if (isset($_SESSION['user_id'])): ?>

            <span class="user-welcome">
                Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
            </span>

            <a href="logout.php" class="logout-btn">
                Logout
            </a>

        <?php else: ?>

            <a href="login.php" class="login-btn">
                Login
            </a>

            <a href="register.php" class="register-btn">
                Register
            </a>

        <?php endif; ?>

    </div>

</header>

<section class="hero" id="home">

    <div class="hero-content">

        <div class="hero-badge">
            <span></span>
            Trusted Home Services
        </div>

        <p class="small-title">
            YOUR HOME, OUR RESPONSIBILITY
        </p>

        <h1>
            Find Trusted
            <span>Home Services</span>
            Easily
        </h1>

        <p class="hero-text">
            FixHub connects you with trusted service providers
            for plumbing, electrical work, cleaning, maintenance
            and more.
        </p>

        <div class="hero-buttons">

            <a href="#services" class="primary-btn">
                Explore Services
                <span>→</span>
            </a>

            <a href="register.php" class="secondary-btn">
                Get Started
            </a>

        </div>

        <div class="hero-stats">

            <div>
                <strong>6+</strong>
                <span>Services</span>
            </div>

            <div>
                <strong>24/7</strong>
                <span>Support</span>
            </div>

            <div>
                <strong>100%</strong>
                <span>Easy Booking</span>
            </div>

        </div>

    </div>

    <div class="hero-visual">

        <div class="tool-orbit orbit-one"></div>
        <div class="tool-orbit orbit-two"></div>

        <div class="tool tool-1">
            <div class="tool-shape wrench">🔧</div>
        </div>

        <div class="tool tool-2">
            <div class="tool-shape hammer">🔨</div>
        </div>

        <div class="tool tool-3">
            <div class="tool-shape screwdriver">🪛</div>
        </div>

        <div class="tool tool-4">
            <div class="tool-shape bolt">⚡</div>
        </div>

        <div class="tool tool-5">
            <div class="tool-shape screw">🔩</div>
        </div>

        <div class="tool tool-6">
            <div class="tool-shape gear">⚙️</div>
        </div>

        <span class="particle particle-1"></span>
        <span class="particle particle-2"></span>
        <span class="particle particle-3"></span>
        <span class="particle particle-4"></span>
        <span class="particle particle-5"></span>
        <span class="particle particle-6"></span>

        <div class="toolbox-wrapper">

            <div class="toolbox-glow"></div>

            <div class="toolbox">

                <div class="toolbox-handle"></div>

                <div class="toolbox-top">
                    <div class="toolbox-lock"></div>
                </div>

                <div class="toolbox-body">

                    <div class="toolbox-line"></div>

                    <div class="toolbox-label">
                        FIX<span>HUB</span>
                    </div>

                </div>

            </div>

            <div class="toolbox-shadow"></div>

        </div>

        <div class="service-floating-card">

            <div class="service-card-icon">
                ✓
            </div>

            <div>
                <strong>Service Ready</strong>
                <small>Professional near you</small>
            </div>

        </div>

    </div>

</section>

<section class="services" id="services">

    <div class="section-title reveal">

        <p>WHAT WE OFFER</p>

        <h2>
            Popular <span>Services</span>
        </h2>

        <span>
            Professional services whenever you need them.
        </span>

    </div>

    <div class="service-grid">

        <?php if (count($services) > 0) { ?>

            <?php foreach ($services as $index => $item) { ?>

                <div
                    class="service-card reveal"
                    onclick="window.location.href='service_details.php?id=<?php echo $item['id']; ?>'"
                >

                    <div class="service-icon">
                        <?php echo htmlspecialchars($item['icon']); ?>
                    </div>

                    <div class="service-number">
                        <?php echo str_pad($index + 1, 2, "0", STR_PAD_LEFT); ?>
                    </div>

                    <h3>
                        <?php echo htmlspecialchars($item['name']); ?>
                    </h3>

                    <p>
                        <?php echo htmlspecialchars($item['description']); ?>
                    </p>

                    <a href="service_details.php?id=<?php echo $item['id']; ?>">
                        View Service <span>→</span>
                    </a>

                </div>

            <?php } ?>

        <?php } else { ?>

            <p>
                No services available at the moment.
            </p>

        <?php } ?>

    </div>

</section>

<section class="about" id="about">

    <div class="about-content reveal">

        <p class="small-title">
            WHY FIXHUB?
        </p>

        <h2>
            One Platform.
            <span>Many Solutions.</span>
        </h2>

    </div>

    <div class="about-text reveal">

        <p>
            FixHub makes it easier to find trusted professionals,
            compare services and book the help you need.
        </p>

        <p>
            Whether it's a small repair or a complete home service,
            FixHub helps you get it done.
        </p>

    </div>

</section>

<section class="cta reveal">

    <div class="cta-decoration"></div>

    <p>READY WHEN YOU ARE</p>

    <h2>
        Need help with your home?
    </h2>

    <span>
        Create your account and find the right service provider.
    </span>

    <a href="register.php">
        Create Your Account
        <strong>→</strong>
    </a>

</section>

<footer id="contact">

    <div class="footer-content">

        <div>

            <a href="#home" class="logo">
                Fix<span>Hub</span>
            </a>

            <p>
                Making home services easier.
            </p>

        </div>

        <div class="footer-links">

            <a href="#home">Home</a>
            <a href="#services">Services</a>
            <a href="#about">About</a>

        </div>

    </div>

    <div class="footer-bottom">
        © 2026 FixHub. All rights reserved.
    </div>

</footer>

<script src="assets/main.js"></script>

</body>

</html>
