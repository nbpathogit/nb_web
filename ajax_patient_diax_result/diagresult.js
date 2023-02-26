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
     $.ajax({
        type: 'POST',
        url: "/ajax_patient_diax_result/save_result_message.php",
        data: {
            'rs_id': rs_id,
            'result_message': result_message,
        },
        success: function (data) {
            if (data != 1) {
                alert("Save Error:: " + data);
                return;
            } else {
                //alert("Save Success:: " + data);
            }
            

        }
    });
    
    
    //Read back to data base
    $.ajax({
        type: 'POST',
        url: "/ajax_patient_diax_result/get_result_message.php",
        data: {
            'rs_id': rs_id,
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
    alert("finish.");
    
}

function edit_txt_rs(rs_id){
    
    

    var edit_result_id = "#edit_result_"+rs_id;
    var save_result_id = "#save_result_"+rs_id;
    $(edit_result_id).hide();
    $(save_result_id).show();
    
    var txt_rs_id = "#txt_rs_"+rs_id;
    $(txt_rs_id).prop('readonly', false);

    
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
 
    if (value == '0') {
        alert("Please select the choices.");
        return;
    }
//    $resultreport->patient_id = $_POST['cur_patient_id'];
//                $resultreport->result_type = $_POST['result_type'];
//                $resultreport->result_type_id = $_POST['result_type_id'];

    $.ajax({
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: '/ajax_patient_diax_result/new_result.php',
        data: {
            'cur_patient_id': patient_id, //=========================================================================
            'group_type': group_type,     //1 for mandatory require 2 for added later	
            'result_type_id': type_id,    //ID link to DB Table report_type
            'result_type': value,         //EX Pathological Diagnosis

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
    
});





$(document).ready(function () {

    //updateJob6(false);
//    refreshTblJob6(false);
    

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
        
        var str =''+
        '<div class="mb-3">'+
            '<label for="result_message"><b>'+datajson[i].result_type+'</b></label> '+messageRelease+
            '<a class="btn btn-outline-primary btn-sm me-1 " id="edit_result_'+datajson[i].id+'" onclick="edit_txt_rs('+datajson[i].id+');" title="Edit"  ><i class="fa-solid fa-marker"></i>Edit</a>'+
            '<a class="btn btn-outline-primary btn-sm me-1 " id="save_result_'+datajson[i].id+'" onclick="save_txt_rs('+datajson[i].id+');" title="Save" style="display: none;" ><i class="fa-solid fa-floppy-disk"></i>Save</a>'+
            '<textarea name="txt_rs_'+datajson[i].id+'" cols="100" rows="5" class="form-control" id="txt_rs_'+datajson[i].id+'" readonly >'+datajson[i].result_message+'</textarea>'+
        '</div>';
        
        
        
        $('#result_list_display').append(str);
      
        
//        $('#table_body_job6 tbody').append(str);
        
//        var str2 = '<span class="badge rounded-pill bg-primary" id="">'+ datajson[i].pre_name +' '+ datajson[i].name+' '+ datajson[i].lastname +'</span> ';
//        $('#owner_job6').append(str2);
//        $('#owner_job6a').append(str2);
//        $('#owner_job6b').append(str2);
        alert("done");
    }

}
