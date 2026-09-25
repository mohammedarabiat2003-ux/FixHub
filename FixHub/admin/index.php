<?php

session_start();

require_once "../config/Database.php";
require_once "../classes/Service.php";

$database = new Database();
$pdo = $database->connect();

$service = new Service($pdo);

$services = $service->getAll();
$deletedServices = $service->getDeleted();

$totalServices = count($services);

$stmt = $pdo->query("SELECT COUNT(*) FROM bookings");
$totalBookings = $stmt->fetchColumn();

$stmt = $pdo->query("
    SELECT status, COUNT(*) AS total
    FROM bookings
    GROUP BY status
");

$bookingStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>FixHub Admin</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f7f7f7;
        }

        .sidebar {
            width: 220px;
            height: 100vh;
            background: #222;
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            padding: 25px;
        }

        .logo {
            font-size: 25px;
            font-weight: bold;
            margin-bottom: 40px;
        }

        .logo span {
            color: #f97316;
        }

        .sidebar a {
            display: block;
            color: #ddd;
            text-decoration: none;
            padding: 12px 0;
        }

        .sidebar a:hover {
            color: #f97316;
            padding-left: 5px;
        }

        .main {
            margin-left: 220px;
            padding: 35px;
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .top h1 {
            margin: 0;
        }

        .add-btn {
            background: #f97316;
            color: white;
            padding: 12px 18px;
            text-decoration: none;
            border-radius: 6px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 35px;
        }

        .card,
        .chart-box,
        .services-box,
        .deleted-box {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 10px #ddd;
        }

        .card h3 {
            margin-top: 0;
            color: #777;
        }

        .card p {
            font-size: 30px;
            font-weight: bold;
            margin-bottom: 0;
        }

        .chart-box {
            margin-bottom: 35px;
        }

        .chart-box h2,
        .services-box h2,
        .deleted-box h2 {
            margin-top: 0;
            margin-bottom: 20px;
        }

        .chart {
            height: 300px;
        }

        .service-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 10px;
            border-bottom: 1px solid #eee;
        }

        .service-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .service-icon {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff7ed;
            border-radius: 10px;
            font-size: 25px;
        }

        .service-info small {
            color: #777;
            display: inline-block;
            margin-top: 5px;
        }

        .service-actions {
            display: flex;
            gap: 8px;
        }

        .service-actions a {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            text-decoration: none;
            font-size: 17px;
        }

        .edit {
            color: #2563eb;
            background: #eff6ff;
        }

        .delete {
            color: #dc2626;
            background: #fef2f2;
        }

        .restore {
            color: #16a34a;
            background: #f0fdf4;
        }

        .permanent-delete {
            color: #dc2626;
            background: #fef2f2;
        }

        .empty-message {
            color: #888;
            margin: 0;
        }

        .deleted-box {
            margin-top: 35px;
        }

        .deleted-box .service-icon {
            background: #f3f4f6;
            filter: grayscale(1);
        }

        @media (max-width: 800px) {

            .cards {
                grid-template-columns: 1fr;
            }

        }

        @media (max-width: 600px) {

            .sidebar {
                display: none;
            }

            .main {
                margin-left: 0;
                padding: 20px;
            }

            .top {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

        }

    </style>

</head>

<body>

<div class="sidebar">

    <div class="logo">
        Fix<span>Hub</span>
    </div>

    <a href="index.php">Dashboard</a>
    <a href="services.php">Services</a>
    <a href="add_service.php">Add Service</a>
    <a href="../index.php">View Website</a>

</div>

<div class="main">
    <div class="top">

        <h1>Dashboard</h1>

        <a href="add_service.php" class="add-btn">
            + Add Service
        </a>

    </div>

    <div class="cards">

        <div class="card">

            <h3>Total Services</h3>

            <p>
                <?php echo $totalServices; ?>
            </p>

        </div>

        <div class="card">
            <h3>Available Services</h3>

            <p>
                <?php echo count($services); ?>
            </p>

        </div>

        <div class="card">

            <h3>Total Bookings</h3>

            <p>
                <?php echo $totalBookings; ?>
            </p>

        </div>
    </div>

    <div class="chart-box">

        <h2>Booking Statistics</h2>

        <div class="chart">

            <canvas id="bookingChart"></canvas>
        </div>
    </div>

    <div class="services-box">

        <h2>Services</h2>


        <?php if (count($services) > 0) { ?>

            <?php foreach ($services as $item) { ?>
                <div class="service-row">
                    <div class="service-info">

                        <div class="service-icon">
                            <?php echo htmlspecialchars($item['icon']); ?>
                        </div>

                        <div>

                            <strong>
                                <?php echo htmlspecialchars($item['name']); ?>
                            </strong>

                            <br>

                            <small>
                                <?php echo htmlspecialchars($item['description']); ?>
                            </small>

                        </div>
                    </div>

                    <div class="service-actions">

                        <a
                            href="edit_service.php?id=<?php echo $item['id']; ?>"
                            class="edit"
                            title="Edit"
                        >
                            ✏
                        </a>

                        <a
                            href="delete_service.php?id=<?php echo $item['id']; ?>"
                            class="delete"
                            title="Delete"
                            onclick="return confirm('Move this service to Recently Deleted?');"
                        >
                            🗑
                        </a>
                    </div>

                </div>

            <?php } ?>

        <?php } else { ?>

            <p class="empty-message">
                No services found.
            </p>

        <?php } ?>

    </div>

    <div class="deleted-box">

        <h2>Recently Deleted</h2>

        <?php if (count($deletedServices) > 0) { ?>

            <?php foreach ($deletedServices as $item) { ?>

                <div class="service-row">

                    <div class="service-info">

                        <div class="service-icon">
                            <?php echo htmlspecialchars($item['icon']); ?>
                        </div>

                        <div>

                            <strong>
                                <?php echo htmlspecialchars($item['name']); ?>
                            </strong>

                            <br>

                            <small>
                                Deleted: <?php echo htmlspecialchars($item['deleted_at']); ?>
                            </small>

                        </div>

                    </div>

                    <div class="service-actions">

                        <a
                            href="restore_service.php?id=<?php echo $item['id']; ?>"
                            class="restore"
                            title="Restore"
                            onclick="return confirm('Restore this service?');"
                        >
                            ↩
                        </a>

                        <a
                            href="permanent_delete.php?id=<?php echo $item['id']; ?>"
                            class="permanent-delete"
                            title="Delete Permanently"
                            onclick="return confirm('Delete this service permanently?');"
                        >
                            🗑
                        </a>

                    </div>

                </div>

            <?php } ?>

        <?php } else { ?>

            <p class="empty-message">
                No recently deleted services.
            </p>

        <?php } ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const bookingData = <?php echo json_encode($bookingStats); ?>;

const labels = bookingData.map(function(item) {
    return item.status;
});

const values = bookingData.map(function(item) {
    return item.total;
});

new Chart(document.getElementById("bookingChart"), {

    type: "bar",

    data: {
        labels: labels,

        datasets: [{
            label: "Bookings",
            data: values
        }]
    },

    options: {
        responsive: true,
        maintainAspectRatio: false,

        scales: {
            y: {
                beginAtZero: true
            }
        }
    }

});

</script>
</body>
</html>