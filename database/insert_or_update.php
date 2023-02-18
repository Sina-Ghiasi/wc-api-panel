<?php

require "db_open.php";

$category = json_decode(file_get_contents('php://input'));


$stmt = $conn->prepare('INSERT INTO wcp_categories (id,category_name,wire_rod,profit) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE    
wire_rod=?, profit=?');
$stmt->bind_param("isiiii", $category->id, $category->name, $category->wire_rod, $category->profit, $category->wire_rod, $category->profit);

$is_executed_correctly = $stmt->execute();
$result = $stmt->get_result();
$error = $stmt->error;

$stmt->close();
$conn->close();

if ($is_executed_correctly) {
    echo "update successfully";
} else {
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode($error));
}