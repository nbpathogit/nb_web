/* global is_diag_editeing_mode */

function showFollowStatus(status) {
    if (status == 'lump') {

        $('#p_cross_section_id_hr').show();
        var a = ['p_cross_section_id', 'p_cross_section_ass_id', 'date_3000'];
        for (var i = 0; i < a.length; i++) {
            $('label[for=' + a[i] + '], input#' + a[i] + ', select#' + a[i]).show();
        }


        $('#p_slide_prep_id_hr').show();
        var a = ['p_slide_prep_id', 'pprice', 'date_6000'];
        for (var i = 0; i < a.length; i++) {
            $('label[for=' + a[i] + '], input#' + a[i] + ', select#' + a[i]).show();
        }

        $('#p_slide_prep_sp_id_hr').show();
        var a = ['p_slide_prep_sp_id', 'pspprice', 'date_8000'];
        for (var i = 0; i < a.length; i++) {
            $('label[for=' + a[i] + '], input#' + a[i] + ', select#' + a[i]).show();
        }

        $('#p_slide_lab_id_hr').hide();
        var a = ['p_slide_lab_id', 'p_slide_lab_price', 'date_10000'];
        for (var i = 0; i < a.length; i++) {
            $('label[for=' + a[i] + '], input#' + a[i] + ', select#' + a[i]).hide();
        }
    } else if (status == 'fluid') {

        $('#p_cross_section_id_hr').hide();
        var a = ['p_cross_section_id', 'p_cross_section_ass_id', 'date_3000'];
        for (var i = 0; i < a.length; i++) {
            $('label[for=' + a[i] + '], input#' + a[i] + ', select#' + a[i]).hide();
        }

        $('#p_slide_prep_id_hr').hide();
        var a = ['p_slide_prep_id', 'pprice', 'date_6000'];
        for (var i = 0; i < a.length; i++) {
            $('label[for=' + a[i] + '], input#' + a[i] + ', select#' + a[i]).hide();
        }

        $('#p_slide_prep_sp_id_hr').hide();
        var a = ['p_slide_prep_sp_id', 'pspprice', 'date_8000'];
        for (var i = 0; i < a.length; i++) {
            $('label[for=' + a[i] + '], input#' + a[i] + ', select#' + a[i]).hide();
        }

        $('#p_slide_lab_id_hr').show();
        var a = ['p_slide_lab_id', 'p_slide_lab_price', 'date_10000'];
        for (var i = 0; i < a.length; i++) {
            $('label[for=' + a[i] + '], input#' + a[i] + ', select#' + a[i]).show();
        }


    } else if ((status == 'hideall')) {

        $('#p_cross_section_id_hr').hide();
        var a = ['p_cross_section_id', 'p_cross_section_ass_id', 'date_3000'];
        for (var i = 0; i < a.length; i++) {
            $('label[for=' + a[i] + '], input#' + a[i] + ', select#' + a[i]).hide();
        }

        $('#p_slide_prep_id_hr').hide();
        var a = ['p_slide_prep_id', 'pprice', 'date_6000'];
        for (var i = 0; i < a.length; i++) {
            $('label[for=' + a[i] + '], input#' + a[i] + ', select#' + a[i]).hide();
        }

        $('#p_slide_prep_sp_id_hr').hide();
        var a = ['p_slide_prep_sp_id', 'pspprice', 'date_8000'];
        for (var i = 0; i < a.length; i++) {
            $('label[for=' + a[i] + '], input#' + a[i] + ', select#' + a[i]).hide();
        }

        $('#p_slide_lab_id_hr').hide();
        var a = ['p_slide_lab_id', 'p_slide_lab_price', 'date_10000'];
        for (var i = 0; i < a.length; i++) {
            $('label[for=' + a[i] + '], input#' + a[i] + ', select#' + a[i]).hide();
        }
    } else {
        $('#p_cross_section_id_hr').show();
        var a = ['p_cross_section_id', 'p_cross_section_ass_id', 'date_3000'];
        for (var i = 0; i < a.length; i++) {
            $('label[for=' + a[i] + '], input#' + a[i] + ', select#' + a[i]).show();
        }

        $('#p_slide_prep_id_hr').show();
        var a = ['p_slide_prep_id', 'pprice', 'date_6000'];
        for (var i = 0; i < a.length; i++) {
            $('label[for=' + a[i] + '], input#' + a[i] + ', select#' + a[i]).show();
        }

        $('#p_slide_prep_sp_id_hr').show();
        var a = ['p_slide_prep_sp_id', 'pspprice', 'date_8000'];
        for (var i = 0; i < a.length; i++) {
            $('label[for=' + a[i] + '], input#' + a[i] + ', select#' + a[i]).show();
        }

        $('#p_slide_lab_id_hr').show();
        var a = ['p_slide_lab_id', 'p_slide_lab_price', 'date_10000'];
        for (var i = 0; i < a.length; i++) {
            $('label[for=' + a[i] + '], input#' + a[i] + ', select#' + a[i]).show();
        }

    }

}




function addAction2Flow() {


//รับเข้า ใส่ข้อมูลคนไข้ 1000
    $("#move1000").on("mouseover", function (e) {
        $(this).addClass("heldover");
    });
    $("#move1000").on("mouseout", function (e) {
        $(this).removeClass("heldover");
    });
    $("#move1000").on("click", function (e) {
        e.preventDefault();
        var cur_status = $(".cur_status").attr('tabindex');
        var isset_date_first_report = $(".isset_date_first_report").attr('tabindex');
        var frm = $("<form>");
        frm.attr('method', 'post');
        frm.attr('');
        frm.append('<input type="hidden" name="status" value="1000" /> ');
        frm.append('<input type="hidden" name="cur_status" value="' + cur_status + '" /> ');
        frm.append('<input type="hidden" name="isset_date_first_report" value="' + isset_date_first_report + '" /> ');
        frm.appendTo("body");
        frm.submit();
    });


//ไปวางแผน 2000

    $("#btnmove2000").on("click", function (e) {
        e.preventDefault();
        var cur_status = $(".cur_status").attr('tabindex');
        var isset_date_first_report = $(".isset_date_first_report").attr('tabindex');
        var frm = $("<form>");
        frm.attr('method', 'post');
        frm.attr('');
        frm.append('<input type="hidden" name="status" value="2000" /> ');
        frm.append('<input type="hidden" name="cur_status" value="' + cur_status + '" /> ');
        frm.append('<input type="hidden" name="isset_date_first_report" value="' + isset_date_first_report + '" /> ');

        frm.append('<input type="hidden" name="pautoscroll" value="' + "patient_plan_section" + '" /> ');
        frm.append('<input type="hidden" name="isautoeditmode" value="' + "patient_plan_section" + '" /> ');


        frm.appendTo("body");
        frm.submit();
    });


    //btnmove3000_10000
    $("#btnmove3000_10000").on("click", function (e) {
        e.preventDefault();
        var cur_status = $(".cur_status").attr('tabindex');
        var cur_speciment_type = $(".cur_speciment_type").attr('tabindex');
        //alert("cur_speciment_type "+cur_speciment_type);
        console.log("cur_speciment_type " + cur_speciment_type);
        var isset_date_first_report = $(".isset_date_first_report").attr('tabindex');
        var frm = $("<form>");
        frm.attr('method', 'post');
        frm.attr('');
        if (cur_speciment_type == "lump") {
            frm.append('<input type="hidden" name="status" value="3000" /> ');
            frm.append('<input type="hidden" name="pautoscroll" value="' + "specimen_prep_section" + '" /> ');
            frm.append('<input type="hidden" name="isautoeditmode" value="' + "specimen_prep_section" + '" /> ');
        } else {
            frm.append('<input type="hidden" name="status" value="10000" /> ');
            frm.append('<input type="hidden" name="pautoscroll" value="' + "lab_fluid_section_section" + '" /> ');
            frm.append('<input type="hidden" name="isautoeditmode" value="' + "lab_fluid_section_section" + '" /> ');
        }
        frm.append('<input type="hidden" name="cur_status" value="' + cur_status + '" /> ');
        frm.append('<input type="hidden" name="isset_date_first_report" value="' + isset_date_first_report + '" /> ');
        frm.appendTo("body");
        frm.submit();
    });



//ไปวางแผน 2000
    $("#move2000").on("mouseover", function (e) {
        $(this).addClass("heldover");
    });
    $("#move2000").on("mouseout", function (e) {
        $(this).removeClass("heldover");
    });
    $("#move2000").on("click", function (e) {
        e.preventDefault();
        var cur_status = $(".cur_status").attr('tabindex');
        var isset_date_first_report = $(".isset_date_first_report").attr('tabindex');
        var frm = $("<form>");
        frm.attr('method', 'post');
        frm.attr('');
        frm.append('<input type="hidden" name="status" value="2000" /> ');
        frm.append('<input type="hidden" name="cur_status" value="' + cur_status + '" /> ');
        frm.append('<input type="hidden" name="isset_date_first_report" value="' + isset_date_first_report + '" /> ');
        frm.appendTo("body");
        frm.submit();
    });

//เตรียมชิ้นเนื้อ 3000
    $("#move3000").on("mouseover", function (e) {
        $(this).addClass("heldover");
    });
    $("#move3000").on("mouseout", function (e) {
        $(this).removeClass("heldover");
    });
    $("#move3000").on("click", function (e) {
        e.preventDefault();
        var cur_status = $(".cur_status").attr('tabindex');
        var isset_date_first_report = $(".isset_date_first_report").attr('tabindex');
        var frm = $("<form>");
        frm.attr('method', 'post');
        frm.attr('');
        frm.append('<input type="hidden" name="status" value="3000" /> ');
        frm.append('<input type="hidden" name="cur_status" value="' + cur_status + '" /> ');
        frm.append('<input type="hidden" name="isset_date_first_report" value="' + isset_date_first_report + '" /> ');
        frm.appendTo("body");
        frm.submit();
    });



//เตรียมสไลด์ 6000
    $("#move6000").on("mouseover", function (e) {
        $(this).addClass("heldover");
    });
    $("#move6000").on("mouseout", function (e) {
        $(this).removeClass("heldover");
    });
    $("#move6000").on("click", function (e) {
        e.preventDefault();
        var cur_status = $(".cur_status").attr('tabindex');
        var isset_date_first_report = $(".isset_date_first_report").attr('tabindex');
        var frm = $("<form>");
        frm.attr('method', 'post');
        frm.attr('');
        frm.append('<input type="hidden" name="status" value="6000" /> ');
        frm.append('<input type="hidden" name="cur_status" value="' + cur_status + '" /> ');
        frm.append('<input type="hidden" name="isset_date_first_report" value="' + isset_date_first_report + '" /> ');
        frm.appendTo("body");
        frm.submit();
    });

//เตรียมชิ้นเนื้อพิเศษ 8000
    $("#move8000").on("mouseover", function (e) {
        $(this).addClass("heldover");
    });
    $("#move8000").on("mouseout", function (e) {
        $(this).removeClass("heldover");
    });
    $("#move8000,#btnmove8000").on("click", function (e) {
        e.preventDefault();
        var cur_status = $(".cur_status").attr('tabindex');
        var isset_date_first_report = $(".isset_date_first_report").attr('tabindex');
        var isCurrentPathoIsOwnerThisCase = $(".isCurrentPathoIsOwnerThisCase").attr('tabindex');

        var isset_sp_slide_assigned = '0';
        $('.p_slide_prep_sp_id li').each(function (index) {
            isset_sp_slide_assigned = $(this).attr('tabindex');
        });
//         console.log("isset_second_patho : " + isset_second_patho);
//          return;

//        if (isset_sp_slide_assigned == '0') {
//            alert("กรุณาเลือกคนเตรียมใสล์พิเศษก่อน!");
//            return;
//        }

        if (cur_status == '12000' && isCurrentPathoIsOwnerThisCase == '0') {
            alert("You not have authorize to do this ! Only owner can proceed");
            return;
        }

        var frm = $("<form>");
        frm.attr('method', 'post');
        frm.attr('');
        frm.append('<input type="hidden" name="status" value="8000" /> ');
        frm.append('<input type="hidden" name="cur_status" value="' + cur_status + '" /> ');
        frm.append('<input type="hidden" name="isset_date_first_report" value="' + isset_date_first_report + '" /> ');
        $('.p_slide_prep_sp_id li').each(function (index) {
            frm.append('<input type="hidden" name="p_slide_prep_sp_id" value="' + $(this).attr('tabindex') + '" /> ');
        });

        frm.appendTo("body");
        frm.submit();
    });


//แลปเซลล์วิทยา 10000
    $("#move10000").on("mouseover", function (e) {
        $(this).addClass("heldover");
    });
    $("#move10000").on("mouseout", function (e) {
        $(this).removeClass("heldover");
    });
    $("#move10000").on("click", function (e) {
        e.preventDefault();
        var cur_status = $(".cur_status").attr('tabindex');
        var isset_date_first_report = $(".isset_date_first_report").attr('tabindex');
        var frm = $("<form>");
        frm.attr('method', 'post');
        frm.attr('');
        frm.append('<input type="hidden" name="status" value="10000" /> ');
        frm.append('<input type="hidden" name="cur_status" value="' + cur_status + '" /> ');
        frm.append('<input type="hidden" name="isset_date_first_report" value="' + isset_date_first_report + '" /> ');
        frm.appendTo("body");
        frm.submit();
    });



//วินิจฉัย12000
    $("#move12000").on("mouseover", function (e) {
        $(this).addClass("heldover");
    });
    $("#move12000").on("mouseout", function (e) {
        $(this).removeClass("heldover");
    });
    $("#move12000").on("click", function (e) {
        e.preventDefault();
        var cur_status = $(".cur_status").attr('tabindex');
        var isset_date_first_report = $(".isset_date_first_report").attr('tabindex');
        var isCurrentPathoIsOwnerThisCase = $(".isCurrentPathoIsOwnerThisCase").attr('tabindex');
        if (isCurrentPathoIsOwnerThisCase == '0') {
            alert("You not have authorize to do this ! Only owner can proceed");
            return;
        }

        var frm = $("<form>");
        frm.attr('method', 'post');
        frm.attr('');
        frm.append('<input type="hidden" name="status" value="12000" /> ');
        frm.append('<input type="hidden" name="cur_status" value="' + cur_status + '" /> ');
        frm.append('<input type="hidden" name="isset_date_first_report" value="' + isset_date_first_report + '" /> ');
        frm.appendTo("body");
        frm.submit();
    });

    //btnmove12000
    $("#btnmove12000").on("click", function (e) {
        e.preventDefault();
        var cur_status = $(".cur_status").attr('tabindex');
        var isset_date_first_report = $(".isset_date_first_report").attr('tabindex');
        var isCurrentPathoIsOwnerThisCase = $(".isCurrentPathoIsOwnerThisCase").attr('tabindex');
        if (isCurrentPathoIsOwnerThisCase == '0') {
            alert("You not have authorize to do this ! Only owner can proceed");
            return;
        }

        var frm = $("<form>");
        frm.attr('method', 'post');
        frm.attr('');
        frm.append('<input type="hidden" name="status" value="12000" /> ');
        frm.append('<input type="hidden" name="cur_status" value="' + cur_status + '" /> ');
        frm.append('<input type="hidden" name="isset_date_first_report" value="' + isset_date_first_report + '" /> ');

        frm.append('<input type="hidden" name="pautoscroll" value="' + "diag_result_section" + '" /> ');
        frm.append('<input type="hidden" name="isautoeditmode" value="' + "NA" + '" /> ');


        frm.appendTo("body");
        frm.submit();
    });

    //btnrejto12000
    $("#btnrejto12000").on("click", function (e) {
        e.preventDefault();
        var cur_status = $(".cur_status").attr('tabindex');
        var isset_date_first_report = $(".isset_date_first_report").attr('tabindex');
        var isCurrentPathoIsSecondOwneThisCase = $(".isCurrentPathoIsSecondOwneThisCase").attr('tabindex');
        if (isCurrentPathoIsSecondOwneThisCase == '0') {
            alert("You not have authorize to do this ! Only owner can proceed");
            return;
        }


        //set second_patho_review = 0
        var cur_patient_id = $(".cur_patient_id").attr('tabindex');
        $.ajax({
            type: 'POST',
            // make sure you respect the same origin policy with this url:
            // http://en.wikipedia.org/wiki/Same_origin_policy
            url: '/ajax_patient_diax_result/set_second_patho_review.php',
            data: {
                'patient_id': cur_patient_id,
                'second_patho_review': 0,
            },
            success: function (data) {
                console.log(data);
                alert(data);

            },
            error: function (jqxhr, status, exception) {
                alert('Exception:', exception);
            }
        });


//        //set autoscroll to confirm_result_section
//        var cur_patient_id = $(".cur_patient_id").attr('tabindex');
//        $.ajax({
//            type: 'POST',
//            // make sure you respect the same origin policy with this url:
//            // http://en.wikipedia.org/wiki/Same_origin_policy
//            url: '/ajax_patient_diax_result/set_autoscroll.php',
//            data: {
//                'patient_id': cur_patient_id,
//                'pautoscroll': 'confirm_result_section',
//            },
//            success: function (data) {
//                console.log(data);
//                alert(data);
//
//            },
//            error: function (jqxhr, status, exception) {
//                alert('Exception:', exception);
//            }
//        });




        var frm = $("<form>");
        frm.attr('method', 'post');
        frm.attr('');
        frm.append('<input type="hidden" name="status" value="12000" /> ');
        frm.append('<input type="hidden" name="cur_status" value="' + cur_status + '" /> ');
        frm.append('<input type="hidden" name="isset_date_first_report" value="' + isset_date_first_report + '" /> ');

        frm.append('<input type="hidden" name="pautoscroll" value="' + "diag_result_section" + '" /> ');
        frm.append('<input type="hidden" name="isautoeditmode" value="' + "NA" + '" /> ');


        frm.appendTo("body");
        frm.submit();
    });

    //วินิจฉัย(คอนเฟิร์ม) 13000
    $("#move13000").on("mouseover", function (e) {
        $(this).addClass("heldover");
    });
    $("#move13000").on("mouseout", function (e) {
        $(this).removeClass("heldover");
    });
    $("#move13000, #btn2review13000").on("click", function (e) {
        e.preventDefault();
        var cur_status = $(".cur_status").attr('tabindex');
        var isset_date_first_report = $(".isset_date_first_report").attr('tabindex');
        var isCurrentPathoIsOwnerThisCase = $(".isCurrentPathoIsOwnerThisCase").attr('tabindex');
        
        var cur_patient_id = $(".cur_patient_id").attr('tabindex');

        // Stop move to 13000 when not select Second Patho
//        var isset_second_patho = '0';
//        $('.uresultSecondPatho li').each(function (index) {
//            isset_second_patho = $(this).attr('tabindex');
//        });
//
//        if (isset_second_patho == '0') {
//            alert("<b>กรุณาเลือกแพทย์ผู้ช่วยรีวิวก่อน!</b>");
//            return;
//        }

        // only owner can do this        
        //console.log("isCurrentPathoIsOwnerThisCase :" + isCurrentPathoIsOwnerThisCase);return;
        if (cur_status == '12000' && isCurrentPathoIsOwnerThisCase == '0') {
            alert("You not have authorize to do this ! Only owner can proceed");
            return;
        }

        //set second_patho_review = 1
        $.ajax({
            type: 'POST',
            // make sure you respect the same origin policy with this url:
            // http://en.wikipedia.org/wiki/Same_origin_policy
            url: '/ajax_patient_diax_result/set_second_patho_review.php',
            data: {
                'patient_id': cur_patient_id,
                'second_patho_review': 1,
            },
            success: function (data) {
                console.log(data);
                alert(data);

            },
            error: function (jqxhr, status, exception) {
                alert('Exception:', exception);
            }
        });

        var frm = $("<form>");
        frm.attr('method', 'post');
        frm.attr('');
        frm.append('<input type="hidden" name="status" value="13000" /> ');
        frm.append('<input type="hidden" name="cur_status" value="' + cur_status + '" /> ');
        frm.append('<input type="hidden" name="pautoscroll" value="' + "confirm_result_section" + '" /> ');
        frm.append('<input type="hidden" name="isset_date_first_report" value="' + isset_date_first_report + '" /> ');
        frm.appendTo("body");
        frm.submit();
        
        
        
        
        
    });


    //ออกผล<br>20000</td>
    $("#move20000").on("mouseover", function (e) {
        $(this).addClass("heldover");
    });
    $("#move20000").on("mouseout", function (e) {
        $(this).removeClass("heldover");
    });
    $("#move20000,#btnagreeto20000,#btn_release").on("click", function (e) {
        e.preventDefault();
        
        if (is_diag_editeing_mode) {
            alert("Result data need to save first!");
            return;
        }

        
        var cur_status = $(".cur_status").attr('tabindex');
        var isset_date_first_report = $(".isset_date_first_report").attr('tabindex');
        var isCurrentPathoIsOwnerThisCase = $(".isCurrentPathoIsOwnerThisCase").attr('tabindex');
        var isCurrentPathoIsSecondOwneThisCaseLastest = $(".isCurrentPathoIsSecondOwneThisCaseLastest").attr('tabindex');
        var cur_patient_id = $(".cur_patient_id").attr('tabindex');

        var uresultTypeNameLastest = '';

        $('.uresultTypeName li').each(function (index) {
            uresultTypeNameLastest = $(this).attr('tabindex');
        });
        
        //uresultReleaseType
        var reported_as = 'ยังไม่ออกผล';
        $('.uresultReleaseType li').each(function (index) {
            reported_as = $(this).attr('tabindex');
        });
        $('.uresultReleaseType2 li').each(function (index) {
            reported_as = $(this).attr('tabindex');
        });
        
        var isset_second_patho = '0';
        $('.uresultSecondPatho li').each(function (index) {
            isset_second_patho = $(this).attr('tabindex');
        });

        if (cur_status == '12000' && isCurrentPathoIsOwnerThisCase == '0') {
            alert("You not have authorize to do this ! Only owner can proceed");
            return;
        }

        if (cur_status == '12000' && isset_second_patho != '0') {
            alert("Second patho need to be agree first!");
            return;
        }

        if (cur_status == '13000' && isCurrentPathoIsSecondOwneThisCaseLastest == '0') {
            alert("Only Second patho of this case can do this!");
            return;
        }

        if (confirm("คุณกำลังจะออกผลถึงลูกค้า! \nหากออกผลแล้วคุณจะไม่สามารถแก้ไขผลที่ออกไปแล้วได้")) {
            //txt = "You pressed OK!";
        } else {
            //txt = "You pressed Cancel!";
            return;
        }

//        console.log("uresultTypeNameLastest ::--" + uresultTypeNameLastest +"--");
//        return;
        
//        if (uresultTypeNameLastest == 'Preliminary') {
//            reported_as = 'ออกผลเบื้องต้น';
//        }
//
//        if (uresultTypeNameLastest == 'Pathological Diagnosis' || uresultTypeNameLastest == 'Addendum' || uresultTypeNameLastest == 'Revised') {
//            reported_as = 'ออกผลแล้ว';
//        }





        //set release date for all null result
        
        $.ajax({
            type: 'POST',
            // make sure you respect the same origin policy with this url:
            // http://en.wikipedia.org/wiki/Same_origin_policy
            url: '/ajax_patient_diax_result/setReleaseTimeIfNull.php',
            data: {
                'patient_id': cur_patient_id,
            },
            success: function (data) {
                console.log(data);
                //alert(data);

            },
            error: function (jqxhr, status, exception) {
                alert('Exception:', exception);
            }
        });
        












        //set second_patho_review = 2
        $.ajax({
            type: 'POST',
            // make sure you respect the same origin policy with this url:
            // http://en.wikipedia.org/wiki/Same_origin_policy
            url: '/ajax_patient_diax_result/set_second_patho_review.php',
            data: {
                'patient_id': cur_patient_id,
                'second_patho_review': 2,
            },
            success: function (data) {
                console.log(data);
                //alert(data);

            },
            error: function (jqxhr, status, exception) {
                alert('Exception:', exception);
            }
        });
        

        var frm = $("<form>");
        frm.attr('method', 'post');
        frm.attr('');
        frm.append('<input type="hidden" name="status" value="20000" /> ');
        frm.append('<input type="hidden" name="cur_status" value="' + cur_status + '" /> ');
        frm.append('<input type="hidden" name="isset_date_first_report" value="' + isset_date_first_report + '" /> ');
        //append last update result index to use for update released date.
        $('.uresultinxlist li').each(function (index) {
            frm.append('<input type="hidden" name="uresultinxlist" value="' + $(this).attr('tabindex') + '" /> ');
        });
        //append last update result release date isSet.
        $('.uresultReleaseSetlist li').each(function (index) {
            frm.append('<input type="hidden" name="uresultReleaseSetlist" value="' + $(this).attr('tabindex') + '" /> ');
        });
        //
        //append last update result name.
        $('.uresultTypeName li').each(function (index) {
            frm.append('<input type="hidden" name="uresultTypeName" value="' + $(this).attr('tabindex') + '" /> ');
        });
        frm.append('<input type="hidden" name="reported_as" value="' + reported_as + '" /> ');
        frm.append('<input type="hidden" name="pautoscroll" value="finish_section" /> ');
        frm.appendTo("body");
        frm.submit();
    });


}

function addAction2statusType() {

    if ($('#lumptype').is(':checked')) {
        //alert("เลือกสิ่งส่งตรวจเป็น ชิ้นเนดื้อ");
        showFollowStatus("lump");
    }

    if ($('#fluidtype').is(':checked')) {
        //alert("เลือกสิ่งส่งตรวจเป็น ของเหลว");
        showFollowStatus("fluid");
    }



    $('#lumptype').click(function () {
        if ($(this).is(':checked')) {
            //alert("เลือกสิ่งส่งตรวจเป็น ชิ้นเนดื้อ");
            showFollowStatus("lump");
        }
    });

    $('#fluidtype').click(function () {
        if ($(this).is(':checked')) {
            //alert("เลือกสิ่งส่งตรวจเป็น ของเหลว");
            showFollowStatus("fluid");
        }
    });

}



$(document).ready(function () {



    addAction2Flow();
    addAction2statusType();


    // Draw dtatus from flow chart in patient_edit.php
    $('#flowtab1 tr').each(function () {
        $(this).find('td').each(function (index) {
            var flowtabl_td = $(this);
            $('.movelist li').each(function (index) {
                if (flowtabl_td.attr('tabindex') == $(this).attr('tabindex')) {

                    if ($(this).attr('tabindex') == "1000") {
                        console.log("Set link to " + $(this).attr('tabindex'));
                        $("#keep1000").attr("id", "move1000");
                    }
                    if ($(this).attr('tabindex') == "2000") {
                        console.log("Set link to " + $(this).attr('tabindex'));
                        $("#keep2000").attr("id", "move2000");
                    }
                    if ($(this).attr('tabindex') == "3000") {
                        console.log("Set link to " + $(this).attr('tabindex'));
                        $("#keep3000").attr("id", "move3000");
                    }
                    if ($(this).attr('tabindex') == "6000") {
                        console.log("Set link to " + $(this).attr('tabindex'));
                        $("#keep6000").attr("id", "move6000");
                    }
                    if ($(this).attr('tabindex') == "8000") {
                        console.log("Set link to " + $(this).attr('tabindex'));
                        $("#keep8000").attr("id", "move8000");
                    }
                    if ($(this).attr('tabindex') == "10000") {
                        console.log("Set link to " + $(this).attr('tabindex'));
                        $("#keep10000").attr("id", "move10000");
                    }
                    if ($(this).attr('tabindex') == "12000") {
                        console.log("Set link to " + $(this).attr('tabindex'));
                        $("#keep12000").attr("id", "move12000");
                    }
                    if ($(this).attr('tabindex') == "13000") {
                        console.log("Set link to " + $(this).attr('tabindex'));
                        $("#keep13000").attr("id", "move13000");
                    }
                    if ($(this).attr('tabindex') == "14000") {
                        console.log("Set link to " + $(this).attr('tabindex'));
                        $("#keep14000").attr("id", "move14000");
                    }
                    if ($(this).attr('tabindex') == "20000") {
                        console.log("Set link to " + $(this).attr('tabindex'));
                        $("#keep20000").attr("id", "move20000");
                    }
                }
            });
        })
    });



    var pautoscroll = $(".pautoscroll").attr('tabindex'); // get from patient_edit.php

    console.log("pautoscroll " + pautoscroll);

    // Auto Scroll to
    if (pautoscroll != "NA" && pautoscroll != null && pautoscroll != 0) {
        document.getElementById(pautoscroll).scrollIntoView();
    }

});


//validat form for patient_edit.php
$("#formAddPatient , #patient_detail , #patient_plan , #save_patient_detail , #save_patient_detail_next").validate({
    rules: {

        //==========  a  =============
        //เลขที่ผู้ป่วย
        pnum: {
            required: true
        },
        //LAB Number
        plabnum: {
            required: false
        },
        //วันที่รับ
        date_1000: {
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
            required: false
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
        pspecimen_id: {
            selectd: false
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
        // แลปเซล
        p_slide_lab_id: {
            selectd: true
        },
        // ราคาแลปเซล
        p_slide_lab_price: {
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
        date_12_13_000: {
            required: true
        },
        //==========  c  =============
        //ผลการตรวจ(เพิ่มเติม)
        rs_diagnosis: {
            required: true
        }

    }

});
