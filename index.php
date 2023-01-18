<?php
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();


session_start();
if (!isset($_SESSION['username'])) {
    if ($_POST['username'] == $_ENV['WCP_USERNAME'] && $_POST['password'] == $_ENV['WCP_PASSWORD']) {
        $_SESSION['username'] = $_ENV['WCP_USERNAME'];
    } else {
        $_SESSION['error_message'] = "اطلاعات کاربری اشتباه می باشد";
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پنل</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css" integrity="sha384-gXt9imSW0VcJVHezoNQsP+TNrjYXoGcrqBZJpry9zJt8PCQjobwmhMGaDHTASo9N" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>

<body>
    <?php

    use Automattic\WooCommerce\Client;
    use Automattic\WooCommerce\HttpClient\HttpClientException;

    //debug utility function
    function console_log($variable)
    {
        echo '<script>console.log(' . json_encode($variable) . ')</script>';
    }

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
    //var_dump($woocommerce);
    $products = $woocommerce->get('products', ["_fields" => ["name", "categories", "type", "price"]]);
    $system_status = $woocommerce->get('system_status', ["_fields" => "settings"]);
    console_log($products);
    ?>
    <div class="container">
        <h2 class="sub-header">لیست محصولات</h2>
        <div class='table-responsive'>
            <table id='myTable' class='table table-striped table-bordered'>
                <thead>
                    <tr>
                        <th>نام</th>
                        <th>دسته ها</th>
                        <th>نوع</th>
                        <th>قیمت</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($products as $product) { ?>
                        <tr>
                            <td><?php echo $product->name; ?></td>
                            <td><?php echo implode(" ", array_map(function ($category) {
                                    return $category->name;
                                }, $product->categories)); ?></td>
                            <td><?php echo $product->type; ?></td>
                            <td><?php echo $product->price . ' ' . $system_status->settings->currency_symbol ?></td>
                        </tr>
                    <?php }
                    ?>
                </tbody>
            </table>
        </div>
        <a href="logout.php" class="btn btn-danger">خروج</a>
    </div>
</body>

</html>