//var subFolder1 = "";
//Update table of speciment in main page
function refreshSpecimenList1(isAlert) {
    //alert("start ajax");
    var cur_patient_id = $(".cur_patient_id").attr('tabindex');
    //alert(cur_patient_id);
    $.ajax({
        url: "ajax_slide1_specimen/getBillingSpecimenList1.php?id=%s",
        data: {id: cur_patient_id},
        success: function (data) {
            //alert(data);
            repaintspecimentable1(data);
            if (isAlert) {
                alert("refresh done");
            }
        }
    });

}

//update drop down list of specimen
function updateSelectionSpeceman1(isalert) {
    var hospital_id = $('#phospital_select_for_price1 option').filter(':selected').val();
//    alert("hospital_id"+hospital_id);
    $.ajax({
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: 'ajax_slide1_specimen/getSpecimenList1.php',
        data: {
            'hospital_id': hospital_id
        },
        success: function (data) {
            if(data[0] != "["){
                alert(data);
            }
            var datajson = JSON.parse(data);

            if (datajson == "" || datajson == null) {
                $('#pspecimen_for_select1 option').remove();
                $('#pspecimen_for_select1').append('<option >No Data for this hospital</option>');
                $('#pspecimen_for_select1').prop('disabled', true);
                $('#add_spcimen_list1').prop('disabled', true);
                if (isalert) {
                    
                    alert('No Data for this Hospital , Please select other hospital');
                }
            } else {
                
                //alert('Success' + datajson);
                $('#pspecimen_for_select1').prop('disabled', false);
                $('#pspecimen_for_select1 option').remove();
                $('#pspecimen_for_select1').append('<option value="' + '' + '" >กรุณาเลือก</option>');
                for (var i in datajson)
                {

                    $('#pspecimen_for_select1').append('<option value="' + datajson[i].id + '" price="' + datajson[i].price + '" specimen_num="' + datajson[i].speciment_num + '" comment="' + datajson[i].comment + '" specimen="' + datajson[i].specimen + '" jobtype="'+datajson[i].jobtype+'" >' + datajson[i].specimen + '(' + datajson[i].speciment_num + ')</option>');

                }
//                if (isalert) {
//                    alert('Please select specimen.');
//                }
            }
        }
    });
}


function repaintspecimentable1(data) {
    if (data[0] != "[") {
        alert(data);
    }
    var datajson = JSON.parse(data);

    // clear all data in table befor show new retrived record
    $('#spcimen_list_table1 tbody tr').remove();
    $('#spcimen_list1 span').remove();
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
//                        <a  billid="<?= $billing['id'] ?>" onclick="delbill1(<?= $billing['id'] .','. $patient[0]['id'] ?>);" class="btn btn-outline-dark btn-sm delete"><i class="fa-solid fa-trash-can"></i> Delete</a>
//                    </td>
//                </tr>
        
        var str = '<tr>'+
                '<td>' + datajson[i].id + '</td>'+
                '<td>' + datajson[i].number + '</td>'+//Surgical Number
                '<td>' + datajson[i].code_description + '</td>'+ //ex 33000
                '<td>' + datajson[i].description + '</td>'+
                '<td>' + datajson[i].cost + '</td>'+
                '<td>' + datajson[i].comment + '</td>'+
                '<td>' + '<a  billid="'+datajson[i].id+'" onclick="delbill1('+datajson[i].id+','+datajson[i].patient_id+');" class="btn btn-outline-dark btn-sm delete"><i class="fa-solid fa-trash-can"></i> Delete</a>' + '</td>'+
                '</tr>';
        
        $('#spcimen_list_table1 tbody').append(str);
        
        var str2 = '<span class="badge rounded-pill bg-primary" id="">('+datajson[i].code_description+')'+datajson[i].description+'</span> ';
        $('#spcimen_list1').append(str2);
    }

}



$("#refresh_spcimen_list1").on("click", function () {
    refreshSpecimenList1(true);
});


//On button click add new specimen bill
$("#add_spcimen_list1").on("click", function () {
    //alert("start ajax");
    var printdbg = true;

    var cur_patient_id = $(".cur_patient_id").attr('tabindex');
    var date_1000 = $(".cur_date_1000").attr('tabindex');
    var cur_phospital_num = $(".cur_phospital_num").attr('tabindex');
    var e = document.getElementById("pclinician_id");
    var pclinician_id = e.value;
    var pclinician_text = e.options[e.selectedIndex].text;
    var cur_pnum = $(".cur_pnum").attr('tabindex');



    var e = document.getElementById("phospital_select_for_price1");
    var phospital_id = e.value;
    var phospital_text = e.options[e.selectedIndex].text;

    var e = document.getElementById("pspecimen_for_select1");
    var specimen_id = e.value;
    var specimen_text_selected = e.options[e.selectedIndex].text;

    var specimen_text = document.getElementById("specimen_for_specimen1").value;
    var specimen_num = document.getElementById("specimen_num1").value;

    let jobtype = document.getElementById("jobtype_for_specimen1").value;
    
    var price_for_specimen = document.getElementById("price_for_specimen1").value;
    if (price_for_specimen == "") {
        price_for_specimen = "0";
    }
    var comment_for_specimen = document.getElementById("comment_for_specimen1").value;
    var hospital_id = get_cur_hospital_id();

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
        url: 'ajax_slide1_specimen/createBillingSpecimen1.php',
        data: {
            'patient_id': cur_patient_id,
            'cur_pnum': cur_pnum,
            'specimen_id': specimen_id,
            'specimen_num': specimen_num,//specimen code
            'specimen_text': specimen_text,
            'price_for_specimen': price_for_specimen,
            'comment_for_specimen': comment_for_specimen,
            'phospital_text': phospital_text,
            'hospital_id': hospital_id,
            'cur_phospital_num': cur_phospital_num,
            'pclinician_text': pclinician_text,
            'date_1000': date_1000,
            'jobtype': jobtype
        },
        success: function (data) {
            repaintspecimentable1(data);
        },
        error: function (jqxhr, status, exception) {
            alert('Exception:', exception);
        }
    });

    alert('Finish Added.');
});



//on click button delete for seleced specimen list bill in main page
function delbill1(billid,patientid) {
    
    if( confirm("delete bill "+billid)){
       
        $.ajax({
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: 'ajax_slide1_specimen/delBill1.php',
        data: {
            'bill_id': billid,
            'patient_id': patientid,
            
        },
        success: function (data) {
           
            repaintspecimentable1(data);
        
            alert('Success');
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
    var hospital_id = $('#phospital_select_for_price1 option').filter(':selected').val();
    if (hospital_id == -1) {
        $("#specimen_num1").val('');
        $("#specimen_for_specimen1").val('');
        $("#price_for_specimen1").val('');
        $("#comment_for_specimen1").val('=ใส่ข้อมูลเอง=');
        $('#add_spcimen_list1').prop('disabled', false);

        $("#specimen_num1").prop('readonly', false);
        $("#specimen_for_specimen1").prop('readonly', false);
        $("#price_for_specimen1").prop('readonly', false);
        $("#comment_for_specimen1").prop('readonly', true);


        $('#pspecimen_for_select1 option').remove();
        $('#pspecimen_for_select1').append('<option ></option>');
        $('#pspecimen_for_select1').prop('disabled', true);

    } else {

        $("#specimen_num1").val('');
        $("#specimen_for_specimen1").val('');
        $("#price_for_specimen1").val('');
        $("#comment_for_specimen1").val('');
        $('#add_spcimen_list1').prop('disabled', true);
        
        $("#specimen_num1").prop('readonly', true);
        $("#specimen_for_specimen1").prop('readonly', true);
        $("#price_for_specimen1").prop('readonly', true);
        $("#comment_for_specimen1").prop('readonly', true);
        
                //update drop down list of specimen
        updateSelectionSpeceman1(true);

    }
});

//on select specimen change
$('#pspecimen_for_select1').on('change', function () {
    //alert( "Get from ID: "+this.value );
    if( ($('#pspecimen_for_select1 option').filter(':selected').attr('value')) == '0' ){
        $('#add_spcimen_list1').prop('disabled', true);
    }else{
        $('#add_spcimen_list1').prop('disabled', false);
    }
    
    $('#specimen_num1').val($('#pspecimen_for_select1 option').filter(':selected').attr('specimen_num'));
    $('#specimen_for_specimen1').val($('#pspecimen_for_select1 option').filter(':selected').attr('specimen'));
    $('#price_for_specimen1').val($('#pspecimen_for_select1 option').filter(':selected').attr('price'));
    $('#comment_for_specimen1').val($('#pspecimen_for_select1 option').filter(':selected').attr('comment'));
    $('#jobtype_for_specimen1').val($('#pspecimen_for_select1 option').filter(':selected').attr('jobtype'));

});




$(document).ready(function () {
    
//    var fileName = location.href.split("/").slice(-1); 
//    fileName = fileName.split("?").slice(1);
//    
//    alert(fileName);
    updateSelectionSpeceman1(false);
    refreshSpecimenList1(false);

});



$("#btntest1").on("click", function () {
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

