<?php
require 'includes/init.php';

$conn = require 'includes/db.php';
require 'user_auth.php';
?>

<?php require 'includes/header.php'; ?>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center p-3 mx-1">

        <?php if (!Auth::isLoggedIn()) : ?>
            You are not authorized.
        <?php else : ?>

            <div class="d-flex align-items-center justify-content-between">
                <a href="/patient_add.php" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-bed-pulse me-2"></i>เพิ่มข้อมูลผู้รักษา</a>
            </div>

    </div>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center p-3 mx-1">


        <table class="table table-hover" id="patient_table" style="width:100%">
            <!--<table border="1" align="center">-->
            <thead>
                <tr>
                    <th>#</th>
                    <th>เลขที่ผู้ป่วย</th>
                    <th>ชื่อผู้ป่วย</th>
                    <th>นามสกุลผู้ป่วย</th>
                    <th>โรงพยาบาล</th>
                    <th>พยาธิแพทย์</th>
                    <th>วันที่รับ</th>
                    <th>วันที่รายงาน</th>
                    <th>สถานะ</th>
                    <th>การออกผล</th>
                    <th>ความสำคัญ</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
        </table>

    <?php endif; ?>

    </div>
</div>

<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    $(document).ready(function() {


        // add color class for priority 
        function colorAdd() {
            var count = $('.table').children('tbody').children('tr:first-child').children('td').length;
            tablee = document.getElementById("patient_table");
            tr = tablee.getElementsByTagName("tr");
            // Loop through all table rows, and hide those who don't match the search query
            for (i = 1; i < tr.length; i++) {
                for (j = 0; j < count; j++) {
                    td = tr[i].getElementsByTagName("td")[j];
                    if (td.innerHTML.indexOf('ด่วน') > -1) {
                        tr[i].classList.add('table-danger');
                    } else if (td.innerHTML.indexOf('ออกผลแล้ว') > -1) {
                        tr[i].classList.add('table-success');
                        break;
                    } else {
                        //DO NOTHING
                    }
                }
            }
        }


        // datatable
        var table = $('#patient_table').DataTable({
            "ajax": "data/patient.php?skey=<?= $_SESSION["skey"]; ?>&psid=8000&range=1m",
            responsive: true,
            dom: 'PlfBrtip',
            buttons: [{
                extend: 'collection',
                text: 'Range',
                autoClose: true,
                buttons: [{
                        text: '1 เดือนล่าสุด',
                        action: function(e, dt, node, config) {
                            dt.ajax.url("data/patient.php?skey=<?= $_SESSION['skey']; ?>&psid=8000&range=1m").load();
                        }
                    },
                    {
                        text: '3 เดือนล่าสุด',
                        action: function(e, dt, node, config) {
                            dt.ajax.url("data/patient.php?skey=<?= $_SESSION['skey']; ?>&psid=8000&range=3m").load();
                        }
                    },
                    {
                        text: '6 เดือนล่าสุด',
                        action: function(e, dt, node, config) {
                            dt.ajax.url("data/patient.php?skey=<?= $_SESSION['skey']; ?>&psid=8000&range=6m").load();
                        }
                    },
                    {
                        text: '1 ปีล่าสุด',
                        action: function(e, dt, node, config) {
                            dt.ajax.url("data/patient.php?skey=<?= $_SESSION['skey']; ?>&psid=8000&range=1y").load();
                        }
                    },
                    {
                        text: '2 ปีล่าสุด',
                        action: function(e, dt, node, config) {
                            dt.ajax.url("data/patient.php?skey=<?= $_SESSION['skey']; ?>&psid=8000&range=2y").load();
                        }
                    },
                    {
                        text: 'ทั้งหมด',
                        action: function(e, dt, node, config) {
                            dt.ajax.url("data/patient.php?skey=<?= $_SESSION['skey']; ?>&psid=8000").load();
                        }
                    }
                ]
            }, ],
            "order": [
                [6, "desc"]
            ],
            searchPanes: {
                initCollapsed: true,
                // cascadePanes: true,
            },
            columnDefs: [{
                    searchPanes: {
                        show: true
                    },
                    targets: [4, 5, 6, 7, 8]
                },
                {
                    searchPanes: {
                        show: false
                    },
                    targets: [0, 1, 2, 3]
                },
                {
                    "render": function(data, type, row) {
                        var renderdata = '<a href="patient_pdf.php?id=' + row[0] + '" class="btn btn-outline-success btn-sm me-1 pdf" target="_blank"><i class="fa-solid fa-file-pdf"></i></a><a href="patient_edit.php?id=' + row[0] + '" class="btn btn-outline-primary btn-sm me-1 edit"><i class="fa-solid fa-marker"></i></a>';
                        <?php if ($isCurUserAdmin) : ?>
                            renderdata += '<a href="patient_del.php?id=' + row[0] + '" class="btn btn-outline-dark btn-sm delete"><i class="fa-solid fa-trash-can"></i></a>';
                        <?php endif; ?>

                        return renderdata;
                    },
                    "targets": -1
                },
                {
                    "render": function(data, type, row) {
                        return '<a href="patient_edit.php?id=' + row[0] + '">' + data + '</a>';
                    },
                    "targets": 1
                },
                {
                    responsivePriority: 1,
                    targets: 1
                },
                {
                    responsivePriority: 2,
                    targets: 2
                },
                {
                    responsivePriority: 3,
                    targets: -1
                },
                {
                    responsivePriority: 10001,
                    targets: 0
                },
            ],
            "initComplete": colorAdd,
        });

        // add color when reload
        table.on('draw', colorAdd);

        // Reload the table data every 30 seconds (paging retained)
        setInterval(function() {
            table.ajax.reload(null, false); // user paging is not reset on reload
        }, 30000);


        // delete user
        $('#patient_table tbody').on('click', 'a.delete', function(e) {
            var data = table.row($(this).parents('tr')).data();

            e.preventDefault();
            if (confirm("Are you sure?")) {
                var frm = $("<form>");
                frm.attr('method', 'post');
                frm.attr('action', "patient_del.php?id=" + data[0]);
                frm.appendTo("body");
                frm.submit();
            }
        });

        // set active tab
        $("#patienttab_8000").addClass("active");


    });
</script>