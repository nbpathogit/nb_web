//btn_export_bill_pdf
$("#btn_export_bill_pdf").on("click", function (e) {
    let page1 = $('#bill_page1').html();
    let page2 = $('#bill_page2').html();
    let page3 = $('#bill_page3').html();
    let page4 = $('#bill_page4').html();
    let page5 = $('#bill_page5').html();

    $('#tempform form').remove();
    var frm = $("<form>");
    frm.attr('method', 'post');
    frm.attr('target', '_blank');
    frm.attr('action', "billing_pdf_show.php");
    frm.attr('');


    frm.append('<textarea hidden  name="page1">' + page1 + '</textarea> ');
    frm.append('<textarea hidden  name="page2">' + page2 + '</textarea> ');
    frm.append('<textarea hidden  name="page3">' + page3 + '</textarea> ');
    frm.append('<textarea hidden  name="page4">' + page4 + '</textarea> ');
    frm.append('<textarea hidden  name="page5">' + page5 + '</textarea> ');
    frm.appendTo($('#tempform'));
    frm.submit();




});

//btn_export_bill_pdf
$("#btn_export_bill_pdf_layout").on("click", function (e) {
    let page1 = $('#bill_page1').html();
    let page2 = $('#bill_page2').html();
    let page3 = $('#bill_page3').html();
    let page4 = $('#bill_page4').html();
    let page5 = $('#bill_page5').html();

    $('#tempform form').remove();
    var frm = $("<form>");
    frm.attr('method', 'post');
    frm.attr('target', '_blank');
    frm.attr('action', "billing_pdf_show.php");
    frm.attr('');

    frm.append('<input type="hidden" name="layout" value="' + '' + '" /> ');
    frm.append('<textarea hidden name="page1">' + page1 + '</textarea> ');
    frm.append('<textarea hidden name="page2">' + page2 + '</textarea> ');
    frm.append('<textarea hidden name="page3">' + page3 + '</textarea> ');
    frm.append('<textarea hidden name="page4">' + page4 + '</textarea> ');
    frm.append('<textarea hidden name="page5">' + page5 + '</textarea> ');
    frm.appendTo("body");
    frm.submit();


});

//Get paramiter from database to DOM
// Btn   1) preview bill by date range  
$("#btn_get_bill_by_range").on("click", function (e) {
    var hospital_id = $("#phospital_id_bill option").filter(":selected").attr('value');
    var hospital_name = $("#phospital_id_bill option").filter(":selected").text();
    var startdate = $("#startdate_billing").val();
    var enddate = $("#enddate_billing").val();
//    alert(startdate.substr(0, 4) + " " + startdate.substr(5, 2) + " " + startdate.substr(8, 2));

    let error_ajax = "";

    error_ajax = "";
    $.ajax({
        'async': false,
        type: 'POST',
        'global': false,
        url: 'ajax_billing/getBillbyHospitalbyDateRange.php',
        data: {
            'hospital_id': hospital_id,
            'startdate': startdate,
            'enddate': enddate,

        },
        success: function (data) {
            if (data[0] != "[") {
                alert(data);
                console.log(data);
                error_ajax = data;
            }
            var datajson = JSON.parse(data);
            if (datajson.length == 0) {
                error_ajax = "No record found";
//                alert("No record found");
//                return;
            }
        },
        error: function (jqxhr, status, exception) {
             alert( jqxhr.responseText);
        }
    });
    if(! (error_ajax.length === 0) ){
        alert("::"+error_ajax);
        return -1;
    }

    error_ajax = "";
    let net_price = null;
    $.ajax({
        'async': false,
        type: 'POST',
        'global': false,
        url: 'ajax_billing/getBillbyHospitalbyDateRangeSumPrice.php',
        data: {
            'hospital_id': hospital_id,
            'startdate': startdate,
            'enddate': enddate,
        },
        success: function (data) {
            if (data[0] != "[") {
                alert(data);
                console.log(data);
                error_ajax = data;
                return;
            }
            let datajson = JSON.parse(data);
            if (datajson.length == 0) {
                error_ajax = "No record found";
            }
            net_price = datajson;
        },
        error: function (jqxhr, status, exception) {
             alert( jqxhr.responseText);
        }
    });
    if(! (error_ajax.length === 0) ){
        alert("::"+error_ajax);
        return -1;
    }


    error_ajax = "";
    let net_byservice_price = null;
    $.ajax({
        'async': false,
        type: 'POST',
        'global': false,
        url: 'ajax_billing/getCostGroupbyServiceTyoebyHospitalbyDateRange.php',
        data: {
            'hospital_id': hospital_id,
            'startdate': startdate,
            'enddate': enddate,
        },
        success: function (data) {
//            console.log(data);
//            alert(data);
            if (data[0] != "[") {
                alert(data);
                error_ajax = data;
            }
            let datajson = JSON.parse(data);
            if (datajson.length == 0) {
                error_ajax = "No record found";
            }
            net_byservice_price = datajson;
        },
        error: function (jqxhr, status, exception) {
             alert( jqxhr.responseText);
        }
    });
    if(! (error_ajax.length === 0) ){
        alert("::"+error_ajax);
        return -1;
    }

    let strs = "";
    strs = strs + "<table>";
    strs = strs + "<thead>";
    strs = strs + '<tr><th style="font-size: 14pt;">' + 'sid' + '</th><th style="font-size: 14pt;">' + 'service_type' + '</th><th style="font-size: 14pt;">' + 'bcost_count' + '</th><th style="font-size: 14pt;">' + 'bcost_sum' + '</th></tr>';
    strs = strs + "</thead>";
    strs = strs + "<tbody>";
    for (let i in net_byservice_price)
    {
        strs = strs + '<tr>\n\
<td><input type="text" style="font-size: 14pt;" value="' + (parseInt(i)+1) + '"></td>\n\
<td><input type="text" style="font-size: 14pt;"  value="' + net_byservice_price[i].service_type_bill + '"></td>\n\
<td><input type="text" style="font-size: 14pt;"  value="' + net_byservice_price[i].bcost_count + '"></td>\n\
<td><input type="text" style="font-size: 14pt;"  value="' + net_byservice_price[i].bcost_sum + '"></td>\n\
</tr>';

    }
    strs = strs + "</tbody>";
    strs = strs + "</table>";
    $('#bill_hospital_by_service_price table').remove();
    $('#bill_hospital_by_service_price').append(strs);



    // get all record one by one
    error_ajax = "";
    $.ajax({
        'async': false,
        type: 'POST',
        'global': false,
        url: 'ajax_billing/getBilling.php',
        data: {
            'hospital_id': hospital_id,
            'startdate': startdate,
            'enddate': enddate,
        },
        success: function (data) {
            if (data[0] != "[") {
                alert(data);
                console.log(data);
                let error_ajax = data;
            }
            var datajson = JSON.parse(data);
            if (datajson.length == 0) {
                let error_ajax = "No record found";
            }
            drawbill_list_all_page_g2(datajson);

            drawbillingTable(datajson);// last page for reference
        },
        error: function (jqxhr, status, exception) {
             alert( jqxhr.responseText);
        }
    });
    if(! (error_ajax.length === 0) ){
        alert("::"+error_ajax);
        return -1;
    }



    // get record groupby code
    error_ajax = "";
    $.ajax({
        'async': false,
        type: 'POST',
        'global': false,
        url: 'ajax_billing/getBillingGroupByCode.php',
        data: {
            'hospital_id': hospital_id,
            'startdate': startdate,
            'enddate': enddate,
        },
        success: function (data) {
            if (data[0] != "[") {
                alert(data);
                console.log(data);
                error_ajax = "No record found";
            }
            var datajson = JSON.parse(data);
            if (datajson.length == 0) {
                error_ajax = "No record found";
            }

            drawbill_list_all_page_g3(datajson);
            
        },
        error: function (jqxhr, status, exception) {
             alert( jqxhr.responseText);
        }
    });
    if(! (error_ajax.length === 0) ){
        alert("::"+error_ajax);
        return -1;
    }



//    C:\anuchit2\nb_web\ajax_billing\getHospital.php
    error_ajax = "";
    let hospital = null;
    $.ajax({
        'async': false,
        type: 'POST',
        'global': false,
        url: 'ajax_billing/getHospital.php',
        data: {
            'hospital_id': hospital_id,
        },
        success: function (data) {
            if (data[0] != "[") {
                alert(data);
                console.log(data);
                error_ajax = data;
            }
            var datajson = JSON.parse(data);
            if (datajson.length == 0) {
                error_ajax = "No record found";
            }
            hospital = datajson;
        },
        error: function (jqxhr, status, exception) {
            alert('Exception:', exception);
        }
    });
    if(! (error_ajax.length === 0) ){
        alert("::"+error_ajax);
        return -1;
    }

    let date = new Date();
    let result = date.toLocaleDateString('th-TH', {year: 'numeric', month: 'long', day: 'numeric', });
    $('#bill_todaydate_thai').val(result);
//    console.log(result);

    date = new Date(startdate.substr(0, 4), startdate.substr(5, 2) -1, startdate.substr(8, 2));
    result = date.toLocaleDateString('th-TH', {year: 'numeric', month: 'long', day: 'numeric', });
    $('#bill_startdate_thai').val(result);

    date = new Date(enddate.substr(0, 4), enddate.substr(5, 2) -1, enddate.substr(8, 2));
    result = date.toLocaleDateString('th-TH', {year: 'numeric', month: 'long', day: 'numeric', });
    $('#bill_enddate_thai').val(result);

    $('#bill_hospitalname').val(hospital_name);
    $('#bill_hospital_taxid').val(hospital[0].tax_id);
    $('#bill_hospital_address').val(hospital[0].address);
    $('#bill_hospital_net_price').val(net_price[0].bcost);
    let cost = $('#bill_hospital_net_price').val();

    let bill_hospital_net_price_spell = "";
    error_ajax = "";
    $.ajax({
        'async': false,
        type: 'POST',
        'global': false,
        url: 'ajax_billing/getCostSpelling.php',
        data: {
            'cost': cost,
        },
        success: function (data) {
            bill_hospital_net_price_spell = data;
        },
        error: function (jqxhr, status, exception) {
            error_ajax = jqxhr.responseText;
        }
    });
    if(! (error_ajax.length === 0) ){
        alert("::"+error_ajax);
        return -1;
    }

    $('#bill_hospital_net_price_spell').val(bill_hospital_net_price_spell);

    $('#bill_count_all_list').val(net_price[0].bcount);

    $('#bill_manager').val("นาย อนุสรณ์ ชุมทอง");

    console.log("end");
    alert("done");

});

//btn   2) Preview on web page.  
$("#btn_bill_preview_web").on("click", function (e) {



    //Read data from table to array
    var pricebyservice = [];
    var headers = [];
    $('#bill_hospital_by_service_price table thead th').each(function (index, item) {
        headers[index] = $(item).html();
    });
    $('#bill_hospital_by_service_price table tbody tr').has('td').each(function () {
        var arrayItem = {};
        $('td input', $(this)).each(function (index, item) {
            arrayItem[headers[index]] = $(item).val();
        });
        pricebyservice.push(arrayItem);
    });


    //Wtite Array to page1
    let str1 = '';
    str1 = str1 + '<table width="100%" style="border: 1px solid green;">';
    for (let i in pricebyservice)
    {
        console.log(pricebyservice[i]);

        str1 = str1 +
                '    <tr>' +
                '        <td width="6%" style="border: 1px solid green;"></td>' +
                '        <td width="60%" style="border: 1px solid green;text-align:left;">' + pricebyservice[i].service_type + ' <span class="billing_count_all_list">' + pricebyservice[i].bcost_count + '</span> รายการ </td>' +
                '        <td  style="border: 1px solid green;text-align:right;"><span class="">' + pricebyservice[i].bcost_sum + '</span> บาท</td>' +
                '        <td width="6%" style="border: 1px solid green;"></td>' +
                '    </tr>';


    }

    str1 = str1 + '</table>';


    $('#bill_by_service_tbl1 table').remove();
    $('#bill_by_service_tbl1').append(str1);


    //Write array to page2,page3
    let str2 = '';
    str2 = str2 + '<table width="100%" style="border: 1px solid black;">' +
            '    <tr>' +
            '        <th width="10%" style="border: 1px solid black;text-align:center;"><b>ลำดับ</b></td>' +
            '        <th width="10%" style="border: 1px solid black;text-align:left;"><b>รายการบริการตรวจทำงพยาธิวิทยา</b></td>' +
            '        <th width="10%" style="border: 1px solid black;text-align:center;"><b>จำนวน(รายการ)</b></td>' +
            '        <th width="%" style="border: 1px solid black;text-align:right;"><b>จำนวนเงิน</b></td>' +
            '     </tr>';
    for (let i in pricebyservice)
    {
        console.log(pricebyservice[i]);
        str2 = str2 +
                '    <tr>' +
                '        <td  style="border: 1px solid black;text-align:center;"><b>' + pricebyservice[i].sid + '</b></td>' +
                '        <td  style="border: 1px solid black;text-align:left;"><b>' + pricebyservice[i].service_type + '</b></td>' +
                '        <td  style="border: 1px solid black;text-align:center;"><b>' + pricebyservice[i].bcost_count + '</b></td>' +
                '        <td  style="border: 1px solid black;text-align:right;"><b>' + pricebyservice[i].bcost_sum + '</b></td>' +
                '    </tr>';

    }

    str2 = str2 +
            '    <tr>' +
            '        <td  style="border: 1px solid black;"><b>&nbsp;</b></td>' +
            '        <td  style="border: 1px solid black;"><b></b></td>' +
            '        <td  style="border: 1px solid black;"><b></b></td>' +
            '        <td  style="border: 1px solid black;"><b></b></td>' +
            '    </tr>' +
            '    <tr>' +
            '        <td colspan="2"  style="border: 1px solid black;text-align:left;"><b>(ตัวอักษร) <span class="bill_hospital_net_price_spell" style="color:red">X</span></b></td>' +
            '        <td colspan="2"  style="border: 1px solid black;text-align:right;"><b>รวมสุทธิ <span class="bill_hospital_net_price" style="color:red">X</span></b></td>' +
            '    </tr>' +
            '    ' +
            '    <tr>  ' +
            '        <td colspan="4"  style="border: 1px solid black;text-align:center;"><b>โดยมีรายละเอียดดังรายการตรวจที่แนบมาด้วย</b></td>' +
            '    </tr>' +
            '</table>';



    $('.bill_by_service_tbl2 table').remove();
    $('.bill_by_service_tbl2').append(str2);



//Write array to page4 at end of table
    let str4_f = '';
    let str5_f = '';

    for (let i in pricebyservice)
    {
        console.log(pricebyservice[i]);
        str4_f = str4_f +
                '            <tr>                                 ' +
                '                <td  colspan="8"  style="font-weight: bold;text-align:right;">' + pricebyservice[i].service_type + ' จำนวน ' + pricebyservice[i].bcost_count + ' รายการ</td>   ' +
                '                <td > <span class="" style=" font-weight: bold;color:red">' + pricebyservice[i].bcost_sum + '</span> </td>                      ' +
                '            </tr>                                ';

    }
    str4_f = str4_f +
            '            <tr>                                 ' +
            '                <td  colspan="8" style="font-weight: bold;text-align:right;"> รวมทั้งสิ้น </td>   ' +
            '                <td > <span class="bill_hospital_net_price" style=" font-weight: bold;color:red">X</span> </td>                      ' +
            '            </tr>                                ';



//    $('#price_by_service_footer tr').remove();
//    $('#price_by_service_footer').append(str4_f);

    str5_f = str5_f +
            '            <tr>                                 ' +
            '                <td  colspan="5" style="font-weight: bold;text-align:right;"> รวมทั้งสิ้น </td>   ' +
            '                <td > <span class="bill_hospital_net_price" style=" font-weight: bold;color:red">X</span> </td>                      ' +
            '            </tr>                                ';

    $('#price_by_service_footer_g3 tr').remove();
    $('#price_by_service_footer_g3').append(str5_f);




    $('.bill_todaydate_thai').text($('#bill_todaydate_thai').val());
    $('.bill_startdate_thai').text($('#bill_startdate_thai').val());
    $('.bill_enddate_thai').text($('#bill_enddate_thai').val());
    $('.bill_hospitalname').text($('#bill_hospitalname').val());
    $('.bill_hospital_taxid').text($('#bill_hospital_taxid').val());
    $('.bill_hospital_address').text($('#bill_hospital_address').val());
    $('.bill_invoice_sub_number').text($('#bill_invoice_sub_number').val());
    $('.bill_hospital_invoice_number').text($('#bill_hospital_invoice_number').val());
    $('.bill_hospital_net_price').text($('#bill_hospital_net_price').val());
    $('.bill_hospital_net_price_spell').text($('#bill_hospital_net_price_spell').val());
    $('.bill_count_all_list').text($('#bill_count_all_list').val());
    $('.bill_manager').text($('#bill_manager').val());



    alert('done');



});




function drawbill_list_all_page(datajson) {

    $('#bill_list_all table').remove();

    let str = "";
 // ลำดับที่   ชื่อ  วันที่รับ เลขที่ แพทย์ผู้ส่ง   รายการ   ค่าตรวจ
    str = str +
            '<table width="100%" >            ' +
            '    <thead>                      ' +
            '        <tr>                     ' +
            '            <th >ลำดับที่</th>          ' +
            '            <th >เลขที่งาน</th>      ' +
            '            <th >ผู้ป่วย</th>        ' +
            '            <th >รหัส</th>       ' +
            '            <th >รายการ</th>' +
            '            <th >วันที่รับ</th>       ' +
            '            <th >เลขที่โรงพยาบาล</th> ' +
            '            <th >แพทย์ผู้ส่งตรวจ</th>  ' +
            '            <th >ค่าตรวจ</th>       ' +
            '        </tr>                    ' +
            '    </thead>                     ' +
            '    <tbody>                      ';

    for (var i in datajson)
    {
        str = str +
                '        <tr>                                                                                      ' +
                '            <td>' + (parseInt(i)+1) + '</td>                                                               ' +
                '            <td>' + datajson[i].number + '</td>                                                      ' +
                '            <td>' + datajson[i].patient_name + '</td>   ' +
                '            <td>' + datajson[i].code_description + '</td>                                            ' +
                '            <td>' + datajson[i].description + '</td>                                                 ' +
                '            <td>' + datajson[i].admit_date + '</td>                                    ' +
                '            <td>' + datajson[i].hospital_num + '</td>                                               ' +
                '            <td>' + datajson[i].patient_name + '</td>                                                 ' +
                '            <td>' + datajson[i].b_cost + '</td>                                                        ' +
                '        </tr>                                                                                     ';


    }
    
    str = str +
            '    </tbody>                                     ' +
            '    <tfoot id="price_by_service_footer">                                      ' +
            '    </tfoot>                                     ' +
            '</table>                                         ';


    console.log(str);
    $('#bill_list_all').append(str);




}

function trim(str, ch) {
    var start = 0, 
        end = str.length;

    while(start < end && str[start] === ch)
        ++start;

    while(end > start && str[end - 1] === ch)
        --end;

    return (start > 0 || end < str.length) ? str.substring(start, end) : str;
}

function trimslash(str) {
    let start = 0;
    let    end = str.length;
    while(start < end && str[start].match(/\//)){
//        alert('found match at start');
        ++start;
    }
    while(end > start && str[end - 1] .match(/\//)){
//        alert('found match at start');
        --end;
    }
    return (start > 0 || end < str.length) ? str.substring(start, end) : str;
}


function drawbill_list_all_page_g2(datajson) {

    $('#bill_list_all_g2 table').remove();

    let str = "";

//p_sn	p_hn	p_admit_date	patient_name	clinicien_name	b_description_concat_nm	b_description_concat_sp	b_description_concat_all	b_cost_sum_nm	b_cost_sum_sp	b_cost_sum_all 

    str = str +
            '<table width="100%" >            ' +
            '    <thead>                      ' +
            '        <tr>                     ' +
            '            <th >ลำดับที่</th>          ' +
            '            <th >เลขที่งาน</th>      ' +
            '            <th >วันที่รับ</th>      ' +
            '            <th >ผู้ป่วย</th>        ' +
            '            <th >เลขที่โรงพยาบาล</th> ' +
            '            <th >แพทย์ผู้ส่งตรวจ</th>        ' +
            '            <th >รายการ</th>'               +
            '            <th >ค่าบริการ</th>       ' +
            '            <th >ค่าตรวจพิเศษ</th>  ' +
            '            <th >รวม</th>       ' +
            '        </tr>                    ' +
            '    </thead>                     ' +
            '    <tbody>                      ';

    let sum_of_b_cost_sum_nm = 0;
    let sum_of_b_cost_sum_sp = 0;
    let sum_of_b_cost_sum_all = 0;
    
    for (var i in datajson)
    {
        str = str +
                '        <tr>                                                                                      ' +
                '            <td>' + (parseInt(i)+1) + '</td>                                                      ' +
                '            <td>' + datajson[i].p_sn + '</td>                                                      ' +
                '            <td>' + datajson[i].p_admit_date + '</td>                                                      ' +
                '            <td>' + datajson[i].patient_name + '</td>                                            ' +
                '            <td>' + datajson[i].p_hn + '</td>                                                         ' +
                '            <td>' + datajson[i].clinicien_name + '</td>                                              ' +
                '            <td>' + trim(trim(datajson[i].b_description_concat_all,' '),'/') + '</td>                               ' +
                '            <td>' + datajson[i].b_cost_sum_nm + '</td>                                                 ' +
                '            <td>' + datajson[i].b_cost_sum_sp + '</td>                                               ' +
                '            <td>' + datajson[i].b_cost_sum_all + '</td>                                             ' +
                '        </tr>                                                                                       ';
        sum_of_b_cost_sum_nm += parseInt(datajson[i].b_cost_sum_nm);
        sum_of_b_cost_sum_sp += parseInt(datajson[i].b_cost_sum_sp);
        sum_of_b_cost_sum_all += parseInt(datajson[i].b_cost_sum_all);
    }
    
    str = str +
            '    </tbody>                                     ' +
            '    <tfoot id="price_by_service_footer">                                      ' +
            '    </tfoot>                                     ' +
            '</table>                                         ';


    console.log(str);
    $('#bill_list_all_g2').append(str);
    
    let str4_f = ' <tr>                                 ' +
            '                <td  colspan="7" style="font-weight: bold;text-align:right;"> รวมทั้งสิ้น </td>   ' +
            '                <td > <span class="" style=" font-weight: bold;color:red">'+sum_of_b_cost_sum_nm+'</span> </td>                      ' +
            '                <td > <span class="" style=" font-weight: bold;color:red">'+sum_of_b_cost_sum_sp+'</span> </td>                      ' +
            '                <td > <span class="" style=" font-weight: bold;color:red">'+sum_of_b_cost_sum_all+'</span> </td>                     ' +
            '            </tr>  ';
    
    $('#price_by_service_footer tr').remove();
    $('#price_by_service_footer').append(str4_f);




}



function drawbill_list_all_page_g3(datajson) {

    $('#bill_list_all_g3 table').remove();

    let str = "";

    str = str +
            '<table width="100%" >            ' +
            '    <thead>                      ' +
            '        <tr>                     ' +
            '            <th >ลำดับที่</th>          ' +
            '            <th >รหัส</th>      ' +
            '            <th >รายการส่งตรวจ</th>        ' +
            '            <th >ราคาต่อหน่วย<br>(บาท)</th>       ' +
            '            <th >จำนวนครั้งที่<br>ส่งตรวจ(ครั้ง)</th>' +
            '            <th >จำนวนเงิน(บาท)</th>       ' +
            '        </tr>                    ' +
            '    </thead>                     ' +
            '    <tbody>                      ';

    for (var i in datajson)
    {
        str = str +
                '        <tr>                                                                            ' +
                '            <td>' + (parseInt(i)+1) + '</td>                                            ' +
                '            <td>' + datajson[i].b_code + '</td>                                         ' +
                '            <td>' + datajson[i].b_description + '</td>                                  ' +
                '            <td>' + datajson[i].b_cost + '</td>                                         ' +
                '            <td>' + datajson[i].bcost_count + '</td>                                    ' +
                '            <td>' + datajson[i].bcost_sum + '</td>                                      ' +
                '        </tr>                                                                            ';


    }
    
    str = str +
            '    </tbody>                                     ' +
            '    <tfoot id="price_by_service_footer_g3">          ' +
            '    </tfoot>                                     ' +
            '</table>                                         ';


    console.log(str);
    $('#bill_list_all_g3').append(str);

}





function drawbillingTable(datajson) {

    $('#billing_table_span table thead tr').remove();
    $('#billing_table_span table tbody tr').remove();


//==== Draw most of data ======
    let str = '<tr>' +
            '<th scope="col">#</th>' +
            '<th scope="col">เลขที่งาน</th>        ' +
            '<th scope="col">ผู้ป่วย</th>          ' +
            '<th scope="col">ชนิดค่าบริการ</th>     ' +
            '<th scope="col">code</th>         ' +
            '<th scope="col">description</th>  ' +
            '<th scope="col">วันที่รับ</th>         ' +
            '<th scope="col">โรงพยาบาล</th>      ' +
            '<th scope="col">เลขที่โรงพยาบาล</th>   ' +
            '<th scope="col">แพทย์ผู้ส่งตรวจ</th>    ' +
            '<th scope="col">ค่าตรวจ</th>         ' +
            '<th scope="col">comment</th>      ' +
            '</tr>';
    $('#billing_table_span table thead').append(str);
    for (var i in datajson)
    {
        /*
         
         <span id="billing_table_span">
         <table class="" id="billing_table" style="width:100%">
         <thead>
         <tr>
         <th scope="col">#</th> <!--0-->
         <th scope="col">เลขที่งาน</th> <!--3-->
         <th scope="col">ผู้ป่วย</th> <!--4-->
         <th scope="col">ชนิดค่าบริการ</th> <!--6-->
         <th scope="col">code</th> <!--7-->
         <th scope="col">description</th> <!--8-->
         <th scope="col">วันที่รับ</th> <!--9-->
         <th scope="col">โรงพยาบาล</th> <!--11-->
         <th scope="col">เลขที่โรงพยาบาล</th> <!--12-->
         <th scope="col">แพทย์ผู้ส่งตรวจ</th> <!--13-->
         <th scope="col">ค่าตรวจ</th> <!--15-->
         <th scope="col">comment</th> <!--16-->
         </tr>
         </thead>
         <tbody>
         <tr class="">
         <td class="">40</td>
         <td><a href="patient_edit.php?id=">SN2302237</a></td>
         <td>นาย  น้อย พุ่มไม้ </td>
         <td>ตรวจธรรมดา</td>
         <td>33000</td>
         <td>ก้อนเนื้อขนาดใหญ่กว่า 5 ซ.ม.และตัดเกิน 5 blocks (38003)</td>
         <td>2023-03-04 08:25:07</td>
         <td>ยังไม่ได้เลือก</td>
         <td></td>
         <td>สิทธิโชค (นพ.) รพ.สุโขทัย</td>
         <td>400</td>
         <td>ราคามาตรฐาน</td>
         </tr>
         </tbody>
         <tfoot>
         </tfoot>
         </table>
         </span>
         
         
         */



        let str = '<tr>' +
                '<td>' + datajson[i].bid + '</td>' +
                '<td><a href="patient_edit.php?id=' + datajson[i].pid + '">' + datajson[i].number + '</a></td>' +
                '<td>' + datajson[i].ppre_name + ' ' + datajson[i].lastname + ' ' + datajson[i].lastname + '</td>' +
                '<td>' + datajson[i].service_type + '</td>' +
                '<td>' + datajson[i].code_description + '</td>' +
                '<td>' + datajson[i].description + '</td>' +
                '<td>' + datajson[i].import_date + '</td>' +
                '<td>' + datajson[i].hospital + '</td>' +
                '<td>' + datajson[i].phospital_num + '</td>' +
                '<td>' + datajson[i].send_doctor + '</td>' +
                '<td>' + datajson[i].cost + '</td>' +
                '<td>' + datajson[i].comment + '</td>' +
                '</tr>';
        console.log(str);
        $('#billing_table_span table tbody').append(str);

    }

}


$(document).ready(function () {

    $('#nb_navbar_top').removeClass("sticky-top");



});