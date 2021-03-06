

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

function addAction2statusType() {

    if ($('#lumptype').is(':checked')) {
        //alert("???????????????????????????????????????????????????????????? ??????????????????????????????");
        showFollowStatus("lump");
    }

    if ($('#fluidtype').is(':checked')) {
        //alert("???????????????????????????????????????????????????????????? ?????????????????????");
        showFollowStatus("fluid");
    }



    $('#lumptype').click(function () {
        if ($(this).is(':checked')) {
            //alert("???????????????????????????????????????????????????????????? ??????????????????????????????");
            showFollowStatus("lump");
        }
    });

    $('#fluidtype').click(function () {
        if ($(this).is(':checked')) {
            //alert("???????????????????????????????????????????????????????????? ?????????????????????");
            showFollowStatus("fluid");
        }
    });

}

function addAction2Flow() {


//??????????????????????????? 1000
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

//????????????????????? 2000
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

//????????????????????????????????????????????? 3000
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



//????????????????????????????????? 6000
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

//???????????????????????????????????????????????????????????? 8000
    $("#move8000").on("mouseover", function (e) {
        $(this).addClass("heldover");
    });
    $("#move8000").on("mouseout", function (e) {
        $(this).removeClass("heldover");
    });
    $("#move8000").on("click", function (e) {
        e.preventDefault();
        var cur_status = $(".cur_status").attr('tabindex');
        var isset_date_first_report = $(".isset_date_first_report").attr('tabindex');
        var isCurrentPathoIsOwnerThisCase = isCurrentPathoIsOwnerThisCase = $(".isCurrentPathoIsOwnerThisCase").attr('tabindex');  

        var isset_sp_slide_assigned = '0';
        $('.p_slide_prep_sp_id li').each(function (index) {
            isset_sp_slide_assigned = $(this).attr('tabindex');
        });
//         console.log("isset_second_patho : " + isset_second_patho);
//          return;
        if (isset_sp_slide_assigned == '0') {
            alert("Person to prepare slide not selected Yet!");
            return;
        }
        
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


//??????????????????????????????????????? 10000
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



//????????????????????????12000
    $("#move12000").on("mouseover", function (e) {
        $(this).addClass("heldover");
    });
    $("#move12000").on("mouseout", function (e) {
        $(this).removeClass("heldover");
    });
    $("#move12000,#btnmove12000").on("click", function (e) {
        e.preventDefault();
        var cur_status = $(".cur_status").attr('tabindex');
        var isset_date_first_report = $(".isset_date_first_report").attr('tabindex');
        
        
        
        var frm = $("<form>");
        frm.attr('method', 'post');
        frm.attr('');
        frm.append('<input type="hidden" name="status" value="12000" /> ');
        frm.append('<input type="hidden" name="cur_status" value="' + cur_status + '" /> ');
        frm.append('<input type="hidden" name="isset_date_first_report" value="' + isset_date_first_report + '" /> ');
        frm.appendTo("body");
        frm.submit();
    });

    //????????????????????????(???????????????????????????) 13000
    $("#move13000").on("mouseover", function (e) {
        $(this).addClass("heldover");
    });
    $("#move13000").on("mouseout", function (e) {
        $(this).removeClass("heldover");
    });
    $("#move13000, #btnmove13000").on("click", function (e) {
        e.preventDefault();
        var cur_status = $(".cur_status").attr('tabindex');
        var isset_date_first_report = $(".isset_date_first_report").attr('tabindex');
        var isCurrentPathoIsOwnerThisCase = isCurrentPathoIsOwnerThisCase = $(".isCurrentPathoIsOwnerThisCase").attr('tabindex');

        // Stop move to 13000 when not select Second Patho
        var isset_second_patho = '0';
        $('.uresultSecondPatho li').each(function (index) {
            isset_second_patho = $(this).attr('tabindex');
        });
//         console.log("isset_second_patho : " + isset_second_patho);
//          return;
        if (isset_second_patho == '0') {
            alert("Second Patho not selected Yet!");
            return;
        }
        
        // only owner can do this        
        //console.log("isCurrentPathoIsOwnerThisCase :" + isCurrentPathoIsOwnerThisCase);return;
        if (cur_status == '12000' && isCurrentPathoIsOwnerThisCase == '0') {
            alert("You not have authorize to do this ! Only owner can proceed");
            return;
        }

        
        
        var frm = $("<form>");
        frm.attr('method', 'post');
        frm.attr('');
        frm.append('<input type="hidden" name="status" value="13000" /> ');
        frm.append('<input type="hidden" name="cur_status" value="' + cur_status + '" /> ');
        frm.append('<input type="hidden" name="isset_date_first_report" value="' + isset_date_first_report + '" /> ');
        frm.appendTo("body");
        frm.submit();
    });


    //???????????????<br>20000</td>
    $("#move20000").on("mouseover", function (e) {
        $(this).addClass("heldover");
    });
    $("#move20000").on("mouseout", function (e) {
        $(this).removeClass("heldover");
    });
    $("#move20000,#btnmove20000").on("click", function (e) {
        e.preventDefault();
        var cur_status = $(".cur_status").attr('tabindex');
        var isset_date_first_report = $(".isset_date_first_report").attr('tabindex');
        var isCurrentPathoIsOwnerThisCase = $(".isCurrentPathoIsOwnerThisCase").attr('tabindex');

        var uresultTypeNameLastest = '';
        $('.uresultTypeName li').each(function (index) {
            uresultTypeNameLastest = $(this).attr('tabindex');
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
        
        if (confirm("?????????????????????????????????????????????! \n????????????????????????????????????????????????????????????????????????????????????????????? \n??????????????????????????????????????????????????????????????????????????????????????????????????? pdf ?????????")) {
            //txt = "You pressed OK!";
        } else {
            //txt = "You pressed Cancel!";
            return;
        }

//        console.log("uresultTypeNameLastest ::--" + uresultTypeNameLastest +"--");
//        return;
        var reported_as = '?????????????????????????????????';
        if ( uresultTypeNameLastest == 'Preliminary' ) {
            reported_as = '??????????????????????????????????????????';
        }
        
        if (uresultTypeNameLastest == 'Pathological Diagnosis' || uresultTypeNameLastest == 'Addendum' || uresultTypeNameLastest == 'Revised' ) {
            reported_as = '???????????????????????????';
        }
        
        
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
        frm.appendTo("body");
        frm.submit();
    });


}

$(document).ready(function () {
    // executes when HTML-Document is loaded and DOM is ready
//    alert("document is ready " + '<?=$back1id?>');

    $("#lodingstatus").remove();
    $("#mainpage").show();

//    $('#date_1000').datepicker().datepicker('setDate', 'today');

//    var myDate = new Date(2000, 11, 31);
//    $('#date_1000').datepicker({dateFormat: 'mm/dd/yyyy'});
//    $('#date_1000').datepicker('setDate', myDate);






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



    addAction2Flow();



    addAction2statusType();

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

$('#sp_slide_owner').change(function () {
    if (this.checked) {

        // Enable #x
        $("#p_slide_prep_sp_id").prop("disabled", false);
        $("#pspprice").prop("disabled", false);
    }else{
        // Disable #x
        $("#p_slide_prep_sp_id").prop("disabled", true);
        $("#pspprice").prop("disabled", true);
    }
    
});

$('#date_1000').on('blur', function () {
//      if($(this).val().trim().length === 0){
    //$(this).val("01/05/2022");
//      }
});

$.validator.addMethod("pgendered", function (value, element) {
    return (value != "??????????????????????????????");
}, "Must be selected.");


$.validator.addMethod("selectd", function (value, element) {
    return (value != 0);
}, "Must be selected.");

$("#formAddPatient , #formEditPatient").validate({
    rules: {

        //==========  a  =============
        //???????????????????????????????????????
        pnum: {
            required: true
        },
        //LAB Number
        plabnum: {
            required: false
        },
        //???????????????????????????
        date_1000: {
            required: true
        },
//        ?????????
        pgender: {
            pgendered: true
        },
        //?????????????????????????????????
        pname: {
            required: true
        },
        //?????????????????????
        plastname: {
            required: true
        },
        //????????????
        pedge: {
            required: true
        },
        //?????????????????????????????????????????????
        phospital_num: {
            required: true
        },
        //???????????????????????????
        phospital_id: {
            selectd: true
        },
        //?????????????????????????????????
        pclinician_id: {
            selectd: true
        },
        //?????????????????????????????????
        pspecimen_id: {
            selectd: false
        },
        //???????????????????????????
        priority_id: {
            selectd: true
        },

        //==========  b  =============
        //???????????????
        status_id: {
            selectd: true
        },
        //?????????????????????????????????????????????
        p_cross_section_id: {
            selectd: true
        },
        //??????????????????????????????????????????????????????????????????
        p_cross_section_ass_id: {
            selectd: true
        },
        //??????????????????????????????????????????????????????

        p_slide_prep_id: {
            selectd: true
        },
        //?????????????????????????????????(?????????)
        pprice: {
            required: true
        },
        //??????????????????????????????????????????????????????????????????
        p_slide_prep_sp_id: {
            selectd: true
        },
        //????????????????????????????????????????????????(?????????)
        pspprice: {
            required: true
        },
        // ??????????????????
        p_slide_lab_id: {
            selectd: true
        },
        // ??????????????????????????????
        p_slide_lab_price: {
            required: true
        },
        //==========  c  =============

        //??????????????????????????????????????????????????????
        ppathologist_id: {
            selectd: true
        },

        //???????????????????????????????????????????????????????????????
        ppathologist2_id: {
            selectd: true
        },

        //??????????????????????????????????????????
        date_12_13_000: {
            required: true
        },
        //==========  c  =============
        //???????????????????????????(???????????????????????????)
        rs_diagnosis: {
            required: true
        }

    }

});


$("#adduser , #chg_passwrd").validate({
    rules: {

        ugroup_id: {
            selectd: true
        },

        uhospital_id: {
            selectd: true
        },

        username: {
            required: true
        },

        password: {
            minlength: 5,
            required: true
        },
        set_password_confirm: {
            minlength: 5,
            required: true,
            equalTo: "#password"
        }

    }

});

$("#edituser").validate({
    rules: {

        ugroup_id: {
            selectd: true
        },

        uhospital_id: {
            selectd: true
        },

        username: {
            required: true
        },

    }

});

$("#add_u_result").validate({
    rules: {

        result_type: {
            selectd: true
        }
    }

});

//function myHide(){
//    document.getElementById('mainpage').style.display = 'block';//content ???????????????????????????????????????????????????????????????????????????????????????????????????
//    document.getElementById('lodingstatus').style.display = 'none';//content ????????????????????????????????????????????????????????????????????????????????????
//}



//$('#sandbox-container .input-group.date').datepicker({
//    daysOfWeekHighlighted: "1,2,3,4,5",
//    todayHighlight: true
//});

