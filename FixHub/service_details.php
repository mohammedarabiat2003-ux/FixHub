<?php

session_start();

require_once "config/Database.php";
require_once "classes/Service.php";

$database = new Database();
$pdo = $database->connect();

$service = new Service($pdo);

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

$item = $service->getById($id);

if (!$item) {
    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo htmlspecialchars($item['name']); ?> - FixHub</title>

    <link rel="stylesheet" href="assets/style.css">

    <style>

        .service-details {
            min-height: 100vh;
            padding: 120px 8% 80px;
            background: #111111;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .details-container {
            width: 100%;
            max-width: 1100px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: #1b1b1b;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
        }

        .details-image {
            min-height: 500px;
            background: #222;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .details-image img {
            width: 100%;
            height: 100%;
            min-height: 500px;
            object-fit: cover;
        }

        .no-image {
            font-size: 80px;
            color: #f97316;
        }

        .details-content {
            padding: 55px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .details-icon {
            width: 65px;
            height: 65px;
            background: rgba(249, 115, 22, 0.12);
            border: 1px solid rgba(249, 115, 22, 0.3);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin-bottom: 25px;
        }

        .details-content h1 {
            color: white;
            font-size: 42px;
            margin: 0 0 20px;
        }

        .details-content p {
            color: #aaa;
            line-height: 1.8;
            font-size: 16px;
            margin-bottom: 35px;
        }

        .details-label {
            color: #f97316;
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }

        .details-buttons {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .book-btn {
            display: inline-block;
            background: #f97316;
            color: white;
            text-decoration: none;
            padding: 14px 25px;
            border-radius: 8px;
            font-weight: 600;
            transition: 0.3s;
        }

        .book-btn:hover {
            background: #ea580c;
            transform: translateY(-2px);
        }

        .back-btn {
            display: inline-block;
            color: #aaa;
            text-decoration: none;
            padding: 14px 20px;
            border-radius: 8px;
            transition: 0.3s;
        }

        .back-btn:hover {
            color: white;
        }

        @media (max-width: 800px) {

            .service-details {
                padding: 100px 20px 50px;
            }

            .details-container {
                grid-template-columns: 1fr;
            }

            .details-image {
                min-height: 300px;
            }

            .details-image img {
                min-height: 300px;
            }

            .details-content {
                padding: 35px 25px;
            }

            .details-content h1 {
                font-size: 32px;
            }

        }

    </style>

</head>

<body>

<header class="navbar">

    <a href="index.php" class="logo">
        Fix<span>Hub</span>
    </a>

    <nav>
        <a href="index.php#home">Home</a>
        <a href="index.php#services">Services</a>
        <a href="index.php#about">About</a>
        <a href="index.php#contact">Contact</a>
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

<section class="service-details">
    <div class="details-container">
        <div class="details-image">
            <?php if (!empty($item['image'])): ?>

                <img
                    src="assets/images/services/<?php echo htmlspecialchars($item['image']); ?>"
                    alt="<?php echo htmlspecialchars($item['name']); ?>"
                >

            <?php else: ?>

                <div class="no-image">
                    <?php echo htmlspecialchars($item['icon']); ?>
                </div>

            <?php endif; ?>

        </div>

        <div class="details-content">

            <div class="details-label">
                FIXHUB SERVICE
            </div>

            <div class="details-icon">
                <?php echo htmlspecialchars($item['icon']); ?>
            </div>

            <h1>
                <?php echo htmlspecialchars($item['name']); ?>
            </h1>

            <p>
                <?php echo htmlspecialchars($item['description']); ?>
            </p>

            <div class="details-buttons">

                <a
                    href="booking.php?service_id=<?php echo $item['id']; ?>"
                    class="book-btn"
                >
                    Book Service
                </a>

                <a href="index.php#services" class="back-btn">
                    ← Back to Services
                </a>

            </div>
        </div>

    </div>
</section>
<script src="assets/main.js"></script>
</body>
</html>