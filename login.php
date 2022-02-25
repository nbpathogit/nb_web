<?php

require 'includes/init.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $conn = require 'includes/db.php';

    if (User::authenticate($conn, $_POST['username'], $_POST['password'])) {
        Auth::login($conn, $_POST['username']);
        Url::redirect('/home.php');
    } else {
        $error = "login incorrect";
    }
}

?>

<?php require 'includes/header.php'; ?>
<div class="container g-4 m-3">
    <div class="row">
        <div class="col-md-3">
        </div>
        <div class="col-md-6">
            <h2>Login</h2>

            <?php if (!empty($error)) : ?>
                <div class="alert alert-danger" role="alert">
                    <p><?= $error ?></p>
                </div><?php endif; ?>

            <form method="post">
                <div class="col-md-10 mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input name="username" id="username" class="form-control" required></input>
                </div>
                <div class="col-md-10 mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required></input>
                </div>
                <div class="col-md-offset-2 col-md-10 mb-3">
                    <button type="submit" class="btn btn-primary">Log in</button>
                    <div>
            </form>
        </div>
    </div>
</div>
<?php require 'includes/footer.php'; ?>