/**
 * Send links of class "delete" via post after a confirm dialog
 */

$(document).ready(function () {
    // executes when HTML-Document is loaded and DOM is ready
    //alert("document is ready");
    $("#lodingstatus").remove();
    $("#mainpage").show();

//    $('#import_date').datepicker().datepicker('setDate', 'today');

    var myDate = new Date(2000, 11, 31);
    $('#import_date').datepicker({dateFormat: 'mm/dd/yyyy'});
    $('#import_date').datepicker('setDate', myDate);
});


//รอรับเข้า 1000
$( "#move1000" ).on("mouseover",function(e) {
  $(this).addClass( "heldover" );
});
$( "#move1000" ).on("mouseout",function(e) {
  $(this).removeClass( "heldover" );
});
$("#move1000").on("click", function (e) {
    e.preventDefault();
        var frm = $("<form>");
        frm.attr('method', 'post');
        frm.attr('')
        frm.append('<input type="hidden" name="status" value="1000" /> ');
        frm.appendTo("body");
        frm.submit();
});

//รับเข้า 2000
$( "#move2000" ).on("mouseover",function(e) {
  $(this).addClass( "heldover" );
});
$( "#move2000" ).on("mouseout",function(e) {
  $(this).removeClass( "heldover" );
});
$("#move2000").on("click", function (e) {
    e.preventDefault();
        var frm = $("<form>");
        frm.attr('method', 'post');
        frm.attr('')
        frm.append('<input type="hidden" name="status" value="2000" /> ');
        frm.appendTo("body");
        frm.submit();
});

//เตรียมชิ้นเนื้อ 3000
$( "#move3000" ).on("mouseover",function(e) {
  $(this).addClass( "heldover" );
});
$( "#move3000" ).on("mouseout",function(e) {
  $(this).removeClass( "heldover" );
});
$("#move3000").on("click", function (e) {
    e.preventDefault();
        var frm = $("<form>");
        frm.attr('method', 'post');
        frm.attr('')
        frm.append('<input type="hidden" name="status" value="3000" /> ');
        frm.appendTo("body");
        frm.submit();
});



//เตรียมสไลด์ 6000
$( "#move6000" ).on("mouseover",function(e) {
  $(this).addClass( "heldover" );
});
$( "#move6000" ).on("mouseout",function(e) {
  $(this).removeClass( "heldover" );
});
$("#move6000").on("click", function (e) {
    e.preventDefault();
        var frm = $("<form>");
        frm.attr('method', 'post');
        frm.attr('')
        frm.append('<input type="hidden" name="status" value="6000" /> ');
        frm.appendTo("body");
        frm.submit();
});

//เตรียมชิ้นเนื้อพิเศษ 8000
$( "#move8000" ).on("mouseover",function(e) {
  $(this).addClass( "heldover" );
});
$( "#move8000" ).on("mouseout",function(e) {
  $(this).removeClass( "heldover" );
});
$("#move8000").on("click", function (e) {
    e.preventDefault();
        var frm = $("<form>");
        frm.attr('method', 'post');
        frm.attr('')
        frm.append('<input type="hidden" name="status" value="8000" /> ');
        frm.appendTo("body");
        frm.submit();
});


//แลปเซลล์วิทยา 10000
$( "#move10000" ).on("mouseover",function(e) {
  $(this).addClass( "heldover" );
});
$( "#move10000" ).on("mouseout",function(e) {
  $(this).removeClass( "heldover" );
});
$("#move10000").on("click", function (e) {
    e.preventDefault();
        var frm = $("<form>");
        frm.attr('method', 'post');
        frm.attr('')
        frm.append('<input type="hidden" name="status" value="10000" /> ');
        frm.appendTo("body");
        frm.submit();
});

//วินิจฉัย(คอนเฟิร์ม) 13000
$( "#move13000" ).on("mouseover",function(e) {
  $(this).addClass( "heldover" );
});
$( "#move13000" ).on("mouseout",function(e) {
  $(this).removeClass( "heldover" );
});
$("#move13000").on("click", function (e) {
    e.preventDefault();
        var frm = $("<form>");
        frm.attr('method', 'post');
        frm.attr('')
        frm.append('<input type="hidden" name="status" value="13000" /> ');
        frm.appendTo("body");
        frm.submit();
});

//วินิจฉัย12000
$( "#move12000" ).on("mouseover",function(e) {
  $(this).addClass( "heldover" );
});
$( "#move12000" ).on("mouseout",function(e) {
  $(this).removeClass( "heldover" );
});
$("#move12000").on("click", function (e) {
    e.preventDefault();
        var frm = $("<form>");
        frm.attr('method', 'post');
        frm.attr('')
        frm.append('<input type="hidden" name="status" value="12000" /> ');
        frm.appendTo("body");
        frm.submit();
});

//ออกผล<br>20000</td>
$( "#move20000" ).on("mouseover",function(e) {
  $(this).addClass( "heldover" );
});
$( "#move20000" ).on("mouseout",function(e) {
  $(this).removeClass( "heldover" );
});
$("#move20000").on("click", function (e) {
    e.preventDefault();
        var frm = $("<form>");
        frm.attr('method', 'post');
        frm.attr('')
        frm.append('<input type="hidden" name="status" value="20000" /> ');
        frm.appendTo("body");
        frm.submit();
});






$.validator.addMethod("dateTime", function (value, element) {
    return (value == "") || !isNaN(Date.parse(value));
}, "Must be a valid date and time");

$("#formArticle").validate({
    rules: {
        title: {
            required: true
        },
        content: {
            required: true
        },
        published_at: {
            dateTime: true
        }
    }
});

$("button.publish").on("click", function (e) {

    var id = $(this).data('id');

    //alert(id);

    var button = $(this);

    $.ajax({
        url: '/admin/publish-article.php',
        type: 'POST',
        data: {id: id}
    })
            .done(function (data) {
                button.parent().html(data);
            });

});


$('#dateInput').datetimepicker({
    format: 'Y-m-d H:i:s'
});

//$('#reportdate').datetimepicker({
//    format:'Y-m-d H:i:s'
//});


$('#import_date').on('blur', function () {
//      if($(this).val().trim().length === 0){
    //$(this).val("01/05/2022");
//      }
});

$.validator.addMethod("pgendered", function (value, element) {
    return (value != "กรุณาเลือก");
}, "Must be selected.");


$.validator.addMethod("selectd", function (value, element) {
    return (value != 0);
}, "Must be selected.");

$("#formAddPatient").validate({
    rules: {

        //==========  a  =============
        //เลขที่ผู้ป่วย
        pnum: {
            required: true
        },
        //LAB Number
        plabnum: {
            required: true
        },
        //วันที่รับ
        import_date: {
            required: true
        },
//        เพศ
        pgender: {
            pgendered: true
        },
        //ชื่อผู้ป่วย
        pname: {
            required: true
        },
        //นามสกุล
        plastname: {
            required: true
        },
        //อายุ
        pedge: {
            required: true
        },
        //เลขที่โรงพยาบาล
        phospital_num: {
            required: true
        },
        //โรงพยาบาล
        phospital_id: {
            selectd: true
        },
        //แพทย์ผู้ส่ง
        pclinician_id: {
            selectd: true
        },
        //สิ่งส่งตรวจ
        uspecimen_id: {
            selectd: true
        },
        //ความสำคัญ
        priority_id: {
            selectd: true
        },

        //==========  b  =============
        //สถานะ
        status_id: {
            selectd: true
        },
        //พนักงานตัดเนื้อ
        p_cross_section_id: {
            selectd: true
        },
        //พนักงานผู้ช่วยตัดเนื้อ
        p_cross_section_ass_id: {
            selectd: true
        },
        //พนักงานเตรียมสไลด์

        p_slide_prep_id: {
            selectd: true
        },
        //ราคาค่าตรวจ(บาท)
        pprice: {
            required: true
        },
        //พนักงานเตรียมไลด์พิเศษ
        p_slide_prep_sp_id: {
            selectd: true
        },
        //ราคาค่าตรวจพิเศษ(บาท)
        pspprice: {
            required: true
        },
        //==========  c  =============

        //พยาธิแพทย์ผู้ออกผล
        ppathologist_id: {
            selectd: true
        },

        //พยาธิแพทย์คอนเฟิร์มผล
        ppathologist2_id: {
            selectd: true
        },

        //วันที่รายงานผล
        report_date: {
            required: true
        },
        //==========  c  =============
        //ผลการตรวจ(เพิ่มเติม)
        p_rs_diagnosis: {
            required: true
        }
    }

});


$("#formEditPatient").validate({
    rules: {

        //==========  a  =============
        //เลขที่ผู้ป่วย
        pnum: {
            required: true
        },
        //LAB Number
        plabnum: {
            required: true
        },
        //วันที่รับ
        import_date: {
            required: true
        },
//        เพศ
        pgender: {
            pgendered: true
        },
        //ชื่อผู้ป่วย
        pname: {
            required: true
        },
        //นามสกุล
        plastname: {
            required: true
        },
        //อายุ
        pedge: {
            required: true
        },
        //เลขที่โรงพยาบาล
        phospital_num: {
            required: true
        },
        //โรงพยาบาล
        phospital_id: {
            selectd: true
        },
        //แพทย์ผู้ส่ง
        pclinician_id: {
            selectd: true
        },
        //สิ่งส่งตรวจ
        uspecimen_id: {
            selectd: true
        },
        //ความสำคัญ
        priority_id: {
            selectd: true
        },

        //==========  b  =============
        //สถานะ
        status_id: {
            selectd: true
        },
        //พนักงานตัดเนื้อ
        p_cross_section_id: {
            selectd: true
        },
        //พนักงานผู้ช่วยตัดเนื้อ
        p_cross_section_ass_id: {
            selectd: true
        },
        //พนักงานเตรียมสไลด์

        p_slide_prep_id: {
            selectd: true
        },
        //ราคาค่าตรวจ(บาท)
        pprice: {
            required: true
        },
        //พนักงานเตรียมไลด์พิเศษ
        p_slide_prep_sp_id: {
            selectd: true
        },
        //ราคาค่าตรวจพิเศษ(บาท)
        pspprice: {
            required: true
        },
        //==========  c  =============

        //พยาธิแพทย์ผู้ออกผล
        ppathologist_id: {
            selectd: true
        },

        //พยาธิแพทย์คอนเฟิร์มผล
        ppathologist2_id: {
            selectd: true
        },

        //วันที่รายงานผล
        report_date: {
            required: true
        },
        //==========  c  =============
        //ผลการตรวจ(เพิ่มเติม)
        p_rs_diagnosis: {
            required: true
        }
    }




});

//function myHide(){
//    document.getElementById('mainpage').style.display = 'block';//content ที่ต้องการแสดงหลังจากเพจโหลดเสร็จ
//    document.getElementById('lodingstatus').style.display = 'none';//content ที่ต้องการแสดงระหว่างโหลดเพจ
//}



$('#sandbox-container .input-group.date').datepicker({
    daysOfWeekHighlighted: "1,2,3,4,5",
    todayHighlight: true
});

