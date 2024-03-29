<?php
require 'includes/init.php';

//Auth::requireLogin();

$conn = require 'includes/db.php';

// $specimens = Specimen::getAll($conn);
// if (isset($_REQUEST['search']) && $_REQUEST['search'] != "") {
//     // var_dump($_REQUEST);
//     // $paginator = new Paginator(isset($_GET['page']) ? $_GET['page'] : 1, 30, Specimen::getTotal($conn));
//     // var_dump($paginator);
//     $specimens = Specimen::getSearch($conn, $_REQUEST['search']);
//     // var_dump($specimens);
// } else {
//     $paginator = new Paginator(isset($_GET['page']) ? $_GET['page'] : 1, 30, Specimen::getTotal($conn));
//     // var_dump($paginator);
//     $specimens = Specimen::getPage($conn, $paginator->limit, $paginator->offset);
// }
?>

<?php require 'includes/header.php'; ?>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">

        <?php require 'user_auth.php'; ?>
        <?php if (!Auth::isLoggedIn()) : ?>
            You are not login.<br>
            คุณไม่ได้ล็อกอิน กรุณาล็อกอินก่อนเข้าใช้งาน
        <?php elseif (($isCurUserClinicianCust || $isCurUserHospitalCust)) : //  เจ้าหน้าที่รับผล(ลูกค้า) เข้าดูไม่ได้  
        ?>
            You have no authorize to view this content. <br>
            คุณไม่มีสิทธิ์ในการเข้าดูส่วนนี้
        <?php else : ?>


            <div class="d-flex align-items-center justify-content-between">
                <a href="/specimen_add.php" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-disease me-2"></i>เพิ่มสิ่งส่งตรวจ</a>
            </div>

    </div>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">

        <table class="table table-hover table-striped text-center" id="specimen_table" style="width:100%">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Number</th>
                    <th scope="col">Specimen</th>
                    <th scope="col">Price</th>
                    <th scope="col">Manage</th>
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
        var table = $('#specimen_table').DataTable({
            "ajax": "data/specimen.php?skey=<?= $_SESSION["skey"]; ?>",
            responsive: true,
            columnDefs: [{
                "render": function(data, type, row) {
                    var renderdata = '<a href="specimen_edit.php?id=' + row[0] + '" class="btn btn-outline-primary btn-sm me-1 edit"><i class="fa-solid fa-marker"></i></a>';

                    <?php if ($isCurUserAdmin) : ?>
                        renderdata += '<a href="specimen_del.php?id=' + row[0] + '" class="btn btn-outline-dark btn-sm delete"><i class="fa-solid fa-trash-can"></i></a>';
                    <?php endif; ?>

                    return renderdata;
                },
                "targets": -1
            }, ],
        });

        // delete user
        $('#specimen_table tbody').on('click', 'a.delete', function(e) {
            var data = table.row($(this).parents('tr')).data();

            e.preventDefault();
            if (confirm("Are you sure?")) {
                var frm = $("<form>");
                frm.attr('method', 'post');
                frm.attr('action', "specimen_del.php?id=" + data[0]);
                frm.appendTo("body");
                frm.submit();
            }
        });

        // set active tab
        $("#specimentab").addClass("active");
        $("#manage_table").addClass("active");
        $("#manage_table").addClass("show");
        $(".manage_table_dropdown").addClass("show");
    });
</script>