function drawPriceTable_sub(datajson){
    
    $('#nb_price_tbl_8c tbody tr').remove();
    for (var i in datajson)
    {
        /*
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
         </tr>

         */
        //repaintTblhire1(data);
        var str = '<tr>' +
                '<td>' + datajson[i].id + '</td>' +
                '<td>' + datajson[i].hospital_id + '</td>' +
                '<td>' + datajson[i].jobtype + '</td>' +
                '<td>' + datajson[i].speciment_num + '</td>' +
                '<td>' + datajson[i].specimen + '</td>' +
                '<td>' + datajson[i].price + '</td>' +
                '<td>' + datajson[i].comment + '</td>' +
                '<td>' + datajson[i].create_date + '</td>' +
                '<td>' + datajson[i].add_user_id + '</td>' +
                '<td>' + datajson[i].edit_user_id + '</td>' +
                '<td>' + datajson[i].service_des + '</td>' +
                '</tr>';
        $('#nb_price_tbl_8c tbody').append(str);

    }

    
}




function drawPriceTable(){
//    alert('drawPriceTable()');
    let type_id = $('#nb_price_type_8c option').filter(':selected').attr('value');
    let hospital_id = $('#nb_price_hospital_select_8c option').filter(':selected').attr('value');
    $('#nb_price_tbl_8c tbody tr').remove();
    $.ajax({
        'async': false,
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: 'ajax_nb_price_8c/get_specimen_8c.php',
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
                $('#nb_price_del_btn_8c').prop('disabled', true);
                $('#nb_price_tbl_8c tbody tr').remove();
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


$("#nb_price_hospital_select_8c").on("change", function () {
    
    $('#nb_price_add_btn_8c').prop('disabled', false);
    $('#nb_price_del_btn_8c').prop('disabled', false);
    $('#nb_price_add_txt_area_8c').prop('disabled', false);

    $(".nb_price_hospital_txt_8c").text('');
    $(".nb_price_type_txt_8c").text('');

    $('#nb_price_tbl_8c tbody tr').remove();
    $("#nb_price_type_8c option").filter(function () {
        //may want to use $.trim in here
        return $(this).val() == 0;
    }).prop('selected', true);
        
    drawPriceTable();    

});


$("#nb_price_type_8c").on("change", function () {
//    alert('nb_price_type_8c change');
    var type_id = $('#nb_price_type_8c option').filter(':selected').attr('value');
    var hospital_id = $('#nb_price_hospital_select_8c option').filter(':selected').attr('value');
//    if (type_id == 0) {
//        $('#nb_price_tbl tbody tr').remove();
//        $('#nb_price_add_btn_8c').prop('disabled',true);
//        $('#nb_price_del_btn_8c').prop('disabled',true);
//        $('#nb_price_add_txt_area_8c').prop('disabled',true);
//        
//        $(".nb_price_hospital_txt_8c").text('');
//        $(".nb_price_type_txt_8c").text('');
//        return;
//    }
    
    $('#nb_price_add_btn_8c').prop('disabled',false);
    $('#nb_price_del_btn_8c').prop('disabled',false);
    $('#nb_price_add_txt_area_8c').prop('disabled',false);

    $(".nb_price_hospital_txt_8c").text('('+$("#nb_price_hospital_select_8c option").filter(':selected').text()+ ')' );
    $(".nb_price_type_txt_8c").text('('+$("#nb_price_type_8c option").filter(':selected').text()+')');
//    alert(type_id + '  ' + hospital_id);

    drawPriceTable();
    



});


$("#nb_price_del_btn_8c").on("click", function (e) {
    if(!confirm("Are you sure to delete ?")){
        return ;
    }
    
    var type_id = $('#nb_price_type_8c option').filter(':selected').attr('value');
    var hospital_id = $('#nb_price_hospital_select_8c option').filter(':selected').attr('value');
    
    $.ajax({
        'async': false,
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: 'ajax_nb_price_8c/delSpecimen_8c.php',
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
                $('#nb_price_del_btn_8c').prop('disabled', true);
                $('#nb_price_tbl_8c tbody tr').remove();
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


$("#nb_price_add_btn_8c").on("click", function (e) {
//    alert($("#nb_price_add_txt_area_8c").val());
    let txt = $("#nb_price_add_txt_area_8c").val();
    txt = txt.trim();
    txt = txt.replaceAll("\"", "");
    
    console.log("txt:: " + txt);
    
    let lines = txt.split('\n');
    let data = [];
//    let jobtype =  $('#nb_price_type option').filter(':selected').attr('value');
    var hospital_id = $('#nb_price_hospital_select_8c option').filter(':selected').attr('value');
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
                    " \"specimen\": \""+line[2]+"\"," +
                    " \"unit_count\": \""+line[3]+"\"," +
                    " \"price\": \""+line[4]+"\"," +
                    " \"comment\": \""+line[5]+"\"," +
                    " \"jobtype\": \""+line[6]+"\"," +
                    " \"hospital_id\": \""+hospital_id+"\"," +
                    " \"add_user_id\": \""+add_user_id+"\"," +
                    " \"edit_user_id\": \""+edit_user_id+"\"," +
                    " \"service_des\": \""+line[7]+"\"" +
                    
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
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: 'ajax_nb_price_8c/addMultRecordiSpecimen_8c.php',
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
                $('#nb_price_del_btn_8c').prop('disabled', true);
                $('#nb_price_tbl_8c tbody tr').remove();
                alert("No record added");
                return;
            }
//            drawPriceTable(datajson);
            $("#nb_price_add_txt_area_8c").val("");
            alert("Record added");
            
            

        },
        error: function (jqxhr, status, exception) {
            alert( jqxhr.responseText);
        }
    });
    
    drawPriceTable();
    
});


$(document).ready(function () {

    $('#nb_price_add_btn_8c').prop('disabled', false);
    $('#nb_price_del_btn_8c').prop('disabled', false);
    $('#nb_price_add_txt_area_8c').prop('disabled', false);
    drawPriceTable();

});