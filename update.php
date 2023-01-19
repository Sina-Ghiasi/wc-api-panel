<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
require __DIR__ . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$woocommerce = new Client(
    $_ENV['WCP_STORE_URL'],
    $_ENV['WCP_CONSUMER_KEY'],
    $_ENV['WCP_CONSUMER_SECRET'],
    [
        'wp_api' => (bool) $_ENV['WCP_WP_API'],
        'version' => $_ENV['WCP_WC_VERSION'],
        'verify_ssl' => (bool) $_ENV['WCP_VERIFY_SSL'],
        'timeout' => intval($_ENV['WCP_TIMEOUT']),
    ]
);
$data = json_decode(file_get_contents('php://input'));

try {
    print_r($woocommerce->post('products/batch', $data));
} catch (HttpClientException $e) {
    header('HTTP/1.1 500 Internal Server Booboo');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode(array('message' => $e->getMessage(), 'code' => $e->getCode())));
}
