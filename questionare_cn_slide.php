<?php
require 'includes/init.php';
// var_dump($_SESSION['user']->id);exit;
// Auth::requireLogin();
Auth::requireLogin("user_sim_admin.php");

$conn = require 'includes/db.php';
require 'user_auth.php';




Log::add($conn, $_SESSION['log_username'], $_SESSION['log_name'], "open questionare_cn_slide.php", "");

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
<?php // require 'includes/data2DOM.php';  ?>

<?php require 'includes/header.php'; ?>



<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">


        <div class="d-flex flex-wrap align-items-center justify-content-between w-100 gap-2">
            <h1 class="mb-0">แบบสอบถาม(CN)</h1>
            <span id="BTN_SELECTION_MESSAGE" class="badge bg-primary fs-6">แสดงจากข้อมูลย้อนหลัง 2 เดือน</span>
        </div>

        <hr class="my-3">

        <div class="d-flex flex-wrap align-items-end gap-3 w-100">
            <div>
                <label for="qn_start_month" class="form-label mb-1 fw-bold">ตั้งแต่เดือน:</label>
                <input type="month" id="qn_start_month" class="form-control form-control-sm" value="<?= date('Y-m', strtotime('-1 month')) ?>">
            </div>
            <div>
                <label for="qn_end_month" class="form-label mb-1 fw-bold">ถึงเดือน:</label>
                <input type="month" id="qn_end_month" class="form-control form-control-sm" value="<?= date('Y-m') ?>">
            </div>
            <div>
                <button type="button" id="btn_filter_month_range" class="btn btn-sm btn-primary">
                    <i class="fa-solid fa-calendar-days me-1"></i>ดูข้อมูลตามช่วงเดือน
                </button>
            </div>
            <div class="ms-auto d-flex flex-wrap gap-1">
                <button type="button" class="btn btn-sm btn-outline-primary qn-quick-range" data-range="1m" data-label="แสดงจากข้อมูลย้อนหลัง 1 เดือน">1 เดือนล่าสุด</button>
                <button type="button" class="btn btn-sm btn-primary qn-quick-range" data-range="2m" data-label="แสดงจากข้อมูลย้อนหลัง 2 เดือน">2 เดือนล่าสุด (ค่าเริ่มต้น)</button>
                <button type="button" class="btn btn-sm btn-outline-primary qn-quick-range" data-range="3m" data-label="แสดงจากข้อมูลย้อนหลัง 3 เดือน">3 เดือนล่าสุด</button>
                <button type="button" class="btn btn-sm btn-outline-primary qn-quick-range" data-range="6m" data-label="แสดงจากข้อมูลย้อนหลัง 6 เดือน">6 เดือนล่าสุด</button>
                <button type="button" class="btn btn-sm btn-outline-primary qn-quick-range" data-range="1y" data-label="แสดงจากข้อมูลย้อนหลัง 1 ปี">1 ปีล่าสุด</button>
                <button type="button" class="btn btn-sm btn-outline-secondary qn-quick-range" data-range="all" data-label="แสดงจากข้อมูลทั้งหมด">ทั้งหมด</button>
            </div>
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
<script type="text/javascript">
    var skey = '<?= $_SESSION["skey"]; ?>';


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

        // table data
        var table = $('#DataTableID').DataTable({
            columns: [
                { title: 'id' }, 
                { title: 'patient_id' },
                { title: 'patient_num' },
                { title: 'accept_date' },
                { title: 'score_thickness' },
                { title: 'score_staining' },
                { title: 'score_mounting' },
                { title: 'score_labeling' },
                { title: 'note' },

            ],
            
            "ajax": "data/questionare_cn_slide_ajax.php?skey=" + skey + "&range=2m",
            responsive: true,
            dom: 'BP<"toolbar">lfritip',
            pageLength: 100,
            processing: true,
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
                    // Data URL generated by http://dataurl.net/#dataurlmaker
                }
            },

            {
                extend: 'collection',
                text: 'ระยะเวลาย้อนหลัง',
                autoClose: true,
                buttons: [
                {
                    text: '1 เดือนล่าสุด',
                    action: function (e, dt, node, config) {
                        $('#BTN_SELECTION_MESSAGE').text('กำลังโหลดข้อมูล...');
                        dt.ajax.url("data/questionare_cn_slide_ajax.php?skey=" + skey + "&range=1m").load(function () {
                            $('#BTN_SELECTION_MESSAGE').text('แสดงจากข้อมูลย้อนหลัง 1 เดือน');
                        });
                    }
                },
                {
                    text: '2 เดือนล่าสุด',
                    action: function (e, dt, node, config) {
                        $('#BTN_SELECTION_MESSAGE').text('กำลังโหลดข้อมูล...');
                        dt.ajax.url("data/questionare_cn_slide_ajax.php?skey=" + skey + "&range=2m").load(function () {
                            $('#BTN_SELECTION_MESSAGE').text('แสดงจากข้อมูลย้อนหลัง 2 เดือน');
                        });
                    }
                },
                {
                    text: '3 เดือนล่าสุด',
                    action: function (e, dt, node, config) {
                        $('#BTN_SELECTION_MESSAGE').text('กำลังโหลดข้อมูล...');
                        dt.ajax.url("data/questionare_cn_slide_ajax.php?skey=" + skey + "&range=3m").load(function () {
                            $('#BTN_SELECTION_MESSAGE').text('แสดงจากข้อมูลย้อนหลัง 3 เดือน');
                        });
                    }
                },
                {
                    text: '6 เดือนล่าสุด',
                    action: function (e, dt, node, config) {
                        $('#BTN_SELECTION_MESSAGE').text('กำลังโหลดข้อมูล...');
                        dt.ajax.url("data/questionare_cn_slide_ajax.php?skey=" + skey + "&range=6m").load(function () {
                            $('#BTN_SELECTION_MESSAGE').text('แสดงจากข้อมูลย้อนหลัง 6 เดือน');
                        });
                    }
                },
                {
                    text: '1 ปีล่าสุด',
                    action: function (e, dt, node, config) {
                        $('#BTN_SELECTION_MESSAGE').text('กำลังโหลดข้อมูล...');
                        dt.ajax.url("data/questionare_cn_slide_ajax.php?skey=" + skey + "&range=1y").load(function () {
                            $('#BTN_SELECTION_MESSAGE').text('แสดงจากข้อมูลย้อนหลัง 1 ปี');
                        });
                    }
                },
                {
                    text: '2 ปีล่าสุด',
                    action: function (e, dt, node, config) {
                        $('#BTN_SELECTION_MESSAGE').text('กำลังโหลดข้อมูล...');
                        dt.ajax.url("data/questionare_cn_slide_ajax.php?skey=" + skey + "&range=2y").load(function () {
                            $('#BTN_SELECTION_MESSAGE').text('แสดงจากข้อมูลย้อนหลัง 2 ปี');
                        });
                    }
                },
                {
                    text: 'ทั้งหมด',
                    action: function (e, dt, node, config) {
                        $('#BTN_SELECTION_MESSAGE').text('กำลังโหลดข้อมูล...');
                        dt.ajax.url("data/questionare_cn_slide_ajax.php?skey=" + skey + "&range=all").load(function () {
                            $('#BTN_SELECTION_MESSAGE').text('แสดงจากข้อมูลทั้งหมด');
                        });
                    }
                }]
            },],
            searchPanes: {
                initCollapsed: true,
                // cascadePanes: true,
            },
            columnDefs: [{
                    searchPanes: {
                        show: true
                    },
                    targets: [0, 1, 2, 3, 4, 5, 6, 7]
                },
                {
                    searchPanes: {
                        show: false
                    },
                    targets: []
                },
                {
                    "render": function (data, type, row) {
                        //example link page
                        return data;
                    },
                    "targets": 1
                },
                {
                    "render": function (data, type, row) {

                        return '<a href="patient_edit.php?id=' + row[1] + '">' + data + '</a>';

                    },
                    "targets": 2
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
                    targets: []
                },
            ]
        });




        // Filter by custom month range
        $('#btn_filter_month_range').on('click', function () {
            var startMonth = $('#qn_start_month').val();
            var endMonth = $('#qn_end_month').val();
            if (!startMonth || !endMonth) {
                alert('กรุณาเลือกทั้งเดือนเริ่มต้นและเดือนสิ้นสุด');
                return;
            }
            if (startMonth > endMonth) {
                var tmp = startMonth;
                startMonth = endMonth;
                endMonth = tmp;
                $('#qn_start_month').val(startMonth);
                $('#qn_end_month').val(endMonth);
            }
            $('.qn-quick-range').removeClass('btn-primary').addClass('btn-outline-primary');
            $('#BTN_SELECTION_MESSAGE').text('กำลังโหลดข้อมูล...');
            var url = "data/questionare_cn_slide_ajax.php?skey=" + encodeURIComponent(skey)
                + "&start_month=" + encodeURIComponent(startMonth)
                + "&end_month=" + encodeURIComponent(endMonth);
            table.ajax.url(url).load(function () {
                if (startMonth === endMonth) {
                    $('#BTN_SELECTION_MESSAGE').text('แสดงข้อมูลประจำเดือน ' + startMonth);
                } else {
                    $('#BTN_SELECTION_MESSAGE').text('แสดงข้อมูลช่วงเดือน ' + startMonth + ' ถึง ' + endMonth);
                }
            });
        });

        // Quick lookback buttons
        $('.qn-quick-range').on('click', function () {
            var $btn = $(this);
            var range = $btn.data('range');
            var label = $btn.data('label');
            $('.qn-quick-range').removeClass('btn-primary').addClass('btn-outline-primary');
            $btn.removeClass('btn-outline-primary btn-outline-secondary').addClass('btn-primary');
            $('#BTN_SELECTION_MESSAGE').text('กำลังโหลดข้อมูล...');
            var url = "data/questionare_cn_slide_ajax.php?skey=" + encodeURIComponent(skey) + "&range=" + encodeURIComponent(range);
            table.ajax.url(url).load(function () {
                $('#BTN_SELECTION_MESSAGE').text(label);
            });
        });

        // set active tab
        $("#view_qn_dropdown").addClass("show");
        $("#view_qn_table").addClass("active");
        $("#qn_cn_tab").addClass("active");
    });
</script>