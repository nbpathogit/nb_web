<?php
require 'includes/init.php';
// var_dump($_SESSION['user']->id);exit;
// Auth::requireLogin();
Auth::requireLogin("user_sim_admin.php");

$conn = require 'includes/db.php';
require 'user_auth.php';





//$ugroups = Ugroup::getAll($conn);
//$hospitals = Hospital::getAll($conn);
//var_dump($hospitals);




if (isset($_GET['id'])) {
    if ($isCurUserAdmin) {
        Auth::adminSimulatelogin($conn, $_GET['id']);
        Url::redirect('/login.php');
    }
}
?>
<?php // require 'includes/data2DOM.php';   ?>

<?php require 'includes/header.php'; ?>



<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">
        
        <!-- Date Range Picker -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="dateRangePicker">เลือกช่วงวันที่:</label>
                <input type="text" id="dateRangePicker" class="form-control" name="dateRangePicker" value="วันนี้" />
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between">

        </div>

    </div>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">

        <table class="table table-hover table-striped text-center" id="DataTableID" style="width:100%">
            <thead>
                <tr>
                </tr>
            </thead>

        </table>



    </div>
</div>




<?php require 'includes/footer.php'; ?>

<!-- Include Moment.js and DateRangePicker CSS and JS -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.js"></script>

<script type="text/javascript">
    var skey = '<?= $_SESSION["skey"]; ?>';

    var datefrom = $("#startdate_billing").val();
    var dateto = $("#enddate_billing").val();

    $(document).ready(function () {
        function processDoc(doc) {
            //
            // https://pdfmake.github.io/docs/fonts/custom-fonts-client-side/
            //
            // Update pdfmake's global font list, using the fonts available in
            // the customized vfs_fonts.js file (do NOT remove the Roboto default):
            pdfMake.fonts = {
                Roboto: {
                    normal: 'Roboto-Regular.ttf',
                    bold: 'Roboto-Medium.ttf',
                    italics: 'Roboto-Italic.ttf',
                    bolditalics: 'Roboto-MediumItalic.ttf'
                },
                angsa: {
                    normal: domain + '/fonts/angsau.ttf',
                    bold: domain + '/fonts/angsaub.ttf',
                    italics: domain + '/fonts/angsaui.ttf',
                    bolditalics: domain + '/fonts/angsauz.ttf',
                }
            };
            // modify the PDF to use a different default font:
            doc.defaultStyle.font = "angsa";
            var i = 1;
        }

        var table;
        $.ajax({
            url: "data/datatable_patient_pn_rev1_ajax.php?skey=" + skey,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                var columns = response.columns || [];
                var columnCount = columns.length;
                var targets = [];
                for (var i = 0; i < columnCount; i++) {
                    targets.push(i);
                }

                table = $('#DataTableID').DataTable({
                    columns: columns,
                    data: response.data,
                    responsive: true,
                    dom: 'BP<"toolbar">lfritip',
                    pageLength: 100,
                    buttons: [
                        {
                            text: 'export excel',
                            extend: 'excel',
                        },
                        {
                            extend: 'csv',
                            text: 'export csv',
                            charset: 'utf-8',
                            extension: '.csv',
                            fieldSeparator: ',',
                            fieldBoundary: '',
                            filename: 'export',
                            bom: true
                        },
                        {
                            text: 'export pdf',
                            extend: 'pdf',
                            customize: function (doc) {
                                processDoc(doc);
                            }
                        },
                    ],
                    searchPanes: {
                        initCollapsed: true,
                    },
                    columnDefs: [{
                        searchPanes: {
                            show: true
                        },
                        targets: targets
                    }]
                });
            }
        });

        // Initialize Date Range Picker
        $('#dateRangePicker').daterangepicker({
            "locale": {
                "format": "DD/MM/YYYY",
                "separator": " - ",
                "applyLabel": "ยืนยัน",
                "cancelLabel": "ยกเลิก",
                "fromLabel": "จาก",
                "toLabel": "ถึง",
                "customRangeLabel": "กำหนดเอง",
                "weekLabel": "ส",
                "daysOfWeek": ["อา", "จ", "อ", "พ", "พฤ", "ศ", "ส"],
                "monthNames": ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"],
                "firstDay": 1
            },
            "startDate": moment(),
            "endDate": moment(),
            "ranges": {
                'วันนี้': [moment().startOf('day'), moment()],
                'เมื่อวาน': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '7 วันล่าสุด': [moment().subtract(6, 'days'), moment()],
                '30 วันล่าสุด': [moment().subtract(29, 'days'), moment()],
                'เดือนนี้': [moment().startOf('month'), moment().endOf('month')],
                'เดือนที่แล้ว': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                '3 เดือนล่าสุด': [moment().subtract(2, 'month').startOf('month'), moment()],
                'ปีนี้': [moment().startOf('year'), moment()]
            },
            "alwaysShowCalendars": true,
            "showDropdowns": true
        }, function(start, end, label) {
            // Update the input field with the selected range
            $('#dateRangePicker').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
            
            // Reload table with new date range
            var startDate = start.format('YYYY-MM-DD');
            var endDate = end.format('YYYY-MM-DD');
            reloadTable("data/datatable_patient_pn_rev1_ajax.php?skey=" + skey + "&startDate=" + startDate + "&endDate=" + endDate);
        });

        function reloadTable(url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    table.clear();
                    table.rows.add(response.data);
                    table.draw();
                }
            });
        }

        // delete user
//        $('#DataTableID tbody').on('click', 'a.delete', function(e) {
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
        $("#SIDE_tab").addClass("active");
        $("#SUBSIDEBAR_tab").addClass("active");
    });
</script>