<?php
//var_dump($_GET);

require 'includes/init.php';

Auth::requireLogin();

$conn = require 'includes/db.php';

if (isset($_GET['id'])) {
    $user = User::getAll($conn, $_GET['id']);
} else {
    $user = null;
}

//var_dump($user);

?>

<?php require 'includes/header.php'; ?>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center p-3 mx-1">

        <div>
            <p>

                <?php
                if (isset($_GET['result'])) {
                    if ($_GET['result'] == 1) : ?>
            <div class="alert alert-success" role="alert">
                USER Added/Edit Successful
            </div>
    <?php endif;
                }
    ?>
    <p>
        </div> <br>

        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th scope="col">Key</th>
                    <th scope="col">Detail</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>id</td>
                    <td>
                        <?= $user[0]['uid']; ?>
                    </td>
                </tr>
                <tr>
                    <td>name</td>
                    <td>
                        <?= $user[0]['name']; ?>
                    </td>
                </tr>
                <tr>
                    <td>lastname</td>
                    <td>
                        <?= $user[0]['lastname']; ?>
                    </td>
                </tr>
                <tr>
                    <td>รายละเอียดเพิ่มเติม</td>
                    <td>
                        <?= $user[0]['udetail']; ?>
                    </td>
                </tr>
                <tr>
                    <td>เบอร์โทรศัพท์</td>
                    <td>
                        <?= $user[0]['umobile']; ?>
                    </td>
                </tr>
                <tr>
                    <td>e-mail</td>
                    <td>
                        <?= $user[0]['uemail']; ?>
                    </td>
                </tr>
                <tr>
                    <td>username</td>
                    <td>
                        <?= $user[0]['username']; ?>
                    </td>
                </tr>
                <tr>
                    <td>password</td>
                    <td>
                        <?= "****" //password$user[0]['password']; ?>
                    </td>
                </tr>
                <tr>
                    <td>hospital</td>
                    <td>
                        <?= $user[0]['hospital']; ?>
                    </td>
                </tr>
                <tr>
                    <td>ugroup</td>
                    <td>
                        <?= $user[0]['ugroup']; ?>
                    </td>
                </tr>
                <tr>
                    <td>detail</td>
                    <td>
                        <?= $user[0]['udetail']; ?>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
</div>

<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    // set active tab
    $("#user_main").addClass("active");
    $("#user_add").addClass("active");
</script>