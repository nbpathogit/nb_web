<?php
require 'includes/init.php';
// var_dump($_SESSION['user']->id);exit;
// Auth::requireLogin();
Auth::requireLogin("user_sim_admin.php");

$conn = require 'includes/db.php';
require 'user_auth.php';


Log::add($conn, $_SESSION['log_username'], $_SESSION['log_name'], "open questionare_sn_slide.php", "");





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

<!-- Chart.js (CDN with local fallback) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" onerror="this.onerror=null;this.src='lib/chart/chart.min.js';"></script>

<style>
    .pgdm-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid rgba(15, 23, 42, 0.08);
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.05);
        padding: 1.15rem 1.25rem;
        height: 100%;
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }
    .pgdm-card:hover {
        box-shadow: 0 8px 26px rgba(15, 23, 42, 0.09);
    }
    .pgdm-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        margin-bottom: 0.85rem;
        padding-bottom: 0.65rem;
        border-bottom: 1px solid #f1f5f9;
        flex-wrap: wrap;
    }
    .pgdm-card-title {
        font-size: 1.02rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .pgdm-card-subtitle {
        font-size: 0.8rem;
        color: #64748b;
        margin: 0.15rem 0 0 0;
    }
    .pgdm-chart-container {
        position: relative;
        width: 100%;
        height: 290px;
    }
    .pgdm-kpi-row {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.55rem;
        margin-bottom: 0.8rem;
    }
    @media (max-width: 768px) {
        .pgdm-kpi-row {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    .pgdm-kpi-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-left: 3.5px solid #6366f1;
        border-radius: 8px;
        padding: 0.4rem 0.65rem;
        line-height: 1.25;
    }
    .pgdm-kpi-item.kpi-indigo  { border-left-color: #4f46e5; }
    .pgdm-kpi-item.kpi-emerald { border-left-color: #10b981; }
    .pgdm-kpi-item.kpi-amber   { border-left-color: #f59e0b; }
    .pgdm-kpi-item.kpi-rose    { border-left-color: #f43f5e; }
    .pgdm-kpi-item.kpi-sky     { border-left-color: #0284c7; }
    .pgdm-kpi-label {
        font-size: 0.72rem;
        color: #64748b;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .pgdm-kpi-value {
        font-size: 0.98rem;
        font-weight: 800;
        color: #0f172a;
        margin-top: 0.1rem;
    }
    .pgdm-kpi-sub {
        font-size: 0.68rem;
        color: #94a3b8;
        font-weight: 500;
    }
    .pgdm-fullscreen-btn {
        width: 30px;
        height: 30px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 7px;
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        color: #475569;
        transition: all 0.15s ease;
        flex-shrink: 0;
    }
    .pgdm-fullscreen-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .pgdm-card.pgdm-card-fullscreen {
        position: fixed !important;
        inset: 14px !important;
        z-index: 2050 !important;
        width: auto !important;
        height: calc(100vh - 28px) !important;
        max-height: calc(100vh - 28px) !important;
        overflow-y: auto !important;
        box-shadow: 0 25px 60px rgba(15, 23, 42, 0.45) !important;
        display: flex;
        flex-direction: column;
    }
    .pgdm-card.pgdm-card-fullscreen .pgdm-chart-container {
        flex: 1 1 auto;
        height: calc(100vh - 220px) !important;
        min-height: 360px;
    }
</style>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">


        <div class="d-flex flex-wrap align-items-center justify-content-between w-100 gap-2">
            <h1 class="mb-0">แบบสอบถาม(SN)</h1>
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

<!-- Trend Charts Row (styled like patient_global_dashboard_main.php) -->
<div class="container-fluid pt-4 px-4">
    <div class="row g-3 mx-0">
        <!-- Left Card: Quality Score Trend -->
        <div class="col-12 col-xl-6 px-1">
            <div class="pgdm-card" id="qn_card_score_trend">
                <div class="pgdm-card-header">
                    <div>
                        <h5 class="pgdm-card-title">
                            <i class="fa-solid fa-chart-line text-primary"></i>
                            แนวโน้มคะแนนคุณภาพสไลด์ (SN) เฉลี่ยตามช่วงเวลา
                        </h5>
                        <p class="pgdm-card-subtitle">คะแนนเต็ม 5.00 แยกตามหัวข้อการประเมิน (อ้างอิงตามช่วงเวลาและตัวกรองในตาราง)</p>
                    </div>
                    <div class="d-flex align-items-center gap-1 flex-wrap">
                        <select id="qn_score_metric_select" class="form-select form-select-sm" style="width:auto;font-size:0.78rem;font-weight:600;">
                            <option value="all_lines" selected>แยกตามหัวข้อ (5 ด้าน)</option>
                            <option value="overall_only">คะแนนเฉลี่ยรวมทุกด้าน</option>
                            <option value="thickness">เฉพาะ Thickness</option>
                            <option value="staining">เฉพาะ Staining</option>
                            <option value="mounting">เฉพาะ Mounting</option>
                            <option value="labeling">เฉพาะ Labeling</option>
                            <option value="contaminate">เฉพาะ Contaminate</option>
                        </select>
                        <select id="qn_time_bucket_select" class="form-select form-select-sm" style="width:auto;font-size:0.78rem;font-weight:600;">
                            <option value="auto" selected>มุมมอง: อัตโนมัติ</option>
                            <option value="month">รายเดือน (YYYY-MM)</option>
                            <option value="week">รายสัปดาห์</option>
                            <option value="day">รายวัน (YYYY-MM-DD)</option>
                        </select>
                        <button type="button" class="pgdm-fullscreen-btn" onclick="qnToggleCardFullscreen('qn_card_score_trend')" title="ขยายเต็มจอ">
                            <i class="fa-solid fa-maximize"></i>
                        </button>
                    </div>
                </div>
                <div class="pgdm-kpi-row">
                    <div class="pgdm-kpi-item kpi-indigo">
                        <div class="pgdm-kpi-label">คะแนนเฉลี่ยรวม (μ)</div>
                        <div class="pgdm-kpi-value" id="kpi_sn_score_mean">-</div>
                        <div class="pgdm-kpi-sub">จากคะแนนเต็ม 5.00</div>
                    </div>
                    <div class="pgdm-kpi-item kpi-amber">
                        <div class="pgdm-kpi-label">ส่วนเบี่ยงเบน (σ)</div>
                        <div class="pgdm-kpi-value" id="kpi_sn_score_sd">-</div>
                        <div class="pgdm-kpi-sub">ความผันผวนระหว่างเคส</div>
                    </div>
                    <div class="pgdm-kpi-item kpi-emerald">
                        <div class="pgdm-kpi-label">ด้านที่ได้คะแนนสูงสุด</div>
                        <div class="pgdm-kpi-value" id="kpi_sn_best_dim">-</div>
                        <div class="pgdm-kpi-sub" id="kpi_sn_best_dim_sub">ค่าเฉลี่ยสูงสุด</div>
                    </div>
                    <div class="pgdm-kpi-item kpi-rose">
                        <div class="pgdm-kpi-label">ด้านที่ควรเฝ้าระวัง</div>
                        <div class="pgdm-kpi-value" id="kpi_sn_low_dim">-</div>
                        <div class="pgdm-kpi-sub" id="kpi_sn_low_dim_sub">ค่าเฉลี่ยต่ำสุด</div>
                    </div>
                </div>
                <div class="pgdm-chart-container">
                    <canvas id="qnSnScoreTrendCanvas"></canvas>
                </div>
            </div>
        </div>

        <!-- Right Card: Questionnaire Volume & Score Distribution Trend -->
        <div class="col-12 col-xl-6 px-1">
            <div class="pgdm-card" id="qn_card_volume_trend">
                <div class="pgdm-card-header">
                    <div>
                        <h5 class="pgdm-card-title">
                            <i class="fa-solid fa-chart-column text-success"></i>
                            แนวโน้มจำนวนแบบประเมินสไลด์ (SN) ตามช่วงเวลา
                        </h5>
                        <p class="pgdm-card-subtitle">จำนวนเคสที่ได้รับการประเมินและสัดส่วนคุณภาพในแต่ละช่วงเวลา</p>
                    </div>
                    <div class="d-flex align-items-center gap-1 flex-wrap">
                        <select id="qn_vol_mode_select" class="form-select form-select-sm" style="width:auto;font-size:0.78rem;font-weight:600;">
                            <option value="stacked_grade" selected>แยกตามระดับคะแนนเฉลี่ย</option>
                            <option value="total_only">จำนวนแบบประเมินรวมทั้งหมด</option>
                        </select>
                        <button type="button" class="pgdm-fullscreen-btn" onclick="qnToggleCardFullscreen('qn_card_volume_trend')" title="ขยายเต็มจอ">
                            <i class="fa-solid fa-maximize"></i>
                        </button>
                    </div>
                </div>
                <div class="pgdm-kpi-row">
                    <div class="pgdm-kpi-item kpi-sky">
                        <div class="pgdm-kpi-label">จำนวนแบบประเมินรวม</div>
                        <div class="pgdm-kpi-value" id="kpi_sn_vol_total">-</div>
                        <div class="pgdm-kpi-sub">เคสในตารางปัจจุบัน</div>
                    </div>
                    <div class="pgdm-kpi-item kpi-indigo">
                        <div class="pgdm-kpi-label">เฉลี่ย / ช่วงเวลา (μ)</div>
                        <div class="pgdm-kpi-value" id="kpi_sn_vol_mean">-</div>
                        <div class="pgdm-kpi-sub" id="kpi_sn_vol_mean_sub">เคสต่อช่วงเวลา</div>
                    </div>
                    <div class="pgdm-kpi-item kpi-amber">
                        <div class="pgdm-kpi-label">ส่วนเบี่ยงเบน (σ)</div>
                        <div class="pgdm-kpi-value" id="kpi_sn_vol_sd">-</div>
                        <div class="pgdm-kpi-sub">ความผันผวนปริมาณเคส</div>
                    </div>
                    <div class="pgdm-kpi-item kpi-emerald">
                        <div class="pgdm-kpi-label">ช่วงเวลาสูงสุด (Peak)</div>
                        <div class="pgdm-kpi-value" id="kpi_sn_vol_peak">-</div>
                        <div class="pgdm-kpi-sub" id="kpi_sn_vol_peak_sub">-</div>
                    </div>
                </div>
                <div class="pgdm-chart-container">
                    <canvas id="qnSnVolumeTrendCanvas"></canvas>
                </div>
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
                { title: 'score_contaminate' },
                { title: 'note' },

            ],
            
            "ajax": "data/questionare_sn_slide_ajax.php?skey=" + skey + "&range=2m",
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
                        dt.ajax.url("data/questionare_sn_slide_ajax.php?skey=" + skey + "&range=1m").load(function () {
                            $('#BTN_SELECTION_MESSAGE').text('แสดงจากข้อมูลย้อนหลัง 1 เดือน');
                        });
                    }
                },
                {
                    text: '2 เดือนล่าสุด',
                    action: function (e, dt, node, config) {
                        $('#BTN_SELECTION_MESSAGE').text('กำลังโหลดข้อมูล...');
                        dt.ajax.url("data/questionare_sn_slide_ajax.php?skey=" + skey + "&range=2m").load(function () {
                            $('#BTN_SELECTION_MESSAGE').text('แสดงจากข้อมูลย้อนหลัง 2 เดือน');
                        });
                    }
                },
                {
                    text: '3 เดือนล่าสุด',
                    action: function (e, dt, node, config) {
                        $('#BTN_SELECTION_MESSAGE').text('กำลังโหลดข้อมูล...');
                        dt.ajax.url("data/questionare_sn_slide_ajax.php?skey=" + skey + "&range=3m").load(function () {
                            $('#BTN_SELECTION_MESSAGE').text('แสดงจากข้อมูลย้อนหลัง 3 เดือน');
                        });
                    }
                },
                {
                    text: '6 เดือนล่าสุด',
                    action: function (e, dt, node, config) {
                        $('#BTN_SELECTION_MESSAGE').text('กำลังโหลดข้อมูล...');
                        dt.ajax.url("data/questionare_sn_slide_ajax.php?skey=" + skey + "&range=6m").load(function () {
                            $('#BTN_SELECTION_MESSAGE').text('แสดงจากข้อมูลย้อนหลัง 6 เดือน');
                        });
                    }
                },
                {
                    text: '1 ปีล่าสุด',
                    action: function (e, dt, node, config) {
                        $('#BTN_SELECTION_MESSAGE').text('กำลังโหลดข้อมูล...');
                        dt.ajax.url("data/questionare_sn_slide_ajax.php?skey=" + skey + "&range=1y").load(function () {
                            $('#BTN_SELECTION_MESSAGE').text('แสดงจากข้อมูลย้อนหลัง 1 ปี');
                        });
                    }
                },
                {
                    text: '2 ปีล่าสุด',
                    action: function (e, dt, node, config) {
                        $('#BTN_SELECTION_MESSAGE').text('กำลังโหลดข้อมูล...');
                        dt.ajax.url("data/questionare_sn_slide_ajax.php?skey=" + skey + "&range=2y").load(function () {
                            $('#BTN_SELECTION_MESSAGE').text('แสดงจากข้อมูลย้อนหลัง 2 ปี');
                        });
                    }
                },
                {
                    text: 'ทั้งหมด',
                    action: function (e, dt, node, config) {
                        $('#BTN_SELECTION_MESSAGE').text('กำลังโหลดข้อมูล...');
                        dt.ajax.url("data/questionare_sn_slide_ajax.php?skey=" + skey + "&range=all").load(function () {
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




        // =========================================================================
        // Trend Charts (Score Trend + Volume Trend) - synced with DataTable
        // =========================================================================
        var snScoreChart = null;
        var snVolumeChart = null;

        window.qnToggleCardFullscreen = function (cardId) {
            var card = document.getElementById(cardId);
            if (!card) return;
            var isFs = card.classList.toggle('pgdm-card-fullscreen');
            var btnIcon = card.querySelector('.pgdm-fullscreen-btn i');
            if (btnIcon) {
                btnIcon.className = isFs ? 'fa-solid fa-minimize' : 'fa-solid fa-maximize';
            }
            setTimeout(function () {
                if (snScoreChart) snScoreChart.resize();
                if (snVolumeChart) snVolumeChart.resize();
            }, 60);
        };

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                var fsCard = document.querySelector('.pgdm-card.pgdm-card-fullscreen');
                if (fsCard && fsCard.id) {
                    window.qnToggleCardFullscreen(fsCard.id);
                }
            }
        });

        function calcStats(arr) {
            var valid = [];
            for (var i = 0; i < arr.length; i++) {
                var v = Number(arr[i]);
                if (!isNaN(v) && isFinite(v)) valid.push(v);
            }
            if (!valid.length) return { count: 0, sum: 0, mean: 0, sd: 0, min: 0, max: 0 };
            var sum = 0;
            var min = valid[0];
            var max = valid[0];
            for (var j = 0; j < valid.length; j++) {
                sum += valid[j];
                if (valid[j] < min) min = valid[j];
                if (valid[j] > max) max = valid[j];
            }
            var mean = sum / valid.length;
            var variance = 0;
            for (var k = 0; k < valid.length; k++) {
                var d = valid[k] - mean;
                variance += d * d;
            }
            var sd = Math.sqrt(variance / valid.length);
            return { count: valid.length, sum: sum, mean: mean, sd: sd, min: min, max: max };
        }

        function getIsoWeekBucket(dateStr) {
            // dateStr expected YYYY-MM-DD...
            var parts = dateStr.substring(0, 10).split('-');
            if (parts.length < 3) return dateStr.substring(0, 7);
            var d = new Date(Number(parts[0]), Number(parts[1]) - 1, Number(parts[2]));
            if (isNaN(d.getTime())) return dateStr.substring(0, 7);
            // Find Monday of the week
            var day = d.getDay();
            var diff = d.getDate() - day + (day === 0 ? -6 : 1);
            var monday = new Date(d.setDate(diff));
            var yyyy = monday.getFullYear();
            var mm = String(monday.getMonth() + 1).padStart(2, '0');
            var dd = String(monday.getDate()).padStart(2, '0');
            return yyyy + '-' + mm + '-' + dd;
        }

        function resolveBucketMode(rows, selectedMode) {
            if (selectedMode !== 'auto') return selectedMode;
            var months = {};
            var days = {};
            for (var i = 0; i < rows.length; i++) {
                var dt = String(rows[i][3] || '').trim();
                if (dt.length >= 10 && dt.substring(0, 4) !== '0000') {
                    months[dt.substring(0, 7)] = true;
                    days[dt.substring(0, 10)] = true;
                }
            }
            var mCount = Object.keys(months).length;
            var dCount = Object.keys(days).length;
            if (mCount >= 4) return 'month';
            if (dCount > 21) return 'week';
            return 'day';
        }

        function updateSnTrendCharts() {
            if (typeof Chart === 'undefined') return;
            var rows = table.rows({ search: 'applied' }).data().toArray();
            var bucketPref = $('#qn_time_bucket_select').val() || 'auto';
            var bucketMode = resolveBucketMode(rows, bucketPref);
            var scoreMetric = $('#qn_score_metric_select').val() || 'all_lines';
            var volMode = $('#qn_vol_mode_select').val() || 'stacked_grade';

            var bucketMap = {};
            var caseOveralls = [];
            var dimTotals = {
                thickness: [],
                staining: [],
                mounting: [],
                labeling: [],
                contaminate: []
            };

            for (var i = 0; i < rows.length; i++) {
                var r = rows[i];
                var rawDate = String(r[3] || '').trim();
                if (!rawDate || rawDate.substring(0, 4) === '0000') continue;

                var bKey = '';
                if (bucketMode === 'month') {
                    bKey = rawDate.substring(0, 7);
                } else if (bucketMode === 'week') {
                    bKey = getIsoWeekBucket(rawDate);
                } else {
                    bKey = rawDate.substring(0, 10);
                }

                if (!bucketMap[bKey]) {
                    bucketMap[bKey] = {
                        count: 0,
                        thickness: [],
                        staining: [],
                        mounting: [],
                        labeling: [],
                        contaminate: [],
                        overall: [],
                        gradeHigh: 0,
                        gradeMid: 0,
                        gradeLow: 0
                    };
                }

                var sThick = parseFloat(r[4]);
                var sStain = parseFloat(r[5]);
                var sMount = parseFloat(r[6]);
                var sLabel = parseFloat(r[7]);
                var sContam = parseFloat(r[8]);

                var validScores = [];
                if (!isNaN(sThick) && sThick > 0) { bucketMap[bKey].thickness.push(sThick); dimTotals.thickness.push(sThick); validScores.push(sThick); }
                if (!isNaN(sStain) && sStain > 0) { bucketMap[bKey].staining.push(sStain); dimTotals.staining.push(sStain); validScores.push(sStain); }
                if (!isNaN(sMount) && sMount > 0) { bucketMap[bKey].mounting.push(sMount); dimTotals.mounting.push(sMount); validScores.push(sMount); }
                if (!isNaN(sLabel) && sLabel > 0) { bucketMap[bKey].labeling.push(sLabel); dimTotals.labeling.push(sLabel); validScores.push(sLabel); }
                if (!isNaN(sContam) && sContam > 0) { bucketMap[bKey].contaminate.push(sContam); dimTotals.contaminate.push(sContam); validScores.push(sContam); }

                bucketMap[bKey].count++;
                if (validScores.length > 0) {
                    var rowMean = 0;
                    for (var vIdx = 0; vIdx < validScores.length; vIdx++) rowMean += validScores[vIdx];
                    rowMean = rowMean / validScores.length;
                    bucketMap[bKey].overall.push(rowMean);
                    caseOveralls.push(rowMean);

                    if (rowMean >= 4.5) bucketMap[bKey].gradeHigh++;
                    else if (rowMean >= 3.5) bucketMap[bKey].gradeMid++;
                    else bucketMap[bKey].gradeLow++;
                } else {
                    bucketMap[bKey].gradeMid++;
                }
            }

            var keys = Object.keys(bucketMap).sort();
            var labels = keys.map(function (k) {
                return bucketMode === 'week' ? ('สัปดาห์ ' + k) : k;
            });

            // KPI 1: Score KPIs
            var overallStat = calcStats(caseOveralls);
            $('#kpi_sn_score_mean').text(overallStat.count ? overallStat.mean.toFixed(2) : '-');
            $('#kpi_sn_score_sd').text(overallStat.count ? ('±' + overallStat.sd.toFixed(2)) : '-');

            var dimMeta = [
                { key: 'thickness', label: 'Thickness' },
                { key: 'staining', label: 'Staining' },
                { key: 'mounting', label: 'Mounting' },
                { key: 'labeling', label: 'Labeling' },
                { key: 'contaminate', label: 'Contaminate' }
            ];
            var bestDim = null;
            var lowDim = null;
            for (var d = 0; d < dimMeta.length; d++) {
                var st = calcStats(dimTotals[dimMeta[d].key]);
                if (st.count > 0) {
                    if (!bestDim || st.mean > bestDim.mean) bestDim = { label: dimMeta[d].label, mean: st.mean };
                    if (!lowDim || st.mean < lowDim.mean) lowDim = { label: dimMeta[d].label, mean: st.mean };
                }
            }
            $('#kpi_sn_best_dim').text(bestDim ? bestDim.label : '-');
            $('#kpi_sn_best_dim_sub').text(bestDim ? ('เฉลี่ย ' + bestDim.mean.toFixed(2) + ' / 5.00') : '-');
            $('#kpi_sn_low_dim').text(lowDim ? lowDim.label : '-');
            $('#kpi_sn_low_dim_sub').text(lowDim ? ('เฉลี่ย ' + lowDim.mean.toFixed(2) + ' / 5.00') : '-');

            // KPI 2: Volume KPIs
            var bucketCounts = keys.map(function (k) { return bucketMap[k].count; });
            var volStat = calcStats(bucketCounts);
            var bucketUnitText = bucketMode === 'month' ? 'เดือน' : (bucketMode === 'week' ? 'สัปดาห์' : 'วัน');
            $('#kpi_sn_vol_total').text(rows.length.toLocaleString() + ' เคส');
            $('#kpi_sn_vol_mean').text(volStat.count ? volStat.mean.toFixed(1) : '0');
            $('#kpi_sn_vol_mean_sub').text('เคส / ' + bucketUnitText);
            $('#kpi_sn_vol_sd').text(volStat.count ? ('±' + volStat.sd.toFixed(1)) : '-');

            var peakKey = '-';
            var peakVal = 0;
            for (var pk = 0; pk < keys.length; pk++) {
                if (bucketMap[keys[pk]].count > peakVal) {
                    peakVal = bucketMap[keys[pk]].count;
                    peakKey = keys[pk];
                }
            }
            $('#kpi_sn_vol_peak').text(peakVal > 0 ? (peakVal.toLocaleString() + ' เคส') : '-');
            $('#kpi_sn_vol_peak_sub').text(peakVal > 0 ? ('ช่วง ' + peakKey) : '-');

            // Build Score Trend Datasets
            var avgSeries = function (field) {
                return keys.map(function (k) {
                    var st = calcStats(bucketMap[k][field]);
                    return st.count ? Number(st.mean.toFixed(2)) : null;
                });
            };

            var scoreDatasets = [];
            if (scoreMetric === 'all_lines') {
                scoreDatasets = [
                    { label: 'Thickness', data: avgSeries('thickness'), borderColor: '#4f46e5', backgroundColor: '#4f46e5', tension: 0.3, borderWidth: 2, pointRadius: 3 },
                    { label: 'Staining', data: avgSeries('staining'), borderColor: '#10b981', backgroundColor: '#10b981', tension: 0.3, borderWidth: 2, pointRadius: 3 },
                    { label: 'Mounting', data: avgSeries('mounting'), borderColor: '#f59e0b', backgroundColor: '#f59e0b', tension: 0.3, borderWidth: 2, pointRadius: 3 },
                    { label: 'Labeling', data: avgSeries('labeling'), borderColor: '#0284c7', backgroundColor: '#0284c7', tension: 0.3, borderWidth: 2, pointRadius: 3 },
                    { label: 'Contaminate', data: avgSeries('contaminate'), borderColor: '#f43f5e', backgroundColor: '#f43f5e', tension: 0.3, borderWidth: 2, pointRadius: 3 }
                ];
            } else {
                var targetField = scoreMetric === 'overall_only' ? 'overall' : scoreMetric;
                var targetLabel = scoreMetric === 'overall_only' ? 'คะแนนเฉลี่ยรวมทุกด้าน' : ('คะแนนเฉลี่ย ' + scoreMetric.charAt(0).toUpperCase() + scoreMetric.slice(1));
                var seriesData = avgSeries(targetField);
                var seriesStat = calcStats(seriesData.filter(function (x) { return x !== null; }));
                scoreDatasets = [
                    {
                        type: 'line',
                        label: targetLabel,
                        data: seriesData,
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.14)',
                        fill: true,
                        tension: 0.32,
                        borderWidth: 2.5,
                        pointRadius: 4
                    },
                    {
                        type: 'line',
                        label: 'ค่าเฉลี่ยรวม (μ = ' + (seriesStat.count ? seriesStat.mean.toFixed(2) : '-') + ')',
                        data: keys.map(function () { return seriesStat.count ? Number(seriesStat.mean.toFixed(2)) : null; }),
                        borderColor: '#ef4444',
                        borderDash: [6, 5],
                        borderWidth: 1.8,
                        pointRadius: 0,
                        fill: false
                    }
                ];
            }

            var ctxScore = document.getElementById('qnSnScoreTrendCanvas');
            if (ctxScore) {
                if (snScoreChart) snScoreChart.destroy();
                snScoreChart = new Chart(ctxScore, {
                    type: 'line',
                    data: { labels: labels, datasets: scoreDatasets },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11 } } }
                        },
                        scales: {
                            x: { ticks: { maxRotation: 45, font: { size: 11 } }, grid: { display: false } },
                            y: {
                                min: 1,
                                max: 5.1,
                                ticks: { stepSize: 0.5, font: { size: 11 } },
                                title: { display: true, text: 'คะแนนเฉลี่ย (1 - 5)', font: { size: 11, weight: 'bold' } }
                            }
                        }
                    }
                });
            }

            // Build Volume Trend Datasets
            var volDatasets = [];
            var meanLineData = keys.map(function () { return volStat.count ? Number(volStat.mean.toFixed(1)) : 0; });
            if (volMode === 'stacked_grade') {
                volDatasets = [
                    {
                        type: 'bar',
                        label: 'ดีมาก (4.50 - 5.00)',
                        data: keys.map(function (k) { return bucketMap[k].gradeHigh; }),
                        backgroundColor: 'rgba(16, 185, 129, 0.82)',
                        borderRadius: 4,
                        stack: 'volStack',
                        order: 2
                    },
                    {
                        type: 'bar',
                        label: 'ดี (3.50 - 4.49)',
                        data: keys.map(function (k) { return bucketMap[k].gradeMid; }),
                        backgroundColor: 'rgba(59, 130, 246, 0.82)',
                        borderRadius: 4,
                        stack: 'volStack',
                        order: 2
                    },
                    {
                        type: 'bar',
                        label: 'ปานกลาง/ควรปรับปรุง (< 3.50)',
                        data: keys.map(function (k) { return bucketMap[k].gradeLow; }),
                        backgroundColor: 'rgba(244, 63, 94, 0.82)',
                        borderRadius: 4,
                        stack: 'volStack',
                        order: 2
                    },
                    {
                        type: 'line',
                        label: 'ค่าเฉลี่ย (μ = ' + (volStat.count ? volStat.mean.toFixed(1) : '0') + ' เคส)',
                        data: meanLineData,
                        borderColor: '#f59e0b',
                        borderDash: [6, 5],
                        borderWidth: 2,
                        pointRadius: 0,
                        fill: false,
                        order: 1
                    }
                ];
            } else {
                volDatasets = [
                    {
                        type: 'bar',
                        label: 'จำนวนแบบประเมิน (เคส)',
                        data: bucketCounts,
                        backgroundColor: 'rgba(14, 165, 233, 0.78)',
                        borderColor: '#0284c7',
                        borderWidth: 1,
                        borderRadius: 5,
                        order: 2
                    },
                    {
                        type: 'line',
                        label: 'ค่าเฉลี่ย (μ = ' + (volStat.count ? volStat.mean.toFixed(1) : '0') + ' เคส)',
                        data: meanLineData,
                        borderColor: '#ef4444',
                        borderDash: [6, 5],
                        borderWidth: 2,
                        pointRadius: 0,
                        fill: false,
                        order: 1
                    }
                ];
            }

            var ctxVol = document.getElementById('qnSnVolumeTrendCanvas');
            if (ctxVol) {
                if (snVolumeChart) snVolumeChart.destroy();
                snVolumeChart = new Chart(ctxVol, {
                    type: 'bar',
                    data: { labels: labels, datasets: volDatasets },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11 } } }
                        },
                        scales: {
                            x: {
                                stacked: volMode === 'stacked_grade',
                                ticks: { maxRotation: 45, font: { size: 11 } },
                                grid: { display: false }
                            },
                            y: {
                                stacked: volMode === 'stacked_grade',
                                beginAtZero: true,
                                ticks: { precision: 0, font: { size: 11 } },
                                title: { display: true, text: 'จำนวนเคส', font: { size: 11, weight: 'bold' } }
                            }
                        }
                    }
                });
            }
        }

        // Sync charts when DataTable finishes loading or filtering
        table.on('xhr.dt draw.dt search.dt', function () {
            setTimeout(updateSnTrendCharts, 50);
        });

        $('#qn_score_metric_select, #qn_time_bucket_select, #qn_vol_mode_select').on('change', function () {
            updateSnTrendCharts();
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
            var url = "data/questionare_sn_slide_ajax.php?skey=" + encodeURIComponent(skey)
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
            var url = "data/questionare_sn_slide_ajax.php?skey=" + encodeURIComponent(skey) + "&range=" + encodeURIComponent(range);
            table.ajax.url(url).load(function () {
                $('#BTN_SELECTION_MESSAGE').text(label);
            });
        });

        // set active tab
        $("#view_qn_dropdown").addClass("show");
        $("#view_qn_table").addClass("active");
        $("#qn_sn_tab").addClass("active");
    });
</script>