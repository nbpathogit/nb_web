
//Update table of job1 in main page
function refreshTblJob1(isAlert) {
    //alert("start ajax");
    var cur_patient_id = $(".cur_patient_id").attr('tabindex');
    //alert(cur_patient_id);
    $.ajax({
        url: "ajax_job1_crossection/getJob1CrossSection.php?cur_patient_id=%s",
        data: {cur_patient_id: cur_patient_id},
        success: function (data) {
            //alert(data);
            repaintTbljob1(data);
            if (isAlert) {
                alert("refresh done 1");
            }
        }
    });

}


function repaintTbljob1(data) {
    if (data[0] != "[") {
        alert(data);
        console.log(data);
    }
    var datajson = JSON.parse(data);

    // clear all data in table befor show new retrived record
    $('#table_body_job1 tbody tr').remove();
    $('#owner_job1 span').remove();
    // Show new retrived record
    for (var i in datajson)
    {
        
//                <tr>
//                    <td ><?= $joblist['id'] ?></td>
//                    <td ><?= $joblist['patient_number'] ?></td>
//                    <td ><?= $joblist['pre_name'] ?> <?= $joblist['name'] ?> <?= $joblist['lastname'] ?></td>
//                    <td ><?= $joblist['jobname'] ?></td>
//                    <td ><?= $joblist['pay'] ?></td>
//                    <td ><?= $joblist['comment'] ?></td>
//                    <td ><?= is_null($joblist['finish_date'])?"Not Specific":$joblist['finish_date'] ?></td>
//                    <td >
//                        <a  billid="<?= $joblist['id'] ?>" onclick="delbill1(<?= $joblist['id'] .','. $patient[0]['id'] ?>);" class="btn btn-outline-dark btn-sm delete"><i class="fa-solid fa-trash-can"></i> Delete</a>
//                    </td>
//                </tr>
        
        var finishdate = (datajson[i].finish_date == null)?'Not Specify':(datajson[i].finish_date);
        var str = '<tr>'+
                '<td>' + datajson[i].id + '</td>'+
                '<td>' + datajson[i].pre_name +' '+ datajson[i].name+' '+ datajson[i].lastname + '</td>'+ 
                '<td>' + datajson[i].patient_number + '</td>'+
                '<td>' + datajson[i].jobname + '</td>'+
                '<td>' + datajson[i].pay + '</td>'+
                '<td>' + datajson[i].comment + '</td>'+
                '<td>' + datajson[i].insert_time + '</td>'+
                '<td>'  + finishdate + '</td>'+
                '<td>' + '<a  jobid="'+datajson[i].id+'" onclick="deljob1('+datajson[i].id+','+datajson[i].patient_id+');" class="btn btn-outline-dark btn-sm delete"><i class="fa-solid fa-trash-can"></i> Delete</a>' + '</td>'+
                '</tr>';
        
        $('#table_body_job1 tbody').append(str);
        
        var str2 = '<span class="badge rounded-pill bg-primary" id="">'+ datajson[i].pre_name +' '+ datajson[i].name+' '+ datajson[i].lastname +'</span> ';
        $('#owner_job1').append(str2);
    }

}

//on click button delete for seleced specimen list bill in main page
function deljob1(jobid,patientid) {
    
    if( confirm("Please confirm delete job di = "+jobid+" ?")){
       
        $.ajax({
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: 'ajax_job1_crossection/delJob1.php',
        data: {
            'job_id': jobid,
            'patient_id': patientid,
            
        },
        success: function (data) {
           
            repaintTbljob1(data);
        
            alert('Success');
        },
        error: function (jqxhr, status, exception) {
            alert('Exception:', exception);
        }

    });
        
    }else{
       
    }

}


$("#refresh_job1").on("click", function () {
    refreshTblJob1(true);
});

//#add_modal_job1

//On button click add new specimen bill
$("#add_job_list1").on("click", function () {
    //alert("start ajax");
    var printdbg = true;

    var value = $('#p_cross_section_id_job1 option').filter(':selected').attr('value');
    var job_role_id = $('#p_cross_section_id_job1 option').filter(':selected').attr('job_role_id');
    var patient_id = $('#p_cross_section_id_job1 option').filter(':selected').attr('patient_id');
    var patient_number = $('#p_cross_section_id_job1 option').filter(':selected').attr('patient_number');
    var user_id = $('#p_cross_section_id_job1 option').filter(':selected').attr('user_id');
    var pre_name = $('#p_cross_section_id_job1 option').filter(':selected').attr('pre_name');
    var name = $('#p_cross_section_id_job1 option').filter(':selected').attr('name');
    var lastname = $('#p_cross_section_id_job1 option').filter(':selected').attr('lastname');
    var jobname = $('#p_cross_section_id_job1 option').filter(':selected').attr('jobname');
    var pay = $('#p_cross_section_id_job1 option').filter(':selected').attr('pay');
    var cost_count_per_day = $('#p_cross_section_id_job1 option').filter(':selected').attr('cost_count_per_day');
    var comment = $('#p_cross_section_id_job1 option').filter(':selected').attr('comment');
    

    if (value == "0" || value == 0) {
        alert("No data select");
        return null;
    }

    $.ajax({
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: 'ajax_job1_crossection/createJobList1.php',
        data: {
            'job_role_id': job_role_id,
            'patient_id': patient_id,
            'patient_number': patient_number,
            'user_id': user_id,
            'pre_name': pre_name,
            'name': name,
            'lastname': lastname,
            'jobname': jobname,
            'pay': pay,
            'cost_count_per_day': cost_count_per_day,
            'comment': comment,

        },
        success: function (data) {
            console.log(data);
            repaintTbljob1(data);
        },
        error: function (jqxhr, status, exception) {
            alert('Exception:', exception);
        }
    });

    alert('Finish Added.');
});



$(document).ready(function () {

    //updateJob1(false);
    refreshTblJob1(false);

});