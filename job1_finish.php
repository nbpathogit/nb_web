<?php
//คนเช่วยตัด

require 'includes/init.php';

$conn = require 'includes/db.php';
?>
<?php require 'user_auth.php';
//$u_cur_group_id = Auth::getUserGroup();
//$cur_user = Auth::getUser();
$cur_user_id = $cur_user->id;
$cur_user_can_manaage_job = $cur_user->can_manaage_job;
?>



<?php 
$userTechnic = User::getAllbyNB($conn);   //2000 2100 2200 

$jobRoles = JobRole::getAll($conn);
$patient[0]['id'] = 0;
$patient[0]['pnum'] = "TBD";


?>
<?php require 'includes/header.php'; ?>
<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">


        <?php if (!Auth::isLoggedIn()) : ?>
            You are not login.<br>
            คุณไม่ได้ล็อกอิน กรุณาล็อกอินก่อนเข้าใช้งาน
        <?php elseif (($isCurUserClinicianCust || $isCurUserHospitalCust)) : //  เจ้าหน้าที่รับผล(ลูกค้า) เข้าดูไม่ได้  
            ?>
            You have no authorize to view this content. <br>
            คุณไม่มีสิทธิ์ในการเข้าดูส่วนนี้
        <?php else : ?>

        </div>
    </div>

    <div class="container-fluid pt-4 px-4">
        <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">
            
           <h1 align="center"><span id="">หน้าลงเวลาผู้ตัดเนื้อ</span></h1>
           <h3 align="center"><span id="title_page_message">กำลังแสดงจากข้อมูลย้อนหลัง 1 เดือน</span></h3>
           <div>
               <input type="checkbox" id="disable_popup" name="disable_popup" value="" >
               <label for="vehicle1"> Disable Warning POP UP </label><br>
           </div>
           <div>
                <div class="<?= $isBorder ? "border" : "" ?> ">
                    <label for="p_cross_section_id_job1"  class="form-label">เลือกพนักงานช่วยตัดเนื้อที่ต้องการจะลงเวลา</label>
                    <select name="p_cross_section_id_job1" id="p_cross_section_id_job1" class="form-select"  <?= ( $isCurUserAdmin || ($cur_user_can_manaage_job == 1) ) ? "" : " disabled readonly " ?>>
                        <!--<option value="">กรุณาเลือก</option>-->
                        <?php foreach ($userTechnic as $user): ?>
                            <?php if($user['user_status'] == 1): ?>
                            <option value="<?= ($user['uid']); //user id     ?>"  
                                    <?= ( $cur_user_id == $user['uid'])? ' selected ' : '' ; ?>
                                    job_role_id="1"
                                    patient_id="<?= $patient[0]['id']; //patient id     ?>"
                                    patient_number="<?= $patient[0]['pnum']; //Sergical number     ?>"
                                    user_id="<?= ($user['uid']); //user id     ?>"
                                    pre_name="<?= ($user['pre_name']); //pre name     ?>"
                                    name="<?= ($user['name']); //name     ?>"
                                    lastname="<?= ($user['lastname']); //name     ?>"
                                    jobname="<?= $jobRoles[0]['name']; //     ?>"
                                    pay="<?= $jobRoles[0]['cost_per_job']; //     ?>"
                                    cost_count_per_day="<?= $jobRoles[0]['cost_count_per_day']; //     ?>"
                                    comment=""
                                    >  <?= $user['pre_name'] . ' ' . $user['name'] . ' ' . $user['lastname'] ?>
                            </option>
                            <?php endif; ?>
                        <?php endforeach; ?>                                     
                    </select> 
                </div>
            </div>

<table class="table table-hover table-striped" id="job1_finish_table" style="width:100%">
                <thead>
                    <tr>
                        <th scope="col">#</th>                
                        <th scope="col">1</th>      
                        <th scope="col">2</th>       
                        <th scope="col">3</th>          
                        <th scope="col">4</th>          
                        <th scope="col">5</th>          
                        <th scope="col">6</th>             
                        <th scope="col">7</th>         
                        <th scope="col">8</th>          
                        <th scope="col">9</th>              
                        <th scope="col">10</th>          
                        <th scope="col">11</th>          
                        <th scope="col">12</th> 
                        <th scope="col">13</th> 

                    </tr>
                </thead>
            </table>

        <?php endif; ?>

    </div>
</div>

<!--<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editQtyModal">
  Launch demo modal
</button>-->


<!-- Modal -->
<div class="modal fade" id="editQtyModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            <label for="qty_modal_job1_finish">Qty:</label>
            <input type="text" id="qty_modal_job1_finish" name="qty_modal_job1_finish">
            
            <a class="btn btn-outline-primary btn-sm me-1 " id="decreaseQty"  title="Decrease"><i class="fa-sharp fa-solid fa-minus"></i></a>
            <a class="btn btn-outline-primary btn-sm me-1 " id="increaseQty" title="Increase"><i class="fa-sharp fa-solid fa-plus"></i></a>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="save_qty_modal_job1_finish" class="btn btn-primary" data-bs-dismiss="modal">Save changes</button>
      </div>
    </div>
  </div>
</div>













<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    var skey = "<?= $_SESSION["skey"] ?>";
    let cur_user_id = "<?= $_SESSION["userid"] ?>";
    let job_role_id = 1;
    let isCurUserAdmin = "<?= $isCurUserAdmin?'1':'0' ?>";
    let cur_user_can_manaage_job = "<?= $cur_user_can_manaage_job ?>";

    
    var domain = "<?= Url::currentURL() ?>";
    
    $(document).ready(function () {


    console.log('isCurUserAdmin::'+isCurUserAdmin);
    console.log('cur_user_can_manaage_job::'+cur_user_can_manaage_job);
    
    
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
            angsa : {
                normal: domain+'/fonts/angsau.ttf',
                bold: domain+'/fonts/angsaub.ttf',
                italics: domain+'/fonts/angsaui.ttf',
                bolditalics: domain+'/fonts/angsauz.ttf',
            }
        };
        // modify the PDF to use a different default font:
        doc.defaultStyle.font = "angsa";
        var i = 1;
    }



    // add color class for priority 
    function colorAdd() {
  
        var count = $('.table').children('tbody').children('tr:first-child').children('td').length;
        tablee = document.getElementById("job1_finish_table");
        tr = tablee.getElementsByTagName("tr");
        // Loop through all table rows, and hide those who don't match the search query
        for (i = 1; i < tr.length; i++) {
            for (j = 0; j < count; j++) {
                td = tr[i].getElementsByTagName("td")[j];
                if (td.innerHTML.indexOf('ออกผลแล้ว') > -1) {
                    tr[i].classList.add('table-success');
                    break;
                } else if (td.innerHTML.indexOf('ด่วน') > -1) {
                    tr[i].classList.add('table-danger');
                } else {
                    //DO NOTHING
                }
            }
        }




    }




    // datatable
    var table = $('#job1_finish_table').DataTable({
        "ajax": "ajax_job1_finish/job1_finish.php?skey=" + skey + "&range=1m",
        responsive: true,
        dom: 'BPlfrtip',
        
//=======================================
//["pid"]=> string(3) "200" ["p_pnum"]=> string(9) "SN2323116" ["p_hn"]=> string(0) "" ["p_accept"]=> string(19) "2023-11-04 23:04:27" 
//---0---------------------------1--------------------------------2------------------------3-----------------------------------------------
//
//["p_patient"]=> string(24) "Mrs.เอบี wcdcwdc" ["j_id"]=> string(3) "225" ["j_patient_id"]=> string(3) "200" ["j_user_id"]=> string(2) "40" 
//-------4--------------------------------------5-------------------------------6--------------------------------7-----------------------
//
//["j_job_role_id"]=> string(1) "2" , $patient['j_jobname'], $patient['j_owners'],$patient['j_qty'], $patient['finish_date']];
//-------8-----------------------------------------9---------------------10------------------11------------------12--------
//

        columns: [
        { title: '#' },
        { title: 'SN' },
        { title: 'HN' },
        { title: 'admit date' },
        { title: 'Patient' },
        { title: 'j_id' },
        { title: 'j_patient_id' },
        { title: 'j_user_id' },
        { title: 'j_job_role_id' },
        { title: 'j_jobname' },
        { title: 'j_owners' },
        { title: 'qty' },
        { title: 'finish_date' },
        { title: 'Manage' }
        ],
        
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
            buttons: [{
                text: '1 เดือนล่าสุด',
                action: function (e, dt, node, config) {
                    dt.ajax.url("ajax_job1_finish/job1_finish.php?skey=" + skey + "&range=1m").load();
                    $('#title_page_message').text('กำลังแสดงจากข้อมูลย้อนหลัง 1 เดือน');

                }
            },
            {
                text: '3 เดือนล่าสุด',
                action: function (e, dt, node, config) {
                    dt.ajax.url("ajax_job1_finish/job1_finish.php?skey=" + skey + "&range=3m").load();
                    $('#title_page_message').text('กำลังแสดงจากข้อมูลย้อนหลัง 3 เดือน');
                }
            },
            {
                text: '6 เดือนล่าสุด',
                action: function (e, dt, node, config) {
                    dt.ajax.url("ajax_job1_finish/job1_finish.php?skey=" + skey + "&range=6m").load();
                    $('#title_page_message').text('กำลังแสดงจากข้อมูลย้อนหลัง 6 เดือน');
                }
            },
            {
                text: '1 ปีล่าสุด',
                action: function (e, dt, node, config) {
                    dt.ajax.url("ajax_job1_finish/job1_finish.php?skey=" + skey + "&range=1y").load();
                    $('#title_page_message').text('กำลังแสดงจากข้อมูลย้อนหลัง 1 ปี');
                }
            },
            {
                text: '2 ปีล่าสุด',
                action: function (e, dt, node, config) {
                    dt.ajax.url("ajax_job1_finish/job1_finish.php?skey=" + skey + "&range=2y").load();
                    $('#title_page_message').text('กำลังแสดงจากข้อมูลย้อนหลัง 2 ปี');
                }
            },
            {
                text: 'ทั้งหมด',
                action: function (e, dt, node, config) {
                    dt.ajax.url("ajax_job1_finish/job1_finish.php?skey=" + skey + "").load();
                    $('#title_page_message').text('กำลังแสดงจากข้อมูลทั้งหมด');
                }
            }
            ]
        },],
        "order": [
            [0, "desc"]
        ],
        searchPanes: {
            initCollapsed: true,
        },
        columnDefs: [
        {
            searchPanes: {
                show: true
            },
            targets: [ 3,10]
        },
        {
            searchPanes: {
                show: false
            },
            targets: [0, 1, 2,4, 5,6,7,8 ,9,11,12]
        },
        {
            visible: true,
            targets: [0, 1, 2, 3,4, 9,10,11,12]
        },
        {
            visible: false,
            targets: [5,6,7,8]
        },
         
        {
            "render": function (data, type, row) {
                let renderdata = '';
                let patient_id = row[0];
                let sn = row[1];
                renderdata += '<a href="patient_edit.php?id='+patient_id+'">'+sn+'</a>';
                return renderdata;
            },
            "targets": 1
        },

        {
            "render": function (data, type, row) {
                return row[2];
            },
            "targets": 2
        },
        {
            "render": function (data, type, row) {
                let patient_id = row[0];

                let renderdata = '<span id="qty_'+patient_id+'" >'+row[11]+'</span>';//<span id="qty_99"></span>
                
                //renderdata += '<a  onclick="opend('+patient_id+','+hn+');"  class="btn btn-outline-primary btn-sm me-1 edit"><i class="fa-solid fa-marker"></i>Edit</a>';
                
                return renderdata;
            },
            "targets": 11
        },
        {
            "render": function (data, type, row) {
                let renderdata = '';
                
                let sn = row[1];
                let hn = row[2];
                let qty  = row[11];

                
                let patient_id = row[0];
                let patient_number = row[1];
 
                renderdata += '<a type="button"  onclick="setTargetPatient_id('+patient_id+',\''+sn+'\',\''+hn+'\','+qty+');" class="btn btn-outline-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editQtyModal"><i class="fa-solid fa-marker"></i> edit Qty </a>';
                renderdata += '<a id="finish_btn_'+patient_id+'"  onclick="finishJob1ByPatientId('+patient_id+',\''+patient_number+'\',\''+hn+'\');"           class="btn btn-outline-primary btn-sm me-1 edit"><i class="fa-solid fa-marker"></i>Add One</a>';
                renderdata += '<a id="remove_btn_'+patient_id+'"  onclick="removeJob1ByPatientId('+patient_id+',\''+patient_number+'\',\''+hn+'\');"           class="btn btn-outline-primary btn-sm me-1 edit"><i class="fa-solid fa-marker"></i>Remove One</a>';
                if(isCurUserAdmin=='1' || cur_user_can_manaage_job == '1' ){
                    renderdata += '<a id="remove_all_btn_'+patient_id+'"  onclick="removeJob1ByPatientIdAll('+patient_id+',\''+patient_number+'\',\''+hn+'\');"           class="btn btn-outline-primary btn-sm me-1 edit"><i class="fa-solid fa-marker"></i>Remove All</a>';
                }else{
                     renderdata += '<a class="btn btn-outline-primary btn-sm me-1 edit disabled"><i class="fa-solid fa-marker"></i>Remove All</a>';
                }
                return renderdata;
            },
            "targets": 13
        }
        ],
//        "initComplete": colorAdd,
    });

    // add color when reload
    table.on('draw', colorAdd);


    // delete user
    $('#patient_table tbody').on('click', 'a.delete', function (e) {
        var data = table.row($(this).parents('tr')).data();

        e.preventDefault();
        if (confirm("Item will move to trash. Are you sure?")) {
            let frm = $("<form>");

            frm.attr('method', 'post');
            frm.attr('action', "patient.php");

            $('<input>', {
                type: 'hidden',
                id: 'foo',
                name: 'delete_id',
                value: data[0]
            }).appendTo(frm);

            frm.appendTo("body");
            frm.submit();
        }
    });

    // trash patient
    $('#patient_table tbody').on('click', 'a.trash', function (e) {
        var data = table.row($(this).parents('tr')).data();

        e.preventDefault();
        if (confirm("Item will move to trash. Are you sure?")) {
            let frm = $("<form>");

            frm.attr('method', 'post');
            frm.attr('action', "patient.php");

            $('<input>', {
                type: 'hidden',
                id: 'foo',
                name: 'trash_id',
                value: data[0]
            }).appendTo(frm);

            frm.appendTo("body");
            frm.submit();


        }

    });

    // set active tab
    $("#patienttab").addClass("active");


});


function finishJob1ByPatientId(patient_id,patient_number,hn) {
    
    let value = $('#p_cross_section_id_job1 option').filter(':selected').attr('value');
    let txt = $('#p_cross_section_id_job1 option').filter(':selected').text();
    if (value == "0" || value == 0) {
        alert("ยังไม่ได้เลือกคนที่ต้องการจะลงเวลา");
        return null;
    }
    
//    if(!$('#disable_popup').is(':checked')){
////        alert('checked');
//    }else{
////        alert('uncheck');
//    }
    
    if(!$('#disable_popup').is(':checked')){
        if( confirm("Please confirm to add "+txt+" to patient number = "+patient_number+" ?")){

        }else{
           return null;
        }
    }

//        alert("cur_user_id="+cur_user_id+"job_role_id="+job_role_id+"patient_id="+patient_id);
        //SELECT * FROM `job` WHERE `job_role_id` = 2 ORDER BY `id` DESC


   
        
        
        //alert("start ajax");
    


    var job_role_id = $('#p_cross_section_id_job1 option').filter(':selected').attr('job_role_id');
//    var patient_id = $('#p_cross_section_id_job1 option').filter(':selected').attr('patient_id');
    var patient_id = patient_id;
//    var patient_number = $('#p_cross_section_id_job1 option').filter(':selected').attr('patient_number');
    var patient_number = patient_number;
    if(hn==null){
        hn='';
    }
    var user_id = $('#p_cross_section_id_job1 option').filter(':selected').attr('user_id');
    var pre_name = $('#p_cross_section_id_job1 option').filter(':selected').attr('pre_name');
    var name = $('#p_cross_section_id_job1 option').filter(':selected').attr('name');
    var lastname = $('#p_cross_section_id_job1 option').filter(':selected').attr('lastname');
    var jobname = $('#p_cross_section_id_job1 option').filter(':selected').attr('jobname');
    var pay = $('#p_cross_section_id_job1 option').filter(':selected').attr('pay');
    var cost_count_per_day = $('#p_cross_section_id_job1 option').filter(':selected').attr('cost_count_per_day');
    var comment = $('#p_cross_section_id_job1 option').filter(':selected').attr('comment');
    
    var printdbg = true;
    if (printdbg) {
        console.log("==============");
        console.log("value::" + value);
        console.log("job_role_id::" + job_role_id);
        console.log("patient_id::" + patient_id);
        console.log("patient_number::" + patient_number);
        console.log("user_id::" + user_id);
        console.log("pre_name::" + pre_name);
        console.log("name::" + name);
        console.log("lastname::" + lastname);
        console.log("jobname::" + jobname);
        console.log("pay::" + pay);
        console.log("cost_count_per_day::" + cost_count_per_day);
        console.log("comment::" + comment);
        console.log("==============");
    }
    



    $.ajax({
        'async': false,
        type: 'POST',
        'global': false,
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: 'ajax_job1_finish/job1_finish_btn.php',
        data: {
            'job_role_id': job_role_id,
            'patient_id': patient_id,
            'patient_number': patient_number,
            'user_id': user_id,
            'pre_name': pre_name,
            'name': name,
            'lastname': lastname,
            'jobname': jobname,
            'pay': pay,
            'cost_count_per_day': cost_count_per_day,
            'comment': comment,

        },
        success: function (data) {
            console.log(data);
//            repaintTbljob1(data);
        },
        error: function (jqxhr, status, exception) {
            alert('Exception:', exception);
        }
    });
    
    let cur_finish_btn_id = '#finish_btn_' + patient_id;
    
    $(cur_finish_btn_id).removeClass();
    //btn btn-sm me-1 edit btn-dark disabled
    $(cur_finish_btn_id).addClass("btn");
    $(cur_finish_btn_id).addClass("btn-sm");
    $(cur_finish_btn_id).addClass("me-1");
    $(cur_finish_btn_id).addClass("edit");
    $(cur_finish_btn_id).addClass("btn-dark");
    $(cur_finish_btn_id).addClass("disabled");

    if(!$('#disable_popup').is(':checked')){
        alert('Finish Added, Refresh page to see result');
    }



}


function removeJob1ByPatientId(patient_id,patient_number,hn) {
    
    let value = $('#p_cross_section_id_job1 option').filter(':selected').attr('value');
    let txt = $('#p_cross_section_id_job1 option').filter(':selected').text();
//    alert(txt);
    if (value == "0" || value == 0) {
        alert("ยังไม่ได้เลือกคนที่ต้องการจะลงเวลา");
        return null;
    }
    
    if(!$('#disable_popup').is(':checked')){
//        alert('checked');
    }else{
//        alert('uncheck');
    }
    
    if(!$('#disable_popup').is(':checked')){
        if( confirm("Please confirm remove "+txt+" from patient number = "+patient_number+" ?")){

        }else{
           return null;
        }
    }
    
    
//        alert("cur_user_id="+cur_user_id+"job_role_id="+job_role_id+"patient_id="+patient_id);
        //SELECT * FROM `job` WHERE `job_role_id` = 2 ORDER BY `id` DESC


   
        
        
        //alert("start ajax");
    


    var job_role_id = $('#p_cross_section_id_job1 option').filter(':selected').attr('job_role_id');
//    var patient_id = $('#p_cross_section_id_job1 option').filter(':selected').attr('patient_id');
    var patient_id = patient_id;
//    var patient_number = $('#p_cross_section_id_job1 option').filter(':selected').attr('patient_number');
    var patient_number = patient_number;
    if(hn==null){
        hn='';
    }
    var user_id = $('#p_cross_section_id_job1 option').filter(':selected').attr('user_id');
    var pre_name = $('#p_cross_section_id_job1 option').filter(':selected').attr('pre_name');
    var name = $('#p_cross_section_id_job1 option').filter(':selected').attr('name');
    var lastname = $('#p_cross_section_id_job1 option').filter(':selected').attr('lastname');
    var jobname = $('#p_cross_section_id_job1 option').filter(':selected').attr('jobname');
    var pay = $('#p_cross_section_id_job1 option').filter(':selected').attr('pay');
    var cost_count_per_day = $('#p_cross_section_id_job1 option').filter(':selected').attr('cost_count_per_day');
    var comment = $('#p_cross_section_id_job1 option').filter(':selected').attr('comment');
    
    var printdbg = true;
    if (printdbg) {
        console.log("==============");
        console.log("value::" + value);
        console.log("job_role_id::" + job_role_id);
        console.log("patient_id::" + patient_id);
        console.log("patient_number::" + patient_number);
        console.log("user_id::" + user_id);
        console.log("pre_name::" + pre_name);
        console.log("name::" + name);
        console.log("lastname::" + lastname);
        console.log("jobname::" + jobname);
        console.log("pay::" + pay);
        console.log("cost_count_per_day::" + cost_count_per_day);
        console.log("comment::" + comment);
        console.log("==============");
    }
    



    $.ajax({
        'async': false,
        type: 'POST',
        'global': false,
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: 'ajax_job1_finish/job1_remove_btn.php',
        data: {
            'job_role_id': job_role_id,
            'patient_id': patient_id,
            'patient_number': patient_number,
            'user_id': user_id,
            'pre_name': pre_name,
            'name': name,
            'lastname': lastname,
            'jobname': jobname,
            'pay': pay,
            'cost_count_per_day': cost_count_per_day,
            'comment': comment,

        },
        success: function (data) {
            console.log(data);
//            repaintTbljob1(data);
        },
        error: function (jqxhr, status, exception) {
            alert('Exception:', exception);
        }
    });
    
    let cur_remove_btn_id = '#remove_btn_' + patient_id;
    
    $(cur_remove_btn_id).removeClass();
    //btn btn-sm me-1 edit btn-dark disabled
    $(cur_remove_btn_id).addClass("btn");
    $(cur_remove_btn_id).addClass("btn-sm");
    $(cur_remove_btn_id).addClass("me-1");
    $(cur_remove_btn_id).addClass("edit");
    $(cur_remove_btn_id).addClass("btn-dark");
    $(cur_remove_btn_id).addClass("disabled");

    if(!$('#disable_popup').is(':checked')){
        alert('Finish Removed, Refresh page to see result.');
    }



}

function removeJob1ByPatientIdAll(patient_id,patient_number,hn) {
    
    let value = $('#p_cross_section_id_job1 option').filter(':selected').attr('value');
    let txt = $('#p_cross_section_id_job1 option').filter(':selected').text();
//    alert(txt);
//    if (value == "0" || value == 0) {
//        alert("ยังไม่ได้เลือกคนที่ต้องการจะลงเวลา");
//        return null;
//    }
    
    if(!$('#disable_popup').is(':checked')){
//        alert('checked');
    }else{
//        alert('uncheck');
    }
    
    if(!$('#disable_popup').is(':checked')){
        if( confirm("Please confirm remove all from patient number = "+patient_number+" ?")){

        }else{
           return null;
        }
    }

    
//        alert("cur_user_id="+cur_user_id+"job_role_id="+job_role_id+"patient_id="+patient_id);
        //SELECT * FROM `job` WHERE `job_role_id` = 2 ORDER BY `id` DESC


   
        
        
        //alert("start ajax");
    


    var job_role_id = $('#p_cross_section_id_job1 option').filter(':selected').attr('job_role_id');
//    var patient_id = $('#p_cross_section_id_job1 option').filter(':selected').attr('patient_id');
    var patient_id = patient_id;
//    var patient_number = $('#p_cross_section_id_job1 option').filter(':selected').attr('patient_number');
    var patient_number = patient_number;
    if(hn==null){
        hn='';
    }
    var user_id = $('#p_cross_section_id_job1 option').filter(':selected').attr('user_id');
    var pre_name = $('#p_cross_section_id_job1 option').filter(':selected').attr('pre_name');
    var name = $('#p_cross_section_id_job1 option').filter(':selected').attr('name');
    var lastname = $('#p_cross_section_id_job1 option').filter(':selected').attr('lastname');
    var jobname = $('#p_cross_section_id_job1 option').filter(':selected').attr('jobname');
    var pay = $('#p_cross_section_id_job1 option').filter(':selected').attr('pay');
    var cost_count_per_day = $('#p_cross_section_id_job1 option').filter(':selected').attr('cost_count_per_day');
    var comment = $('#p_cross_section_id_job1 option').filter(':selected').attr('comment');
    
    var printdbg = true;
    if (printdbg) {
        console.log("==============");
        console.log("value::" + value);
        console.log("job_role_id::" + job_role_id);
        console.log("patient_id::" + patient_id);
        console.log("patient_number::" + patient_number);
        console.log("user_id::" + user_id);
        console.log("pre_name::" + pre_name);
        console.log("name::" + name);
        console.log("lastname::" + lastname);
        console.log("jobname::" + jobname);
        console.log("pay::" + pay);
        console.log("cost_count_per_day::" + cost_count_per_day);
        console.log("comment::" + comment);
        console.log("==============");
    }
    



    $.ajax({
        'async': false,
        type: 'POST',
        'global': false,
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: 'ajax_job1_finish/job1_remove_all_btn.php',
        data: {
            'job_role_id': job_role_id,
            'patient_id': patient_id,
            'patient_number': patient_number,
            'user_id': user_id,
            'pre_name': pre_name,
            'name': name,
            'lastname': lastname,
            'jobname': jobname,
            'pay': pay,
            'cost_count_per_day': cost_count_per_day,
            'comment': comment,

        },
        success: function (data) {
            console.log(data);
//            repaintTbljob1(data);
        },
        error: function (jqxhr, status, exception) {
            alert('Exception:', exception);
        }
    });
    
    let cur_remove_all_btn_id = '#remove_all_btn_' + patient_id;
    
    $(cur_remove_all_btn_id).removeClass();
    //btn btn-sm me-1 edit btn-dark disabled
    $(cur_remove_all_btn_id).addClass("btn");
    $(cur_remove_all_btn_id).addClass("btn-sm");
    $(cur_remove_all_btn_id).addClass("me-1");
    $(cur_remove_all_btn_id).addClass("edit");
    $(cur_remove_all_btn_id).addClass("btn-dark");
    $(cur_remove_all_btn_id).addClass("disabled");

    if(!$('#disable_popup').is(':checked')){
        alert('Finish Removed All, Refresh page to see result.');
    }



}

 var cur_patient_id;
//on click button delete for seleced specimen list bill in main page
function setTargetPatient_id(patient_id,sn,hn,qty) {
    cur_patient_id = patient_id;
    var cur_sn = sn;
    var cur_hn = hn;
    var cur_qty = qty;
    if(cur_hn==null){
        cur_hn='';
    }
    console.log('cur_patient_id::'+cur_patient_id);
    
    $('#qty_modal_job1_finish').val(cur_qty);
    $('#exampleModalLabel').text(cur_sn);
}

$('#increaseQty').on("click", function (e) {
    let newVal = parseInt( $('#qty_modal_job1_finish').val());
    newVal += 1;
    $('#qty_modal_job1_finish').val(newVal);
    
});

$('#decreaseQty').on("click", function (e) {
    let newVal = parseInt( $('#qty_modal_job1_finish').val());
    newVal -= 1;
    $('#qty_modal_job1_finish').val(newVal);
    
});

//save_qty_modal_job1_finish
$('#save_qty_modal_job1_finish').on("click", function (e) {
    let job1qty = parseInt( $('#qty_modal_job1_finish').val());
    
    $.ajax({
        'async': false,
        type: 'POST',
        'global': false,
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: 'ajax_job1_finish/job1_finish_qty.php',
        data: {
            'patient_id': cur_patient_id,
            'job1qty': job1qty,
        },
        success: function (data) {
            console.log(data);
//            repaintTbljob1(data);
        },
        error: function (jqxhr, status, exception) {
            alert('Exception:', exception);
        }
    });
    
    //Refresh Qty On Screen
    let qty_id = 'qty_' + cur_patient_id;
    let span_id = document.getElementById(qty_id);
    span_id.innerText = span_id.textContent = job1qty.toString();


    alert('Finish Added.');
});







</script>
<!--<script src="<?= Url::getSubFolder1() ?>/js/job1_finish.js?v0"></script>-->