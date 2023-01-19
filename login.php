<!DOCTYPE html>
<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

?>

<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود به پنل</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css" integrity="sha384-gXt9imSW0VcJVHezoNQsP+TNrjYXoGcrqBZJpry9zJt8PCQjobwmhMGaDHTASo9N" crossorigin="anonymous">
    <link rel="stylesheet" href="css/main.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</head>

<body>
    <div class="container">
        <h2 class="text-center text-dark mt-5">پنل مدیریت قیمت محصولات</h2>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card my-5">
                    <form action="index.php" method="post" class=" card-body cardbody-color p-lg-4 ">

                        <div class="text-center">
                            <img src="img/enter.png" class="img-fluid my-3" width="100px" alt="profile">
                        </div>

                        <div class="mb-3">
                            <input type="text" name="username" class="form-control" id="Username" placeholder="نام کاربری">
                        </div>
                        <div class="mb-3">
                            <input type="password" name="password" class="form-control" id="password" placeholder="گذرواژه">
                        </div>
                        <button type="submit" name="login" value="login" class="btn btn-primary px-5 mb-3 w-100 text-center">ورود</button>

                        <?php
                        if (isset($_SESSION['error_message'])) {
                        ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                <?php echo $_SESSION['error_message']; ?>
                            </div>
                        <?php
                            unset($_SESSION['error_message']);
                        }
                        ?>

                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>