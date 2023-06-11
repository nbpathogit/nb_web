
//Update table of job6 in main page
//refreshTblJob6(true,patient_id,result_id);
function refreshTblJob6(isAlert,patient_id,result_id) {
    //alert("start ajax");
  
    //alert(cur_patient_id);
    $.ajax({
        type: 'POST',
        url: "ajax_job6_patho/getJob6.php",
        data: {
         
            patient_id: patient_id,
            result_id: result_id,
        
        },
        success: function (data) {
//            alert(data);
            repaintTbljob6(data,result_id);
            if (isAlert) {
                alert("refresh done 6");
            }
        }
    });

}


function updateSecondPathoEditPage(isAlert,patient_id) {
    $('.uresultinxlist2 li').each(function (index) {
        var result_id2 = $(this).attr('tabindex');
        //alert("result_id2 = "+result_id2);
        
        var isDataAval = true;
        $.ajax({
            type: 'POST',
            url: "ajax_job6_patho/getJob6.php",
            data: {

                patient_id: patient_id,
                result_id: result_id2,

            },
            success: function (data) {
                console.log(data);
                //alert(result_id2+"data \n"+data);
              
                if (data[0] == "[" && data[1] == "]" ) {
                    isDataAval = false;
                    //alert(result_id2+"data is equal t [] \n"+data);
                    //console.log(data);
                }
                
                if (data[0] != "[") {
                    alert(" Please capture this message to admin web. result_id2="+result_id2+"data[0] != '[' \n"+data);
                    console.log(data);
                }
                var datajson = JSON.parse(data);
                var owner_job6_id = ".owner_job6_"+result_id2;
                //alert("owner_job6_id = "+owner_job6_id);
                $(owner_job6_id+' span').remove();
                if(isDataAval){
                    for (var i in datajson){
                        var str2 = '<span class="badge rounded-pill bg-primary" id="">'+ datajson[i].pre_name +' '+ datajson[i].name+' '+ datajson[i].lastname +'</span> ';
                        //alert("str2="+str2);
                        $(owner_job6_id).append(str2);
                    }
                }else{
                    //Nodata
                    var str2 = '<span class="badge rounded-pill bg-secondary" id="">'+ 'NA' +'</span> ';
                    //alert("str2="+str2);
                    $(owner_job6_id).append(str2);
                }
            }
        });
    });
}


function repaintTbljob6(data,result_id) {
    if (data[0] != "[" || data == "[]") {
        alert(data);
        console.log(data);
    }
    var datajson = JSON.parse(data);
    var owner_job6_id = ".owner_job6_"+result_id;
    //alert(owner_job6_id);
    // clear all data in table befor show new retrived record
    $('#table_body_job6 tbody tr').remove();
//    $('#owner_job6 span').remove();
//    $('#owner_job6a span').remove();
//    $('#owner_job6b span').remove();
    $(owner_job6_id+' span').remove();
//    $('.owner_job6b span').remove();
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
//                        <a  billid="<?= $joblist['id'] ?>" onclick="delbill6(<?= $joblist['id'] .','. $patient[0]['id'] ?>);" class="btn btn-outline-dark btn-sm delete"><i class="fa-solid fa-trash-can"></i> Delete</a>
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
                '<td>' + '<a  jobid="'+datajson[i].id+'" onclick="deljob6('+datajson[i].id+','+datajson[i].patient_id+','+result_id +');" class="btn btn-outline-dark btn-sm delete"><i class="fa-solid fa-trash-can"></i> Delete</a>' + '</td>'+
                '</tr>';
        
        $('#table_body_job6 tbody').append(str);
        
        var str2 = '<span class="badge rounded-pill bg-primary" id="">'+ datajson[i].pre_name +' '+ datajson[i].name+' '+ datajson[i].lastname +'</span> ';
//        $('#owner_job6').append(str2);
//        $('#owner_job6a').append(str2);
//        $('#owner_job6b').append(str2);
//        alert("str2="+str2);
        
        $(owner_job6_id).append(str2);
//        $('.owner_job6b').append(str2);

        // Write second patho user_id to DOM
        var str = '<li tabindex="'+datajson[i].id+'">job6s::job6["id"]::'+datajson[i].id+'</li>';
        $(".job6_id2").append(str);
    }

}

//on click button delete for seleced specimen list bill in main page
function deljob6(jobid,patient_id,result_id) {
    
    if( confirm("Please confirm delete job id = "+jobid+" ?")){
        var cur_patient_id = $(".cur_patient_id").attr('tabindex');
        //Uresult index id lastest
       
        $.ajax({
            type: 'POST',
            // make sure you respect the same origin policy with this url:
            // http://en.wikipedia.org/wiki/Same_origin_policy
            url: 'ajax_job6_patho/delJob6.php',
            data: {
                'job_id': jobid,
                'patient_id': patient_id,
                'result_id': result_id,

            },
            success: function (data) {

                repaintTbljob6(data,result_id);
    //            $("#btn2review13000").hide();
                $("#btn2review13000").prop('disabled', true);
    //            .prop('disabled', true);
                $("#add_job6").show();

                alert('Success');
            },
            error: function (jqxhr, status, exception) {
                alert('Exception:', exception);
            }

        });
        
    }else{
       
    }

}



//refresh_job6(<?= $patients[0]['id']?>,<?= $presultupdate['id']?>)
function refresh_job6(patient_id,result_id){
    refreshTblJob6(true,patient_id,result_id);
}

//});

//#add_modal_job6

//On button click add new specimen bill
//$("#add_job_list6").on("click", function () {
    
function add_job_list6(result_id){
    //alert("start ajax");
    var printdbg = true;
    
    var value = $('#select_job6 option').filter(':selected').attr('value');
    var job_role_id = $('#select_job6 option').filter(':selected').attr('job_role_id');
    var patient_id = $('#select_job6 option').filter(':selected').attr('patient_id');
    var patient_number = $('#select_job6 option').filter(':selected').attr('patient_number');
    var user_id = $('#select_job6 option').filter(':selected').attr('user_id');
    var pre_name = $('#select_job6 option').filter(':selected').attr('pre_name');
    var name = $('#select_job6 option').filter(':selected').attr('name');
    var lastname = $('#select_job6 option').filter(':selected').attr('lastname');
    var jobname = $('#select_job6 option').filter(':selected').attr('jobname');
    var pay = $('#select_job6 option').filter(':selected').attr('pay');
    var cost_count_per_day = $('#select_job6 option').filter(':selected').attr('cost_count_per_day');
    var comment = $('#select_job6 option').filter(':selected').attr('comment');
    

    if (value == "0" || value == 0) {
        alert("No data select");
        return null;
    }
    
    // Write second patho user_id to DOM
    var str = '<li tabindex="'+user_id+'">uresultSecondPatho2::prsu["pathologist2_id"]::'+user_id+'</li>';
    $(".uresultSecondPatho2").append(str);
    


    $.ajax({
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: 'ajax_job6_patho/createJobList6.php',
        data: {
            'job_role_id': job_role_id,
            'patient_id': patient_id,
            'result_id': result_id,
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
            //alert(data);
            repaintTbljob6(data,result_id);



            $("#btn2review13000").prop('disabled', false);//enable
            $("#add_job6").hide();
        },
        error: function (jqxhr, status, exception) {
            alert('Exception:', exception);
        }
    });

    alert('Finish Added.');
}
//});



$(document).ready(function () {

    //updateJob6(false);
    //refreshTblJob6(false);
    var patient_id = get_cur_patient_id();
    var result_id = get_lastest_uresultid();
//    alert("patient_id"+patient_id);
//    alert("result_id"+result_id);
    
    updateSecondPathoEditPage(false,patient_id);// loop update second patho for all in list.
    refreshTblJob6(false,patient_id,result_id);// update on last one

});