<?php

require_once "../config/Database.php";
require_once "../classes/Service.php";

$database = new Database();
$pdo = $database->connect();

$service = new Service($pdo);

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

$item = $service->getById($id);

if (!$item) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $description = $_POST['description'];
    $icon = $_POST['icon'];

    $image = $item['image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {

        $fileName = $_FILES['image']['name'];
        $tmpName = $_FILES['image']['tmp_name'];

        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $newName = uniqid() . "." . $extension;

        $uploadPath = "../assets/images/services/" . $newName;

        if (move_uploaded_file($tmpName, $uploadPath)) {
            $image = $newName;
        }
    }

    $service->update(
        $id,
        $name,
        $description,
        $icon,
        $image
    );

    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Service</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f7f7f7;
        }

        .container {
            width: 600px;
            margin: 60px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 10px #ddd;
        }

        h1 {
            margin-top: 0;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 15px;
        }

        input[type="file"] {
            background: white;
        }

        textarea {
            height: 120px;
            resize: vertical;
        }

        .current-image {
            margin-bottom: 20px;
        }

        .current-image img {
            width: 150px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .buttons {
            display: flex;
            gap: 10px;
        }

        button,
        .cancel {
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
            font-size: 15px;
        }

        button {
            background: #f97316;
            color: white;
        }

        button:hover {
            background: #ea580c;
        }

        .cancel {
            background: #eee;
            color: #333;
        }

        .cancel:hover {
            background: #ddd;
        }

    </style>

</head>

<body>

    <div class="container">

        <h1>Edit Service</h1>

        <form method="POST" enctype="multipart/form-data">

            <label>Name</label>

            <input
                type="text"
                name="name"
                value="<?php echo htmlspecialchars($item['name']); ?>"
                required
            >

            <label>Description</label>

            <textarea
                name="description"
                required
            ><?php echo htmlspecialchars($item['description']); ?></textarea>

            <label>Icon</label>

            <input
                type="text"
                name="icon"
                value="<?php echo htmlspecialchars($item['icon']); ?>"
                required
            >

            <label>Current Image</label>

            <?php if (!empty($item['image'])) { ?>

                <div class="current-image">

                    <img
                        src="../assets/images/services/<?php echo htmlspecialchars($item['image']); ?>"
                        alt="Service Image"
                    >

                </div>

            <?php } ?>

            <label>Change Image</label>

            <input
                type="file"
                name="image"
                accept="image/*"
            >

            <div class="buttons">

                <button type="submit">
                    Update Service
                </button>

                <a href="index.php" class="cancel">
                    Cancel
                </a>

            </div>

        </form>

    </div>

</body>

</html>
