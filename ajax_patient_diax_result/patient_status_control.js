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
//            alert("กรุณาเลือกคนเตรียมสไลด์พิเศษก่อน!");
//            return;
//        }

        if (cur_status == '12000' && isCurrentPathoIsOwnerThisCase == '0') {
            alert("You not have authorize to do this ! Only owner can proceed");
            return;
        }
        
        
        
        
        
        
        let cur_patient_id = $(".cur_patient_id").attr('tabindex');
        let lastest_secondP_userid = get_lastest_SecondPatho_userid_in_uresult();
        let lastest_result_id = get_lastest_uresultid();
        let lastest_job6_id = get_lastest_job6_id();
        let cur_user_id = get_cur_user_id();
        
        let comment = $("#p_sp_patho_comment").val();
        
//        alert("lastest_secondP_userid = "+lastest_secondP_userid+" and lastest_result_id= "+lastest_result_id+" lastest_job6_id="+lastest_job6_id);
//        return;

        //set second_patho_review = 1
//        $.ajax({
//            type: 'POST',
//            // make sure you respect the same origin policy with this url:
//            // http://en.wikipedia.org/wiki/Same_origin_policy
//            url: 'ajax_job4_prep_sp_slide/set_request_sp_slide.php',
//            data: {
//                'patient_id': cur_patient_id,
//                'request_sp_slide': 1,
////                'result_id': lastest_result_id,
////                'user_id': lastest_secondP_userid,   //Allow to add user later
////                'job6_id': lastest_job6_id,            //Allow to add job later
//            },
//            success: function (data) {
//                console.log(data);
////                alert(data);
//                if (data[0] != "[") {
//                    alert(data);
//                }
//
//
//            },
//            error: function (jqxhr, status, exception) {
//                alert('Exception:', exception);
//            }
//        });

        
        
        
        //====Check Whether Data Ready to Proceed======
        
        let sp2_tr = [];
        let sp2_td = [];
        
        let spcimen_list2_scope = document.getElementById("spcimen_list_table2");
        let taginside_tr = spcimen_list2_scope.getElementsByTagName("tr");
        let spcimen_list2_count = 0;
        for (let i = 0; i < taginside_tr.length; i++) {
            if(i==0){continue};
            spcimen_list2_count = spcimen_list2_count + 1;
            console.log(taginside_tr[i].textContent);
            let taginside_td=taginside_tr[i].getElementsByTagName("td");
            sp2_td = [];
            for(let j = 0;  j<taginside_td.length ; j++ ){
                console.log(taginside_td[j].textContent);
                sp2_td.push(taginside_td[j].textContent);
            }
            sp2_tr.push(sp2_td);
        }
        //
        
        console.log(sp2_tr);
//        return null;
        //owner_job4
//        let owner_job4_scope = document.getElementById("owner_job4");
//        let taginside2 = owner_job4_scope.getElementsByTagName("span");
//        let owner_job4_count = 0;
//        for (i = 0; i < taginside2.length; i++) {
//            owner_job4_count = owner_job4_count + 1;
//            console.log(taginside2[i].textContent);
//        }
        
        if(spcimen_list2_count == 0){
            alert('ยังไม่มีรายการเตรวจพิเศษเลือกไว้');
            return null;
        }
//        if(owner_job4_count == 0){
//            //alert('ยังไม่ได้เลือกพนักงานเตรียมสไลด์พิเศษ');
//            //return null;
//        }
        

//        alert("a");
//        return null;

        
        
        
        //====Create Request ID======
        $.ajax({
            type: 'POST',
            'async': false,
            url: 'ajax_job4_prep_sp_slide/new_request_sp_slide.php',
            data: {
                'patient_id': cur_patient_id,
                'cur_user_id':cur_user_id,
                'comment': comment
            },
            success: function (data) {

                console.log(data);
                if (data[0] != "[") {
                    alert(data);
                }
                //return null;

                //repaintspecimentable2(data);
            },
            error: function (jqxhr, status, exception) {
                alert('Exception:', exception);
            }
        });
        
         refreshTblJob4(false);
         refreshSpecimenList2(false);
        
        
        
        
        
        
        
        
        
        
        $('.sp_slide_req_btn').attr({"aria-disabled":"true"});
//        $('.sp_slide_req_btn').prop({"class":"disable"});
        $('.sp_slide_req_btn').addClass('disabled');
        
        $('#btnmove8000').prop('disabled',true);
        
        
        $('#btnfinish8000').prop('disabled',false);
        
        $("#sp_status_message h3").remove();
        $("#sp_status_message").append('<h3 align="center" style="color: #ff8000;font-weight: bold;">ร้องขอย้อมพิเศษ</h3>');
        $("#p_sp_patho_comment").val("");
        refreshSpSlideRequested(false);
        
        alert('done');
        


//        var frm = $("<form>");
//        frm.attr('method', 'post');
//        frm.attr('');
//        frm.append('<input type="hidden" name="status" value="8000" /> ');
//        frm.append('<input type="hidden" name="cur_status" value="' + cur_status + '" /> ');
//        frm.append('<input type="hidden" name="isset_date_first_report" value="' + isset_date_first_report + '" /> ');
//        $('.p_slide_prep_sp_id li').each(function (index) {
//            frm.append('<input type="hidden" name="p_slide_prep_sp_id" value="' + $(this).attr('tabindex') + '" /> ');
//        });
//
//        frm.appendTo("body");
//        frm.submit();
        
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

        frm.append('<input type="hidden" name="pautoscroll" value="' + "confirm_result_section_bottom" + '" /> ');
        frm.append('<input type="hidden" name="isautoeditmode" value="' + "NA" + '" /> ');


        frm.appendTo("body");
        frm.submit();
    });

    //btnrejto12000
    $("#btnrejto12000").on("click", function (e) {
        e.preventDefault();
        var cur_status = $(".cur_status").attr('tabindex');
        var isset_date_first_report = $(".isset_date_first_report").attr('tabindex');
//        var isCurrentPathoIsSecondOwneThisCase = $(".isCurrentPathoIsSecondOwneThisCase").attr('tabindex');
//        var isCurrentPathoIsSecondOwneThisCaseForPN = $(".isCurrentPathoIsSecondOwneThisCaseForPN").attr('tabindex');
//        if (isCurrentPathoIsSecondOwneThisCase == '0' || isCurrentPathoIsSecondOwneThisCaseForPN == '0') {
//            alert("You not have authorize to do this ! Only owner can proceed");
//            return;
//        }

        var lastest_secondP_userid = get_lastest_SecondPatho_userid_in_uresult();
        var lastest_result_id = get_lastest_uresultid();
        var lastest_job6_id = get_lastest_job6_id();
        
        //set second_patho_review = 0
        var cur_patient_id = $(".cur_patient_id").attr('tabindex');
        $.ajax({
            type: 'POST',
            // make sure you respect the same origin policy with this url:
            // http://en.wikipedia.org/wiki/Same_origin_policy
            url: 'ajax_patient_diax_result/set_second_patho_review.php',
            data: {
                'patient_id': cur_patient_id,
                'second_patho_review': 0,
                
                
                                    'patient_id': cur_patient_id,
                    'second_patho_review': 0,
                    'result_id': lastest_result_id,
                    'user_id': lastest_secondP_userid,
                    'job6_id': lastest_job6_id,
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
        if (isCurrentPathoIsOwnerThisCase == '0') {
            alert("You not have authorize to do this ! Only owner can proceed");
            return;
        }
        
        var lastest_secondP_userid = get_lastest_SecondPatho_userid_in_uresult();
        var lastest_result_id = get_lastest_uresultid();
        var lastest_job6_id = get_lastest_job6_id();
        
//        alert("lastest_secondP_userid = "+lastest_secondP_userid+" and lastest_result_id= "+lastest_result_id+" lastest_job6_id="+lastest_job6_id);
//        return;

        //set second_patho_review = 1
        $.ajax({
            type: 'POST',
            // make sure you respect the same origin policy with this url:
            // http://en.wikipedia.org/wiki/Same_origin_policy
            url: 'ajax_patient_diax_result/set_second_patho_review.php',
            data: {
                'patient_id': cur_patient_id,
                'second_patho_review': 1,
                'result_id': lastest_result_id,
                'user_id': lastest_secondP_userid,
                'job6_id': lastest_job6_id,
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
        //frm.attr('action', 'patient_edit.php');
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
        
        if (get_spcimen_list1_count()==0) {
            alert("Please Add Speciment price before release");
            return;
        }

        
        var cur_status = $(".cur_status").attr('tabindex');
        var isset_date_first_report = $(".isset_date_first_report").attr('tabindex');
        var isCurrentPathoIsOwnerThisCase = $(".isCurrentPathoIsOwnerThisCase").attr('tabindex');
        var isCurrentPathoIsSecondOwneThisCaseLastest = $(".isCurrentPathoIsSecondOwneThisCaseLastest").attr('tabindex');
        var isCurrentPathoIsSecondOwneThisCaseLastestForPN = $(".isCurrentPathoIsSecondOwneThisCaseLastestForPN").attr('tabindex');
        var cur_patient_id = get_cur_patient_id();

        //Uresult index id lastest
        var uresultid = get_lastest_uresultid();


        //Pathological Diagnosis,Provisional Diagnosis, Specimen , Clinical Diagnosis , Gross Description , Microscopic Description
        var uresultTypeNameLastest = '';
        $('.uresultTypeName li').each(function (index) {
            uresultTypeNameLastest = $(this).attr('tabindex');
        });
        $('.uresultTypeName2 li').each(function (index) {
            uresultTypeNameLastest = $(this).attr('tabindex');
        });
        
        //uresultReleaseType  //List of release_type ยังไม่ออกผล/ออกผลแล้ว/ออกผลเบื้องต้น
        var reported_as = 'ยังไม่ออกผล';
        $('.uresultReleaseType li').each(function (index) {
            reported_as = $(this).attr('tabindex');
        });
        $('.uresultReleaseType2 li').each(function (index) {
            reported_as = $(this).attr('tabindex');
        });
        
        
        
     
        
        let lastest_SecondPatho_userid = get_lastest_SecondPatho_userid_in_uresult();
        
//        alert("lastest_SecondPatho_userid:"+lastest_SecondPatho_userid);

        if (cur_status == '12000' && isCurrentPathoIsOwnerThisCase == '0') {
            alert("You not have authorize to do this ! Only owner can proceed");
            return;
        }

        if (cur_status == '12000' && lastest_SecondPatho_userid != '0') {
            alert("Second patho need to be agree first!");
            return;
        }
//        alert('dbg');
//        return;
        
        if (cur_status == '13000')
        {
            console.log('isCurrentPathoIsSecondOwneThisCaseLastest::'+isCurrentPathoIsSecondOwneThisCaseLastest);
            if (!(isCurrentPathoIsSecondOwneThisCaseLastest == '1' || isCurrentPathoIsSecondOwneThisCaseLastestForPN == '1')) {
                alert("Only Second patho of this case can do this!");
                return;
            }
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




        let importdate = get_cur_date_1000();
        //set release date for all null result
//        console.log('==========================================================================');
//        console.log('import date = '+importdate);
//        console.log('==========================================================================');
//        return;
//        
//        Set first Date release field
        $.ajax({
            type: 'POST',
            // make sure you respect the same origin policy with this url:
            // http://en.wikipedia.org/wiki/Same_origin_policy
            url: 'ajax_patient_diax_result/setReleaseTimeIfNull.php',
            data: {
                'patient_id': cur_patient_id,
                'importdate': importdate,
            },
            success: function (data) {
                console.log('data='+data);
                //alert('data='+data);

            },
            error: function (jqxhr, status, exception) {
                alert('Exception:', exception);
            }
        });
        
        //return;








        var lastest_secondP_userid = get_lastest_SecondPatho_userid_in_uresult();
        var lastest_result_id = get_lastest_uresultid();
        var lastest_job6_id = get_lastest_job6_id();
        

        // If Second Reviewer, set field second reviewer first
        if (cur_status == "13000") {
            //set second_patho_review = 2
            $.ajax({
                type: 'POST',
                url: 'ajax_patient_diax_result/set_second_patho_review.php',
                data: {
                    'patient_id': cur_patient_id,
                    'second_patho_review': 2,
                    'result_id': lastest_result_id,
                    'user_id': lastest_secondP_userid,
                    'job6_id': lastest_job6_id,
                },
                success: function (data) {
                    console.log(data);
                    //alert(data);

                },
                error: function (jqxhr, status, exception) {
                    alert('Exception:', exception);
                }
            });
        }

        // Prepare the form and then submit on the fly.
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
        frm.append('<input type="hidden" name="auto_release_pdf" value="1" /> ');
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

$("#btnfinish8000").on("click", function (e) {
        e.preventDefault();
        var cur_patient_id = get_cur_patient_id();
        $.ajax({
            type: 'POST',
            // make sure you respect the same origin policy with this url:
            // http://en.wikipedia.org/wiki/Same_origin_policy
            url: 'ajax_job4_prep_sp_slide/set_request_sp_slide.php',
            data: {
                'patient_id': cur_patient_id,
                'request_sp_slide': 2,
//                'result_id': lastest_result_id,
//                'user_id': lastest_secondP_userid,   //Allow to add user later
//                'job6_id': lastest_job6_id,            //Allow to add job later
            },
            success: function (data) {
                console.log(data);
//                alert(data);

            },
            error: function (jqxhr, status, exception) {
                alert('Exception:', exception);
            }
        });
        $("#sp_status_message h3").remove();
        $("#sp_status_message").append('<h3 align="center" style="color: #30A64A;font-weight: bold;">เสร็จสิ้นย้อมพิเศษ</h3>');
        $("#btnfinish8000").prop('disabled',true);
        alert('done');
        

    });


//validat form for patient_edit.php
$("#patient_detail , #patient_plan , #save_patient_detail , #save_patient_detail_next").validate({
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
