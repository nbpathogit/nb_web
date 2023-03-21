$("#nb_price_hospital_select").on("change", function () {
    
    $('#nb_price_add_btn').prop('disabled', true);
    $('#nb_price_del_btn').prop('disabled', true);
    $('#nb_price_add_txt_area').prop('disabled', true);

    $(".nb_price_hospital_txt").text('');
    $(".nb_price_type_txt").text('');

    $('#nb_price_tbl tbody tr').remove();
    $("#nb_price_type option").filter(function () {
        //may want to use $.trim in here
        return $(this).val() == 0;
    }).prop('selected', true);

});




$("#nb_price_type").on("change", function () {
    var type_id = $('#nb_price_type option').filter(':selected').attr('value');
    var hospital_id = $('#nb_price_hospital_select option').filter(':selected').attr('value');
    if (type_id == 0) {
        $('#nb_price_tbl tbody tr').remove();
        $('#nb_price_add_btn').prop('disabled',true);
        $('#nb_price_del_btn').prop('disabled',true);
        $('#nb_price_add_txt_area').prop('disabled',true);
        
        $(".nb_price_hospital_txt").text('');
        $(".nb_price_type_txt").text('');
        return;
    }
    
    $('#nb_price_add_btn').prop('disabled',false);
    $('#nb_price_del_btn').prop('disabled',false);
    $('#nb_price_add_txt_area').prop('disabled',false);

    $(".nb_price_hospital_txt").text('('+$("#nb_price_hospital_select option").filter(':selected').text()+ ')' );
    $(".nb_price_type_txt").text('('+$("#nb_price_type option").filter(':selected').text()+')');
//    alert(type_id + '  ' + hospital_id);


    $.ajax({
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: '/ajax_nb_price/get_specimen.php',
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
                $('#nb_price_del_btn').prop('disabled', true);
                alert("No record for this hospital");
                return;
            }
            
            
            $('#nb_price_tbl tbody tr').remove();
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
                        '</tr>';
                $('#nb_price_tbl tbody').append(str);

            }
        },
        error: function (jqxhr, status, exception) {
            alert('Exception:', exception);
        }
    });



});

$("#nb_price_del_btn").on("click", function (e) {
    if(!confirm("Are you sure to delete?")){
        return ;
    }
    
    alert("deleted");
});


$("#nb_price_add_btn").on("click", function (e) {
//    alert($("#nb_price_add_txt_area").val());
    let txt = $("#nb_price_add_txt_area").val();
    txt = txt.trim();
    let lines = txt.split('\n');
    let data = [];
    let jobtype =  $('#nb_price_type option').filter(':selected').attr('value');
    var hospital_id = $('#nb_price_hospital_select option').filter(':selected').attr('value');
    var add_user_id = get_cur_user_id();
    var edit_user_id = 0;
    
    for (var i = 0; i < lines.length; i++) {
        //code here using lines[i] which will give you each line

        let line_cur = lines[i];
        let line = line_cur.split('\t');
        //number   speciment_num  specimen    unit_count   price  comment  | jobtype  hospital_id create_date add_user_id edit_user_id
        //ลำดับ	   code	          รายการ	      หน่วยนับ	   ราคา    comment  | 

            let str = "{" +
                    " \"number\": \""+line[0]+"\"," +
                    " \"speciment_num\": \""+line[1]+"\"," +
                    " \"specimen\": \""+line[2]+"\"," +
                    " \"unit_count\": \""+line[3]+"\"," +
                    " \"price\": \""+line[4]+"\"," +
                    " \"comment\": \""+line[5]+"\"," +
                    " \"jobtype\": \""+jobtype+"\"," +
                    " \"hospital_id\": \""+hospital_id+"\"," +
                    " \"add_user_id\": \""+add_user_id+"\"," +
                    " \"edit_user_id\": \""+edit_user_id+"\"" +
                    
                    "}";
            JSON.parse(str);
            data.push((str));
            console.log(str);

    }
    
    console.log(data);
    
    //C:\anuchit2\nb_web\ajax_nb_price\addMultRecordiSpecimen.php
    $.ajax({
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: '/ajax_nb_price/addMultRecordiSpecimen.php',
        data: {

            'data': data,


        },
        success: function (data) {
            console.log(data);
            alert(data);
            return;
            
            if (data[0] != "[") {
                alert(data);
                console.log(data);
                return;
            }
            var datajson = JSON.parse(data);
            if(datajson.length == 0){
                $('#nb_price_del_btn').prop('disabled', true);
                alert("No record for this hospital");
                return;
            }
            
            
            $('#nb_price_tbl tbody tr').remove();
            
        },
        error: function (jqxhr, status, exception) {
            alert('Exception:', exception);
        }
    });
    
    
    
});


$(document).ready(function () {

    $('#nb_price_add_btn').prop('disabled', true);
    $('#nb_price_del_btn').prop('disabled', true);
    $('#nb_price_add_txt_area').prop('disabled', true);


});