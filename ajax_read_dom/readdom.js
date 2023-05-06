
function get_lastest_uresultid() {
    //Uresult index id lastest
    var uresultid = '0';
    $('.uresultinxlist li').each(function (index) {
        uresultid = $(this).attr('tabindex');
    });
    $('.uresultinxlist2 li').each(function (index) {
        uresultid = $(this).attr('tabindex');
    });
    return uresultid;
}

function get_cur_user_id(){
    return $(".cur_user_id").attr('tabindex');
}


function get_cur_patient_id(){
    return $(".cur_patient_id").attr('tabindex');
}

function get_cur_hospital_id(){
    return $(".cur_phospital_id").attr('tabindex');
}



function get_lastest_SecondPatho_userid_in_uresult(){
    //Second user id lastest in u_result
    var userid = '0';
    $('.uresultSecondPatho li').each(function (index) {
        userid = $(this).attr('tabindex');
    });
    $('.uresultSecondPatho2 li').each(function (index) {
        userid = $(this).attr('tabindex');
    });
    return userid;
    
}


function get_lastest_job6_id(){
    //Second user id lastest in u_result
    var id = '0';
    $('.job6_id li').each(function (index) {
        id = $(this).attr('tabindex');
    });
    $('.job6_id2 li').each(function (index) {
        id = $(this).attr('tabindex');
    });
    return id;

}

function get_user_id_for_edit(){
    return $(".user_id_for_edit").attr('tabindex');
}

function get_isCurUserCust(){
    return $(".isCurUserCust").attr('tabindex');
}

function get_cur_date_1000(){
    return $(".cur_date_1000").attr('tabindex');
}