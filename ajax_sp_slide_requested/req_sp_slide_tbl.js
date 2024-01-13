
//retrive data from DB write to DOM
$("#btn_get_sp_slide_rq_by_range").on("click", function (e) {

    var startdate = $("#startdate_sp_slide_rq").val();
    var enddate = $("#enddate_sp_slide_rq").val();
//    alert(startdate.substr(0, 4) + " " + startdate.substr(5, 2) + " " + startdate.substr(8, 2));



//==========Note==============================
//JavaScript counts months from 0 to 11:
//January = 0.
//December = 11.
//============================================

    let date = new Date();
    let result = date.toLocaleDateString('th-TH', {year: 'numeric', month: 'long', day: 'numeric', });
    //$('#bill_todaydate_thai').val(result);
//    console.log(result);

    date = new Date(startdate.substr(0, 4), (startdate.substr(5, 2))-1, startdate.substr(8, 2));
    result = date.toLocaleDateString('th-TH', {year: 'numeric', month: 'long', day: 'numeric', });
//    alert(result);
    $('.startdate_thai').text(result);

    date = new Date(enddate.substr(0, 4), (enddate.substr(5, 2))-1, enddate.substr(8, 2));
    result = date.toLocaleDateString('th-TH', {year: 'numeric', month: 'long', day: 'numeric', });
//    alert(result);
    $('.enddate_thai').text(result);
 


    var datajson;
    $.ajax({
        'async': false,
        type: 'POST',
        'global': false,
        url: 'ajax_sp_slide_requested/get_sp_slide_tbl_list.php',
        data: {
            
            'startdate': startdate,
            'enddate': enddate,

        },
        success: function (data) {
            
                //console.log(data);
                //alert(data);
                
            
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

 

 

    let strs = "";
    strs = strs + '<table  width="100%">';
    strs = strs + '<thead   >';
    strs = strs + '<tr>\n\
        <th style="font-size: 14pt;width:15%;">' + 'Request_id' + '</th>\n\
        <th style="font-size: 14pt;width:15%;">' + 'Patient_Num' + '</th>\n\
        <th style="font-size: 14pt;width:15%;">' + 'Request' + '</th>\n\
        <th style="font-size: 14pt;width:15%;">' + 'Block' + '</th>\n\
        <th style="font-size: 14pt;width:15%;">' + 'Pathologist' + '</th>\n\
        <th style="font-size: 14pt;">' + 'Note' + '</th>\n\
        </tr>';
    strs = strs + "</thead>";
    strs = strs + "<tbody>";
    for (let i in datajson)
    {
        strs = strs + '<tr>\n\
        <td>' + datajson[i].rid + '</td>\n\
        <td>' + datajson[i].number + '</td>\n\
        <td>' + datajson[i].description + '</td>\n\
        <td>' + datajson[i].sp_slide_block + '</td>\n\
        <td>' + datajson[i].pathologist + '</td>\n\
        <td>' + '' + '</td>\n\
        </tr>';

    }
    strs = strs + "</tbody>";
    strs = strs + "</table>";
    $('#rq_sp_slide_tbl_prn table').remove();
    $('#rq_sp_slide_tbl_prn').append(strs);



//    console.log("end");
//    alert("done");

});


$("#btn_export_rq_slide_pdf").on("click", function (e) {
    let page1 = $('#req_slide_page1').html();


    $('#tempform form').remove();
    var frm = $("<form>");
    frm.attr('method', 'post');
    frm.attr('target', '_blank');
    frm.attr('action', "patient_monitor_8000_print_job_pdf.php");
    frm.attr('');

    frm.append('<textarea hidden  name="page1">' + page1 + '</textarea> ');

    frm.appendTo($('#tempform'));
    frm.submit();




});





$(document).ready(function () {

    $('#nb_navbar_top').removeClass("sticky-top");



});