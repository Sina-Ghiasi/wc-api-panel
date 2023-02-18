<?php

require "db_open.php";

$_GET["category_id"];

$stmt = $conn->prepare('SELECT * FROM wcp_categories WHERE id = ?');
$stmt->bind_param("i", $_GET["category_id"]);

$is_executed_correctly = $stmt->execute();
$result = $stmt->get_result();
$error = $stmt->error;

$stmt->close();
$conn->close();

if ($is_executed_correctly) {
    echo json_encode($result->fetch_object());
} else {
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode($error));
}