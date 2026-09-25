<?php

session_start();

require_once "config/Database.php";
require_once "classes/Service.php";
require_once "classes/Booking.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$database = new Database();
$pdo = $database->connect();

$service = new Service($pdo);
$booking = new Booking($pdo);

if (!isset($_GET['service_id'])) {
    header("Location: index.php");
    exit;
}

$serviceId = (int) $_GET['service_id'];
$item = $service->getById($serviceId);

if (!$item) {
    header("Location: index.php");
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $date = $_POST['booking_date'];
    $time = $_POST['booking_time'];
    $notes = $_POST['notes'];

    $booking->create(
        $_SESSION['user_id'],
        $serviceId,
        $date,
        $time,
        $notes
    );

    $message = "Booking submitted successfully!";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Book <?php echo htmlspecialchars($item['name']); ?> - FixHub</title>

    <link rel="stylesheet" href="assets/style.css">

    <style>

        .booking-page {
            min-height: 100vh;
            padding: 120px 20px 60px;
            background: #111;
        }

        .booking-container {
            max-width: 900px;
            margin: auto;
            background: #1b1b1b;
            border-radius: 18px;
            padding: 40px;
            color: white;
        }

        .booking-service {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 35px;
        }

        .booking-service img {
            width: 120px;
            height: 90px;
            object-fit: cover;
            border-radius: 10px;
        }

        .booking-icon {
            width: 80px;
            height: 80px;
            background: #fff7ed;
            color: #f97316;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 35px;
        }

        .booking-service h1 {
            margin: 0 0 8px;
        }

        .booking-service p {
            margin: 0;
            color: #aaa;
        }

        .booking-form {
            display: grid;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            color: #ddd;
            font-weight: 600;
        }

        .form-group input,
        .form-group textarea {
            padding: 13px;
            border: 1px solid #333;
            border-radius: 8px;
            background: #222;
            color: white;
            font-size: 15px;
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .confirm-btn {
            background: #f97316;
            color: white;
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }

        .confirm-btn:hover {
            background: #ea580c;
        }

        .success-message {
            background: #14532d;
            color: #bbf7d0;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
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

        <span class="user-welcome">
            Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
        </span>

        <a href="logout.php" class="logout-btn">
            Logout
        </a>

    </div>

</header>

<section class="booking-page">

    <div class="booking-container">

        <div class="booking-service">

            <?php if (!empty($item['image'])): ?>

                <img
                    src="assets/images/services/<?php echo htmlspecialchars($item['image']); ?>"
                    alt="<?php echo htmlspecialchars($item['name']); ?>"
                >

            <?php else: ?>

                <div class="booking-icon">
                    <?php echo htmlspecialchars($item['icon']); ?>
                </div>

            <?php endif; ?>

            <div>

                <h1>
                    Book <?php echo htmlspecialchars($item['name']); ?>
                </h1>

                <p>
                    Choose your preferred date and time.
                </p>

            </div>

        </div>

        <?php if ($message): ?>

            <div class="success-message">
                <?php echo $message; ?>
            </div>

        <?php endif; ?>

        <form method="POST" class="booking-form">

            <div class="form-group">

                <label>Booking Date</label>

                <input
                    type="date"
                    name="booking_date"
                    required
                >

            </div>

            <div class="form-group">

                <label>Booking Time</label>

                <input
                    type="time"
                    name="booking_time"
                    required
                >

            </div>

            <div class="form-group">

                <label>Notes</label>

                <textarea
                    name="notes"
                    placeholder="Tell us more about the service you need..."
                ></textarea>

            </div>

            <button type="submit" class="confirm-btn">
                Confirm Booking
            </button>

        </form>

    </div>

</section>

<script src="assets/main.js"></script>

</body>

</html>