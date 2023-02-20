
//Update table of hire1 in main page
function refreshTblHire1(isAlert) {
    //alert("start ajax");
    var patient_id = $(".cur_patient_id").attr('tabindex');
    //alert(cur_patient_id);
    $.ajax({
        type: 'POST',
        url: "/ajax_hire1_fluidlab/getHire1.php",
        data: {patient_id: patient_id},
        success: function (data) {
            //alert(data);
            repaintTblhire1(data);
            if (isAlert) {
                alert("refresh done");
            }
        }
    });

}


function repaintTblhire1(data) {
    if (data[0] != "[") {
        alert(data);
        console.log(data);
    }
    var datajson = JSON.parse(data);

    // clear all data in table befor show new retrived record
    $('#table_body_hire1 tbody tr').remove();
    // Show new retrived record
    for (var i in datajson)
    {
        /*
                    <td ><?= $hires['id'] ?></td>
                    <td ><?= $hires['name'] ?></td>
                    <td > แลปเซลล์วิทยา </td>
                    <td ><?= $hires['patient_id'] ?></td>
                    <td ><?= $hires['patient_number'] ?></td>
                    <td ><?= $hires['cost'] ?></td>
                    <td ><?= $hires['accept_time'] ?></td>
                    <td ><?= $hires['finish_time'] ?></td>
        */
        var finishdate = (datajson[i].finish_date == null)?'Not Specify':(datajson[i].finish_date);
        var str = '<tr>'+
                '<td>' + datajson[i].id + '</td>'+
                '<td>' + datajson[i].name + '</td>'+ 
                '<td>' + 'แลปเซลล์วิทยา' + '</td>'+
                '<td>' + datajson[i].patient_id + '</td>'+
                '<td>' + datajson[i].patient_number + '</td>'+
                '<td>' + datajson[i].cost + '</td>'+
                '<td>' + datajson[i].accept_time + '</td>'+
                '<td>' + datajson[i].finish_time + '</td>'+
                '<td>' + '<a  jobid="'+datajson[i].id+'" onclick="delhire1('+datajson[i].id+','+datajson[i].patient_id+');" class="btn btn-outline-dark btn-sm delete"><i class="fa-solid fa-trash-can"></i> Delete</a>' + '</td>'+
                '</tr>';
        
        $('#table_body_hire1 tbody').append(str);
    }

}

//on click button delete for seleced specimen list bill in main page
function delhire1(jobid,patientid) {
    
    if( confirm("Please confirm delete job di = "+jobid+" ?")){
       
        $.ajax({
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: '/ajax_hire1_fluidlab/delHire1.php',
        data: {
            'job_id': jobid,
            'patient_id': patientid,
            
        },
        success: function (data) {
           
            repaintTblhire1(data);
        
            alert('Success');
        },
        error: function (jqxhr, status, exception) {
            alert('Exception:', exception);
        }

    });
        
    }else{
       
    }

}


$("#refresh_hire1").on("click", function () {
    refreshTblHire1(true);
});

//#add_modal_hire1

//On button click add new 
$("#add_list_hire1").on("click", function () {
    //alert("start ajax");
    var printdbg = true;

    var value = $('#select_hire1 option').filter(':selected').attr('value');
    var name = $('#select_hire1 option').filter(':selected').attr('name');
    var cost = $('#select_hire1 option').filter(':selected').attr('cost');
    var job_name = $('#select_hire1 option').filter(':selected').attr('job_name');
    var patient_id = $('#select_hire1 option').filter(':selected').attr('patient_id');
    var patient_number = $('#select_hire1 option').filter(':selected').attr('patient_number');
    var accept_time = $('#select_hire1 option').filter(':selected').attr('accept_time');
    var comment = $('#select_hire1 option').filter(':selected').attr('comment');
    

    if (value == "0" || value == 0) {
        alert("No data select");
        return null;
    }

    $.ajax({
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: '/ajax_hire1_fluidlab/createHireList1.php',
        data: {
            'outside_id': value,
            'name': name,
            'cost': cost,
            'job_name': job_name,
            'patient_id': patient_id,
            'patient_number': patient_number,
            'accept_time': accept_time,
            'comment': comment,
        },
        success: function (data) {
            console.log(data);
            repaintTblhire1(data);
        },
        error: function (jqxhr, status, exception) {
            alert('Exception:', exception);
        }
    });

    alert('Finish Added.');
});



$(document).ready(function () {

    //updateHire1(false);
    

});