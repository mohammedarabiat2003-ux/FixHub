<?php

require_once "../config/Database.php";
require_once "../classes/Service.php";

$database = new Database();
$pdo = $database->connect();

$service = new Service($pdo);

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $service->delete($id);
}

header("Location: index.php");
exit;
