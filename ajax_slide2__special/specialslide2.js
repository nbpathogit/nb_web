
//Update Price to text box
function refreshDetailOfPrice(value) {
    
    //alert("value:"+value);
    
    if( ($('#pspecimen_for_select2 option').filter(':selected').attr('value')) == '0' ){
        $('#add_spcimen_list2').prop('disabled', true);
    }else{
        $('#add_spcimen_list2').prop('disabled', false);
    }
    
   
    
    $.ajax({
        'async': false,
        type: 'POST',
        'global': false,
        type: 'POST',
        url: 'ajax_slide2__special/getSpecimenList2_getByID.php',
        data: {
            'id': value,
        },
        success: function (data) {
            console.log(data);
            var datajson = JSON.parse(data); //convert String to JS Object
            for (var i in datajson)
            {
                console.log(datajson[i].speciment_num);
                console.log(datajson[i].specimen);
                console.log(datajson[i].price);
                console.log(datajson[i].comment);
                console.log(datajson[i].jobtype);
                $('#specimen_num2').val(datajson[i].speciment_num);
                $('#specimen_for_specimen2').val(datajson[i].specimen);
                $('#price_for_specimen2').val(datajson[i].price);
                $('#comment_for_specimen2').val('');
                $('#comment_note').val(datajson[i].comment);
                $('#job_type').val(datajson[i].jobtype);
                
                
                $('#comment_for_specimen2').prop('readonly', false);

                
            }

        },
        error: function (jqxhr, status, exception) {
            alert('Exception:', exception);
        }
    });

    
//    $('#specimen_num2').val($('#pspecimen_for_select2 option').filter(':selected').attr('specimen_num'));
//    $('#specimen_for_specimen2').val($('#pspecimen_for_select2 option').filter(':selected').attr('specimen'));
//    $('#price_for_specimen2').val($('#pspecimen_for_select2 option').filter(':selected').attr('price'));
//    $('#comment_for_specimen2').val($('#pspecimen_for_select2 option').filter(':selected').attr('comment'));

}

//Update 
function clearDetailOfPrice() {
    
    $('#specimen_num2').val('');
    $('#specimen_num2').prop('readonly', true);
    $('#specimen_for_specimen2').val('');
    $('#specimen_for_specimen2').prop('readonly', true);
    $('#price_for_specimen2').val('');
    $('#price_for_specimen2').prop('readonly', true);
    $('#comment_for_specimen2').val('');
    $('#comment_for_specimen2').prop('readonly', true);
    
//    $('#specimen_num2').val($('#pspecimen_for_select2 option').filter(':selected').attr('specimen_num'));
//    $('#specimen_for_specimen2').val($('#pspecimen_for_select2 option').filter(':selected').attr('specimen'));
//    $('#price_for_specimen2').val($('#pspecimen_for_select2 option').filter(':selected').attr('price'));
//    $('#comment_for_specimen2').val($('#pspecimen_for_select2 option').filter(':selected').attr('comment'));

}


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

//update drop down list of specimen2 type
function updateSelectionSpeceman2Type(isalert) {
    var hospital_id = $('#phospital_select_for_price2 option').filter(':selected').val();
//    alert('updateSelectionSpeceman2Type()');
    $.ajax({
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: 'ajax_servicetype/getServiceTypeGroup2.php',
        data: {
            'hospital_id': hospital_id// un used
        },
        success: function (data) {
            if(data[0] != "["){
                alert(data);
            }
            var datajson = JSON.parse(data);
            if (datajson == "" || datajson == null) {
                $('#nb_price2_type option').remove();
                $('#nb_price2_type_div label').remove();
                $('#nb_price2_type_div select').remove();
                $('#nb_price2_type_div div').remove();
                
                $('#nb_price2_type_div').append('<label for="nb_price2_type" class="">เลือกชนิดตรวจพิเศษ</label>');
                $('#nb_price2_type_div').append('<select name="nb_price2_type" id="nb_price2_type" class=""  > </select>');
                $('#nb_price2_type').append('<option >No Data</option>');
                $('#nb_price2_type').prop('disabled', true);
                $('#nb_price2_type').prop('disabled', true);
                $('#nb_price2_type').selectize({
                    sortField: 'text'
                });
                if (isalert) {
                    
                    alert('No Data');
                }
            } else {
                $('#nb_price2_type option').remove();
                $('#nb_price2_type_div label').remove();
                $('#nb_price2_type_div select').remove();
                $('#nb_price2_type_div div').remove();
               
                $('#nb_price2_type_div').append('<label for="nb_price2_type" class="">เลือกชนิดตรวจพิเศษ</label>');
                $('#nb_price2_type_div').append('<select name="nb_price2_type" id="nb_price2_type" class=""> </select>');
                //alert('Success' + datajson);
                
//                $('#pspecimen_for_select2 option').remove();
                $('#nb_price2_type').append('<option value="' + '' + '" >กรุณาเลือก</option>');
                for (var i in datajson)
                {
                    $('#nb_price2_type').append('<option value="' + datajson[i].id + '" order_list="' + datajson[i].order_list + '" group_type="' + datajson[i].group_type + '" >' + datajson[i].service_type + ')</option>');
                }

                $('#nb_price2_type').selectize({
                    sortField: 'text',
                    onChange: function(value, isOnInitialize) {
//                        if(value !== ''){
//                           alert('Value has been changed: ' + value);
//                        }
                        //Clear Price to text box
                        clearDetailOfPrice(value);
                        //Update Price to text box
                        //refreshDetailOfPrice(value);
                        
                        let price_id = $('#nb_price2_type option').filter(':selected').val();
                        updateSelectionSpeceman2(false,price_id);
                    }
                });
                $('#nb_price2_type').prop('disabled', false);
//                if (isalert) {
//                    alert('Please select specimen.');
//                }
            }
        }
    });
}


//update drop down list of specimen2 of price
function updateSelectionSpeceman2(isalert,service_id) {
    let hospital_id = $('#phospital_select_for_price2 option').filter(':selected').val();

    //alert(hospital_id);
    $.ajax({
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: 'ajax_slide2__special/getSpecimenList2.php',
        data: {
            'hospital_id': hospital_id,
            'service_id': service_id
        },
        success: function (data) {
            if(data[0] != "["){
                alert(data);
            }
            var datajson = JSON.parse(data);
            if (datajson == "" || datajson == null) {
                $('#pspecimen_for_select2 option').remove();
                $('#pspecimen_for_select2_div label').remove();
                $('#pspecimen_for_select2_div select').remove();
                $('#pspecimen_for_select2_div div').remove();
                $('#pspecimen_for_select2_div').append('<label for="pspecimen_for_select2" class="" >เลือกรายการสไลด์ย้อมพิเศษ</label>');
                $('#pspecimen_for_select2_div').append('<select name="pspecimen_for_select2" id="pspecimen_for_select2" class=""  > </select>');
                $('#pspecimen_for_select2').append('<option >No Data for this hospital</option>');
                $('#pspecimen_for_select2').prop('disabled', true);
                $('#add_spcimen_list2').prop('disabled', true);
                $('#pspecimen_for_select2').selectize({
                    sortField: 'text'
                });
                if (isalert) {
                    
                    alert('No Data for this Hospital , Please select other hospital');
                }
            } else {
                $('#pspecimen_for_select2 option').remove();
                $('#pspecimen_for_select2_div label').remove();
                $('#pspecimen_for_select2_div select').remove();
                $('#pspecimen_for_select2_div div').remove();
                
                $('#pspecimen_for_select2_div').append('<label for="pspecimen_for_select2" class="" >เลือกรายการสไลด์ย้อมพิเศษ</label>');
                $('#pspecimen_for_select2_div').append('<select name="pspecimen_for_select2" id="pspecimen_for_select2" class=""  > </select>');
                //alert('Success' + datajson);
                
//                $('#pspecimen_for_select2 option').remove();
                $('#pspecimen_for_select2').append('<option value="' + '' + '" >กรุณาเลือก</option>');
                for (var i in datajson)
                {
  
                    $('#pspecimen_for_select2').append('<option value="' + datajson[i].id + '" price="' + datajson[i].price + '" specimen_num="' + datajson[i].speciment_num + '" comment="' + datajson[i].comment + '" specimen="' + datajson[i].specimen + '">' + datajson[i].specimen + '(' + datajson[i].speciment_num + ')</option>');
                    
                }
                $('#pspecimen_for_select2').prop('disabled', false);
                $('#pspecimen_for_select2').selectize({
                    sortField: 'text',
                    onChange: function(value, isOnInitialize) {
//                        if(value !== ''){
//                           alert('Value has been changed: ' + value);
//                        }
                        refreshDetailOfPrice(value);
                    }
                });

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
                '<td>' + datajson[i].slide_type + '</td>'+
                '<td>' + datajson[i].patient_id + '</td>'+
                '<td>' + datajson[i].number + '</td>'+//Surgical Number
                '<td>' + datajson[i].code_description + '</td>'+ //ex 33000
                '<td>' + datajson[i].description + '</td>'+
                '<td>' + datajson[i].sp_slide_block + '</td>'+
                '<td>' + datajson[i].cost + '</td>'+
                '<td>' + datajson[i].comment + '</td>'+
                '<td>' + datajson[i].create_date + '</td>'+
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
//    alert("start ajax");
    let printdbg = true;

    
    let pspecimen_for_select2 = $('#pspecimen_for_select2 option').filter(':selected').attr('value');
    
//    alert("pspecimen_for_select2::"+pspecimen_for_select2)
    if (pspecimen_for_select2 == null || pspecimen_for_select2 == "" ) {
        alert("ยังไม่ได้เลือกรายการ");
        return null;
    }
    
    let block_sp2 = $('#block_sp2').val();
    if (block_sp2 == null || block_sp2 == "" ) {
        alert("ยังไม่ได้เลือกบล็อก");
        return null;
    }
    
    
    let cur_patient_id = $(".cur_patient_id").attr('tabindex');
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
    
    let comment_note = document.getElementById("comment_note").value;
    let job_type = document.getElementById("job_type").value;

    if (printdbg) {
        console.log("==============");
        console.log("cur_patient_id::" + cur_patient_id);
        console.log("date_1000::" + date_1000);
        console.log("cur_phospital_num::" + cur_phospital_num);
        console.log("phospital_id::" + phospital_id);
        console.log("pclinician_text::" + pclinician_text);
        console.log("cur_pnum::" + cur_pnum);

        console.log("specimen_id::" + specimen_id);//Specimen code

        console.log("specimen_text::" + specimen_text);
        
        console.log("pspecimen_for_select2::" + pspecimen_for_select2);
        
        
        console.log("price_for_specimen::" + price_for_specimen);
        console.log("comment_for_specimen::" + comment_for_specimen);
        

        console.log("comment_note::" + comment_note);
        console.log("job_type::" + job_type);
        console.log("==============");

    }
    
    




    $.ajax({
        type: 'POST',
        'async': false,
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: 'ajax_slide2__special/createBillingSpecimen2.php',
        data: {
            'patient_id': cur_patient_id,
            'blox_name':block_sp2,
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
            'comment_note': comment_note,
            'job_type': job_type
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

//    alert('Finish Added.');
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
    clearDetailOfPrice();

    //update drop down list of specimen2
    updateSelectionSpeceman2Type(false);
    
    //update drop down list of specimen2
    updateSelectionSpeceman2(false,0);

//    updateSelectionSpeceman2(true,0);
});

//on select specimen change
$('#pspecimen_for_select2').on('change', function () {
    //alert( "Get from ID: "+this.value );
    alert("On Change.");
    //refreshDetailOfPrice(true);
    

});




$(document).ready(function () {
    if(sn_type == 'PN' ||sn_type == 'LN'){
        
    }else{
        //update drop down list of specimen2
        updateSelectionSpeceman2Type(false);
        //update drop down list of specimen2
        updateSelectionSpeceman2(false,0);
        //Update table of speciment in main page
        refreshSpecimenList2(false);
    }
    
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

