<?php
require 'includes/init.php';

$conn = require 'includes/db.php';

require 'includes/header.php'; ?>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center p-3 mx-1">

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
                <a href="#" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-download me-2"></i>Export</a>
            </div>

    </div>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center p-3 mx-1">


        <table class="table table-hover table-striped" id="job_table" style="width:100%">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">job role id</th>
                    <th scope="col">patient id</th>
                    <th scope="col">Patient</th>
                    <th scope="col">user id</th>
                    <th scope="col">prename</th>
                    <th scope="col">name</th>
                    <th scope="col">lastname</th>
                    <th scope="col">jobname</th>
                    <th scope="col">pay</th>
                    <th scope="col">cost count per day</th>
                    <th scope="col">Detail</th>
                    <th scope="col">Date</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
        </table>

    <?php endif; ?>

    </div>
</div>

<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    $(document).ready(function() {

        // delete
        $("a.delete").on("click", function(e) {

            e.preventDefault();

            if (confirm("Are you sure?")) {

                var frm = $("<form>");
                frm.attr('method', 'post');
                frm.attr('action', $(this).attr('href'));
                frm.appendTo("body");
                frm.submit();
            }
        });

        // table data
        var table = $('#job_table').DataTable({
            "ajax": "data/job.php?skey=<?= $_SESSION["skey"]; ?>",
            responsive: true,
            columnDefs: [
                // {
                //     "render": function(data, type, row) {
                //         var renderdata = '<a href="job_detail.php?id=' + row[0] + '" class="btn btn-outline-success btn-sm me-1 detail"><i class="fa-solid fa-money-check"></i></a><a href="job_edit.php?id=' + row[0] + '" class="btn btn-outline-primary btn-sm me-1 edit"><i class="fa-solid fa-marker"></i></a>';
                // <?php //if ($isCurUserAdmin) : 
                    ?>
                //             renderdata += '<a href="job_del.php?id=' + row[0] + '" class="btn btn-outline-dark btn-sm delete"><i class="fa-solid fa-trash-can"></i></a>';
                //         <?php //endif; 
                            ?>
                //         return renderdata;
                //     },
                //     "targets": -1
                // },
                {
                    "render": function(data, type, row) {
                        return '<b>No.: </b><a href="patient_edit.php?id=' + row[2] + '">' + row[3] + '</a><br><b>Name: </b>' + row[5] + " " + row[6] + " " + row[7] + "<br>" + "<b>Job: </b>" + row[8];
                    },
                    "targets": 3
                },
                {
                    "render": function(data, type, row) {
                        return "<b>Create: </b>" + row[13] + "<br><b>Finish: </b>" + (row[12] == null ? "-" : row[12]);
                    },
                    "targets": 12
                },
                {
                    "render": function(data, type, row) {
                        let html = "<b>#:</b>" + row[0];
                        html += "<br><b>job role id:</b> " + row[1];
                        html += "<br><b>patient id:</b> " + row[2];
                        html += "<br><b>patient number:</b> " + row[3];
                        html += "<br><b>user id:</b> " + row[4];
                        html += "<br><b>pay:</b> " + row[9];
                        html += "<br><b>cost count per day:</b> " + row[10];
                        return html;
                    },
                    "targets": 11
                },
                {
                    visible: false,
                    targets: [0, 1, 2, 4, 5, 6, 7,8, 9, 10, 13]
                },
            ],
        });

        // delete job
        $('#job_table tbody').on('click', 'a.delete', function(e) {
            var data = table.row($(this).parents('tr')).data();

            e.preventDefault();
            if (confirm("Are you sure?")) {
                var frm = $("<form>");
                frm.attr('method', 'post');
                frm.attr('action', "job_del.php?id=" + data[0]);
                frm.appendTo("body");
                frm.submit();
            }
        });

        //set active tab
        $("#job_tab").addClass("active");

    });
</script>