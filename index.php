<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>

<body>
    <?php

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
            'timeout' =>intval($_ENV['WCP_TIMEOUT']) ,
        ]
    );
    //test
    var_dump($woocommerce);
    print_r( $woocommerce->get('products'));

    ?>
</body>

</html>