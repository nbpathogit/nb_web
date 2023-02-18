
//Update table of speciment in main page
function refreshSpecimenList(isAlert) {
    //alert("start ajax");
    var cur_patient_id = $(".cur_patient_id").attr('tabindex');
    //alert(cur_patient_id);
    $.ajax({
        url: "/ajax_patient_specimen_list/getBillingSpecimenList.php?id=%s",
        data: {id: cur_patient_id},
        success: function (data) {
            //alert(data);
            repeintspecimentable(data);
            if (isAlert) {
                alert("refresh done");
            }
        }
    });

}

//update drop down list of specimen
function updateSelectionSpeceman(isalert) {
    var hospital_id = $('#phospital_select_for_price1 option').filter(':selected').val();
    //alert(hospital_id);
    $.ajax({
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: '/ajax_patient_specimen_list/getSpecimenList.php',
        data: {
            'hospital_id': hospital_id
        },
        success: function (data) {

            var datajson = JSON.parse(data);
            if (datajson == "" || datajson == null) {
                $('#pspecimen_for_select option').remove();
                $('#pspecimen_for_select').append('<option >No Data for this hospital</option>');
                $('#pspecimen_for_select').prop('disabled', true);
                $('#add_spcimen_list').prop('disabled', true);
                if (isalert) {
                    
                    alert('No Data for this Hospital , Please select other hospital');
                }
            } else {
                
                //alert('Success' + datajson);
                $('#pspecimen_for_select').prop('disabled', false);
                $('#pspecimen_for_select option').remove();
                for (var i in datajson)
                {
                    if (i == 0) {
                        $('#pspecimen_for_select').append('<option value="' + datajson[i].id + '" >กรุณาเลือก</option>');
                    } else {
                        $('#pspecimen_for_select').append('<option value="' + datajson[i].id + '" price="' + datajson[i].price + '" specimen_num="' + datajson[i].speciment_num + '" comment="' + datajson[i].comment + '" specimen="' + datajson[i].specimen + '">' + datajson[i].specimen + '(' + datajson[i].speciment_num + ')</option>');
                    }
                }
//                if (isalert) {
//                    alert('Please select specimen.');
//                }
            }
        }
    });
}


function repeintspecimentable(data) {
    var datajson = JSON.parse(data);
    if((typeof datajson) != 'object'){alert(data);}
    // clear all data in table befor show new retrived record
    $('#spcimen_list_table tbody tr').remove();
    // Show new retrived record
    for (var i in datajson)
    {
        
//                <tr>
//                    <td ><?= $billing['id'] ?></td>
//                    <td ><?= $billing['number'] ?></td>
//                    <td ><?= $billing['code_description'] ?></td>
//                    <td ><?= $billing['description'] ?></td>
//                    <td ><?= $billing['cost'] ?></td>
//                    <td ><?= $billing['comment'] ?></td>
//                    <td >
//                        <a  billid="<?= $billing['id'] ?>" onclick="delbill(<?= $billing['id'] .','. $patient[0]['id'] ?>);" class="btn btn-outline-dark btn-sm delete"><i class="fa-solid fa-trash-can"></i> Delete</a>
//                    </td>
//                </tr>
        
        var str = '<tr>'+
                '<td>' + datajson[i].id + '</td>'+
                '<td>' + datajson[i].number + '</td>'+//Surgical Number
                '<td>' + datajson[i].code_description + '</td>'+ //ex 33000
                '<td>' + datajson[i].description + '</td>'+
                '<td>' + datajson[i].cost + '</td>'+
                '<td>' + datajson[i].comment + '</td>'+
                '<td>' + '<a  billid="'+datajson[i].id+'" onclick="delbill('+datajson[i].id+','+datajson[i].patient_id+');" class="btn btn-outline-dark btn-sm delete"><i class="fa-solid fa-trash-can"></i> Delete</a>' + '</td>'+
                '</tr>';
        
        $('#spcimen_list_table tbody').append(str);
    }

}



$("#refresh_spcimen_list").on("click", function () {
    refreshSpecimenList(true);
});


//On button click add new specimen bill
$("#add_spcimen_list").on("click", function () {
    //alert("start ajax");
    var printdbg = true;

    var cur_patient_id = $(".cur_patient_id").attr('tabindex');
    var date_1000 = $(".cur_date_1000").attr('tabindex');
    var cur_phospital_num = $(".cur_phospital_num").attr('tabindex');

    var e = document.getElementById("phospital_select_for_price1");
    var phospital_id = e.value;
    var phospital_text = e.options[e.selectedIndex].text;

    var e = document.getElementById("pclinician_id");
    var pclinician_id = e.value;
    var pclinician_text = e.options[e.selectedIndex].text;


    var cur_pnum = $(".cur_pnum").attr('tabindex');

    var e = document.getElementById("pspecimen_for_select");
    var specimen_id = e.value;
    var specimen_text_selected = e.options[e.selectedIndex].text;

    var specimen_text = document.getElementById("specimen_for_specimen").value;
    var specimen_num = document.getElementById("specimen_num").value;

    var price_for_specimen = document.getElementById("price_for_specimen").value;
    if (price_for_specimen == "") {
        price_for_specimen = "0";
    }
    var comment_for_specimen = document.getElementById("comment_for_specimen").value;

    if (printdbg) {
        console.log("==============");
        console.log("cur_patient_id::" + cur_patient_id);
        console.log("date_1000::" + date_1000);
        console.log("cur_phospital_num::" + cur_phospital_num);
        console.log("phospital_id::" + phospital_id);
        console.log("pclinician_text::" + pclinician_text);
        console.log("cur_pnum::" + cur_pnum);

        console.log("specimen_id::" + specimen_id);//Specimen code

        console.log("specimen_text" + specimen_text);
        console.log("price_for_specimen" + price_for_specimen);
        console.log("comment_for_specimen" + comment_for_specimen);
        console.log("==============");

    }

    if (specimen_text == "" || price_for_specimen == "") {
        alert("No data insert");
        return null;
    }

    $.ajax({
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: '/ajax_patient_specimen_list/createBillingSpecimen.php',
        data: {
            'patient_id': cur_patient_id,
            'cur_pnum': cur_pnum,
            'specimen_id': specimen_id,
            'specimen_num': specimen_num,//specimen code
            'specimen_text': specimen_text,
            'price_for_specimen': price_for_specimen,
            'comment_for_specimen': comment_for_specimen,
            'phospital_text': phospital_text,
            'cur_phospital_num': cur_phospital_num,
            'pclinician_text': pclinician_text,
            'date_1000': date_1000
        },
        success: function (data) {
            repeintspecimentable(data);
        },
        error: function (jqxhr, status, exception) {
            alert('Exception:', exception);
        }
    });

    alert('Finish Added.');
});



//on click button delete for seleced specimen list bill in main page
function delbill(billid,patientid) {
    
    if( confirm("delete bill "+billid)){
       
        $.ajax({
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: '/ajax_patient_specimen_list/delBill.php',
        data: {
            'bill_id': billid,
            'patient_id': patientid,
            
        },
        success: function (data) {
           
            repeintspecimentable(data);
            if (isAlert) {
                alert("refresh done");
            }
        
            alert('Success'+data);
        },
        error: function (jqxhr, status, exception) {
            alert('Exception:', exception);
        }

    });
        
    }else{
       
    }

}


//on select hospital change 
$('#phospital_select_for_price1').on('change', function () {
    //update drop down list of specimen
    updateSelectionSpeceman(true);
});

//on select specimen change
$('#pspecimen_for_select').on('change', function () {
    //alert( "Get from ID: "+this.value );
    if( ($('#pspecimen_for_select option').filter(':selected').attr('value')) == '0' ){
        $('#add_spcimen_list').prop('disabled', true);
    }else{
        $('#add_spcimen_list').prop('disabled', false);
    }
    
    $('#specimen_num').val($('#pspecimen_for_select option').filter(':selected').attr('specimen_num'));
    $('#specimen_for_specimen').val($('#pspecimen_for_select option').filter(':selected').attr('specimen'));
    $('#price_for_specimen').val($('#pspecimen_for_select option').filter(':selected').attr('price'));
    $('#comment_for_specimen').val($('#pspecimen_for_select option').filter(':selected').attr('comment'));

});




$(document).ready(function () {
    updateSelectionSpeceman(false);

});



$("#btntest").on("click", function () {
    //alert("start ajax");
    var cur_patient_id = $(".cur_patient_id").attr('tabindex');
    var date_1000 = $(".cur_date_1000").attr('tabindex');
    var cur_pnum = $(".cur_pnum").attr('tabindex');

    var e = document.getElementById("pspecimen_for_select");
    var specimen_id = e.value;
    var specimen_text = e.options[e.selectedIndex].text;

    var price_for_specimen = document.getElementById("price_for_specimen").value;
    if (price_for_specimen == "") {
        price_for_specimen = "0";
    }
    var comment_for_specimen = document.getElementById("comment_for_specimen").value;

    //phospital_select_for_price1
    var e = document.getElementById("phospital_select_for_price1");
    var hospital_id = e.value;
    var hospital_text = e.options[e.selectedIndex].text;

    console.log("==============");
    console.log(specimen_id);
    console.log(specimen_text);
    console.log("==============");
    console.log(price_for_specimen);
    console.log(comment_for_specimen);
    console.log("==============");
    console.log(hospital_id);
    console.log(hospital_text);
    console.log("==============");

});

