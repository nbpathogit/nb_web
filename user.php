<?php
require 'includes/init.php';


$conn = require 'includes/db.php';

//$users = User::getAll($conn);

$ugroups = Ugroup::getAll($conn);

$hospitals = Hospital::getAll($conn);

//Ternary Operator
// $paginator = new Paginator(isset($_GET['page']) ? $_GET['page'] : 1, 10, User::getTotal($conn));

// $users = User::getPage($conn, $paginator->limit, $paginator->offset);

// $users = User::getAll($conn, 0);

//var_dump($users);
//var_dump($ugroups);
//var_dump($hospitals);
?>

<?php require 'includes/header.php'; ?>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center p-3 mx-1">

        <?php if (!Auth::isLoggedIn()) : ?>
            You are not authorized.
        <?php else : ?>

            <div class="d-flex align-items-center justify-content-between">
                <a href="/user_add.php" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-user-plus me-2"></i>เพิ่มผู้ใช้งานระบบ</a>
            </div>

    </div>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center p-3 mx-1">

        <table class="table table-hover table-striped text-center" id="user_table">
            <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">username</th>
                    <th scope="col">pre name</th>
                    <th scope="col">name</th>
                    <th scope="col">lastname</th>
                    <!-- <th scope="col">password</th> -->
                    <th scope="col">hospital</th>
                    <th scope="col">group</th>
                    <th scope="col">manage</th>
                </tr>
            </thead>

        </table>

    <?php endif; ?>

    </div>
</div>

<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    $(document).ready(function() {


        // table data
        var table = $('#user_table').DataTable({
            "ajax": "data/user.php?skey=<?= $_SESSION["skey"]; ?>",
            dom: 'Plfrtip',
            searchPanes: {
                initCollapsed: true,
                // cascadePanes: true,
            },
            columnDefs: [{
                    searchPanes: {
                        show: true
                    },
                    targets: [4, 5]
                },
                {
                    searchPanes: {
                        show: false
                    },
                    targets: [0, 1, 2, 3]
                }, {
                    "targets": -1,
                    "data": null,
                    "defaultContent": '<a class="btn btn-outline-success btn-sm me-1 detail"><i class="fa-solid fa-money-check"></i></a><a class="btn btn-outline-primary btn-sm me-1 edit"><i class="fa-solid fa-marker fa-lg"></i></a><a class="btn btn-outline-dark btn-sm delete"><i class="fa-solid fa-trash-can fa-lg"></i></a>',
                },
                {
                    "render": function(data, type, row) {
                        // return data + ' (' + row[3] + ')';
                        return '<a href="user_detail.php?id=' + row[0] + '">' + data + '</a>';
                    },
                    "targets": 1
                },
            ]
        });

        $('#user_table tbody').on('click', 'a.detail', function() {
            var data = table.row($(this).parents('tr')).data();
            // alert( data[0] +"'s salary is: "+ data[ 5 ] );
            location.href = "user_detail.php?id=" + data[0];
        });
        $('#user_table tbody').on('click', 'a.edit', function() {
            var data = table.row($(this).parents('tr')).data();
            // alert( data[0] +"'s salary is: "+ data[ 5 ] );
            location.href = "user_edit.php?id=" + data[0];
        });
        $('#user_table tbody').on('click', 'a.delete', function(e) {
            var data = table.row($(this).parents('tr')).data();

            e.preventDefault();
            if (confirm("Are you sure?")) {
                var frm = $("<form>");
                frm.attr('method', 'post');
                frm.attr('action', "user_del.php?id=" + data[0]);
                frm.appendTo("body");
                frm.submit();
            }
        });


        // set active tab
        $("#user").addClass("active");
    });
</script>