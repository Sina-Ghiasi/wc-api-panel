<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable("../");
$dotenv->safeLoad();

/*
$driver = new mysqli_driver();
$driver -> report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;
*/

$conn = new mysqli($_ENV['WCP_SERVERNAME'], $_ENV['WCP_DB_USERNAME'], $_ENV['WCP_DB_PASSWORD'], $_ENV['WCP_DB_NAME']);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE TABLE IF NOT EXISTS  wcp_categories (
    id INT PRIMARY KEY,
    category_name VARCHAR(50) NOT NULL,
    wire_rod INT,
    profit INT
    ) CHARACTER SET " . $_ENV['WCP_DB_CHARACTER'] . " COLLATE " . $_ENV['WCP_DB_COLLATE'];

$conn->query($sql);