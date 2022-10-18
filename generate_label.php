<?php
//session_start();
require 'includes/init.php';
$conn = require 'includes/db.php';
Auth::requireLogin();

//var_dump($_POST);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
//    die();
    if (isset($_POST['add'])) {

        $labelPrint = new LabelPrint();

        $labelPrint->userid = $_POST['userid'];
        $labelPrint->sn_num = $_POST['sn_num'];
        $labelPrint->hn_num = $_POST['hn_num'];
        $labelPrint->patho_abbreviation = $_POST['patho_abbreviation'];
        $labelPrint->speciment_abbreviation = $_POST['speciment_abbreviation'];
        $labelPrint->accept_date = $_POST['accept_date'];

        if ($labelPrint->create($conn)) {

            Url::redirect("/generate_label.php");
        } else {
            echo '<script>alert("Add user fail. Please verify again")</script>';
        }
    }
//    if (isset($_POST['viewpdf1'])) {
//        Url::redirect("/patient_sn_pdf1.php");
//    }
}




//Get Specific Row from Table

$labelPrints = LabelPrint::getAllbyUserID($conn, $_SESSION['userid']);

if (!$labelPrints) {
    // Skip show table
}
?>


<?php require 'includes/header.php'; ?>

<style>

    /*    th,td{
            border: 1px solid black;
            border-collapse: collapse;
            text-align: center;
            font-size: 1rem;
            font-weight: 400;
            line-height: 0.5;
        }*/
</style>

<?php //Write Data to DOM pass value to java script ?>
<?php $hidden_data2dom = true; ?>
<li class="pautoscroll" tabindex="insert_label_list_section" style="<?= $hidden_data2dom ? "display: none;" : "" ?>"  >insert_label_list_section </li>


<div id="patient_plan_section" class="container-fluid pt-4 px-4">

    <div class="bg-light rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
        <h1>Table List to print out label</h1>
        <table class=""> 
            <tr>
                <th> row_id </td>
                <th> User ID </th>
                <th> SN Number </th>
                <th> HN Number </th>
                <th> Pathologist Abbreviation</th>
                <th> Speciment Abbreviation </th>
                <th> Income date </th>
                <th> Company Name </th>  

            </tr>
            <?php foreach ($labelPrints as $labelprint) : ?>
                <tr>
                    <td> <?= $labelprint['id'] ?> </td>
                    <td> <?= $labelprint['userid'] ?> </td>
                    <td> <?= $labelprint['sn_num'] ?> </td>
                    <td> <?= $labelprint['hn_num'] ?> </td>
                    <td> <?= $labelprint['patho_abbreviation'] ?> </td>
                    <td> <?= $labelprint['speciment_abbreviation'] ?> </td>
                    <td> <?= $labelprint['accept_date'] ?> </td>
                    <td> <?= $labelprint['company_name'] ?> </td>
                </tr>
            <?php endforeach; ?>

        </table>
    </div>

    <br>

    <div id="insert_label_list_section"  class="bg-light rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
        <h1>Fill in data for insert to list</h1>
        <form action="" method="post" class="">
            <div class="row <?= $isBorder ? "border" : "" ?>">
                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
                    <label for="sn_num" class="form-label">SN Number: </label>
                    <input type="text" name="sn_num" class="form-control"  value="SN229988">
                </div>
                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
                    <label for="hn_num" class="form-label">Hospital Number: </label>
                    <input type="text" name="hn_num" class="form-control" value="HN221234">
                </div>
                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
                    <label for="patho_abbreviation" class="form-label">patho_Abbreviation: </label>
                    <input type="text" name="patho_abbreviation" class="form-control" value="AC.">
                </div>
                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
                    <label for="speciment_abbreviation" class="form-label">specimen_Abbrevation: </label>
                    <input type="text" name="speciment_abbreviation" class="form-control" value="Z">
                </div>

                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
                    <label for="accept_date" class="form-label">accept_date: </label>
                    <input type="text" name="accept_date" class="form-control" value="01//02//2526">
                </div>
            </div>
            <label for="" class="form-label">Select type </label>
            <br>
            <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="A" name="A"><label for="A" class="form-check-label">A</label></div>
            <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="B" name="B"><label for="B" class="form-check-label">B</label></div>
            <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="C" name="C"><label for="C" class="form-check-label">C</label></div>
            <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="D" name="D"><label for="D" class="form-check-label">D</label></div>
            <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="E" name="E"><label for="E" class="form-check-label">E</label></div>
            <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="F" name="F"><label for="F" class="form-check-label">F</label></div>
            <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="G" name="G"><label for="G" class="form-check-label">G</label></div>
            <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="H" name="H"><label for="H" class="form-check-label">H</label></div>
            <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="I" name="I"><label for="I" class="form-check-label">I</label></div>
            <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="J" name="J"><label for="J" class="form-check-label">J</label></div>
            <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="K" name="K"><label for="K" class="form-check-label">K</label></div>
            <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="L" name="L"><label for="L" class="form-check-label">L</label></div>
            <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="M" name="M"><label for="M" class="form-check-label">M</label></div>
            <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="N" name="N"><label for="N" class="form-check-label">N</label></div>
            <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="O" name="O"><label for="O" class="form-check-label">O</label></div>
            <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="P" name="P"><label for="P" class="form-check-label">P</label></div>
            <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="Q" name="Q"><label for="Q" class="form-check-label">Q</label></div>
            <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="R" name="R"><label for="R" class="form-check-label">R</label></div>
            <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="S" name="S"><label for="S" class="form-check-label">S</label></div>
            <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="T" name="T"><label for="T" class="form-check-label">T</label></div>
            <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="U" name="U"><label for="U" class="form-check-label">U</label></div>
            <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="V" name="V"><label for="V" class="form-check-label">V</label></div>
            <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="W" name="W"><label for="W" class="form-check-label">W</label></div>
            <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="X" name="X"><label for="X" class="form-check-label">X</label></div>
            <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="Y" name="Y"><label for="Y" class="form-check-label">Y</label></div>
            <div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="Z" name="Z"><label for="Z" class="form-check-label">Z</label></div>
            <br>

            <div class="">
                <button name="add" type="submit" class="btn btn-primary" >&nbsp;&nbsp;Add to list&nbsp;&nbsp;</button>
            </div>
            <input type="hidden" name="userid"  readonly="readonly" value="<?= $_SESSION['userid'] ?>">


        </form>

        <br>
        </div>
        <div id="insert_label_list_section"  class="bg-light rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
        <div class="">
            <a href="<?= Url::currentURL() ?>/sn_pdf1.php?userid=<?= $_SESSION['userid'] ?>"  target="_blank">
                <button name="viewpdf1" type="submit" class="btn btn-primary" >&nbsp;&nbsp;1) Generate PDF Label 2x2.3 cm&nbsp;&nbsp;</button>
            </a>

        </div>
    </div>

</div>
<?php require 'includes/footer.php'; ?>