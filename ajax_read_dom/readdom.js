
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


function get_cur_patient_id(){
    return $(".cur_patient_id").attr('tabindex');
}



