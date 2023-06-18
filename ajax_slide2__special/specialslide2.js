
//Update table of speciment in main page
function refreshSpecimenList2(isAlert) {
    //alert("start ajax");
    var cur_patient_id = $(".cur_patient_id").attr('tabindex');
    //alert(cur_patient_id);
    $.ajax({
        url: "ajax_slide2__special/getBillingSpecimenList2.php?id=%s",
        data: {id: cur_patient_id},
        success: function (data) {
            //alert(data);
            repaintspecimentable2(data);
            if (isAlert) {
                alert("refresh done");
            }
        }
    });

}

//update drop down list of specimen
function updateSelectionSpeceman2(isalert) {
    var hospital_id = $('#phospital_select_for_price2 option').filter(':selected').val();
    //alert(hospital_id);
    $.ajax({
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: 'ajax_slide2__special/getSpecimenList2.php',
        data: {
            'hospital_id': hospital_id
        },
        success: function (data) {
            if(data[0] != "["){
                alert(data);
            }
            var datajson = JSON.parse(data);
            if (datajson == "" || datajson == null) {
                $('#pspecimen_for_select2 option').remove();
                $('#pspecimen_for_select2').append('<option >No Data for this hospital</option>');
                $('#pspecimen_for_select2').prop('disabled', true);
                $('#add_spcimen_list2').prop('disabled', true);
                if (isalert) {
                    
                    alert('No Data for this Hospital , Please select other hospital');
                }
            } else {
                
                //alert('Success' + datajson);
                $('#pspecimen_for_select2').prop('disabled', false);
                $('#pspecimen_for_select2 option').remove();
                for (var i in datajson)
                {
                    if (i == 0) {
                        $('#pspecimen_for_select2').append('<option value="' + datajson[i].id + '" >กรุณาเลือก</option>');
                    } else {
                        $('#pspecimen_for_select2').append('<option value="' + datajson[i].id + '" price="' + datajson[i].price + '" specimen_num="' + datajson[i].speciment_num + '" comment="' + datajson[i].comment + '" specimen="' + datajson[i].specimen + '">' + datajson[i].specimen + '(' + datajson[i].speciment_num + ')</option>');
                    }
                }
//                if (isalert) {
//                    alert('Please select specimen.');
//                }
            }
        }
    });
}


function repaintspecimentable2(data) {
    if (data[0] != "[") {
        alert(data);
    }
    var datajson = JSON.parse(data);
    if((typeof datajson) != 'object'){alert(data);}
    // clear all data in table befor show new retrived record
    $('#spcimen_list_table2 tbody tr').remove();
    $('#spcimen_list2 span').remove();
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
//                        <a  billid="<?= $billing['id'] ?>" onclick="delbill2(<?= $billing['id'] .','. $patient[0]['id'] ?>);" class="btn btn-outline-dark btn-sm delete"><i class="fa-solid fa-trash-can"></i> Delete</a>
//                    </td>
//                </tr>
        
        var str = '<tr>'+
                '<td>' + datajson[i].id + '</td>'+
                '<td>' + datajson[i].number + '</td>'+//Surgical Number
                '<td>' + datajson[i].code_description + '</td>'+ //ex 33000
                '<td>' + datajson[i].description + '</td>'+
                '<td>' + datajson[i].sp_slide_block + '</td>'+
                '<td>' + datajson[i].cost + '</td>'+
                '<td>' + datajson[i].comment + '</td>'+
                '<td>' + '<a  billid="'+datajson[i].id+'" onclick="delbill2('+datajson[i].id+','+datajson[i].patient_id+');" class="btn btn-outline-dark btn-sm delete"><i class="fa-solid fa-trash-can"></i> Delete</a>' + '</td>'+
                '</tr>';
        
        $('#spcimen_list_table2 tbody').append(str);
        
        var str2 = '<span class="badge rounded-pill bg-primary" id="">('+datajson[i].code_description+')('+datajson[i].description+')('+datajson[i].sp_slide_block+')('+datajson[i].cost+')</span> ';
        $('#spcimen_list2').append(str2);
    }

}



$("#refresh_spcimen_list2").on("click", function () {
    refreshSpecimenList2(true);
});


//On button click add new specimen bill
$("#add_spcimen_list2").on("click", function () {
    //alert("start ajax");
    var printdbg = true;

    var cur_patient_id = $(".cur_patient_id").attr('tabindex');
    var date_1000 = $(".cur_date_1000").attr('tabindex');
    var cur_phospital_num = $(".cur_phospital_num").attr('tabindex');
    var e = document.getElementById("pclinician_id");
    var pclinician_id = e.value;
    var pclinician_text = e.options[e.selectedIndex].text;
    var cur_pnum = $(".cur_pnum").attr('tabindex');



    var e = document.getElementById("phospital_select_for_price2");
    var phospital_id = e.value;
    var phospital_text = e.options[e.selectedIndex].text;

    var e = document.getElementById("pspecimen_for_select2");
    var specimen_id = e.value;
    var specimen_text_selected = e.options[e.selectedIndex].text;

    var specimen_text = document.getElementById("specimen_for_specimen2").value;
    var specimen_num = document.getElementById("specimen_num2").value;

    var price_for_specimen = document.getElementById("price_for_specimen2").value;
    if (price_for_specimen == "") {
        price_for_specimen = "0";
    }
    var comment_for_specimen = document.getElementById("comment_for_specimen2").value;
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
    
    
    
    var uresultTypeNameLastest = '';
    $('.uresultTypeName li').each(function (index) {
        uresultTypeNameLastest = $(this).attr('tabindex');
    });


    //loop insert block name.
    let SP2_Scope = document.getElementById("SP2_Scope");
    let input_blox = SP2_Scope.getElementsByTagName("input");
    let blox_name = [];
    let blox_count = 0;
    for (i = 0; i < input_blox.length; i++) {
        if (input_blox[i].checked) {
            console.log("Checked");
            console.log(input_blox[i].getAttribute("va"));
            blox_name.push(input_blox[i].getAttribute("va"));
            blox_count = blox_count + 1;
        } else {
            console.log("UnChecked");
            console.log(input_blox[i].getAttribute("va"));
        }
    }
    
    if(blox_count==0){
        alert("ยังไม่ได้เลือกชนิดบล็ฮก กรุณาเลือกชนิดบล็อก ก่อน");
        return null;
    }
    
    for(i = 0; i < blox_name.length ; i++){
        console.log(blox_name[i]);
    }
    
    console.log("blox_count = "+blox_count)


    
    //return null;

    $.ajax({
        type: 'POST',
        'async': false,
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: 'ajax_slide2__special/createBillingSpecimen2.php',
        data: {
            'patient_id': cur_patient_id,
            'blox_name':blox_name,
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
            'date_1000': date_1000
        },
        success: function (data) {
            
            console.log(data);
            if (data[0] != "[") {
                alert(data);
            }
            //return null;
            
            repaintspecimentable2(data);
        },
        error: function (jqxhr, status, exception) {
            alert('Exception:', exception);
        }
    });

    alert('Finish Added.');
});



//on click button delete for seleced specimen list bill in main page
function delbill2(billid,patientid) {
    
    if( confirm("delete bill "+billid)){
       
        $.ajax({
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: 'ajax_slide2__special/delBill2.php',
        data: {
            'bill_id': billid,
            'patient_id': patientid,
            
        },
        success: function (data) {
           
            repaintspecimentable2(data);
        
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
$('#phospital_select_for_price2').on('change', function () {
    //update drop down list of specimen
    updateSelectionSpeceman2(true);
});

//on select specimen change
$('#pspecimen_for_select2').on('change', function () {
    //alert( "Get from ID: "+this.value );
    if( ($('#pspecimen_for_select2 option').filter(':selected').attr('value')) == '0' ){
        $('#add_spcimen_list2').prop('disabled', true);
    }else{
        $('#add_spcimen_list2').prop('disabled', false);
    }
    
    $('#specimen_num2').val($('#pspecimen_for_select2 option').filter(':selected').attr('specimen_num'));
    $('#specimen_for_specimen2').val($('#pspecimen_for_select2 option').filter(':selected').attr('specimen'));
    $('#price_for_specimen2').val($('#pspecimen_for_select2 option').filter(':selected').attr('price'));
    $('#comment_for_specimen2').val($('#pspecimen_for_select2 option').filter(':selected').attr('comment'));

});




$(document).ready(function () {
        updateSelectionSpeceman2(false);
        refreshSpecimenList2(false);
    
});



$("#btntest2").on("click", function () {
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

    //phospital_select_for_price2
    var e = document.getElementById("phospital_select_for_price2");
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

