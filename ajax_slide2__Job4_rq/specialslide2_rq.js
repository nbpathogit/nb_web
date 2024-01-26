
$(document).ready(function () {

    refreshSpSlideRequested(false);

});

function refreshSpSlideRequested(isAlert) {
    //alert("start ajax");
    var cur_patient_id = $(".cur_patient_id").attr('tabindex');
    //alert(cur_patient_id);
    console.log("cur_patient_id = " + cur_patient_id);


    let req_id_list;
    $.ajax({
        type: 'POST',
        'async': false,
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: "ajax_slide2__Job4_rq/getReqIdFromPatientId.php",
        data: {
            'patient_id': cur_patient_id,
        },
        success: function (data) {
            req_id_list = data;


            if (data[0] != "[") {
                alert(data);
            }
            //return null;

//            repaintspecimentable2(data);
        },
        error: function (jqxhr, status, exception) {
            alert('Exception:', exception);
        }
    });
    console.log(req_id_list);
    //alert(req_id_list);
    
    $('#sp_slide_requested div').remove();
    
    
//===============================================
//HTML draft for JS script
//===============================================
//<div class="bg-nb  bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
//    <p align="center"><b>รายการที่ xx ส่งย้อมพิเศษแล้วเมื่อ xx</b></p>
//    <p align="left">
//        <b>พนักงานเตรียมสไลด์พิเศษ:</b>
//        <span id="owner_job4_rq" style="font-size:20px">
//            <span class="badge rounded-pill bg-primary" id="">Aaaaaa</span>
//            <span class="badge rounded-pill bg-primary" id="">Bbbbbb</span>
//            <span class="badge rounded-pill bg-primary" id="">Cccccc</span>
//        </span>
//    </p>
//    <p align="left">
//        <b>order ย้อมพิเศษ:</b>
//        <span id="spcimen_list2_rq" style="font-size:20px">
//            <span class="badge rounded-pill bg-primary" id="">Aaaaaa</span>
//            <span class="badge rounded-pill bg-primary" id="">Bbbbbb</span>
//            <span class="badge rounded-pill bg-primary" id="">Cccccc</span>
//        </span>  
//    </p>
//</div>
    var datajson = JSON.parse(req_id_list);
    let str1 = "";
    let str2 = "";
    for (var i in datajson)
    {
        str1="";
        str2="";
        str1 = str1 + '';
        str1 = str1 + '<div class="bg-nb  bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">';

        str1 = str1 + '<p align="center"><b>';
        str1 = str1 + "รายการที่ร้องขอแล้ว (เลขที่" + datajson[i].id + ") สั่งย้อมเมื่อ [" + datajson[i].req_date + "]";
        str1 = str1 + ' [ปุ่มลบกำลังพัฒนา หากต้องการลบ เบื้องต้ให้ติดต่อโปรแกรมเมอร์ลบให้เป็นการชั่วคราว]</b></p>';
        


//        str1 = str1 + '<p align="left"><b>พนักงานเตรียมสไลด์พิเศษ:</b><span id="" style="font-size:20px">';
//        $.ajax({
//            type: 'POST',
//            'async': false,
//            url: "ajax_slide2__Job4_rq/getJobFromReqId.php",
//            data: {
//                'req_id': datajson[i].id,
//            },
//            success: function (data) {
//                if (data[0] != "[") {
//                    alert(data);
//                }
//                var datajson2 = JSON.parse(data);
//                for (var j in datajson2)
//                {
//                    str1 = str1 + '<span class="badge rounded-pill bg-primary" id="">' + datajson2[j].name + ' ' + datajson2[j].lastname + '</span>';
//                }
//            },
//            error: function (jqxhr, status, exception) {
//                alert('Exception:', exception);
//            }
//        });
        str1 = str1 + '</span></p>';

        str1 = str1 + '<p align="left"><b>order ย้อมพิเศษ:</b><span id="" style="font-size:20px">';
        
        
        
        str1 += '    <table class="table table-bordered border-dark" id="">';
        str1 += '        <thead>';
        str1 += '            <tr>';
        str1 += '                <th >Id</th>';
        str1 += '                <th >Id#2</th>';
        str1 += '                <th >Patient Number</th>';
        str1 += '                <th >Code Name</th>';
        str1 += '                <th >Description</th>';
        str1 += '                <th >block</th>';
        str1 += '                <th >Price</th>';
        str1 += '                <th >Remark/comment</th>';
        str1 += '                <th >Manage</th>';
        str1 += '            </tr>';
        str1 += '        </thead>';
        str1 += '        <tbody id="xx">';
        
        $.ajax({
            type: 'POST',
            'async': false,
            url: "ajax_slide2__Job4_rq/getBillFromReqId.php",
            data: {
                'req_id': datajson[i].id,
            },
            success: function (data) {
                var datajson3 = JSON.parse(data);
                for (var j in datajson3)
                {
                    if (data[0] != "[") {
                        alert(data);
                    }
                    //str1 = str1 + '<span class="badge rounded-pill bg-primary" id="">' + "("+datajson3[j].code_description + ")(" + datajson3[j].description  + ")(" + datajson3[j].sp_slide_block  + ")(" + datajson3[j].cost  + ')</span>';
                
                
                
                    str1 += ' <tr>                                              ';
                    str1 += '     <td >'+datajson3[j].id + '</td>               ';
                    str1 += '     <td >'+datajson3[j].slide_type + '</td>               ';
                    str1 += '     <td >'+datajson3[j].number+'</td>             ';
                    str1 += '     <td >'+datajson3[j].code_description+'</td>   ';
                    str1 += '     <td >'+datajson3[j].description+'</td>        ';
                    str1 += '     <td >'+datajson3[j].sp_slide_block+'</td>     ';
                    str1 += '     <td >'+datajson3[j].cost+'</td>               ';
                    str1 += '     <td >'+datajson3[j].comment+'</td>            ';
                    str1 += '     <td >  </td>                                  ';
                    str1 += ' </tr>                                             ';
                

                }
                
            },
            error: function (jqxhr, status, exception) {
                alert('Exception:', exception);
            }
        });
        
        str1 += '         </tbody>';
        str1 += '    </table>';
        
        str1 = str1 + '</span></p>';
        str1 = str1 + '<p align="left"><b>Comment:</b><span id="" style="font-size:20px">';
        str1 = str1 + datajson[i].comment;
        str1 = str1 + '</span></p>';
        str1 = str1 + "</div>";
        

        
        $('#sp_slide_requested').append(str1);
        $('#sp_slide_requested').append(str2);
        str1 = "";
        str2 = "";

    }




}