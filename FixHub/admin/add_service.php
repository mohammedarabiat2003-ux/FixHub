<?php

require_once "../config/Database.php";
require_once "../classes/Service.php";

$database = new Database();
$pdo = $database->connect();

$service = new Service($pdo);

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $description = $_POST['description'];
    $icon = $_POST['icon'];

    $image = "";

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {

        $fileName = $_FILES['image']['name'];
        $tmpName = $_FILES['image']['tmp_name'];

        $extension = strtolower(
            pathinfo($fileName, PATHINFO_EXTENSION)
        );

        $allowedTypes = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($extension, $allowedTypes)) {

            $newName = uniqid() . "." . $extension;

            $uploadPath = "../assets/images/services/" . $newName;

            if (move_uploaded_file($tmpName, $uploadPath)) {
                $image = $newName;
            }
        }
    }

    $service->add(
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

    <title>Add Service</title>

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

        <h1>Add Service</h1>

        <form method="POST" enctype="multipart/form-data">

            <label>Name</label>

            <input
                type="text"
                name="name"
                required
            >

            <label>Description</label>

            <textarea
                name="description"
                required
            ></textarea>

            <label>Icon</label>

            <input
                type="text"
                name="icon"
                placeholder="🔧"
                required
            >

            <label>Service Image</label>

            <input
                type="file"
                name="image"
                accept=".jpg,.jpeg,.png,.webp"
                required
            >

            <div class="buttons">

                <button type="submit">
                    Add Service
                </button>

                <a href="index.php" class="cancel">
                    Cancel
                </a>

            </div>

        </form>

    </div>

</body>

</html>
