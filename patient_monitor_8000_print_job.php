<?php
require 'includes/init.php';

$conn = require 'includes/db.php';
require 'user_auth.php';
?>
<?php if (!Auth::isLoggedIn()) : ?>
    You are not authorized.
<?php else : ?>
<?php endif; ?>
<?php require 'includes/header.php'; ?>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">

        <div class="d-flex align-items-center justify-content-between">
            <a href="" class="btn btn-outline-primary m-2 mb-0"><i class="fa-solid fa-bed-pulse me-2"></i></a>
        </div>

    </div>
</div>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">
        <div class="col-xl-4 col-md-6">
            <b>Start Date:</b>
            <input type="text" name="startdate_sp_slide_rq" id="startdate_sp_slide_rq" class="form-control">
        </div>

        <div class="col-xl-4 col-md-6">
            <b> To End Date:</b> 
            <input type="text" name="enddate_sp_slide_rq" id="enddate_sp_slide_rq" class="form-control">
        </div>
        <div class="col-xl-4 col-md-6">
            <button name="btn_get_sp_slide_rq_by_range" id="btn_get_sp_slide_rq_by_range" type="submit" class="btn btn-primary">&nbsp;&nbsp;retrieve data by date range&nbsp;&nbsp;</button>
        </div>
    </div>
</div>


<?php require 'includes/opencontainer.php'; ?>
<?php
$str1 = file_get_contents('patient_monitor_8000/request_sp_slide_tbl.php');

echo '<h1 align="center">Report webpage draft</h1><hr>';
echo '<button name="btn_export_rq_slide_pdf" id="btn_export_rq_slide_pdf" type="submit" class="btn btn-primary">&nbsp;&nbsp;Generate official pdf&nbsp;&nbsp;</button>';
echo '<span id="req_slide_page1">';
echo $str1;
echo '</span>';
?>
<?php require 'includes/closecontainer.php'; ?>    
<span id="tempform"></span>


<?php require 'includes/footer.php'; ?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>

    $(function() {
        $("#startdate_sp_slide_rq").datepicker({
            dateFormat: 'yy-mm-dd'
        });
        $("#enddate_sp_slide_rq").datepicker({
            dateFormat: 'yy-mm-dd'
        });
    });
</script>
<script type="text/javascript" src="<?= Url::getSubFolder1() ?>/ajax_sp_slide_requested/req_sp_slide_tbl.js?v=2"></script>
