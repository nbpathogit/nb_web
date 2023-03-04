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
                <a href="#" id="csvdownload" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-download me-2"></i>Export</a>
            </div>

    </div>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded align-items-center justify-content-center p-3 mx-1">


        <table class="table table-hover table-striped" id="billing_table" style="width:100%">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">specimen id</th>
                    <th scope="col">patient id</th>
                    <th scope="col">Number</th>
                    <th scope="col">name</th>
                    <th scope="col">lastname</th>
                    <th scope="col">slide type</th>
                    <th scope="col">code description</th>
                    <th scope="col">description</th>
                    <th scope="col">import date</th>
                    <th scope="col">Date</th>
                    <th scope="col">Hospital</th>
                    <th scope="col">hn</th>
                    <th scope="col">Doctor</th>
                    <th scope="col">pathologist</th>
                    <th scope="col">cost</th>
                    <th scope="col">Others</th>
                </tr>
            </thead>
        </table>

    <?php endif; ?>

    </div>
</div>

<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    $(document).ready(function() {

        var rawdata;

        // table data
        var table = $('#billing_table').DataTable({
            "ajax": "data/billing.php?skey=<?= $_SESSION["skey"]; ?>&range=1m",
            responsive: true,
            dom: 'lBrtip',
            buttons: [{
                extend: 'collection',
                text: 'ระยะเวลาย้อนหลัง',
                autoClose: true,
                buttons: [{
                        text: '1 เดือนล่าสุด',
                        action: function(e, dt, node, config) {
                            dt.ajax.url("data/billing.php?skey=<?= $_SESSION['skey']; ?>&range=1m").load();
                        }
                    },
                    {
                        text: '3 เดือนล่าสุด',
                        action: function(e, dt, node, config) {
                            dt.ajax.url("data/billing.php?skey=<?= $_SESSION['skey']; ?>&range=3m").load();
                        }
                    },
                    {
                        text: '6 เดือนล่าสุด',
                        action: function(e, dt, node, config) {
                            dt.ajax.url("data/billing.php?skey=<?= $_SESSION['skey']; ?>&range=6m").load();
                        }
                    },
                    {
                        text: '1 ปีล่าสุด',
                        action: function(e, dt, node, config) {
                            dt.ajax.url("data/billing.php?skey=<?= $_SESSION['skey']; ?>&range=1y").load();
                        }
                    },
                    {
                        text: '2 ปีล่าสุด',
                        action: function(e, dt, node, config) {
                            dt.ajax.url("data/billing.php?skey=<?= $_SESSION['skey']; ?>&range=2y").load();
                        }
                    },
                    {
                        text: 'ทั้งหมด',
                        action: function(e, dt, node, config) {
                            dt.ajax.url("data/billing.php?skey=<?= $_SESSION['skey']; ?>").load();
                        }
                    }
                ]
            }, ],
            columnDefs: [{
                    "render": function(data, type, row) {
                        return '<b>No.: </b><a href="patient_edit.php?id=' + row[2] + '" target=”_blank”>' + row[3] + '</a><br><b>Name: </b>' + row[4] + " " + row[5] + "<br><b>Description: </b><mute>" + row[8] + "</mute>";
                    },
                    "targets": 3
                },
                {
                    "render": function(data, type, row) {
                        return "<b>Import: </b>" + row[9] + "<br><b>Report: </b>" + (row[10] == null ? "-" : row[10]);
                    },
                    "targets": 10
                },
                {
                    "render": function(data, type, row) {
                        let html = "<b>Hospital:</b>" + row[11];
                        html += "<br><b>HN:</b> " + row[12];
                        return html;
                    },
                    "targets": 11
                },
                {
                    "render": function(data, type, row) {
                        let html = "<b>Send doctor:</b>" + row[13];
                        html += "<br><b>Pathologist:</b> " + row[14];
                        return html;
                    },
                    "targets": 13
                },
                {
                    "render": function(data, type, row) {
                        let html = "<b>#:</b>" + row[0];
                        html += "<br><b>Specimen id:</b> " + row[1];
                        html += "<br><b>Slide type:</b> " + row[6];
                        html += "<br><b>Code description:</b> " + row[7];
                        html += "<br><b>Cost:</b> " + row[15];
                        html += "<br><b>Comment:</b> " + row[16];
                        return html;
                    },
                    "targets": 16
                },
                {
                    visible: false,
                    targets: [0, 1, 2, 4, 5, 6, 7, 8, 9, 12, 14, 15]
                },
            ],
        });

        table.on('draw', function() {
            // console.log(table.rows().data());
            rawdata = table.rows().data();
            console.log(rawdata);
        });

        $("#csvdownload").click(function() {
            var data = [
                ['id', 'specimen_id', 'patient_id', 'number', 'name', 'lastname', 'slide_type', 'code_description', 'description', 'import_date', 'report_date', 'hospital', 'hn', 'send_doctor', 'pathologist', 'cost', 'comment']
            ];

            // console.log(label_history.length);return;

            for (var count = 0; count < rawdata.length; count++) {
                data.push(rawdata[count]);
            }

            let csvContent = "data:text/csv;charset=utf-8," +
                data.map(e => e.join(",")).join("\n");

            var encodedUri = encodeURIComponent("\uFEFF" + csvContent);
            var link = document.createElement("a");
            link.setAttribute('href', 'data:text/csv; charset=utf-8,' + encodedUri);
            link.setAttribute("download", "billingdata_" + rawdata[rawdata.length - 1][9] + "_to_" + rawdata[0][9] + ".csv");
            document.body.appendChild(link);
            link.click();
        });


        //set active tab
        $("#billing_tab").addClass("active");

    });
</script>