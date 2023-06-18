<?php
//session_start();
require 'includes/init.php';
$conn = require 'includes/db.php';
Auth::requireLogin();
require 'user_auth.php';
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

    <div class="bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
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

    <div id="insert_label_list_section"  class="bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
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
                            <label class="form-check-label"><div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="A" name="A">A</div></label>
            <label class="form-check-label"><div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="B" name="B">B</div></label>
            <label class="form-check-label"><div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="C" name="C">C</div></label>
            <label class="form-check-label"><div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="D" name="D">D</div></label>
            <label class="form-check-label"><div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="E" name="E">E</div></label>
            <label class="form-check-label"><div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="F" name="F">F</div></label>
            <label class="form-check-label"><div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="G" name="G">G</div></label>
            <label class="form-check-label"><div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="H" name="H">H</div></label>
            <label class="form-check-label"><div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="I" name="I">I</div></label>
            <label class="form-check-label"><div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="J" name="J">J</div></label>
            <label class="form-check-label"><div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="K" name="K">K</div></label>
            <label class="form-check-label"><div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="L" name="L">L</div></label>
            <label class="form-check-label"><div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="M" name="M">M</div></label>
            <label class="form-check-label"><div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="N" name="N">N</div></label>
            <label class="form-check-label"><div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="O" name="O">O</div></label>
            <label class="form-check-label"><div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="P" name="P">P</div></label>
            <label class="form-check-label"><div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="Q" name="Q">Q</div></label>
            <label class="form-check-label"><div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="R" name="R">R</div></label>
            <label class="form-check-label"><div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="S" name="S">S</div></label>
            <label class="form-check-label"><div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="T" name="T">T</div></label>
            <label class="form-check-label"><div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="U" name="U">U</div></label>
            <label class="form-check-label"><div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="V" name="V">V</div></label>
            <label class="form-check-label"><div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="W" name="W">W</div></label>
            <label class="form-check-label"><div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="X" name="X">X</div></label>
            <label class="form-check-label"><div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="Y" name="Y">Y</div></label>
            <label class="form-check-label"><div class="form-check form-check-inline"><input type="checkbox" class="form-check-input" value="Z" name="Z">Z</div></label>
            <div class="">
                <button name="add" type="submit" class="btn btn-primary" >&nbsp;&nbsp;Add to list&nbsp;&nbsp;</button>
            </div>
            <input type="hidden" name="userid"  readonly="readonly" value="<?= $_SESSION['userid'] ?>">


        </form>

        <br>
        </div>
        <div id="insert_label_list_section"  class="bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
        <div class="">
            <a href="<?= Url::currentURL() ?>/sn_pdf1.php?userid=<?= $_SESSION['userid'] ?>"  target="_blank">
                <button name="viewpdf1" type="submit" class="btn btn-primary" >&nbsp;&nbsp;1) Generate PDF Label 2x2.3 cm&nbsp;&nbsp;</button>
            </a>

        </div>
    </div>

</div>
<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#generate_label").addClass("active");
    });
</script>