<?php
require 'includes/init.php';


$conn = require 'includes/db.php';
require 'user_auth.php';
//$users = User::getAll($conn);

// $ugroups = Ugroup::getAll($conn);

// $hospitals = Hospital::getAll($conn);

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

        <table class="table table-hover table-striped text-center" id="user_table" style="width:100%">
            <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">username</th>
                    <th scope="col">#</th>
                    <th scope="col">ชื่อ</th>
                    <th scope="col">นามสกุล</th>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Lastname</th>
                    <!-- <th scope="col">password</th> -->
                    <th scope="col">Hospital</th>
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
            responsive: true,
            dom: 'Plfrtip',
            searchPanes: {
                initCollapsed: true,
                // cascadePanes: true,
            },
            columnDefs: [{
                    searchPanes: {
                        show: true
                    },
                    targets: [8, 9]
                },
                {
                    searchPanes: {
                        show: false
                    },
                    targets: [0, 1, 2, 3, 4, 5, 6, 7]
                },
                {
                    "render": function(data, type, row) {

                        var renderdata = '<a href="user_detail.php?id=' + row[0] + '" class="btn btn-outline-success btn-sm me-1 detail"><i class="fa-solid fa-money-check"></i></a><a href="user_edit.php?id=' + row[0] + '" class="btn btn-outline-primary btn-sm me-1 edit"><i class="fa-solid fa-marker fa-lg"></i></a>';

                        <?php if ($isCurUserAdmin) : ?>
                            renderdata += '<a href="user_del.php?id=' + row[0] + '" class="btn btn-outline-dark btn-sm delete"><i class="fa-solid fa-trash-can"></i></a>';
                        <?php endif; ?>

                        return renderdata;
                    },
                    "targets": -1
                },
                {
                    "render": function(data, type, row) {
                        // return data + ' (' + row[3] + ')';
                        return '<a href="user_detail.php?id=' + row[0] + '">' + data + '</a>';
                    },
                    "targets": 1
                },
                {
                    responsivePriority: 1,
                    targets: 1
                },
                {
                    responsivePriority: 3,
                    targets: 3
                },
                {
                    responsivePriority: 4,
                    targets: [8, 9]
                },
                {
                    responsivePriority: 2,
                    targets: 10
                },
                {
                    responsivePriority: 10004,
                    targets: [2, 5]
                },
                {
                    responsivePriority: 10003,
                    targets: [6, 7]
                },
                {
                    responsivePriority: 10002,
                    targets: 4
                },
                {
                    responsivePriority: 10005,
                    targets: 0
                }
            ]
        });

        // delete user
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