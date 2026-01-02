var is_diag_editeing_mode = false;


//Save text result by id
function save_txt_rs(rs_id){

    var edit_result_id = "#edit_result_"+rs_id;
    var save_result_id = "#save_result_"+rs_id;
    $(edit_result_id).show();
    $(save_result_id).hide();
    
    var txt_rs_id = "#txt_rs_"+rs_id;
    var result_message = $(txt_rs_id).val();
    //alert($(txt_rs_id).val());
    
    //Save to data base
//     $.ajax({
//        type: 'POST',
//        url: "/ajax_patient_diax_result/save_result_message.php",
//        data: {
//            'rs_id': rs_id,
//            'result_message': result_message,
//        },
//        success: function (data) {
//            if (data != 1) {
//                alert("Save Error:: " + data);
//                return;
//            } else {
//                //alert("Save Success:: " + data);
//            }
//            
//
//        }
//    });
    
    
    //Read back to data base
    $.ajax({
        type: 'POST',
        url: "ajax_patient_diax_result/save_get_result_message.php",
        data: {
            'rs_id': rs_id,
            'result_message': result_message,
            'patient_id': patient_id,
            'usergroup': usergroup,
        },
        success: function (data) {
            //alert(data);
            if (data[0] != "[") {
               alert("Read back Error:: " + data);
               console.log("Read back Error:: " +data);
               return;
            }else{
                //Result ok , do not alert.
                //alert("read back ok:: " + data);
                
            }
            var datajson = JSON.parse(data);
            for (var i in datajson)
            {
                $(txt_rs_id).val(datajson[i].result_message);
            }
        }
    });
    
    
    $(txt_rs_id).prop('readonly', true);
    //alert("finish.");
    is_diag_editeing_mode = false;
    
}

function edit_txt_rs(rs_id){
    
    

    var edit_result_id = "#edit_result_"+rs_id;
    var save_result_id = "#save_result_"+rs_id;
    $(edit_result_id).hide();
    $(save_result_id).show();
    
    var txt_rs_id = "#txt_rs_"+rs_id;
    $(txt_rs_id).prop('readonly', false);

    is_diag_editeing_mode = true;
}

function delete_txt_rs(rs_id){

    if (confirm("Delete this report block?")) {
        txt = "You pressed OK!";
            //Read back to data base
        $.ajax({
            type: 'POST',
            url: "ajax_patient_diax_result/delete_result.php",
            data: {
                'rs_id': rs_id,
            },
            success: function (data) {
                alert("Delete done.");
                //alert(data);
//                if (data[0] != "true") {
//                   alert("Read back Error:: " + data);
//                   console.log("Read back Error:: " +data);
//                   return;
//                }else{
//                    //Result ok , do not alert.
//                    //alert("read back ok:: " + data);
//
//                }
//                var datajson = JSON.parse(data);
//                for (var i in datajson)
//                {
//                    $(txt_rs_id).val(datajson[i].result_message);
//                }
            }
        });
        
        
        // Prepare the form and then submit on the fly.
        var frm = $("<form>");
        frm.attr('method', 'post');
        frm.attr('');
        frm.append('<input type="hidden" name="refreshpage" value="0" /> ');
        frm.append('<input type="hidden" name="pautoscroll" value="end_section" /> ');
        
        frm.appendTo("body");
        frm.submit();
        
        
    } else {
        txt = "You pressed Cancel!";
    }

}


$("#add_u_result").on("click", function () {
    //alert("click add_u_result");
    var value = $('#result_type option').filter(':selected').attr('value');
//    alert("value:: "+value);
    
    var type_id = $('#result_type option').filter(':selected').attr('type_id');
//    alert("type_id:: "+type_id);
    
    var group_type = $('#result_type option').filter(':selected').attr('group_type');
//    alert("group_type:: "+group_type);
    
    var patient_id = $('#result_type option').filter(':selected').attr('patient_id');
//    alert("patient_id:: "+patient_id);
 
    var release_type = $('#result_type option').filter(':selected').attr('release_type');
    

    


    
    if (value == '0') {
        alert("Please select the choices.");
        return;
    }
//    $resultreport->patient_id = $_POST['cur_patient_id'];
//                $resultreport->result_type = $_POST['result_type'];
//                $resultreport->result_type_id = $_POST['result_type_id'];
    // Write data2DOM
    // as the lastest result is here
    //<?php //List of release_type ยังไม่ออกผล/ออกผลแล้ว/ออกผลเบื้องต้น ?>
//    var str = ''+
//    '<ul class="uresultReleaseType2" style="display: none;">'+
//    '        <li tabindex="'+release_type+'">uresult::prsu["release_type"]::'+release_type+'</li>'+
//    '</ul>';
//    $("#data2DOM").append(str);
    
    var str = '<li tabindex="'+release_type+'">uresultReleaseType::prsu["release_type"]::'+release_type+'</li>';
    $(".uresultReleaseType2").append(str);
    

    //btn_release
    $("#btn_release").prop('disabled', false);


    $.ajax({
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: 'ajax_patient_diax_result/new_result.php',
        data: {
            'cur_patient_id': patient_id, //=========================================================================
            'group_type': group_type,     //1 for mandatory require 2 for added later	
            'result_type_id': type_id,    //ID link to DB Table report_type
            'result_type': value,         //EX Pathological Diagnosis
            'release_type': release_type,         //EX Pathological Diagnosis

        },
        success: function (data) {

//            alert('Success');
            console.log(data);
            append2page(data);
            
            
            
        },
        error: function (jqxhr, status, exception) {
            alert('jqxhr:', jqxhr);
            alert('status:', status);
            alert('Exception:', exception);
        }

    });
    
    $('#add_new_report_section_btn').prop("disabled", true);
    
});


//critical_report     
$("#critical_report").on("click", function (e) {
    //e.preventDefault();

    if ($("#critical_report").prop("checked"))
    {
        
        // set critical flag true here
        //$patient[0]['iscritical'] = true;
        set_criticalreport('1');
        alert('checked');
    } else
    {
        
        // set critical flag false here
        //$patient[0]['iscritical'] = false;
        set_criticalreport('0');
        alert('Un checked');
    }

});

//Save text result by id
function set_criticalreport(flag){
    
    var cur_patient_id = $(".cur_patient_id").attr('tabindex');
    //Read back to data base
    $.ajax({
        type: 'POST',
        url: "ajax_patient_diax_result/set_is_critical.php",
        data: {
            'patient_id': cur_patient_id,
            'critical_report': flag,

        },
        success: function (data) {
            //alert(data);
            if (data[0] != "1") {
                alert(data);
            }else{
                //Result ok , do not alert.
                //alert("read back ok:: " + data);
            }
        }
    });
    //alert("finish.");
    
}


$(document).ready(function () {

        //updateJob6(false);
    //    refreshTblJob6(false);
    $('#top_free_text').append($('#item_name_a').html());


    //alert($('#item_name_a').text());

    

});



function append2page(data) {
    if (data[0] != "[") {
        alert(data);
        console.log(data);
    }
    var datajson = JSON.parse(data);

    // clear all data in table befor show new retrived record
   

    for (var i in datajson)
    {
//        console.log(datajson[i].id);
//        console.log(datajson[i].group_type);     // 1 for mandatory require 2 for added later
//        console.log(datajson[i].patient_id);
//        console.log(datajson[i].result_type);    // EX Provisional Diagnosis, Pathological Diagnosis
//        console.log(datajson[i].result_type_id); //  ID Link to DB   table "report_type"
//        console.log(datajson[i].result_message);
//        console.log(datajson[i].pathologist_id);
//        console.log(datajson[i].pathologist2_id);
//        console.log(datajson[i].release_time);
        
        /*
         * 
:96
:278 2
:279 153
:280 Pathological Diagnosis
:281 12
:282 
:283 0
:284 0
:285 null
         * 
         */
        
/*

        <span id="result_list_display">
        <label for="result_message"><b><?= $presultupdate['result_type'] ?></b></label>   <?= $isCurResultReleased ?  "ออกผลแล้วเมื่อ[".$presultupdate['release_time']."] ไม่สามารถแก้ไขได้" : "ยังไม่ออกผล"  ?>
        <a class="btn btn-outline-primary btn-sm me-1 " id="edit_result_<?= $presultupdate['id']?>" onclick="edit_txt_rs(<?= $presultupdate['id']?>);" title="Edit" <?= ($is_show_edit_btn)? '':'style="display: none;"'; ?> ><i class="fa-solid fa-marker"></i>Edit</a>
        <a class="btn btn-outline-primary btn-sm me-1 " id="save_result_<?= $presultupdate['id']?>" onclick="save_txt_rs(<?= $presultupdate['id']?>);" title="Save"<?= ($is_show_save_btn)? '':'style="display: none;"'; ?> ><i class="fa-solid fa-floppy-disk"></i>Save</a>
        <a class="btn btn-outline-primary btn-sm me-1 " id="btn_template_<?= $presultupdate['id']?>" onclick="alert('Under construction. \nThe feature will avalable soon.');" title="Template" <?= ($is_show_template_btn)? '':'style="display: none;"'; ?> ><i class="fa-solid fa-marker"></i>Template</a>
        
        <textarea name="txt_rs_<?= $presultupdate['id']?>" cols="100" rows="5" class="form-control" id="txt_rs_<?= $presultupdate['id']?>" readonly ><?= $presultupdate['result_message'] ?> </textarea>
        </span>


 */
        var messageRelease = "";
        if(datajson[i].release_time==null){
            messageRelease = "ยังไม่ออกผล";
        }else{
            messageRelease = "ออกผลแล้วเมื่อ["+datajson[i].release_time+"] ไม่สามารถแก้ไขได้";
        }
        var cur_patient_id = get_cur_patient_id();
        var cur_user_id = get_cur_user_id();
        
        var str =''+
        '<hr style="height:1px;border-width:0;color:black;background-color:black;">'+
        '<div class="row">'+
            '<div class="col-6">'+
                '<label for="result_message"><b>'+datajson[i].result_type+'</b></label> '+messageRelease+
                '<a class="btn btn-outline-primary btn-sm me-1 " id="edit_result_'+datajson[i].id+'" onclick="edit_txt_rs('+datajson[i].id+');" title="Edit"  ><i class="fa-solid fa-marker"></i>Edit</a>'+
                '<a class="btn btn-outline-primary btn-sm me-1 " id="save_result_'+datajson[i].id+'" onclick="save_txt_rs('+datajson[i].id+');" title="Save" style="display: none;" ><i class="fa-solid fa-floppy-disk"></i>Save</a>'+
                '<a class="btn btn-outline-primary btn-sm me-1 " id="btn_template_'+datajson[i].id+'" onclick="add_tp_2_txt_rs('+datajson[i].id+','+datajson[i].result_type_id+','+cur_user_id+');" title="Template"><i class="fa-solid fa-marker"></i>Template</a>'+
                '<a class="btn btn-outline-primary btn-sm me-1 " id="delete_result_'+datajson[i].id+'" onclick="delete_txt_rs('+datajson[i].id+');" title="Delete" ><i class="fa-solid fa-marker"></i>Delete</a>'+
            '</div>'+
            '<div class="col-6">'+
                '<b>พยาธิแพทย์คอนเฟิร์มผล:</b><a href="patient_edit.php?id='+cur_patient_id+'&pautoscroll=confirm_result_section_bottom" class="btn btn-outline-primary btn-sm me-1 " ><i class="fa-solid "></i>Reload Page</a>'+
            '</div>'+

            '<textarea name="txt_rs_'+datajson[i].id+'" cols="100" rows="5" class="form-control" id="txt_rs_'+datajson[i].id+'" readonly >'+datajson[i].result_message+'</textarea>'+

        '</div>';
        
        
        $('#result_list_display').append(str);
      
        
        
        
          var str = '<li tabindex="'+datajson[i].id+'">uresultinxlist2::prsu["id"]::'+datajson[i].id+'</li>';
          $(".uresultinxlist2").append(str);


        // Write second patho user_id to DOM
        var str = '<li tabindex="'+datajson[i].pathologist2_id+'">uresultSecondPatho2::prsu["pathologist2_id"]::'+datajson[i].pathologist2_id+'</li>';
        $(".uresultSecondPatho2").append(str);
    }

}
