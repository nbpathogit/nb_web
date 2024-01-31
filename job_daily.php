<?php
require 'includes/init.php';

$conn = require 'includes/db.php';
require 'user_auth.php';
?>
<?php if (!Auth::isLoggedIn()) : ?>
    You are not authorized.
<?php else : ?>
<?php endif; ?>
<?php   
  $nbusers = User::getAllbyNB($conn);
  $jobtypes = JobRole::getAll($conn);
?>
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
            <input type="text" name="startdate_job_daily" id="startdate_job_daily" class="form-control">
        </div>

        <div class="col-xl-4 col-md-6">
            <b> To End Date:</b> 
            <input type="text" name="enddate_job_daily" id="enddate_job_daily" class="form-control">
        </div>
        
        <div class=" ">
            <label for="user_job_daily" class="form-label">เลือกพนักงาน</label>
            <select name="user_job_daily" id="user_job_daily" class="form-select">
                <!--<option value="0" >   กรุณาเลือก  </option>-->
                <?php foreach ($nbusers as $n) : ?>
                <option value="<?= $n['uid'] ?>" >   <?= $n['name'].' '.$n['lastname'] ?>  </option>
                <?php endforeach; ?>
            </select> 
        </div>
        <div class=" ">
            <label for="role_job_daily" class="form-label">ชนิดงาน</label>
            <select name="role_job_daily" id="role_job_daily" class="form-select">
                <option value="0" >   กรุณาเลือก  </option
                <?php foreach ($jobtypes as $j) : ?>
                <option value="<?=$j['id']?>" >   <?= $j['name'] ?>  </option>
                <?php endforeach; ?>  
            </select> 
        </div>
        
        <div class="col-xl-4 col-md-6">
            <button name="btn_get_job_count_by_range" id="btn_get_job_count_by_range" type="submit" class="btn btn-primary">&nbsp;&nbsp;retrieve data by date range&nbsp;&nbsp;</button>
        </div>
    </div>
</div>


<?php require 'includes/opencontainer.php'; ?>
<?php
$str1 = file_get_contents('ajax_job_daily/job_count_daily_tbl.php');

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
        $("#startdate_job_daily").datepicker({
            dateFormat: 'yy-mm-dd'
        });
        $("#enddate_job_daily").datepicker({
            dateFormat: 'yy-mm-dd'
        });
    });
    
    
//retrive data from DB write to DOM
$("#btn_get_job_count_by_range").on("click", function (e) {

    var startdate = $("#startdate_job_daily").val();
    var enddate = $("#enddate_job_daily").val();
//    alert(startdate.substr(0, 4) + " " + startdate.substr(5, 2) + " " + startdate.substr(8, 2));

//alert("#btn_get_job_count_by_range");

//==========Note==============================
//JavaScript counts months from 0 to 11:
//January = 0.
//December = 11.
//============================================

    let date = new Date();
    let result = date.toLocaleDateString('th-TH', {year: 'numeric', month: 'long', day: 'numeric', });
    //$('#bill_todaydate_thai').val(result);
//    console.log('result::'+result);
 

    date = new Date(startdate.substr(0, 4), (startdate.substr(5, 2))-1, startdate.substr(8, 2));
    let result_startdate = date.toLocaleDateString('th-TH', {year: 'numeric', month: 'long', day: 'numeric', });
//    alert(result);
    $('.startdate_thai').text(result_startdate);

    date = new Date(enddate.substr(0, 4), (enddate.substr(5, 2))-1, enddate.substr(8, 2));
    let result_enddate = date.toLocaleDateString('th-TH', {year: 'numeric', month: 'long', day: 'numeric', });
//    alert(result);
    $('.enddate_thai').text(result_enddate);
 
    let user_job_daily = $('#user_job_daily option').filter(':selected').attr('value');
    let role_job_daily = $('#role_job_daily option').filter(':selected').attr('value');
    
    let user_job_daily_txt = $('#user_job_daily option').filter(':selected').text();
    let role_job_daily_txt = $('#role_job_daily option').filter(':selected').text();
    
    $('.user_job_daily').text(user_job_daily_txt);
    $('.role_job_daily').text(role_job_daily_txt);
    

//    console.log('result_startdate'+result_startdate);
//    console.log('result_enddate'+result_enddate);
    let datajson;
    $.ajax({
        'async': false,
        type: 'POST',
        'global': false,
        url: 'ajax_job_daily/getJob.php',
        data: {
            
            'startdate': startdate,
            'enddate': enddate,
            'user_job_daily': user_job_daily,
            'role_job_daily': role_job_daily,

        },
        success: function (data) {
            
                //console.log(data);
                //alert(data);
                
            
            if (data[0] != "[") {
                alert(data);
                console.log(data);
                return;
            }
            datajson = JSON.parse(data);
            if (datajson.length == 0) {
                alert("No record found");
                return;
            }
        },
        error: function (jqxhr, status, exception) {
            alert( jqxhr.responseText);
        }
    });

//    console.log(datajson);
//    alert(datajson);


 

    let strs = "";
    strs = strs + '<table  width="100%">';
    strs = strs + '<thead   >';
    strs = strs + '<tr>\n\
        <th style="font-size: 14pt;width:15%;">' + 'ลำดับ' + '</th>\n\
        <th style="font-size: 14pt;width:15%;">' + 'ชื่อ' + '</th>\n\
        <th style="font-size: 14pt;width:15%;">' + 'วันที่รับเข้า' + '</th>\n\
        <th style="font-size: 14pt;width:15%;">' + 'ชืองาน' + '</th>\n\
        <th style="font-size: 14pt;width:15%;">' + 'จำนวนงาน' + '</th>\n\
        <th style="font-size: 14pt;">' + 'Note' + '</th>\n\
        </tr>';
    strs = strs + "</thead>";
    strs = strs + "<tbody>";
    
//owner_name	
//accept_date	
//job_role_name	
//job_qty_sum
    for (let i in datajson)
    {
        strs = strs + '<tr>\n\
        <td>' + i + '</td>\n\
        <td>' + datajson[i].owner_name + '</td>\n\
        <td>' + datajson[i].accept_date + '</td>\n\
        <td>' + datajson[i].job_role_name + '</td>\n\
        <td>' + datajson[i].job_qty_sum + '</td>\n\
        <td>' + '' + '</td>\n\
        </tr>';

    }
    strs = strs + "</tbody>";
    strs = strs + "</table>";
    $('#job_daily_tbl_prn table').remove();
    $('#job_daily_tbl_prn').append(strs);



//    console.log("end");
//    alert("done");

});


$("#btn_export_rq_slide_pdf").on("click", function (e) {
    let page1 = $('#req_slide_page1').html();


    $('#tempform form').remove();
    var frm = $("<form>");
    frm.attr('method', 'post');
    frm.attr('target', '_blank');
    frm.attr('action', "job_daily_pdf.php");
    frm.attr('');

    frm.append('<textarea hidden  name="page1">' + page1 + '</textarea> ');

    frm.appendTo($('#tempform'));
    frm.submit();




});





$(document).ready(function () {

    $('#nb_navbar_top').removeClass("sticky-top");



});
    
</script>

