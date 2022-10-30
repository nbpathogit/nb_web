<?php
require 'includes/init.php';

if (Auth::isLoggedIn()) {
    Url::redirect("/patient.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

//    var_dump($_POST);
//    die();

    $conn = require 'includes/db.php';

    if (User::authenticate($conn, $_POST['username'], $_POST['password'])) {
        Auth::login($conn, $_POST['username']);
        if (isset($_POST['page_name']) && $_POST['page_name'] != "" ) {
            Url::redirect('/' . $_POST['page_name'] . '?id=' . $_POST['page_id']);
        } else {
            Url::redirect('/patient.php');
        }
    } else {
        $error = "login incorrect,Please try again";
        $_GET['page_name'] = $_POST['page_name'];
        $_GET['page_id'] = $_POST['page_id'];
    }
}
?>



<!DOCTYPE html>
<html lang="th">

    <head>
        <meta charset="utf-8">
        <title>NB Phatho - Login</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Favicon -->
        <link href="img/favicon.ico" rel="icon">

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Icon Font Stylesheet -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Customized Bootstrap Stylesheet -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="css/style.css" rel="stylesheet">
    </head>

    <body>
        <div class="container-xxl position-relative bg-white d-flex p-0">
            <!-- Spinner Start -->
            <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <!-- Spinner End -->


            <!-- Sign In Start -->
            <div class="container-fluid">
                <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                    <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                        <div class="bg-light rounded p-4 p-sm-5 my-4 mx-3">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <a href="/" class="">
                                    <h3 class="text-primary"><i class="fa-solid fa-microscope"></i> NB Phatho</h3>
                                </a>
                                <!-- <h3>เข้าสู่ระบบ</h3> -->
                            </div>
<?php if (!empty($error)) : ?>
                                <div class="alert alert-danger" role="alert">
                                    <p><?= $error ?></p>
                                </div><?php endif; ?>

                            <form method="post">
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="username" name="username" placeholder="username" required>
                                    <label for="username">Username</label>
                                </div>
                                <div class="form-floating mb-4">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                    <label for="password">Password</label>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <!-- <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                    <label class="form-check-label" for="exampleCheck1">Check me out</label>
                                </div>
                                <a href="">Forgot Password</a> -->
                                </div>
                                <input type="hidden" id="page_name" name="page_name" value="<?= isset($_GET['page_name'])?  $_GET['page_name']:"";   ?>">
                                <input type="hidden" id="page_id" name="page_id" value="<?= isset($_GET['page_id'])?  $_GET['page_id']:""; ?>">
                                <button type="submit" class="btn btn-primary py-3 w-100 mb-4">เข้าสู่ระบบ</button>
                                <!-- <p class="text-center mb-0">Don't have an Account? <a href="">Sign Up</a></p> -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sign In End -->
        </div>

        <!-- JavaScript Libraries -->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Template Javascript -->
        <script src="js/main.js"></script>
    </body>

</html>