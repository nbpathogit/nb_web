<?php
require 'includes/init.php';
$conn = require 'includes/db.php';
!Auth::requireLogin();
require 'user_auth.php';
if (!Auth::isLoggedIn()) {
    Util::alert(" You are not login.");
    die();
} elseif (($isCurUserClinicianCust || $isCurUserHospitalCust)) {
    Util::alert("You have no authorize to access this page.");
} else {
    //Allow to do next 
}




$hospitals = Hospital::getAll($conn);

$serviceType = ServiceType::getAll_v2($conn);

if ($hide) {
    $nbprices = ServicePriceList::getAll($conn);
}
?>



<?php require 'user_auth.php'; ?>
<?php require 'includes/data2DOM.php'; ?>
<?php require 'includes/header.php'; ?>


<div class="container-fluid pt-4 px-4">
    <div class="row bg-info rounded align-items-center justify-content-center p-3 mx-1">


        <div class="row align-items-center">
            <div class="col-auto">
                <select name="nb_price_hospital_select_9c" id="nb_price_hospital_select_9c" class="form-select" <?= (true) ? "" : " disabled readonly " ?>>
                    <!--<option value="กรุณาเลือก">กรุณาเลือกโรงพยาบาล</option>-->
                    <?php foreach ($hospitals as $hospital) : ?>
                        <?php //Target Format : <option value="1">โรงพยาบาลรวมแพทย์</option>   
                        ?>
                        <option value="<?= htmlspecialchars($hospital['id']); ?>"><?= ($hospital['id'] == 0) ? "ราคามาตรฐาน" : $hospital['hospital']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-auto">
                <select name="nb_price_type_9c" id="nb_price_type_9c" class="form-select" <?= (true) ? "" : " disabled readonly " ?>>
                -<option value="0">แสดงทุกชนิดของโรงพยาบาลนี้</option>
                <?php foreach ($serviceType as $key => $st) : ?>
                    <option value="<?= ($st['id']); ?>" order_list="<?= ($st['order_list']); ?>"   ><?= $st['service_type'] ; ?></option>
                <?php endforeach; ?>
<!--                    <option value="0">กรุณาเลือกชนิด</option>
                    <option value="1">สิ่งส่งตรวจ</option>
                    <option value="2">ย้อมพิเศษ</option>-->
                    
                    
                </select>
            </div>
        </div>

    </div>
</div>



<div class="container-fluid pt-4 px-4">
    <div class="row bg-info rounded align-items-center justify-content-left p-3 mx-1">
        <h1>Price of type <span class="nb_price_type_txt"></span> of hospital <span class="nb_price_hospital_txt"></span> in system</h1>
        <div class="col-auto">
            <button name="nb_price_del_btn_9c" id="nb_price_del_btn_9c" type="" class="btn btn-primary">&nbsp;&nbsp;delete&nbsp;&nbsp;</button>
        </div>


        <div class="bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">

            <style>
                table,
                th,
                td {
                    /*padding: 10px;*/
                    border: 1px solid black;
                    border-collapse: collapse;
                }
            </style>
            <table class="" id="nb_price_tbl_9c">
                <thead>
                    <tr>
                        <th> row_id </th>         
                        <th> hospital </th>
                        <th> type </th>
                        <th> code </th>
                        <th> code2 </th>
                        <th> detail </th>
                        <th> price </th>
                        <th> comment </th>
                        <th> create date </th>
                        <th> add by </th>
                        <th> edit by </th>
                        <th> type Description </th>
                    </tr>
                </thead>
                <tbody id="">
                    <?php if ($hide) : ?>
                        <?php foreach ($nbprices as $p) : ?>
                            <tr>
                                <td> <?= $p['id'] ?> </td>
                                <td> <?= $p['hospital_id'] ?> </td>
                                <td> <?= $p['jobtype'] ?> </td>
                                <td> <?= $p['speciment_num'] ?> </td>
                                <td> <?= $p['specimen'] ?> </td>
                                <td> <?= $p['price'] ?> </td>
                                <td> <?= $p['comment'] ?> </td>
                                <td> <?= $p['create_date'] ?> </td>
                                <td> <?= $p['add_user_id'] ?> </td>
                                <td> <?= $p['edit_user_id'] ?> </td>
                                <td> <?= $p['service_des'] ?> </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>

            </table>
        </div>



    </div>
</div>


<div class="container-fluid pt-4 px-4">
    <div class="row bg-info rounded align-items-left justify-content-left p-3 mx-1">
        <h1>Add price of type <span class="nb_price_type_txt"></span> to hospital<span class="nb_price_hospital_txt"></span> to system.</h1>
        <div class="col-auto">
            <button name="nb_price_add_btn_9c" id="nb_price_add_btn_9c" type="" class="btn btn-primary">&nbsp;&nbsp;Add&nbsp;&nbsp;</button>
        </div>
        <label for="result_message"><b>Paste data from excel (9 Columns)</b></label>
        <p>ลำดับ->code->code2->รายการ->หน่วยนับ->ราคา->comment->service_id->service_description</p>
        <textarea name="nb_price_add_txt_area_9c" cols="100" rows="5" class="form-control" id="nb_price_add_txt_area_9c"></textarea>
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

<script>
    $("#manage_table").addClass("active");
    $("#price_tab_9c").addClass("active");
    $("#manage_table").addClass("show");
    $(".manage_table_dropdown").addClass("show");
    
    
    
    function drawPriceTable_sub(datajson){
    
    $('#nb_price_tbl_9c tbody tr').remove();
    for (var i in datajson)
    {
        /*
         <tr>
         <td> <?= $p['id'] ?> </td>
         <td> <?= $p['hospital_id'] ?> </td>
         <td> <?= $p['jobtype'] ?> </td>
         <td> <?= $p['speciment_num'] ?> </td>
         <td> <?= $p['code2'] ?> </td>
         <td> <?= $p['specimen'] ?> </td>
         <td> <?= $p['price'] ?> </td>
         <td> <?= $p['comment'] ?> </td>
         <td> <?= $p['create_date'] ?> </td>
         <td> <?= $p['add_user_id'] ?> </td>
         <td> <?= $p['edit_user_id'] ?> </td>
         </tr>

         */
        //repaintTblhire1(data);
        var str = '<tr>' +
                '<td>' + datajson[i].id + '</td>' +
                '<td>' + datajson[i].hospital_id + '</td>' +
                '<td>' + datajson[i].jobtype + '</td>' +
                '<td>' + datajson[i].speciment_num + '</td>' +
                '<td>' + datajson[i].code2 + '</td>' +
                '<td>' + datajson[i].specimen + '</td>' +
                '<td>' + datajson[i].price + '</td>' +
                '<td>' + datajson[i].comment + '</td>' +
                '<td>' + datajson[i].create_date + '</td>' +
                '<td>' + datajson[i].add_user_id + '</td>' +
                '<td>' + datajson[i].edit_user_id + '</td>' +
                '<td>' + datajson[i].service_des + '</td>' +
                '</tr>';
        $('#nb_price_tbl_9c tbody').append(str);

    }

    
}


    function drawPriceTable(){
    //    alert('drawPriceTable()');
        let type_id = $('#nb_price_type_9c option').filter(':selected').attr('value');
        let hospital_id = $('#nb_price_hospital_select_9c option').filter(':selected').attr('value');
        $('#nb_price_tbl_9c tbody tr').remove();
        $.ajax({
            'async': false,
            type: 'POST',
            // make sure you respect the same origin policy with this url:
            // http://en.wikipedia.org/wiki/Same_origin_policy
            url: 'ajax_nb_price_9c/get_specimen_9c.php',
            data: {

                'type_id': type_id,
                'hospital_id': hospital_id,

            },
            success: function (data) {
                if (data[0] != "[") {
                    alert(data);
                    console.log(data);
                    return;
                }
                let datajson = JSON.parse(data);
                if(datajson.length == 0){
                    $('#nb_price_del_btn_9c').prop('disabled', true);
                    $('#nb_price_tbl_9c tbody tr').remove();
                    alert("No record for this hospital");
                    return;
                }
    //             alert('data for table'+data);
                 console.log(data);
                 drawPriceTable_sub(datajson);

            },
            error: function (jqxhr, status, exception) {
                alert( jqxhr.responseText);
            }
        });



    }


$("#nb_price_hospital_select_9c").on("change", function () {
    
    $('#nb_price_add_btn_9c').prop('disabled', false);
    $('#nb_price_del_btn_9c').prop('disabled', false);
    $('#nb_price_add_txt_area_9c').prop('disabled', false);

    $(".nb_price_hospital_txt_9c").text('');
    $(".nb_price_type_txt_9c").text('');

    $('#nb_price_tbl_9c tbody tr').remove();
    $("#nb_price_type_9c option").filter(function () {
        //may want to use $.trim in here
        return $(this).val() == 0;
    }).prop('selected', true);
        
    drawPriceTable();    

});


$("#nb_price_type_9c").on("change", function () {
//    alert('nb_price_type_9c change');
    var type_id = $('#nb_price_type_9c option').filter(':selected').attr('value');
    var hospital_id = $('#nb_price_hospital_select_9c option').filter(':selected').attr('value');
    //    
    

    
    $('#nb_price_add_btn_9c').prop('disabled',false);
    $('#nb_price_del_btn_9c').prop('disabled',false);
    $('#nb_price_add_txt_area_9c').prop('disabled',false);

    $(".nb_price_hospital_txt_9c").text('('+$("#nb_price_hospital_select_9c option").filter(':selected').text()+ ')' );
    $(".nb_price_type_txt_9c").text('('+$("#nb_price_type_9c option").filter(':selected').text()+')');
//    alert(type_id + '  ' + hospital_id);

    drawPriceTable();
    



});


$("#nb_price_del_btn_9c").on("click", function (e) {
    if(!confirm("Are you sure to delete ?")){
        return ;
    }
    
    var type_id = $('#nb_price_type_9c option').filter(':selected').attr('value');
    var hospital_id = $('#nb_price_hospital_select_9c option').filter(':selected').attr('value');
    
    $.ajax({
        'async': false,
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: 'ajax_nb_price_9c/delSpecimen_9c.php',
        data: {

            'type_id': type_id,
            'hospital_id': hospital_id,

        },
        success: function (data) {
            if (data[0] != "[") {
                alert(data);
                console.log(data);
                return;
            }
            var datajson = JSON.parse(data);
            if(datajson.length == 0){
                $('#nb_price_del_btn_9c').prop('disabled', true);
                $('#nb_price_tbl_9c tbody tr').remove();
                alert("Record empty now");
                return;
            }
            
           //drawPriceTable(datajson);
            
        },
        error: function (jqxhr, status, exception) {
            alert( jqxhr.responseText);
        }
    });

    drawPriceTable();
    
    
    
});


$("#nb_price_add_btn_9c").on("click", function (e) {
//    alert($("#nb_price_add_txt_area_9c").val());
    let txt = $("#nb_price_add_txt_area_9c").val();
    txt = txt.trim();
    txt = txt.replaceAll("\"", "");
    
    console.log("txt:: " + txt);
    
    let lines = txt.split('\n');
    let data = [];
//    let jobtype =  $('#nb_price_type option').filter(':selected').attr('value');
    var hospital_id = $('#nb_price_hospital_select_9c option').filter(':selected').attr('value');
    var add_user_id = get_cur_user_id();
    var edit_user_id = 0;
    
    for (var i = 0; i < lines.length; i++) {
        //code here using lines[i] which will give you each line

        let line_cur = lines[i];
        let line = line_cur.split('\t');
        if(line[0].localeCompare("") == 0) continue;
        //number   speciment_num  specimen    unit_count   price  comment  | jobtype  hospital_id create_date add_user_id edit_user_id
        //ลำดับ	   code	          รายการ	      หน่วยนับ	   ราคา    comment  | 

            let str = "{" +
                    " \"number\": \""+line[0]+"\"," +
                    " \"speciment_num\": \""+line[1]+"\"," +
                    " \"code2\": \""+line[2]+"\"," +
                    " \"specimen\": \""+line[3]+"\"," +
                    " \"unit_count\": \""+line[4]+"\"," +
                    " \"price\": \""+line[5]+"\"," +
                    " \"comment\": \""+line[6]+"\"," +
                    " \"jobtype\": \""+line[7]+"\"," +
                    " \"hospital_id\": \""+hospital_id+"\"," +
                    " \"add_user_id\": \""+add_user_id+"\"," +
                    " \"edit_user_id\": \""+edit_user_id+"\"," +
                    " \"service_des\": \""+line[8]+"\"" +
                    
                    "}";
            console.log(str);
            
            try{
                
                JSON.parse(str);
            }catch (e){
               
                alert(e + str);
            }
            data.push((str));
            

    }
    
//    console.log(data);
//    alert('data'+data);
    
    //C:\anuchit2\nb_web\ajax_nb_price\addMultRecordiSpecimen.php
    $.ajax({
        'async': false,
        type: 'POST',

        url: 'ajax_nb_price_9c/addMultRecordiSpecimen_9c.php',
        data: {

            'data': data,


        },
        success: function (data) {
            console.log(data);
            //alert(data);
            //return;
            
            if (data[0] != "[") {
                alert("Error::"+data);
                console.log(data);
                return;
            }
            var datajson = JSON.parse(data);
            if(datajson.length == 0){
                $('#nb_price_del_btn_9c').prop('disabled', true);
                $('#nb_price_tbl_9c tbody tr').remove();
                alert("No record added");
                return;
            }
//            drawPriceTable(datajson);
            $("#nb_price_add_txt_area_9c").val("");
            alert("Record added");
            
            

        },
        error: function (jqxhr, status, exception) {
            alert( jqxhr.responseText);
        }
    });
    
    drawPriceTable();
    
});


$(document).ready(function () {

    $('#nb_price_add_btn_9c').prop('disabled', false);
    $('#nb_price_del_btn_9c').prop('disabled', false);
    $('#nb_price_add_txt_area_9c').prop('disabled', false);
    drawPriceTable();

});
    
    
</script>