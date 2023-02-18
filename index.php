<?php
require __DIR__ . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();


session_start();
if (!isset($_SESSION['username'])) {
    if (empty($_POST)) {
        header("Location: login.php");
        exit();
    } elseif ($_POST['username'] == $_ENV['WCP_USERNAME'] && $_POST['password'] == $_ENV['WCP_PASSWORD']) {
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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css"
        integrity="sha384-gXt9imSW0VcJVHezoNQsP+TNrjYXoGcrqBZJpry9zJt8PCQjobwmhMGaDHTASo9N" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="assets/js/main.js"></script>
</head>

<body>
    <?php

    //debug utility function
    function console_log($variable)
    {
        echo '<script>console.log(' . json_encode($variable) . ')</script>';
    }
    function get_value_of_key_in_metadata($metadata, $key)
    {
        foreach ($metadata as $data_object) {
            if ($data_object->key == $key) {
                return $data_object->value;
            }
        }
        return false;
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

    $system_status = $woocommerce->get('system_status', ["_fields" => "settings"]);
    $category_id = "";
    if (isset($_GET["category_id"]))
        $category_id = $_GET["category_id"];
    $search_str = "";
    if (isset($_GET["search_str"]))
        $search_str = $_GET["search_str"];
    ?>
    <div class="container mt-3">
        <h2 class="sub-header mb-3">لیست محصولات</h2>
        <div class="d-flex my-2">
            <input id="products-search-input" class="form-control w-25 me-2" type="text" placeholder="جستجو"
                value="<?php echo $search_str; ?>">
            <select id="products-category-select" class="form-select w-25 me-2" aria-label="Default select example">
                <option value="all" <?php if (!isset($_GET["category_id"]))
                    echo "selected"; ?>>همه دسته بندی ها
                </option>
                <?php
                $categories = $woocommerce->get('products/categories', ["per_page" => 80, "hide_empty" => true, "_fields" => ["name", "id", 'count']]);
                foreach ($categories as $category) {
                    if ($category->id == $category_id) {
                        ?>
                        <option value="<?php echo $category->id; ?>" selected><?php echo $category->name . " ({$category->count})"; ?>
                        </option>
                        <?php
                    } else {
                        ?>
                        <option value="<?php echo $category->id; ?>"><?php echo $category->name . " ({$category->count})"; ?>
                        </option>
                        <?php
                    }
                } ?>
            </select>
            <button id="filter-products-btn" class="btn btn-dark me-2">فیلتر</button>


            <button type="button" class="btn btn-primary ms-4" data-bs-toggle="modal" data-bs-target="#priceBulkEdit">
                تغییر قیمت دسته جمعی
            </button>

            <div class="modal fade" id="priceBulkEdit" tabindex="-1" aria-labelledby="priceBulkEdit" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">تغییر قیمت دسته جمعی</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <select id="modal-category-select" class="form-select w-25 mb-3"
                                aria-label="Default select example">
                                <?php
                                foreach ($categories as $category) {
                                    ?>
                                    <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?>
                                    </option>
                                    <?php
                                } ?>
                            </select>

                            <div class="row g-3 align-items-center my-2">
                                <div class="col-auto">
                                    <label for="wireRod" class="col-form-label">مقدار وایرود</label>
                                </div>
                                <div class="col-auto">
                                    <input id="wir-rod" type="number" class="form-control">
                                </div>
                                <div class="col-auto">
                                    <label for="profit-value" class="col-form-label">مقدار سود</label>
                                </div>
                                <div class="col-auto">
                                    <input id="profit-value" type="number" class="form-control">
                                </div>
                                <div class="col-auto">
                                    <button id="update-category-values" class="btn btn-dark ">
                                        ذخیره مقادیر
                                    </button>
                                </div>
                            </div>
                            <div id="modal-update-result" class="alert mt-4" role="alert">

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="update-products-by-category" class="btn btn-success ">
                                به روز رسانی محصولات در این دسته
                            </button>
                            <button class="btn btn btn-danger" data-bs-dismiss="modal">بستن</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <div class='table-responsive'>
            <table id='products-table' class='table table-striped table-bordered'>
                <thead>
                    <tr>
                        <th>نام</th>
                        <th>دسته ها</th>
                        <th>جزء قیمت</th>
                        <th>قیمت (
                            <?php echo $system_status->settings->currency_symbol; ?>)
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $products_page = 1;

                    do {
                        $products = $woocommerce->get('products', ["per_page" => 50, "page" => $products_page, "category" => $category_id, "search" => $search_str, "_fields" => ["name", "categories", "meta_data", "price", "id"]]);
                        foreach ($products as $product) { ?>
                            <tr data-modified="0" data-product-id="<?php echo $product->id ?>">
                                <td>
                                    <?php echo $product->name; ?>
                                </td>
                                <td>
                                    <?php echo implode(", ", array_map(function ($category) {
                                        return $category->name;
                                    }, $product->categories)); ?>
                                </td>
                                <td contenteditable='true'>
                                    <?php
                                    echo get_value_of_key_in_metadata($product->meta_data, "wpc_product_price_component");
                                    ?>
                                </td>
                                <td contenteditable='true'>
                                    <?php
                                    echo $product->price; ?>
                                </td>
                            </tr>
                        <?php }
                        $last_response_headers = $woocommerce->http->getResponse()->getHeaders();
                        $products_totalpages = isset($last_response_headers['x-wp-totalpages']) ? intval($last_response_headers['x-wp-totalpages']) : 1;
                        $products_page++;
                    } while ($products_page <= $products_totalpages);
                    ?>
                </tbody>
            </table>
        </div>
        <button id="update-products-by-list" class="btn btn-success">به روز رسانی تغییرات لیست</button>
        <a href="logout.php" class="btn btn-danger">خروج</a>
        <div id="update-products-result" class="alert mt-4" role="alert">

        </div>
    </div>
</body>

</html>