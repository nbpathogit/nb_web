var selected_presultupdate_id = 0;
// // add template to textarea result
// input result_id and reporttype_id and user_id
function add_tp_2_txt_rs(rs_id, rpt_id, user_id) {

    selected_presultupdate_id = rs_id;

    let txt_rs_name = "#txt_rs_" + selected_presultupdate_id;
    var isDisabled = $(txt_rs_name).prop('readonly');
    if (isDisabled) {
        alert("Please click edit first");
        return;
    }

    $("#add_txt_rs").prop("disable", true);

//    alert(rs_id + " " + rpt_id + " " + user_id);
    $("#modal_txt_tps").val("");

    let edit_result_id = "#edit_result_" + rs_id;
    let save_result_id = "#save_result_" + rs_id;

    let datajson = null;
    $.ajax({
        'async': false,
        type: 'POST',
        'global': false,
        url: 'ajax_template_rs/getTemplateNameListByUserByType.php',
        data: {
            'user_id': user_id,
            'type_id': rpt_id,

        },
        success: function (data) {
            console.log(data);
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
            alert('Exception:', exception);
        }
    });




    $('#modal_tprs_select').prop('disabled', false);
    $('#modal_tprs_select option').remove();
    $('#modal_tprs_select').append('<option value="0" >กรุณาเลือก</option>');
    for (var i in datajson)
    {
        console.log(datajson[i]);
        $('#modal_tprs_select').append('<option value="' + datajson[i].tid + '">' + datajson[i].tname + '</option>');
    }




    $("#add_txt_rs").prop("disabled", true);
//        $('#add_modal_template_rs').modal('toggle');
    $('#add_modal_template_rs').modal('show');
//        $('#add_modal_template_rs').modal('hide');



}


//on select hospital change 
$('#modal_tprs_select').on('change', function () {
    //update drop down list of specimen

    var template_id = $('#modal_tprs_select option').filter(':selected').val();




    let datajson = null;
    $.ajax({
        'async': false,
        type: 'POST',
        'global': false,
        url: 'ajax_template_rs/getTemplateByID.php',
        data: {
            'id': template_id,
    
        },
        success: function (data) {
            console.log(data);
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
            alert('Exception:', exception);
        }
    });




//    alert(template_id);
    $("#modal_txt_tps").val(datajson[0].tdescription);
//    document.getElementById("modal_txt_tps").value = template_id;



    if (template_id == 0) {
        $("#add_txt_rs").prop("disabled", true);
    } else {
        $("#add_txt_rs").prop("disabled", false);
    }


});

function add_selected_tp_2_txt_rs() {
    let txt_rs_name = "#txt_rs_" + selected_presultupdate_id;
    let txt_rs_id = $("#modal_txt_tps").val();
    $(txt_rs_name).val(txt_rs_id);
    //alert("add " + txt_rs_id + " to " + txt_rs_name);
}