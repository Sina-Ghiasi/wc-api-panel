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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css" integrity="sha384-gXt9imSW0VcJVHezoNQsP+TNrjYXoGcrqBZJpry9zJt8PCQjobwmhMGaDHTASo9N" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/main.css">
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
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 20 20">
                                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                </svg>
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