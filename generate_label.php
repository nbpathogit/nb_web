<?php
//session_start();
require 'includes/init.php';
$conn = require 'includes/db.php';
Auth::requireLogin();

var_dump($_POST);

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

<style>

    th,td{
        border: 1px solid black;
        border-collapse: collapse;
        text-align: center;
    }
</style>

<table> 
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

<br>
<br>
<br>


<form action="" method="post" class="">
    <div class="">
        <input type="hidden" name="userid" readonly="readonly" value="<?= $_SESSION['userid'] ?>">
    </div>
    <div class="">
        <label for="sn_num">SN Number: </label>
        <input type="text" name="sn_num">
    </div>
    <div class="">
        <label for="hn_num">Hospital Number: </label>
        <input type="text" name="hn_num">
    </div>
    <div class="">
        <label for="patho_abbreviation">patho_Abbreviation: </label>
        <input type="text" name="patho_abbreviation">
    </div>
    <div class="">
        <label for="speciment_abbreviation">specimen_Abbrevation: </label>
        <input type="text" name="speciment_abbreviation">
    </div>
    
    
    <input type="checkbox" value="A" name="A"><label for="A">A</label>
    
    <input type="checkbox" value="B" name="B"><label for="B">B</label>

    <div class="">
        <label for="accept_date">accept_date: </label>
        <input type="text" name="accept_date">
    </div>

    <div class="">
        <button name="add" type="submit" >&nbsp;&nbsp;Add to list&nbsp;&nbsp;</button>
    </div>

</form>

<div class="">
    <a href="<?= URL::currentURL() ?>/patient_sn_pdf1.php?userid=<?= $_SESSION['userid'] ?>"  target="_blank">
        <button name="viewpdf1" type="submit" >&nbsp;&nbsp;1) Generate PDF Label 2x2.3 cm&nbsp;&nbsp;</button>
    </a>

</div>
