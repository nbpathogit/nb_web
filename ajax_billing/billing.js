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
    var startdate = $("#startdate_billing").val();
    var enddate = $("#enddate_billing").val();
//    alert(hospital_id + " " + startdate + " " + enddate);

    $.ajax({
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: '/ajax_billing/getBillingGroupbyServiceType.php',
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


    $.ajax({
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
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

