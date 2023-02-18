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


function get_value_of_key_in_metadata($metadata, $key)
{
    foreach ($metadata as $data_object) {
        if ($data_object->key == $key) {
            return $data_object->value;
        }
    }
    return false;
}
$category = json_decode(file_get_contents('php://input'));


$products_page = 1;

do {
    $products_data = [
        'update' => []
    ];
    $products = $woocommerce->get('products', ["per_page" => 50, "page" => $products_page, "category" => $category->id, "_fields" => ["id", "meta_data"]]);
    foreach ($products as $product) {
        $product_price = $category->wire_rod + $category->profit + intval(get_value_of_key_in_metadata($product->meta_data, "wpc_product_price_component"));
        array_push($products_data['update'], [
            "id" => $product->id,
            "regular_price" => $product_price
        ]);
    }
    try {
        print_r($woocommerce->post('products/batch', $products_data));
    } catch (HttpClientException $e) {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode(array('message' => $e->getMessage(), 'code' => $e->getCode())));
    }

    $last_response_headers = $woocommerce->http->getResponse()->getHeaders();
    $products_totalpages = isset($last_response_headers['x-wp-totalpages']) ? intval($last_response_headers['x-wp-totalpages']) : 1;
    $products_page++;
} while ($products_page <= $products_totalpages);