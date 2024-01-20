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
            
           <h1 align="center"><span id="">หน้าแสดงรายการราคา</span></h1>



<table class="table table-hover table-striped" id="nb_price_view_table" style="width:100%">
                <thead>
                    <tr>
                        <th> spl_id </th>              
                        <th> h_id </th>                
                        <th> st_id </th>               
                        <th> spl_number </th>          
                        <th> h_hospital </th>          
                        <th> st_service_type </th>     
                        <th> spl_speciment_num </th>   
                        <th> spl_specimen </th>        
                        <th> spl_price </th>           
                        <th> spl_unit_count </th>      
                        <th> spl_create_date </th>     
                        <th> user_add </th>            
                        <th> user_edit </th>           
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

<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">
        <h3 style="text-align:center;">
            <a target="_black" href="https://os5.mycloud.com/action/share/db0a2eb2-0c0f-41b2-9e15-8303f5bf49c2" > Link For Reference Excel Sheet </a>
        </h3>
    </div>
</div>






<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    
    $("#manage_table").addClass("active");
    $("#price_tab_view").addClass("active");
    $("#manage_table").addClass("show");
    $(".manage_table_dropdown").addClass("show");
    
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
    var table = $('#nb_price_view_table').DataTable({
        "ajax": "ajax_nb_price_view/nb_price_view.php?skey=" + skey + "&range=1m",
        responsive: true,
        dom: 'BPlfriptip',
        
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

//        columns: [
//        { title: '#' },
//        { title: 'SN' },
//        { title: 'HN' },
//        { title: 'admit date' },
//        { title: 'Patient' },
//        { title: 'j_id' },
//        { title: 'j_patient_id' },
//        { title: 'j_user_id' },
//        { title: 'j_job_role_id' },
//        { title: 'j_jobname' },
//        { title: 'j_owners' },
//        { title: 'qty' },
//        { title: 'finish_date' },
//        { title: 'Manage' }
//        ],
        
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
            
            ],
        "order": [
            [0, "desc"]
        ],
        
        
//  $sql = "SELECT spl.id as spl_id                                    $data[] = [$p['spl_id']               //0   <th> spl_id </th>               
//              ,h.id as h_id                                                   , $p['h_id']                 //1   <th> h_id </th>                 
//              ,st.id as st_id                                                 , $p['st_id']                //2   <th> st_id </th>                
//              ,spl.number as spl_number                                       , $p['spl_number']           //3   <th> spl_number </th>           
//              ,h.hospital as h_hospital                                       , $p['h_hospital']           //4   <th> h_hospital </th>           
//              ,st.service_type as st_service_type                             , $p['st_service_type']      //5   <th> st_service_type </th>      
//              ,spl.speciment_num as spl_speciment_num                         , $p['spl_speciment_num']    //6   <th> spl_speciment_num </th>    
//              ,spl.specimen as spl_specimen                                   , $p['spl_specimen']         //7   <th> spl_specimen </th>         
//              ,spl.price as spl_price                                         , $p['spl_price']            //8   <th> spl_price </th>            
//              ,spl.unit_count as spl_unit_count                               , $p['spl_unit_count']       //9   <th> spl_unit_count </th>       
//              ,DATE(spl.create_date) as spl_create_date                       , $p['spl_create_date']      //10  <th> spl_create_date </th>      
//              ,CONCAT(u_add.name , ' ' , u_add.lastname) as user_add          , $p['user_add']             //11  <th> user_add </th>             
//              ,CONCAT(u_edit.name , ' ' , u_edit.lastname) as user_edit       , $p['user_edit']]           //12  <th> user_edit </th>            
//          FROM `service_price_list` as spl                                                                     
//          JOIN hospital as h ON h.id = spl.hospital_id                                                         
//          JOIN service_type as st ON st.id = spl.jobtype                                                       
//          LEFT JOIN user as u_add ON u_add.id = spl.add_user_id                                                
//          LEFT JOIN user as u_edit ON u_edit.id = spl.edit_user_id                                             
//                                                                                                               

        
        searchPanes: {
            initCollapsed: true,
        },
        columnDefs: [
        {
            searchPanes: {
                show: true
            },
            targets: []
        },
        {
            searchPanes: {
                show: false
            },
            targets: [0,1,2,8]
        },
        {
            visible: true,
            targets: []
        },
        {
            visible: false,
            targets: [0,1,2]
        },
        {
            "render": function (data, type, row) {
                return row[2];
            },
            "targets": 2
        }
        ],
//        "initComplete": colorAdd,
    });

    // add color when reload
    //table.on('draw', colorAdd);




    // set active tab
//    $("#patienttab").addClass("active");


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