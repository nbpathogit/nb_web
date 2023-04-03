//btn_export_bill_pdf
$("#btn_export_bill_pdf").on("click", function (e) {
    var hospital_id = $("#phospital_id_bill option").filter(":selected").attr('value');
    var startdate = $("#startdate_billing").val();
    var enddate = $("#enddate_billing").val();

    var frm = $("<form>");
    frm.attr('method', 'post');
    frm.attr('target', '_blank');
    frm.attr('action', "billing_pdf_show.php");
    frm.attr('');
    frm.append('<input type="hidden" name="hospital_id" value="' + hospital_id + '" /> ');
    frm.append('<input type="hidden" name="startdate" value="' + startdate + '" /> ');
    frm.append('<input type="hidden" name="enddate" value="' + enddate + '" /> ');
    frm.appendTo("body");
    frm.submit();


});

//btn_export_bill_pdf
$("#btn_export_bill_pdf_layout").on("click", function (e) {
    var hospital_id = $("#phospital_id_bill option").filter(":selected").attr('value');
    var startdate = $("#startdate_billing").val();
    var enddate = $("#enddate_billing").val();

    var frm = $("<form>");
    frm.attr('method', 'post');
    frm.attr('target', '_blank');
    frm.attr('action', "billing_pdf_show.php");
    frm.attr('');
    frm.append('<input type="hidden" name="hospital_id" value="' + hospital_id + '" /> ');
    frm.append('<input type="hidden" name="startdate" value="' + startdate + '" /> ');
    frm.append('<input type="hidden" name="enddate" value="' + enddate + '" /> ');
    frm.append('<input type="hidden" name="layout" value="' + enddate + '" /> ');
    frm.appendTo("body");
    frm.submit();


});


$("#btn_get_bill_by_range").on("click", function (e) {
    var hospital_id = $("#phospital_id_bill option").filter(":selected").attr('value');
    var hospital_name = $("#phospital_id_bill option").filter(":selected").text();
    var startdate = $("#startdate_billing").val();
    var enddate = $("#enddate_billing").val();
//    alert(startdate.substr(0, 4) + " " + startdate.substr(5, 2) + " " + startdate.substr(8, 2));

    $.ajax({
        'async': false,
        type: 'POST',
        'global': false,
        url: '/ajax_billing/getBillbyHospitalbyDateRange.php',
        data: {
            'hospital_id': hospital_id,
            'startdate': startdate,
            'enddate': enddate,

        },
        success: function (data) {
            if (data[0] != "[") {
                alert(data);
                console.log(data);
                return;
            }
            var datajson = JSON.parse(data);
            if (datajson.length == 0) {
                alert("No record found");
                return;
            }
        },
        error: function (jqxhr, status, exception) {
            alert('Exception:', exception);
        }
    });
    
    let net_price = null;
    $.ajax({
        'async': false,
        type: 'POST',
        'global': false,
        url: '/ajax_billing/getBillbyHospitalbyDateRangeSumPrice.php',
        data: {
            'hospital_id': hospital_id,
            'startdate': startdate,
            'enddate': enddate,
        },
        success: function (data) {
            if (data[0] != "[") {
                alert(data);
                console.log(data);
                return;
            }
            let datajson = JSON.parse(data);
            if (datajson.length == 0) {
                alert("No record found");
                return;
            }
            net_price = datajson;
        },
        error: function (jqxhr, status, exception) {
            alert('Exception:', exception);
        }
    });


    $.ajax({
        'async': false,
        type: 'POST',
        'global': false,
        url: '/ajax_billing/getBilling.php',
        data: {
            'hospital_id': hospital_id,
            'startdate': startdate,
            'enddate': enddate,
        },
        success: function (data) {
            if (data[0] != "[") {
                alert(data);
                console.log(data);
                return;
            }
            var datajson = JSON.parse(data);
            if (datajson.length == 0) {
                alert("No record found");
                return;
            }
            drawbillingTable(datajson);
        },
        error: function (jqxhr, status, exception) {
            alert('Exception:', exception);
        }
    });
    
//    C:\anuchit2\nb_web\ajax_billing\getHospital.php
    let hospital = null;
    $.ajax({
        'async': false,
        type: 'POST',
        'global': false,
        url: '/ajax_billing/getHospital.php',
        data: {
            'hospital_id': hospital_id,
        },
        success: function (data) {
            if (data[0] != "[") {
                alert(data);
                console.log(data);
                return;
            }
            var datajson = JSON.parse(data);
            if (datajson.length == 0) {
                alert("No hospital record found");
                return;
            }
            hospital = datajson;
        },
        error: function (jqxhr, status, exception) {
            alert('Exception:', exception);
        }
    });

    let date = new Date();
    let result = date.toLocaleDateString('th-TH', {year: 'numeric', month: 'long', day: 'numeric', });
    $('#bill_todaydate_thai').val(result);
//    console.log(result);

    date = new Date(startdate.substr(0, 4), startdate.substr(5, 2), startdate.substr(8, 2));
    result = date.toLocaleDateString('th-TH', {year: 'numeric', month: 'long', day: 'numeric', });
    $('#bill_startdate_thai').val(result);

    date = new Date(enddate.substr(0, 4), enddate.substr(5, 2), enddate.substr(8, 2));
    result = date.toLocaleDateString('th-TH', {year: 'numeric', month: 'long', day: 'numeric', });
    $('#bill_enddate_thai').val(result);

    $('#bill_hospitalname').val(hospital_name);
    $('#bill_hospital_taxid').val(hospital[0].tax_id);
    $('#bill_hospital_address').val(hospital[0].address);
    $('#bill_hospital_net_price').val(net_price[0].bcost);
    let cost = $('#bill_hospital_net_price').val();
    
    let bill_hospital_net_price_spell = "";
        $.ajax({
        'async': false,
        type: 'POST',
        'global': false,
        url: '/ajax_billing/getCostSpelling.php',
        data: {
            'cost': cost,
        },
        success: function (data) {
            bill_hospital_net_price_spell = data;
        },
        error: function (jqxhr, status, exception) {
            alert('Exception:', exception);
            return;
        }
    });
    
    $('#bill_hospital_net_price_spell').val(bill_hospital_net_price_spell);
    
    $('#bill_count_all_list').val(net_price[0].bcount);
    
    $('#bill_manager').val("นาย อนุสรณ์ ชุมทอง");

    console.log("end");
    alert("done");

});


$("#btn_bill_preview_web").on("click", function (e) {
    
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

function drawbillingTable(datajson) {

    $('#billing_table_span table thead tr').remove();
    $('#billing_table_span table tbody tr').remove();

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
        //repaintTblhire1(data);


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

//btn_bill_preview_web

$(document).ready(function () {

    $('#nb_navbar_top').removeClass( "sticky-top" );

    

});