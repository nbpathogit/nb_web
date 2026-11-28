
 $.fn.removeClassStartingWith = function (filter) {
    $(this).removeClass(function (index, className) {
        return (className.match(new RegExp("\\S*" + filter + "\\S*", 'g')) || []).join(' ')
    });
    return this;
};


$(document).ready(function () {
    // executes when HTML-Document is loaded and DOM is ready
//    alert("document is ready " + '<?=$back1id?>');

    $("#lodingstatus").remove();
    $("#mainpage").show();
    
    readFirstDataFromDom();
    
    
    $('#phospital_id').selectize({
        sortField: 'text'
    });
    $('#phospital_select_for_price2').selectize({
        sortField: 'text'
    });
//    $('#block_sp2').selectize({
//        sortField: 'text'
//    });//block_sp2

//    $('#date_1000').datepicker().datepicker('setDate', 'today');

//    var myDate = new Date(2000, 11, 31);
//    $('#date_1000').datepicker({dateFormat: 'mm/dd/yyyy'});
//    $('#date_1000').datepicker('setDate', myDate);




//$('#MyElement').addClass('MyClass');
//
//$('#MyElement').removeClass('MyClass');
//
//if ( $('#MyElement').hasClass('MyClass') )

//.bg-primary
//.bg-secondary
//.bg-success
//.bg-danger
//.bg-warning
//.bg-info
//.bg-light
//.bg-dark
//.bg-body
//.bg-white
//.bg-transparent

//bg-
    $(".nb_bg_set").removeClassStartingWith('bg-');
    $('.nb_bg_set').addClass('bg-blue-a');

//Set default for add_patent.php
    //Disable
    $("#prenum").prop("disabled", true);
    $("#yearnum").prop("disabled", true);
    $("#runnum").prop("disabled", true);
    //Enable
    $("#snprefix").prop("disabled", false);






    //on ready for User Form Action handler

    //Check box id username_enable is checked
    if ($('#username_enable').is(':checked')) {
        // Enable #x
        $("#username").prop("disabled", false);
        $("#old_password").prop("disabled", false);
        $("#password").prop("disabled", false);
        $("#set_password_confirm").prop("disabled", false);
        $("#save_userpass").prop("disabled", false);
    } else {
        // Disable #x
        $("#username").prop("disabled", true);
        $("#old_password").prop("disabled", true);
        $("#password").prop("disabled", true);
        $("#set_password_confirm").prop("disabled", true);
        $("#save_userpass").prop("disabled", true);
    }
    
    //Check box enable user detail is checked
    if ($('#userdetail_enable').is(':checked')) {
        
        $(".userdetail_input").prop("disabled", false);
        if(!get_isCurUserCust()){ // Customer cannot change the user group and hospital
             $(".userdetail_input2").prop("disabled", false);
        }
    } else {
        $(".userdetail_input").prop("disabled", true);
        $(".userdetail_input2").prop("disabled", true);
    }


    //Check box id umobile_enable is checked
    if ($('#umobile_enable').is(':checked')) {
        $("#umobile").prop("disabled", false);
    } else {
        $("#umobile").prop("disabled", true);
    }


    //Check box id uemail_enable is checked
    if ($('#uemail_enable').is(':checked')) {
        $("#uemail").prop("disabled", false);
    } else {
        $("#uemail").prop("disabled", true);
    }





    // show message iniValue to page
    document.getElementById("sessioncountdown").value = "iniValue";

    // get sesstion time limit from Server that write to DOM
    var sesstion_timelimit_int_sec = $(".sesstion_timelimit_int_sec").attr('tabindex');

    console.log("sesstion_timelimit_int_sec " + sesstion_timelimit_int_sec);

    setTimeOutLimitIntSecs = parseInt(sesstion_timelimit_int_sec);
    runningWebDownTimeIntSecs = 0;

    const date = new Date();
    curWebTimeIntSecs = Math.floor(date.getTime() / 1000);
    targetWebTimeOutIntSecs = Math.floor(curWebTimeIntSecs + setTimeOutLimitIntSecs);
    runningWebDownTimeIntSecs = Math.floor(targetWebTimeOutIntSecs - curWebTimeIntSecs);
    showTimeRuning(runningWebDownTimeIntSecs);
    console.log("curWebTimeIntSecs:" + curWebTimeIntSecs);
    console.log("runningWebDownTimeIntSecs:" + runningWebDownTimeIntSecs);
    console.log("----");

    setTimeout(decrement, 1000);
    initSessionReloginModal();
    
    if (   typeof carry_msg !== 'undefined') {
        alert(carry_msg);
    }


});




var servtimeRemainIntSecs;

var setTimeOutLimitIntSecs; 
var runningWebDownTimeIntSecs; 

var curWebTimeIntSecs;
var targetWebTimeOutIntSecs;
var sessionReloginModalInstance = null;
var sessionReloginSyncTimer = null;

function decrement() {


    console.log("===========================================");
    
    //update time remain from server every 5 min
    if (
         (displayedHrsInt == 0 && displayedMinInt == 15 && displayedSecsInt == 0)
         ||
         (displayedHrsInt == 0 && displayedMinInt == 10 && displayedSecsInt == 0)
         ||
         (displayedHrsInt == 0 && displayedMinInt == 5 && displayedSecsInt == 0)
         ||
         (displayedHrsInt == 0 && displayedMinInt == 1 && displayedSecsInt == 0)  
         ||
         (displayedHrsInt == 0 && displayedMinInt == 0 && displayedSecsInt <= 2)
            ) {
        
        servtimeRemainIntSecs = parseInt(getSrvTimeRemain());// get time remain from server
        if (!isNaN(servtimeRemainIntSecs)) {
            const date = new Date();
            var curWebTimeIntSecs = Math.floor(date.getTime() / 1000);

            targetWebTimeOutIntSecs = Math.floor(parseInt(curWebTimeIntSecs) + parseInt(servtimeRemainIntSecs));
            console.log("-----------------------");
            console.log("curWebTimeIntSecs "+curWebTimeIntSecs);
            console.log("targetWebTimeOutIntSecs "+targetWebTimeOutIntSecs);
            console.log("servtimeRemainIntSecs "+servtimeRemainIntSecs);
            console.log("-----------------------");
        }

    }

    const date = new Date();
    var curWebTimeIntSecs = Math.floor(date.getTime() / 1000);

    runningWebDownTimeIntSecs = Math.floor(parseInt(targetWebTimeOutIntSecs) - parseInt(curWebTimeIntSecs));

    // กรณีแท็บถูกพัก (Background tab / Sleep) จนเวลาข้ามช่วง <= 2 วินาที ให้ซิงค์กับ Server อีกครั้งก่อนตัดสินว่าหมดเวลา
    if (runningWebDownTimeIntSecs <= -1) {
        var checkRemain = parseInt(getSrvTimeRemain());
        if (!isNaN(checkRemain) && checkRemain > 0) {
            servtimeRemainIntSecs = checkRemain;
            targetWebTimeOutIntSecs = Math.floor(parseInt(curWebTimeIntSecs) + parseInt(servtimeRemainIntSecs));
            runningWebDownTimeIntSecs = Math.floor(parseInt(targetWebTimeOutIntSecs) - parseInt(curWebTimeIntSecs));
        }
    }

    console.log("----");
    console.log("servtimeRemainIntSecs:" + servtimeRemainIntSecs);
    console.log("servtimeRemainIntSecs:" + convertTimeSec2HHMMSS(servtimeRemainIntSecs));

    console.log("----");

    console.log("runningWebDownTimeIntSecs:" + runningWebDownTimeIntSecs);
    console.log("runningWebDownTimeIntSecs:" + convertTimeSec2HHMMSS(runningWebDownTimeIntSecs));

    console.log("----");
    console.log("----");

    if (runningWebDownTimeIntSecs > -1) {
        //Show time in web page
        showTimeRuning(runningWebDownTimeIntSecs);
        setTimeout(decrement, 1000);
    } else {
        // แสดงเวลาเป็น 0:00:00 และเปิด Popup ให้ใส่รหัสผ่านเพื่อทำงานต่อโดยหน้าเว็บไม่หาย
        showTimeRuning(0);
        showSessionReloginModal();
    }
}

function resumeSessionCountdown(newRemainSecs) {
    if (sessionReloginSyncTimer) {
        clearInterval(sessionReloginSyncTimer);
        sessionReloginSyncTimer = null;
    }
    var limitSecs = parseInt(newRemainSecs);
    if (isNaN(limitSecs) || limitSecs <= 0) {
        limitSecs = setTimeOutLimitIntSecs > 0 ? setTimeOutLimitIntSecs : 1200;
    }
    const nowDate = new Date();
    curWebTimeIntSecs = Math.floor(nowDate.getTime() / 1000);
    targetWebTimeOutIntSecs = Math.floor(curWebTimeIntSecs + limitSecs);
    runningWebDownTimeIntSecs = Math.floor(targetWebTimeOutIntSecs - curWebTimeIntSecs);
    showTimeRuning(runningWebDownTimeIntSecs);
    setTimeout(decrement, 1000);
}

function showSessionReloginModal() {
    var modalEl = document.getElementById('sessionReloginModal');
    if (!modalEl) {
        $(window).attr('location', 'logout.php');
        return;
    }

    $('#relogin_error_box').addClass('d-none').text('');
    $('#relogin_password').val('').attr('readonly', 'readonly');

    if (!sessionReloginModalInstance && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        sessionReloginModalInstance = new bootstrap.Modal(modalEl, {
            backdrop: 'static',
            keyboard: false
        });
        modalEl.addEventListener('shown.bs.modal', function () {
            setTimeout(function() {
                $('#relogin_password').val('').removeAttr('readonly').trigger('focus');
            }, 50);
        });
    }

    if (sessionReloginModalInstance) {
        sessionReloginModalInstance.show();
    } else {
        $(modalEl).modal({ backdrop: 'static', keyboard: false });
        $(modalEl).modal('show');
    }

    // ปรับ backdrop ของ Bootstrap ให้เป็นสีดำทึบ 100% ไม่ให้มองเห็นเนื้อหาหน้าเว็บเบื้องหลัง
    setTimeout(function () {
        $('.modal-backdrop').addClass('session-relogin-backdrop');
    }, 10);

    // ตรวจสอบทุก 5 วินาที เผื่อผู้ใช้ล็อกอินต่อเวลาจากอีกแท็บหนึ่ง
    if (sessionReloginSyncTimer) {
        clearInterval(sessionReloginSyncTimer);
    }
    sessionReloginSyncTimer = setInterval(function () {
        $.ajax({
            type: 'POST',
            global: false,
            dataType: 'html',
            url: 'ajax_timeout/getSrvTimeRemain.php',
            data: { patient_id: 1 },
            success: function (data) {
                var remain = parseInt(data);
                if (!isNaN(remain) && remain > 2) {
                    if (sessionReloginModalInstance) {
                        sessionReloginModalInstance.hide();
                    } else {
                        $(modalEl).modal('hide');
                    }
                    resumeSessionCountdown(remain);
                }
            }
        });
    }, 5000);
}

function initSessionReloginModal() {
    $('#sessionReloginForm').on('submit', function (e) {
        e.preventDefault();

        var $btn = $('#btn_relogin_submit');
        var $errBox = $('#relogin_error_box');
        var passwordVal = $('#relogin_password').val();

        if (!passwordVal) {
            $errBox.removeClass('d-none').text('กรุณากรอกรหัสผ่าน');
            $('#relogin_password').trigger('focus');
            return;
        }

        $errBox.addClass('d-none').text('');
        var origBtnHtml = $btn.html();
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>กำลังตรวจสอบ...');

        var currentSkey = (typeof skey !== 'undefined' && skey) ? skey : $('#relogin_skey').val();

        $.ajax({
            type: 'POST',
            url: 'ajax_timeout/relogin.php',
            dataType: 'json',
            data: {
                username: $('#relogin_username').val(),
                password: passwordVal,
                subfolder: $('#relogin_subfolder').val(),
                skey: currentSkey,
                adminusername: $('#relogin_adminusername').val(),
                ugroup_id: $('#relogin_ugroup_id').val()
            },
            success: function (res) {
                $btn.prop('disabled', false).html(origBtnHtml);
                if (res && res.status === 'success') {
                    if (res.skey && typeof skey !== 'undefined') {
                        skey = res.skey;
                    }
                    var modalEl = document.getElementById('sessionReloginModal');
                    if (sessionReloginModalInstance) {
                        sessionReloginModalInstance.hide();
                    } else if (modalEl) {
                        $(modalEl).modal('hide');
                    }
                    $('#relogin_password').val('').attr('readonly', 'readonly');
                    resumeSessionCountdown(res.timeRemain);
                } else {
                    var msg = (res && res.message) ? res.message : 'รหัสผ่านไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง';
                    $errBox.removeClass('d-none').text(msg);
                    $('#relogin_password').val('').trigger('focus');
                }
            },
            error: function () {
                $btn.prop('disabled', false).html(origBtnHtml);
                $errBox.removeClass('d-none').text('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้ กรุณาลองใหม่อีกครั้ง');
            }
        });
    });
}



function showTimeRuning(runningWebDownTimeIntSecs) {

    document.getElementById("sessioncountdown").innerHTML = convertTimeSec2HHMMSS(runningWebDownTimeIntSecs);


}

function getSrvTimeRemain() {
    var SrvTimeRemain;
    $.ajax({
        'async': false,
        type: 'POST',
        'global': false,
        'dataType': 'html',
        url: "ajax_timeout/getSrvTimeRemain.php",
        data: {
            patient_id: 1,
        },
        success: function (data) {

            SrvTimeRemain = data;
        }
    });
    return SrvTimeRemain;
}







var displayedHrsInt;
var displayedMinInt;
var displayedSecsInt;
function convertTimeSec2HHMMSS(runningWebDownTimeIntSecs) {

    var displayedSecs = runningWebDownTimeIntSecs % 60;
    var displayedMin = Math.floor(runningWebDownTimeIntSecs / 60) % 60;
    var displayedHrs = Math.floor(runningWebDownTimeIntSecs / 60 / 60);
    displayedSecsInt = parseInt(displayedSecs);
    displayedMinInt = parseInt(displayedMin);
    displayedHrsInt = parseInt(displayedHrs);

    if (displayedMin <= 9)
        displayedMin = "0" + displayedMin;
    if (displayedSecs <= 9)
        displayedSecs = "0" + displayedSecs;

    return displayedHrs + ":" + displayedMin + ":" + displayedSecs;

}




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
    } else {
        // Disable #x
        $("#p_slide_prep_sp_id").prop("disabled", true);
        $("#pspprice").prop("disabled", true);
    }

});

$('#autogen').change(function () {
    if (this.checked) {
        //Disable
        $("#prenum").prop("disabled", true);
        $("#yearnum").prop("disabled", true);
        $("#runnum").prop("disabled", true);
        //Enable
        $("#snprefix").prop("disabled", false);

    }

});


$('#manualgen').change(function () {
    if (this.checked) {
        //Enable
        $("#prenum").prop("disabled", false);
        $("#yearnum").prop("disabled", false);
        $("#runnum").prop("disabled", false);
        //Disable
        $("#snprefix").prop("disabled", true);

    }

});

$('#date_1000').on('blur', function () {
//      if($(this).val().trim().length === 0){
    //$(this).val("01/05/2022");
//      }
});


//User Form Action handler

//Check box id username_enable is checked
$('#username_enable').change(function () {
    if (this.checked) {
        $("#username").prop("disabled", false);
        $("#old_password").prop("disabled", false);
        $("#password").prop("disabled", false);
        $("#set_password_confirm").prop("disabled", false);
        $("#save_userpass").prop("disabled", false);
    } else {
        // Disable #x
        $("#username").prop("disabled", true);
        $("#old_password").prop("disabled", true);
        $("#password").prop("disabled", true);
        $("#set_password_confirm").prop("disabled", true);
        $("#save_userpass").prop("disabled", true);
    }
});

//Check box enable user detail is checked
$('#userdetail_enable').change(function () {
    if (this.checked) {
        $(".userdetail_input").prop("disabled", false);
        if(!get_isCurUserCust()){// Customer cannot change the user group and hospital
             $(".userdetail_input2").prop("disabled", false);
        }
    } else {
        $(".userdetail_input").prop("disabled", true);
        $(".userdetail_input2").prop("disabled", true);
    }
});

//Check box id umobile_enable is checked
$('#umobile_enable').change(function () {
    if (this.checked) {
        $("#umobile").prop("disabled", false);
    } else {
        $("#umobile").prop("disabled", true);
    }
});

//Check box id uemail_enable is checked
$('#uemail_enable').change(function () {
    if (this.checked) {
        $("#uemail").prop("disabled", false);
    } else {
        $("#uemail").prop("disabled", true);
    }
});


$.validator.addMethod("pgendered", function (value, element) {
    return (value != "กรุณาเลือก");
}, "Must be selected.");


$.validator.addMethod("selectd", function (value, element) {
    return (value != 0);
}, "Must be selected.");

$.validator.addMethod("txtarearequired", function (value, element) {
    return (value.length != 0);
}, "Data require to fill in.");



$("#adduser , #chg_passwrd ,#edituser").validate({
    rules: {

        ugroup_id: {
            selectd: true
        },

        uhospital_id: {
            selectd: true
        },
        
        ugroup_id_user_add: {
            selectd: true
        },

        uhospital_id_user_add: {
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

$("#addtemplate").validate({
    rules: {

        rname_selected: {
            selectd: true
        },

        tname: {
            required: true
        },

        tdescription: {
            required: true
        }

    }

});


$("#add_u_result").validate({
    rules: {

        result_type: {
            selectd: true
        }
    }

});


//$("#formAddPatient").validate({
//    rules: {
//        prenum: {
//            required: true,
//            minlength: 2,
//        },
//        yearnum: {
//            required: true,
//            digits: true,
//            minlength: 2,
//        },
//        runnum: {
//            required: true,
//            digits: true,
//            minlength: 5
//        },
//        plastname: {
//            required: true,
//        },
//        pname: {
//            required: true,
//        },
//
//    }
//
//});
