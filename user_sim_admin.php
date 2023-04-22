<?php
require 'includes/init.php';
// var_dump($_SESSION['user']->id);exit;
// Auth::requireLogin();
Auth::requireLogin("user_sim_admin.php");

$conn = require 'includes/db.php';
require 'user_auth.php';





$ugroups = Ugroup::getAll($conn);

$hospitals = Hospital::getAll($conn);
//var_dump($hospitals);




if (isset($_GET['id'])) {
    if ($isCurUserAdmin) {
        Auth::adminSimulatelogin($conn, $_GET['id']);
        Url::redirect('/login.php');
    }
}
?>
<?php // require 'includes/data2DOM.php';  ?>

<?php require 'includes/header.php'; ?>



<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">


        <div class="d-flex align-items-center justify-content-between">

        </div>

    </div>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">

        <table class="table table-hover table-striped text-center" id="user_sim_table" style="width:100%">
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
                    <th scope="col">Short name</th>
                    <!-- <th scope="col">password</th> -->
                    <th scope="col">Hospital</th>
                    <th scope="col">group</th>
                    <th scope="col">manage</th>
                </tr>
            </thead>

        </table>



    </div>
</div>




<?php require 'includes/footer.php'; ?>
<script type="text/javascript">


    $(document).ready(function () {


        // table data
        var table = $('#user_sim_table').DataTable({
            "ajax": "data/user_sim_admin_ajax.php?skey=<?= $_SESSION["skey"]; ?>",
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
                    targets: [9, 10]
                },
                {
                    searchPanes: {
                        show: false
                    },
                    targets: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                },
                {
                    "render": function (data, type, row) {

                        var renderdata = '';

<?php if ($isCurUserAdmin) : ?>
                            renderdata += '<a href="user_sim_admin.php?id=' + row[0] + '" class="btn btn-outline-dark btn-sm "><i class="fa-solid fa-people-arrows"></i> SIM</a>';
<?php endif; ?>

                        return renderdata;
                    },
                    "targets": -1
                },
                {
                    "render": function (data, type, row) {
                        // return data + ' (' + row[3] + ')';
                        return '<a href="user_detail.php?id=' + row[0] + '">' + data + '</a>';
                    },
                    "targets": 1
                },
                {
                    "render": function (data, type, row) {
                        // return data + ' (' + row[3] + ')';
                        data = row[2] + ' ' + row[3] + ' ' + row[4] + '<br>' + row[5] + ' ' + row[6] + ' ' + row[7];
                        if (row[8] != null) {
                            data += "<br>" + row[8];
                        }
                        return data;
                    },
                    "targets": 3
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
                    targets: [9, 10]
                },
                {
                    responsivePriority: 2,
                    targets: 11
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
                },
                {
                    responsivePriority: 10005,
                    targets: 8
                },
                {
                    visible: false,
                    targets: [0, 2, 4, 5, 6, 7, 8]
                },
            ]
        });

        // delete user
//        $('#user_sim_table tbody').on('click', 'a.delete', function(e) {
//            var data = table.row($(this).parents('tr')).data();
//
//            e.preventDefault();
//            if (confirm("Are you sure?")) {
//                var frm = $("<form>");
//                frm.attr('method', 'post');
//                frm.attr('action', "user_del.php?id=" + data[0]);
//                frm.appendTo("body");
//                frm.submit();
//            }
//        });


        // set active tab
        $("#admin_tab").addClass("active");
        $("#user_sim_admin_tab").addClass("active");
    });
</script>