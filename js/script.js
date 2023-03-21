
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
    } else {
        $(".userdetail_input").prop("disabled", true);
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


});




var servtimeRemainIntSecs;

var setTimeOutLimitIntSecs; 
var runningWebDownTimeIntSecs; 

var curWebTimeIntSecs;
var targetWebTimeOutIntSecs;

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
        //
        //
        //assigned server to local.
        //runningWebDownTimeIntSecs = servtimeRemainIntSecs;
        const date = new Date();
        var curWebTimeIntSecs = Math.floor(date.getTime() / 1000);

        targetWebTimeOutIntSecs = Math.floor(parseInt(curWebTimeIntSecs) + parseInt(servtimeRemainIntSecs));
        console.log("-----------------------");
        console.log("curWebTimeIntSecs "+curWebTimeIntSecs);
        console.log("targetWebTimeOutIntSecs "+targetWebTimeOutIntSecs);
        console.log("servtimeRemainIntSecs "+servtimeRemainIntSecs);
        console.log("-----------------------");

    }

    const date = new Date();
    var curWebTimeIntSecs = Math.floor(date.getTime() / 1000);

    runningWebDownTimeIntSecs = Math.floor(parseInt(targetWebTimeOutIntSecs) - parseInt(curWebTimeIntSecs));

    console.log("----");
    console.log("servtimeRemainIntSecs:" + servtimeRemainIntSecs);
    console.log("servtimeRemainIntSecs:" + convertTimeSec2HHMMSS(servtimeRemainIntSecs));

    console.log("----");

    console.log("runningWebDownTimeIntSecs:" + runningWebDownTimeIntSecs);
    console.log("runningWebDownTimeIntSecs:" + convertTimeSec2HHMMSS(runningWebDownTimeIntSecs));

    console.log("----");
    console.log("----");

    //Show time in web page
    showTimeRuning(runningWebDownTimeIntSecs);

    if (runningWebDownTimeIntSecs > -1) {
        setTimeout(decrement, 1000);
    } else {
        //Auto Logout if time remain is minus
        $(window).attr('location', '/logout.php');
    }
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
        url: "/ajax_timeout/getSrvTimeRemain.php",
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
    } else {
        $(".userdetail_input").prop("disabled", true);
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



$("#adduser , #chg_passwrd ,#edituser").validate({
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



$("#add_u_result").validate({
    rules: {

        result_type: {
            selectd: true
        }
    }

});

