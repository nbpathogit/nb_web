<?php
//session_start();
require "includes/init.php";
$conn = require "includes/db.php";
Auth::requireLogin();
require "user_auth.php";
//var_dump($_POST);

//Add record to database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //    if (isset($_POST['add'])) {
    //
    //        $labelPrint = new LabelPrint();
    //
    //        $labelPrint->userid = $_POST['userid'];
    //        $labelPrint->sn_num = $_POST['sn_num'];
    //        $labelPrint->hn_num = $_POST['hn_num'];
    //        $labelPrint->patho_abbreviation = $_POST['patho_abbreviation'];
    //        $labelPrint->speciment_abbreviation = $_POST['speciment_abbreviation'];
    //        $labelPrint->accept_date = $_POST['accept_date'];
    //
    //        if ($labelPrint->create($conn)) {
    //
    //            Url::redirect("/generate_label.php");
    //        } else {
    //            echo '<script>alert("Add user fail. Please verify again")</script>';
    //        }
    //    }

    if (isset($_POST["delAll"])) {
        if (LabelPrint::deleteAllbyUserID($conn, $_POST["userid"])) {
            Url::redirect("/generate_label.php");
        } else {
            echo '<script>alert("Delete fail. Please verify again")</script>';
        }
    }

    //    if (isset($_POST['viewpdf1'])) {
    //        Url::redirect("/patient_sn_pdf1.php");
    //    }
}

//Get Specific Row from Table for generate pdf
$labelPrints = LabelPrint::getAllbyUserID($conn, $_SESSION["userid"]);

//
$patientLists = Patient::getAllJoin_forlableprint($conn, 1);

//var_dump($patientLists);

if (!$labelPrints) {
    // Skip show table
}
?>


<?php require "includes/header.php"; ?>

<style>
    :root {
        --primary-color: #009CFF;
        --primary-dark: #007ACC;
        --primary-light: #E3F2FF;
        --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        --card-shadow-hover: 0 8px 25px rgba(0, 156, 255, 0.15);
    }

    .modern-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        border: 1px solid rgba(0, 156, 255, 0.1);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .modern-card:hover {
        box-shadow: var(--card-shadow-hover);
        border-color: var(--primary-color);
    }

    .card-header-custom {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: #ffffff;
        padding: 1.25rem 1.5rem;
        border: none;
        border-bottom: 3px solid rgba(255, 255, 255, 0.3);
    }

    .card-header-custom h1 {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
        color: #ffffff;
    }

    .card-header-custom h3 {
        font-size: 1.1rem;
        font-weight: 500;
        margin: 0;
        color: #ffffff;
        opacity: 0.95;
    }

    .card-body-custom {
        padding: 1.5rem;
    }

    .modern-label {
        font-weight: 600;
        color: var(--primary-dark);
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .modern-form-control {
        border: 2px solid #E8EEF5;
        border-radius: 8px;
        padding: 0.6rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #FAFBFD;
    }

    .modern-form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(0, 156, 255, 0.1);
        background: #ffffff;
    }

    .modern-form-control[readonly] {
        background: #F5F8FC;
        cursor: default;
    }

    .modern-select {
        border: 2px solid #E8EEF5;
        border-radius: 8px;
        padding: 0.6rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #FAFBFD;
        cursor: pointer;
    }

    .modern-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(0, 156, 255, 0.1);
        background: #ffffff;
    }

    .modern-btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border: none;
        color: #ffffff;
        padding: 0.7rem 1.8rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 156, 255, 0.3);
    }

    .modern-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 156, 255, 0.4);
        color: #ffffff;
    }

    .modern-btn-success {
        background: linear-gradient(135deg, #28A745 0%, #218838 100%);
        border: none;
        color: #ffffff;
        padding: 0.8rem 2rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    }

    .modern-btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
        color: #ffffff;
    }

    .modern-btn-danger {
        background: linear-gradient(135deg, #DC3545 0%, #C82333 100%);
        border: none;
        color: #ffffff;
        padding: 0.7rem 1.8rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
    }

    .modern-btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
        color: #ffffff;
    }

    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
    }

    .modern-table thead th {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: #ffffff;
        font-weight: 600;
        padding: 1rem;
        text-align: center;
        border: none;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .modern-table thead th:first-child {
        border-top-left-radius: 8px;
    }

    .modern-table thead th:last-child {
        border-top-right-radius: 8px;
    }

    .modern-table tbody tr {
        transition: all 0.3s ease;
    }

    .modern-table tbody tr:nth-child(even) {
        background: #F8FBFE;
    }

    .modern-table tbody tr:hover {
        background: var(--primary-light);
        transform: scale(1.01);
        box-shadow: 0 2px 8px rgba(0, 156, 255, 0.1);
    }

    .modern-table tbody td {
        padding: 0.9rem 1rem;
        border: none;
        border-bottom: 1px solid #E8EEF5;
        text-align: center;
        color: #495057;
        font-size: 0.9rem;
    }

    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }

    .modern-table tbody tr:last-child td:first-child {
        border-bottom-left-radius: 8px;
    }

    .modern-table tbody tr:last-child td:last-child {
        border-bottom-right-radius: 8px;
    }

    .form-group-custom {
        margin-bottom: 1.25rem;
    }

    .form-row-custom {
        background: #FAFBFD;
        border-radius: 10px;
        padding: 1.5rem;
        border: 1px solid #E8EEF5;
    }

    .section-divider {
        height: 2px;
        background: linear-gradient(90deg, transparent 0%, var(--primary-color) 50%, transparent 100%);
        margin: 2rem 0;
        opacity: 0.3;
    }

    .modern-hr {
        border: none;
        height: 2px;
        background: linear-gradient(90deg, transparent 0%, var(--primary-color) 50%, transparent 100%);
        margin: 2.5rem 0;
    }

    .input-group-text-custom {
        background: var(--primary-color);
        color: #ffffff;
        border: 2px solid var(--primary-color);
        border-radius: 8px 0 0 8px;
        padding: 0.6rem 1rem;
        font-weight: 600;
    }

    .table-container {
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--card-shadow);
        border: 1px solid rgba(0, 156, 255, 0.1);
    }

    .section-title {
        color: var(--primary-dark);
        font-weight: 600;
        font-size: 1.2rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title::before {
        content: '';
        width: 4px;
        height: 24px;
        background: var(--primary-color);
        border-radius: 2px;
    }

    .select-label-inline {
        font-weight: 600;
        color: var(--primary-dark);
        margin-right: 0.5rem;
    }

    .config-image {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        border: 2px solid #E8EEF5;
    }

    @media (max-width: 768px) {
        .modern-table {
            font-size: 0.8rem;
        }

        .modern-table thead th,
        .modern-table tbody td {
            padding: 0.6rem 0.5rem;
        }
    }
</style>



<div id="patient_plan_section" class="container-fluid pt-4 px-4">

    <div class="modern-card mb-4">
        <div class="card-header-custom">
            <h1><i class="fas fa-table me-2"></i>Table List to Print Out Label</h1>
        </div>
        <div class="card-body-custom">
            <div class="table-container mb-3">
                <table id="tableforprintlabel" class="modern-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User ID</th>
                            <th>SN Number</th>
                            <th>HN Number</th>
                            <th>Pathology</th>
                            <th>Specimen</th>
                            <th>Admit Date</th>
                            <th>Organization</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($labelPrints as $labelprint): ?>
                            <tr>
                                <td><?= $labelprint["id"] ?></td>
                                <td><?= $labelprint["userid"] ?></td>
                                <td><?= $labelprint["sn_num"] ?></td>
                                <td><?= $labelprint["hn_num"] ?></td>
                                <td><?= $labelprint[
                                    "patho_abbreviation"
                                ] ?></td>
                                <td><?= $labelprint[
                                    "speciment_abbreviation"
                                ] ?></td>
                                <td><?= $labelprint["accept_date"] ?></td>
                                <td><?= $labelprint["company_name"] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="text-center">
                <form action="" method="post" class="d-inline-block">
                    <button name="delAll" type="submit" class="modern-btn-danger">
                        <i class="fas fa-trash-alt me-2"></i>Remove All List
                    </button>
                    <input type="hidden" name="userid" readonly="readonly" value="<?= $_SESSION[
                        "userid"
                    ] ?>">
                </form>
            </div>
        </div>
    </div>

    <br>

    <div class="modern-card mb-4">
        <div class="card-header-custom">
            <!--<h1><i class="fas fa-plus-circle me-2"></i>Fill in data for insert to list</h1>-->
            <h1><i class="fas fa-plus-circle me-2"></i>SN Number 1 รายการ</h1>
        </div>
        <div class="card-body-custom">
            <form action="" id="form_add_record" method="post" class="">
                <div class="form-row-custom mb-4">
                    <div class="row">
                        <div class="col-xl-4 col-md-6 form-group-custom">
                            <label for="target_accept_date" class="modern-label">
                                <i class="far fa-calendar-alt me-1"></i>Accept Date
                            </label>
                            <input type="text" name="target_accept_date" id="target_accept_date" class="modern-form-control" placeholder="Select date">
                        </div>

                        <div class="col-xl-4 col-md-6 form-group-custom">
                            <span id="pnum_id_span">
                                <label for="pnum_id" class="modern-label">
                                    <i class="fas fa-barcode me-1"></i>SN Number
                                </label>
                                <select name="pnum_id" id="pnum_id" class="modern-select" required>
                                    <option value="">Select SN Number</option>
                                    <?php foreach (
                                        $patientLists
                                        as $patient
                                    ): ?>
                                        <option value="<?= htmlspecialchars(
                                            $patient["pid"],
                                        ) ?>"
                                                p_pnum="<?= htmlspecialchars(
                                                    $patient["p_pnum"],
                                                ) ?>"
                                                patho_abbreviation="<?= htmlspecialchars(
                                                    $patient["ab_patho"],
                                                ) ?>"
                                                accept_date="<?= htmlspecialchars(
                                                    $patient["accept_date"],
                                                ) ?>">
                                            <?= htmlspecialchars(
                                                $patient["p_pnum"],
                                            ) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </span>
                        </div>

                        <div class="col-xl-4 col-md-6 form-group-custom">
                            <label for="patho_abbreviation" class="modern-label">
                                <i class="fas fa-file-medical me-1"></i>Pathology Abbreviation
                            </label>
                            <input type="text" name="patho_abbreviation" id="patho_abbreviation" class="modern-form-control" value="" readonly required>
                        </div>

                        <div class="col-xl-4 col-md-6 form-group-custom">
                            <label for="accept_date" class="modern-label">
                                <i class="far fa-calendar me-1"></i>Accept Date
                            </label>
                            <input type="text" name="accept_date" id="accept_date" class="modern-form-control" value="" readonly required>
                        </div>

                        <div class="col-xl-4 col-md-6 form-group-custom">
                            <label for="hn_num" class="modern-label">
                                <i class="fas fa-hospital me-1"></i>HN Number
                            </label>
                            <input type="text" name="hn_num" id="hn_num" class="modern-form-control" value="" readonly required>
                        </div>
                    </div>
                </div>



                <div class="modern-hr"></div>

                <div class="section-title">
                    <i class="fas fa-sliders-h me-2"></i>Label Configuration
                </div>

                <div class="form-row-custom">
                    <div class="row">
                        <div class="col-xl-4 col-md-6 form-group-custom">
                            <label class="select-label-inline">
                                <i class="fas fa-font me-1"></i>Select Letter:
                            </label>
                            <?php
                            echo '<select name="letter" id="letter" class="modern-select" required>';
                            foreach (range("A", "Z") as $letter) {
                                echo '<option value="' .
                                    $letter .
                                    '">' .
                                    $letter .
                                    "</option>";
                            }
                            echo "</select>";
                            ?>
                        </div>

                        <div class="col-xl-4 col-md-6 form-group-custom">
                            <label class="select-label-inline">
                                <i class="fas fa-sort-numeric-up me-1"></i>Number From:
                            </label>
                            <?php
                            echo '<select name="start_num" id="start_num" class="modern-select" required>';
                            for ($i = 1; $i <= 99; $i++) {
                                echo '<option value="' .
                                    $i .
                                    '">' .
                                    $i .
                                    "</option>";
                            }
                            echo "</select>";
                            ?>
                        </div>

                        <div class="col-xl-4 col-md-6 form-group-custom">
                            <label class="select-label-inline">
                                <i class="fas fa-sort-numeric-down me-1"></i>Number To:
                            </label>
                            <?php
                            echo '<select name="end_num" id="end_num" class="modern-select" required>';
                            for ($i = 1; $i <= 99; $i++) {
                                echo '<option value="' .
                                    $i .
                                    '">' .
                                    $i .
                                    "</option>";
                            }
                            echo "</select>";
                            ?>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button name="add" type="submit" class="modern-btn-primary">
                        <i class="fas fa-plus me-2"></i>Add to List
                    </button>
                </div>
                <input type="hidden" name="userid" id="userid" readonly="readonly" value="<?= $_SESSION[
                    "userid"
                ] ?>">
            </form>
        </div>
    </div>
    <br>


    <div class="modern-card mb-4">
        <div class="card-header-custom">
            <h1><i class="fas fa-list me-2"></i>SN Number ทีละหลายรายการ</h1>
        </div>
        <div class="card-body-custom">
            <div class="form-row-custom mb-4">
                <div class="row">
                    <div class="col-xl-4 col-md-6 form-group-custom">
                        <label for="filter_accept_date" class="modern-label">
                            <i class="far fa-calendar-alt me-1"></i>Filter by Accept Date
                        </label>
                        <input type="text" name="filter_accept_date" id="filter_accept_date" class="modern-form-control" placeholder="Select date to filter">
                    </div>
                </div>
            </div>
            <div class="table-container">
                <div class="table-responsive">
                    <table id="snDataTable" class="modern-table" style="width:100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>SN Number</th>
                                <th>HN Number</th>
                                <th>Pathology</th>
                                <th>Accept Date</th>
                                <th>Letter</th>
                                <th>Number from</th>
                                <th>Number to</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="text-center mt-4">
                <button id="btn_add_all_to_list" type="button" class="modern-btn-success">
                    <i class="fas fa-layer-group me-2"></i>Add All to List
                </button>
            </div>
        </div>
    </div>


    <!-- ===============================================
    button to show pdf section =========================
    ====================================================-->
    <div class="modern-card mb-4">
        <div class="card-header-custom">
            <h1><i class="fas fa-print me-2"></i>Generate Sticker Slide</h1>
        </div>
        <div class="card-body-custom">
            <h3 class="section-title">
                <i class="fas fa-file-pdf me-2"></i>1. Generate PDF (Paper A4) - Sticker Label 2.3x2.0 cm
            </h3>
            <div class="form-row-custom">
                <div class="row">
                    <div class="col-md-3 form-group-custom">
                        <label for="1A" class="modern-label">A:</label>
                        <input type="text" id="1A" name="1A" value="2.5" class="modern-form-control">
                    </div>
                    <div class="col-md-3 form-group-custom">
                        <label for="1B" class="modern-label">B:</label>
                        <input type="text" id="1B" name="1B" value="2.5" class="modern-form-control">
                    </div>
                    <div class="col-md-3 form-group-custom">
                        <label for="1X" class="modern-label">X:</label>
                        <input type="text" id="1X" name="1X" value="3.6" class="modern-form-control">
                    </div>
                    <div class="col-md-3 form-group-custom">
                        <label for="1Y" class="modern-label">Y:</label>
                        <input type="text" id="1Y" name="1Y" value="6.0" class="modern-form-control">
                    </div>
                </div>
                <div class="text-center mt-3">
                    <button name="viewpdf1" id="viewpdf1a" class="modern-btn-primary me-2" onclick="onBtnViewPdf1A()">
                        <i class="fas fa-border-all me-2"></i>Generate with grid line
                    </button>
                    <button name="viewpdf1" id="viewpdf1b" class="modern-btn-primary" onclick="onBtnViewPdf1B()">
                        <i class="far fa-square me-2"></i>Generate with no grid line
                    </button>
                </div>
            </div>
        </div>
    </div>


    <div class="modern-card mb-4">
        <div class="card-header-custom">
            <h3><i class="fas fa-file-invoice me-2"></i>2. Generate PDF (Paper 76mm x 20mm) - Sticker Label 2.3x2.0cm x3 per row</h3>
        </div>
        <div class="card-body-custom text-center">
            <a href="<?= Url::currentURL() ?>/sn_pdf2.php?userid=<?= $_SESSION[
    "userid"
] ?>" target="_blank" class="text-decoration-none">
                <button name="viewpdf2" id="viewpdf2" type="submit" class="modern-btn-primary me-3">
                    <i class="fas fa-border-all me-2"></i>Generate with grid line
                </button>
            </a>
            <a href="<?= Url::currentURL() ?>/sn_pdf2.php?userid=<?= $_SESSION[
    "userid"
] ?>&ishideborder" target="_blank" class="text-decoration-none">
                <button name="viewpdf2" id="viewpdf2" type="submit" class="modern-btn-primary">
                    <i class="far fa-square me-2"></i>Generate with no grid line
                </button>
            </a>
        </div>
    </div>




    <!-- ===============================================
    button to show pdf section =========================
    ====================================================-->
    <div class="modern-card mb-4">
        <div class="card-header-custom">
            <h1><i class="fas fa-tags me-2"></i>Generate Sticker Specimen (Print x2 per record)</h1>
        </div>
        <div class="card-body-custom">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <h3 class="section-title">
                        <i class="fas fa-file-invoice me-2"></i>1. Generate PDF (Paper 5.0cm x 2.5cm) - Sticker Label 5.0cm x 2.5cm x1 per row
                    </h3>
                    <div class="text-center">
                        <a href="<?= Url::currentURL() ?>/sn_sp_pdf1.php?userid=<?= $_SESSION[
    "userid"
] ?>" target="_blank" class="text-decoration-none">
                            <button name="viewpdf3" id="viewpdf3" type="submit" class="modern-btn-primary me-2">
                                <i class="fas fa-border-all me-2"></i>Generate with grid line
                            </button>
                        </a>
                        <a href="<?= Url::currentURL() ?>/sn_sp_pdf1.php?userid=<?= $_SESSION[
    "userid"
] ?>&ishideborder" target="_blank" class="text-decoration-none">
                            <button name="viewpdf3" id="viewpdf3" type="submit" class="modern-btn-primary">
                                <i class="far fa-square me-2"></i>Generate with no grid line
                            </button>
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <h3 class="section-title">
                        <i class="fas fa-file-invoice me-2"></i>2. Generate PDF (Paper 5.0cm x 1.9cm) - Sticker Label 5.0cm x 1.9cm x1 per row
                    </h3>
                    <div class="text-center">
                        <a href="<?= Url::currentURL() ?>/sn_sp_pdf2.php?userid=<?= $_SESSION[
    "userid"
] ?>" target="_blank" class="text-decoration-none">
                            <button name="viewpdf3" id="viewpdf3" type="submit" class="modern-btn-primary me-2">
                                <i class="fas fa-border-all me-2"></i>Generate with grid line
                            </button>
                        </a>
                        <a href="<?= Url::currentURL() ?>/sn_sp_pdf2.php?userid=<?= $_SESSION[
    "userid"
] ?>&ishideborder" target="_blank" class="text-decoration-none">
                            <button name="viewpdf3" id="viewpdf3" type="submit" class="modern-btn-primary">
                                <i class="far fa-square me-2"></i>Generate with no grid line
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modern-card">
        <div class="card-header-custom">
            <h1><i class="fas fa-cogs me-2"></i>Configuration</h1>
        </div>
        <div class="card-body-custom text-center">
            <img src="generate_label/configuration.png" alt="Configuration Image" class="config-image img-fluid">
        </div>
    </div>
</div>


<?php $hidden_data2dom = true; ?>
<!-- Container where we will add the form -->
<div id="formContainer"></div>

<?php if (isset($patientLists)): ?>
    <ul class="patientlist" id="patientlist" style="<?= $hidden_data2dom
        ? "display: none;"
        : "" ?>" >
        <?php foreach ($patientLists as $patient): ?>
            <li
                tabindex="<?= $patient["pid"] ?>"
                pnum="<?= htmlspecialchars($patient["p_pnum"]) ?>"
                hn_num="<?= htmlspecialchars($patient["p_phospital_num"]) ?>"
                patho_abbreviation="<?= htmlspecialchars(
                    $patient["ab_patho"],
                ) ?>"
                accept_date="<?= htmlspecialchars($patient["accept_date"]) ?>"
                >
                tabindex="<?= $patient["pid"] ?>"
                pnum="<?= htmlspecialchars($patient["p_pnum"]) ?>"
                hn_num="<?= htmlspecialchars($patient["p_phospital_num"]) ?>"
                patho_abbreviation="<?= htmlspecialchars(
                    $patient["ab_patho"],
                ) ?>"
                accept_date="<?= htmlspecialchars($patient["accept_date"]) ?>"
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>



<?php require "includes/footer.php"; ?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<!--<script src="https://code.jquery.com/jquery-3.6.0.js"></script>-->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script type="text/javascript">

    var resultArray;
    var targetpatient;
    var cur_user_id = "<?= $_SESSION["userid"] ?>";

    function retrivepnumbyacceptdate() {
        //let tardate = $("#target_accept_date").val();
        //alert("tardate::"+tardate);
        drawSelectionAndDOM();
    }


    $(function() {
        $("#target_accept_date").datepicker({
            dateFormat: 'yy-mm-dd'
        });
        $("#filter_accept_date").datepicker({
            dateFormat: 'yy-mm-dd',
            onSelect: function(dateText) {
                filterTableByDate(dateText);
            }
        });

    });

    function filterTableByDate(selectedDate){
        let user_id = $('#userid').val();
        let accept_date = selectedDate;
        let pnumjson;
        let existingSNs = new Set(); // Store existing SN numbers

        // Show loading indicator
        $("#snDataTable").find("tbody").html('<tr><td colspan="8" class="text-center">Loading...</td></tr>');

        // First, fetch existing records from tableforprintlabel
        $.ajax({
            'async': false,
            type: 'POST',
            'global': false,
            url: 'ajax_data/generate_label_get_record.php',
            data: {
                'user_id': user_id,
            },
            success: function (data) {
                try {
                    if (data && data.trim() !== '') {
                        let existingRecords = JSON.parse(data);
                        // Extract SN numbers from existing records
                        $.each(existingRecords, function(index, record) {
                            if (record.sn_num) {
                                existingSNs.add(record.sn_num);
                            }
                        });
                    }
                } catch (e) {
                    console.error("Error parsing existing records:", e);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error fetching existing records:", error);
            }
        });

        // get data from database
        $.ajax({
            'async': false,
            type: 'POST',
            'global': false,
            url: 'ajax_data/generate_label_get_SN_by_date.php',
            data: {
                'user_id': user_id,
                'accept_date': accept_date,
            },
            success: function (data) {
                console.log("Filtered data::");
                console.log(data);
                pnumjson = JSON.parse(data);

                // Check if DataTable already exists, if yes destroy it first
                if ($.fn.DataTable.isDataTable('#snDataTable')) {
                    $('#snDataTable').DataTable().clear().destroy();
                }

                // Clear table body
                $("#snDataTable").find("tbody").empty();

                // Create select options for Letter (A-Z) with "none" as default
                let letterOptions = '<option value="none" selected>-- None --</option>';
                for (let i = 65; i <= 90; i++) {
                    let letter = String.fromCharCode(i);
                    letterOptions += '<option value="' + letter + '">' + letter + '</option>';
                }

                // Create select options for Number from/to (1-99)
                let numOptions = '';
                for (let i = 1; i <= 99; i++) {
                    numOptions += '<option value="' + i + '">' + i + '</option>';
                }

                // Loop through pnumjson and add rows to table
                if (pnumjson.length > 0) {
                    $.each(pnumjson, function(index, item) {
                        let acceptDateFormatted = convertDateFormat(item.accept_date);
                        let rowId = 'row_' + index;
                        let isFinished = existingSNs.has(item.p_pnum);
                        let finishedBadge = isFinished ? ' <span class="badge bg-success">Finish</span>' : '';
                        let rowClass = isFinished ? 'table-secondary' : '';

                        $("#snDataTable").find("tbody").append(
                            "<tr id='" + rowId + "' class='" + rowClass + "'>" +
                            "<td>" + (index + 1) + finishedBadge + "</td>" +
                            "<td>" + item.p_pnum + "</td>" +
                            "<td>" + item.p_phospital_num + "</td>" +
                            "<td>" + item.name_patho + "</td>" +
                            "<td>" + acceptDateFormatted + "</td>" +
                            "<td>" +
                                "<select class='form-select form-select-sm letter-select' data-pid='" + item.pid + "'>" +
                                    letterOptions +
                                "</select>" +
                            "</td>" +
                            "<td>" +
                                "<select class='form-select form-select-sm start-num-select' data-pid='" + item.pid + "'>" +
                                    numOptions +
                                "</select>" +
                            "</td>" +
                            "<td>" +
                                "<select class='form-select form-select-sm end-num-select' data-pid='" + item.pid + "'>" +
                                    numOptions +
                                "</select>" +
                            "</td>" +
                            "</tr>"
                        );
                    });
                } else {
                    $("#snDataTable").find("tbody").html('<tr><td colspan="8" class="text-center">No SN numbers found for this date</td></tr>');
                }

                // Initialize DataTable
                $("#snDataTable").DataTable({
                    "responsive": true,
                    "pageLength": 10,
                    "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                    "language": {
                        "emptyTable": "No SN numbers found for this date"
                    },
                    "order": [[0, "asc"]],
                    "columnDefs": [
                        { "width": "5%", "targets": 0 },
                        { "width": "15%", "targets": 1 },
                        { "width": "12%", "targets": 2 },
                        { "width": "20%", "targets": 3 },
                        { "width": "12%", "targets": 4 },
                        { "width": "10%", "targets": 5 },
                        { "width": "8%", "targets": 6 },
                        { "width": "8%", "targets": 7 }
                    ]
                });
            },
            error: function(xhr, status, error) {
                console.error("Error fetching filtered data:", error);
                $("#snDataTable").find("tbody").html('<tr><td colspan="8" class="text-center text-danger">Error loading data. Please try again.</td></tr>');
            }
        });
    }

    function openPdf1(x,y,a,b,ishideborder){
        if(ishideborder){
            window.open("sn_pdf1.php?userid="+cur_user_id+"&a="+a+"&b="+b+"&x="+x+"&y="+y+"&ishideborder", "_blank");
        }else{
            window.open("sn_pdf1.php?userid="+cur_user_id+"&a="+a+"&b="+b+"&x="+x+"&y="+y+"", "_blank");
        }
    }
    function onBtnViewPdf1A(){
        //alert("btn1a");
        let a = $("#1A").val();
        let b = $("#1B").val();
        let x = $("#1X").val();
        let y = $("#1Y").val();
        console.log('a::'+a);
        console.log('b::'+b);
        console.log('x::'+x);
        console.log('y::'+y);
//        alert('a');
        openPdf1(x,y,a,b,false);
    }
    function onBtnViewPdf1B(){
        //alert("btn1b");
        let a = $("#1A").val();
        let b = $("#1B").val();
        let x = $("#1X").val();
        let y = $("#1Y").val();
        console.log('a::'+a);
        console.log('b::'+b);
        console.log('x::'+x);
        console.log('y::'+y);
//        alert('a');
        openPdf1(x,y,a,b,true);
    }


    function drawSelectionAndDOM(updateTable = true){
        //$patientLists = Patient::getAllJoin_forlableprint($conn, 1);
        let user_id = $('#userid').val();
        let accept_date = $('#target_accept_date').val();
        let pnumjson;
        let existingSNs = new Set(); // Store existing SN numbers

        // First, fetch existing records from tableforprintlabel
        $.ajax({
            'async': false,
            type: 'POST',
            'global': false,
            url: 'ajax_data/generate_label_get_record.php',
            data: {
                'user_id': user_id,
            },
            success: function (data) {
                try {
                    if (data && data.trim() !== '') {
                        let existingRecords = JSON.parse(data);
                        // Extract SN numbers from existing records
                        $.each(existingRecords, function(index, record) {
                            if (record.sn_num) {
                                existingSNs.add(record.sn_num);
                            }
                        });
                    }
                } catch (e) {
                    console.error("Error parsing existing records:", e);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error fetching existing records:", error);
            }
        });

        // get data from database
        $.ajax({
            'async': false,
            type: 'POST',
            'global': false,
            type: 'POST',
            url: 'ajax_data/generate_label_get_SN_by_date.php',
            data: {
                'user_id': user_id,
                'accept_date': accept_date,
            },
            success: function (data) {
                console.log("data::");
                console.log(data);//print json string
                //{"pid":"35101","p_pnum":"CN2600061","p_phospital_num":"489883","name_patho":"\u0e08\u0e38\u0e25\u0e34\u0e19\u0e17\u0e23\u0e2a\u0e33\u0e23\u0e32\u0e0d","ab_patho":"JS.","accept_date":"2026-01-13"}
                pnumjson = JSON.parse(data); //convert String to JS Object
                for (var i in pnumjson)
                {
                    //{"id":"195","userid":"2","sn_num":"CN2501854","hn_num":"","patho_abbreviation":"AC.","speciment_abbreviation":"B1","accept_date":"31\/12\/2025","company_name":"N.B.Pathology","create_date":null},
                    console.log("pnumjson[i].pid"+pnumjson[i].pid);
                }

            },
            error: function (jqxhr, status, exception) {
                alert('Exception:', exception);
            }
        });

        //====== print selection.=====
        //==Expect output==
        //------before selectize---------
        //<div class="col-xl-4 col-md-6">
        //    <label for="pnum_id" class="">SN Number: </label>
        //    <select name="pnum_id" id="pnum_id" class="" required>
        //        <option value=""></option>
        //
        //            <option value="pid"
        //                    p_pnum="p_pnum"
        //                    patho_abbreviation="ab_patho"
        //                    accept_date="accept_date"
        //                    >
        //            </option>
        //    </select>
        //</div>
        //-------------After apply selectize------------
        ////<select name="pnum_id" id="pnum_id" class="selectized" tabindex="-1" style="display: none;">
        //	<option value="" selected="selected"></option>
        //</select>
        //<div class="selectize-control single">
        //	<div class="selectize-input items required invalid not-full has-options">
        //		<input type="select-one" autocomplete="off" tabindex="" id="pnum_id-selectized" required="" style="width: 4px; opacity: 1; position: relative; left: 0px;">
        //	</div>
        //	<div class="selectize-dropdown single" style="display: none; visibility: visible; width: 380.067px; top: 34.1333px; left: 0px;">
        //		<div class="selectize-dropdown-content">
        //			<div class="option" data-selectable="" data-value="35325">LN2600004</div>
        //			<div class="option" data-selectable="" data-value="35324">LN2600003</div>
        //			<div class="option" data-selectable="" data-value="35323">SN2600599</div>
        //			<div class="option" data-selectable="" data-value="34299">CN2501822</div>
        //		</div>
        //	</div>
        //</div>
        //--------------------------

        // Step 1: Select the dropdown
        let $select_pnum_id_span = $("#pnum_id_span");

        // Step 2: Clear existing options
        $select_pnum_id_span.empty();

        // Create and append a new label element with attributes
        // <label for="pnum_id" class="">SN Number: </label>
        let newLabel = $("<label>", {
          for: "pnum_id",
          class: "",
          text: "SN Number: " // text inside the label
        });
        $select_pnum_id_span.append(newLabel);

        // Create and append a new select element with attributes
        // <select name="pnum_id" id="pnum_id" class="" required>
        let newselectBox = $("<select>", {
          name: "pnum_id",
          id: "pnum_id",
          class: "",       // empty class here
          required: true   // required attribute
        });
        // Optionally add a default option
        newselectBox.append($("<option>", {
          value: "",
          text: "-- Select SN --"
        }));

        $select_pnum_id_span.append(newselectBox);

        // Step 3: Loop through JSON and add new options
        //{"pid":"34796","p_pnum":"PN2600008","p_phospital_num":"7995","name_patho":"\u0e07","ab_patho":"AC.","accept_date":"2026-01-07"}

        $.each(pnumjson, function(index, item) {
            // Check if SN number already exists in tableforprintlabel
            let isFinished = existingSNs.has(item.p_pnum);
            let displayText = (index + 1) + ":: " + item.p_pnum + (isFinished ? " (Finish)" : "");

 //            console.log();
 //            console.log("item.pid"+item.pid);
 //            console.log("item.p_pnum"+item.p_pnum);
 //            console.log("item.p_phospital_num"+item.p_phospital_num);
 //            console.log("item.name_patho"+item.name_patho);
 //            console.log("item.ab_patho"+item.ab_patho);
 //            console.log("item.accept_date"+item.accept_date);
            //-------------Draw following structure-------------
            //            <option value="pid"
            //                    p_pnum="p_pnum"
            //                    patho_abbreviation="ab_patho"
            //                    accept_date="accept_date"
            //                    >
            //            </option>
            //-------------------------------------------------
            newselectBox.append($("<option>", {
              value: item.pid,
              p_pnum: item.p_pnum,
              p_phospital_num: item.p_phospital_num,
              name_patho: item.name_patho,
              ab_patho: item.ab_patho,
              accept_date: item.accept_date,
              text: displayText,
            }));

        });

        $('#pnum_id').selectize({
//            sortField: 'text'
        });





        //====== print to DOM========
        //----Expect data to DOM---
        //<ul class="patientlist" style="display: none">
        //        <li
        //            tabindex=" $patient['pid'] "
        //            pnum=" ($patient['p_pnum']); "
        //            hn_num=" ($patient['p_phospital_num']); "
        //            patho_abbreviation=" ($patient['ab_patho']); "
        //            accept_date=" ($patient['accept_date']); "
        //            >
        //            tabindex=" $patient['pid'] "
        //            pnum=" ($patient['p_pnum']); "
        //            hn_num=" ($patient['p_phospital_num']); "
        //            patho_abbreviation=" ($patient['ab_patho']); "
        //            accept_date=" ($patient['accept_date']); "
        //        </li>
        //</ul>
        //--------------------------------------
        let uldom = $("#patientlist");
        uldom.empty();

        $.each(pnumjson, function(index, item) {
//            console.log();
//            console.log("item.pid"+item.pid);
//            console.log("item.p_pnum"+item.p_pnum);
//            console.log("item.p_phospital_num"+item.p_phospital_num);
//            console.log("item.name_patho"+item.name_patho);
//            console.log("item.ab_patho"+item.ab_patho);
//            console.log("item.accept_date"+item.accept_date);

            // Check if SN number already exists in tableforprintlabel
            let isFinished = existingSNs.has(item.p_pnum);
            let listText = item.pid+'::'+item.p_pnum+'::'+item.p_phospital_num+'::'+item.name_patho+'::'+item.ab_patho+'::'+item.accept_date;
            if (isFinished) {
                listText += ' (Finish)';
            }

            uldom.append($("<li>", {
              tabindex: item.pid,
              pnum: item.p_pnum,
              hn_num: item.p_phospital_num,
              patho_abbreviation: item.ab_patho,
              ab_patho: item.ab_patho,
              accept_date: item.accept_date,
              text: listText,
            }));
        });
        //============================================================================
        inifunction();

        // Select all <li> inside the ul.patientlist
        // Then keep as global variable resultArray
        const items = document.querySelectorAll("ul.patientlist li");

        // Convert NodeList to array and map attributes
        resultArray = Array.from(items).map(li => {
          return {
            tabindex: li.getAttribute("tabindex"),
            pnum: li.getAttribute("pnum"),
            hn_num: li.getAttribute("hn_num"),
            patho_abbreviation: li.getAttribute("patho_abbreviation"),
            accept_date: li.getAttribute("accept_date")
          };
        });

        //====== Populate DataTable for All SN Number List ======
        // Only update table if updateTable parameter is true
        if (updateTable) {
            let $snDataTable = $("#snDataTable");

            // Check if DataTable already exists, if yes destroy it first
            if ($.fn.DataTable.isDataTable('#snDataTable')) {
                $('#snDataTable').DataTable().clear().destroy();
            }

            // Clear table body
            $snDataTable.find("tbody").empty();

        // Create select options for Letter (A-Z) with "none" as default
        let letterOptions = '<option value="none" selected>-- None --</option>';
        for (let i = 65; i <= 90; i++) {
            let letter = String.fromCharCode(i);
            letterOptions += '<option value="' + letter + '">' + letter + '</option>';
        }

        // Create select options for Number from/to (1-99)
        let numOptions = '';
        for (let i = 1; i <= 99; i++) {
            numOptions += '<option value="' + i + '">' + i + '</option>';
        }

        // Loop through pnumjson and add rows to table
        $.each(pnumjson, function(index, item) {
            let acceptDateFormatted = convertDateFormat(item.accept_date);
            let rowId = 'row_' + index;
            let isFinished = existingSNs.has(item.p_pnum);
            let finishedBadge = isFinished ? ' <span class="badge bg-success">Finish</span>' : '';
            let rowClass = isFinished ? 'table-secondary' : '';

            $snDataTable.find("tbody").append(
                "<tr id='" + rowId + "' class='" + rowClass + "'>" +
                "<td>" + (index + 1) + finishedBadge + "</td>" +
                "<td>" + item.p_pnum + "</td>" +
                "<td>" + item.p_phospital_num + "</td>" +
                "<td>" + item.name_patho + "</td>" +
                "<td>" + acceptDateFormatted + "</td>" +
                "<td>" +
                    "<select class='form-select form-select-sm letter-select' data-pid='" + item.pid + "'>" +
                        letterOptions +
                    "</select>" +
                "</td>" +
                "<td>" +
                    "<select class='form-select form-select-sm start-num-select' data-pid='" + item.pid + "'>" +
                        numOptions +
                    "</select>" +
                "</td>" +
                "<td>" +
                    "<select class='form-select form-select-sm end-num-select' data-pid='" + item.pid + "'>" +
                        numOptions +
                    "</select>" +
                "</td>" +
                "</tr>"
            );
        });

        // Initialize DataTable
            $("#snDataTable").DataTable({
            "responsive": true,
            "pageLength": 10,
            "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            "language": {
                "emptyTable": "No SN numbers found for this date"
            },
            "order": [[0, "asc"]],
            "columnDefs": [
                { "width": "5%", "targets": 0 },
                { "width": "15%", "targets": 1 },
                { "width": "12%", "targets": 2 },
                { "width": "20%", "targets": 3 },
                { "width": "13%", "targets": 4 },
                { "width": "13%", "targets": 5 },
                { "width": "10%", "targets": 6 },
                { "width": "10%", "targets": 7 }
            ]
            });
        }

    }

    // Function to add all SN numbers from DataTable to the print list
    function addAllToListAsync() {
        let user_id = $('#userid').val();
        let successCount = 0;
        let failCount = 0;
        let totalRows = 0;

        // Get all rows from the DataTable
        let table = $('#snDataTable').DataTable();
        let rows = table.rows().nodes();
        totalRows = rows.length;

        if (totalRows === 0) {
            alert('No SN numbers to add!');
            return;
        }

        // Collect all records into arrays
        let recordsArray = [];

        // Loop through each row and collect data
        $(rows).each(function(index, row) {

            // Get data from the row using the row's cells
            let $row = $(row);
            let $letterSelect = $row.find('.letter-select');
            let pid = $letterSelect.data('pid');

            // Get values from table cells
            let $cells = $row.find('td');
            let sn_num = $cells.eq(1).text().trim();
            let hn_num = $cells.eq(2).text().trim();
            let patho_full = $cells.eq(3).text().trim();
            let accept_date = $cells.eq(4).text().trim();

            // Extract abbreviation from "Name (AB)"
            let patho_abbrev = "";
            let match = patho_full.match(/\(([^)]+)\)/);
            if (match) {
                patho_abbrev = match[1];
            }

            // Get values from select dropdowns
            let letter = $letterSelect.val();
            let start_num = $row.find('.start-num-select').val();
            let end_num = $row.find('.end-num-select').val();
            let company_name = "N.B.Pathology";

            // Validate data before adding to array
            if (!pid || !sn_num) {
                failCount++;
                return; // Skip this row
            }

            // Skip if letter is "none"
            if (letter === 'none' || !letter) {
                return; // Skip this row
            }

            // Add record to array
            recordsArray.push({
                'patient_id': pid,
                'userid': user_id,
                'sn_num': sn_num,
                'hn_num': hn_num,
                'patho_abbrev': patho_abbrev,
                'accept_date': accept_date,
                'company_name': "N.B.Pathology",
                'letter': letter,
                'start_num': start_num,
                'end_num': end_num
            });
        });

        console.log("Collected " + recordsArray.length + " valid records to send");

        // Check if there are records to send
        if (recordsArray.length === 0) {
            alert('No valid records to add!');
            return;
        }

        // Prepare data to send - send records as JSON string in POST parameter
        let requestData = {
            'records': JSON.stringify(recordsArray)
        };

        console.log("Adding " + recordsArray.length + " SN numbers to the list...");

        // Send single AJAX request with all records
        $.ajax({
            type: 'POST',
            url: 'ajax_data/generate_label_add_multiple_record.php',
            data: requestData,
            success: function (response) {
                try {
                    let responseData = typeof response === 'string' ? JSON.parse(response) : response;
                    // alert(responseData.message || 'Added ' + recordsArray.length + ' SN numbers successfully!');
                    drawtableforprintlabel();

                    // Refresh SN Number List table to show updated "Finish" status
                    let filterDate = $('#filter_accept_date').val();
                    if (filterDate) {
                        filterTableByDate(filterDate);
                    } else {
                        // If no filter date, refresh the dropdown and current table
                        let targetDate = $('#target_accept_date').val();
                        if (targetDate) {
                            drawSelectionAndDOM(true);
                        }
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                    console.error('Response:', response);
                    alert('Error: Server returned invalid response.\n' + response.substring(0, 200));
                }
            },
            error: function (jqxhr, status, exception) {
                console.error('Error adding SN numbers:', status, exception);
                console.error('Response Text:', jqxhr.responseText);
                let errorMsg = 'Error adding SN numbers. Status: ' + status;
                if (jqxhr.responseText) {
                    errorMsg += '\nResponse: ' + jqxhr.responseText.substring(0, 200);
                }
                alert(errorMsg);
            }
        });
    }

    // Event handler for "Add All to List" button
    $(document).on('click', '#btn_add_all_to_list', function() {
        addAllToListAsync();
    });



    function drawtableforprintlabel() {
        let user_id = $('#userid').val();
        let datajson;
        // get data from database
        $.ajax({
            'async': false,
            type: 'POST',
            'global': false,
            type: 'POST',
            url: 'ajax_data/generate_label_get_record.php',
            data: {
                'user_id': user_id,
            },
            success: function (data) {
                if (!data || data.trim() === '') {
                    datajson = [];
                    return;
                }
                try {
                    datajson = JSON.parse(data);
                } catch (e) {
                    console.error("Error parsing JSON:", e);
                    datajson = [];
                }
            },
            error: function (jqxhr, status, exception) {
                alert('Exception:', exception);
            }
        });


        // print to table
        // Get the table element by ID
        let $tablelabel = $("#tableforprintlabel");

        // Remove everything inside the table (rows, cells, etc.)
        $tablelabel.empty();

        // Create and append thead with header row
        let $thead = $("<thead></thead>");
        $thead.append("<tr> <th>ID</th> <th>User ID</th> <th>SN Number</th> <th>HN Number</th> <th>Pathology</th> <th>Specimen</th> <th>Admit Date</th> <th>Organization</th> </tr>");
        $tablelabel.append($thead);

        // Create and append tbody
        let $tbody = $("<tbody></tbody>");

        for (var i in datajson)
        {
            let id =datajson[i].id;
            //{"id":"195","userid":"2","sn_num":"CN2501854","hn_num":"","patho_abbreviation":"AC.","speciment_abbreviation":"B1","accept_date":"31\/12\/2025","company_name":"N.B.Pathology","create_date":null},
            $tbody.append("<tr>"+
                "<td>"+datajson[i].id+"</td>"+
                "<td>"+datajson[i].userid+" </td>"+
                "<td>"+datajson[i].sn_num+"</td>"+
                "<td>"+datajson[i].hn_num+"</td>"+
                "<td>"+datajson[i].patho_abbreviation+"</td>"+
                "<td>"+datajson[i].speciment_abbreviation+"</td>"+
                "<td>"+datajson[i].accept_date+"</td>"+
                "<td>"+datajson[i].company_name+"</td>"+
                "</tr>");
        }

        $tablelabel.append($tbody);

    }


    function convertDateFormat(dateStr) {
        // Split the string into parts
        const parts = dateStr.split("-"); // ["2025", "12", "08"]

        const year = parts[0];
        const month = parts[1];
        const day = parts[2];

        // Return in DD/MM/YYYY format
        return `${day}/${month}/${year}`;
    }

    function inifunction(){

        //=============Add record to database===========================================
        $("#form_add_record").off("submit").on("submit", function(e) {
            e.preventDefault(); // prevent normal form submission

            // prepare all parameter
            let patient_id = targetpatient.tabindex;
            let userid = $('#userid').val();
            let sn_num = targetpatient.pnum;
            let hn_num = targetpatient.hn_num;
            let patho_abbrev = targetpatient.patho_abbreviation;
            let accept_date = convertDateFormat(targetpatient.accept_date);
            let company_name = "N.B.Pathology";
            let letter = $('#letter').selectize()[0].selectize.getValue();
            let start_num = $('#start_num').selectize()[0].selectize.getValue();
            let end_num = $('#end_num').selectize()[0].selectize.getValue();

            $.ajax({
                'async': false,
                type: 'POST',
                'global': false,
                type: 'POST',
                url: 'ajax_data/generate_label_add_record.php',
                data: {
                    'patient_id':  patient_id    ,
                    'userid':      userid        ,
                    'sn_num':      sn_num        ,
                    'hn_num':      hn_num        ,
                    'patho_abbrev':patho_abbrev  ,
                    'accept_date': accept_date   ,
                    'company_name':company_name  ,
                    'letter':      letter        ,
                    'start_num':   start_num     ,
                    'end_num':     end_num       ,
                },
                success: function (data) {
                    console.log(data);
                },
                error: function (jqxhr, status, exception) {
                    alert('Exception:', exception);
                }
            });

            drawtableforprintlabel();


        });


        //========Update related input field when pnum_id dropdown list is changed  =====================
        $("#pnum_id").off("change").on("change", function() {

            let selectize = $('#pnum_id').selectize()[0].selectize;            // Initialize Selectize
            let pnum_id_selected = selectize.getValue();             // Get value when needed


            targetpatient = resultArray.find(obj => obj.tabindex === pnum_id_selected);
            //console.log("resultArray::"+resultArray);

            // Set values with jQuery
            $('#patho_abbreviation').val(targetpatient.patho_abbreviation);
            $('#accept_date').val(targetpatient.accept_date);
            $('#hn_num').val(targetpatient.hn_num);
            //alert("done set field");

            let patient_id = targetpatient.tabindex;
            let userid = $('#userid').val();
            let sn_num = targetpatient.pnum;
            let hn_num = targetpatient.hn_num;
            let patho_abbrev = targetpatient.patho_abbreviation;
            let accept_date = convertDateFormat(targetpatient.accept_date);
            let company_name = "N.B.Pathology";
            let letter = $('#letter').selectize()[0].selectize.getValue();
            let start_num = $('#start_num').selectize()[0].selectize.getValue();
            let end_num = $('#end_num').selectize()[0].selectize.getValue();

        });

        // First clear any previous change handlers, then add a new one
        // target_accept_date change event only updates dropdown, not the table
        $("#target_accept_date").off("change").on("change", function() {
            drawSelectionAndDOM(false); // Pass false to skip table update
        });


    }


    $(document).ready(function () {
        $("#generate_label").addClass("active");

        $('select').selectize({
//            sortField: 'text'
        });



        //=============Print table of row record selected=============================
        drawtableforprintlabel();


        // Select all <li> inside the ul.patientlist
        // Then keep as global variable resultArray
        const items = document.querySelectorAll("ul.patientlist li");

        // Convert NodeList to array and map attributes
        resultArray = Array.from(items).map(li => {
          return {
            tabindex: li.getAttribute("tabindex"),
            pnum: li.getAttribute("pnum"),
            hn_num: li.getAttribute("hn_num"),
            patho_abbreviation: li.getAttribute("patho_abbreviation"),
            accept_date: li.getAttribute("accept_date")
          };
        });
//


        //============================================================================
        inifunction();




    });






</script>
