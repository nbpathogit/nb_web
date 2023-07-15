



//$('#ugroup_id_user_add').on('change', function () {
//    //update drop down list of specimen
//    var ugroup_id = $('#ugroup_id_user_add option').filter(':selected').attr('value');
////    alert(ugroup_id);
//    if(ugroup_id=="5100"){
//        $.ajax({
//            type: 'POST',
//            // make sure you respect the same origin policy with this url:
//            // http://en.wikipedia.org/wiki/Same_origin_policy
//            url: 'ajax_user/getAllbyNonUserId.php',
//            data: {
//                'id': 1,
//            },
//            success: function (data) {
//                if(data[0] != "["){
//                    alert(data);
//                }
//                var datajson = JSON.parse(data);
//                if (datajson == "" || datajson == null) {
//                    $('#uhospital_id_user_add option').remove();
//                    $('#uhospital_id_user_add').append('<option >No Data </option>');
//                    $('#uhospital_id_user_add').prop('disabled', true);
//    //                $('#add_spcimen_list2').prop('disabled', true);
//                    if (isalert) {
//
//                        alert('No Hospital, All hospital already have user account');
//                    }
//                } else {
//
//                    //alert('Success' + datajson);
//                    $('#uhospital_id_user_add').prop('disabled', false);
//                    $('#uhospital_id_user_add option').remove();
//                    for (var i in datajson)
//                    {
//                        if (i == 0) {
//                            $('#uhospital_id_user_add').append('<option value="' + datajson[i].id + '" >กรุณาเลือก</option>');
//                        } else {
//                            $('#uhospital_id_user_add').append('<option value="' + datajson[i].id + '" >' + datajson[i].hospital + ' </option>');
//                        }
//                    }
//    //                if (isalert) {
//    //                    alert('Please select specimen.');
//    //                }
//                }
//            }
//        });
//    }
//    
//    if(ugroup_id=="1000" || ugroup_id=="1000" || ugroup_id=="2100" || ugroup_id=="2200" || ugroup_id=="2500" || ugroup_id=="2600" || ugroup_id=="2700" || ugroup_id=="5000"){
//           $.ajax({
//            type: 'POST',
//            // make sure you respect the same origin policy with this url:
//            // http://en.wikipedia.org/wiki/Same_origin_policy
//            url: 'ajax_user/getAll.php',
//            data: {
//                'id': 1,
//            },
//            success: function (data) {
//                if(data[0] != "["){
//                    alert(data);
//                }
//                var datajson = JSON.parse(data);
//                if (datajson == "" || datajson == null) {
//                    $('#uhospital_id_user_add option').remove();
//                    $('#uhospital_id_user_add').append('<option >No Data </option>');
//                    $('#uhospital_id_user_add').prop('disabled', true);
//    //                $('#add_spcimen_list2').prop('disabled', true);
//                    if (isalert) {
//
//                        alert('No Hospital, All hospital already have user account');
//                    }
//                } else {
//
//                    //alert('Success' + datajson);
//                    $('#uhospital_id_user_add').prop('disabled', false);
//                    $('#uhospital_id_user_add option').remove();
//                    for (var i in datajson)
//                    {
//                        if (i == 0) {
//                            $('#uhospital_id_user_add').append('<option value="' + datajson[i].id + '" >กรุณาเลือก</option>');
//                        } else {
//                            $('#uhospital_id_user_add').append('<option value="' + datajson[i].id + '" >' + datajson[i].hospital + ' </option>');
//                        }
//                    }
//    //                if (isalert) {
//    //                    alert('Please select specimen.');
//    //                }
//                }
//            }
//        });
//        
//        
//    }
//        
//    if(ugroup_id=="0"){
//        $('#uhospital_id_user_add option').remove();
//        $('#uhospital_id_user_add').append('<option >No Data </option>');
//        $('#uhospital_id_user_add').prop('disabled', true);
////                $('#add_spcimen_list2').prop('disabled', true);
//        
//        alert('No Hospital, All hospital already have user account');
//       
//        
//        
//    }
//    
//});


$("#save_user_status").on("click", function () {
    
    
    let status = $('#user_status option').filter(':selected').attr('value');
    let user_id = get_user_id_for_edit();
    
//    alert(" "+status+" "+user_id);
    
    $.ajax({
        'async': false,
        type: 'POST',
        'global': false,
        url: 'ajax_user/setUserStatus.php',
        data: {
            'user_id': user_id,
            'status': status,


        },
        success: function (data) {
            alert('done');
        },
        error: function (jqxhr, status, exception) {
            alert('Exception:', exception);
        }
    });
    
    
});


$("#reset_password_btn").on("click", function () {
//    let status = $('#user_status option').filter(':selected').attr('value');
    let user_id = get_user_id_for_edit();
    let username_rst = $('#username_rst').val();
    let password_rst = $('#password_rst').val();
    
//    alert(user_id + " " + username_rst + " " + password_rst);
    if (confirm("Do you want to reset password for user '"+username_rst+"' ?.")) {

        $.ajax({
            'async': false,
            type: 'POST',
            'global': false,
            url: 'ajax_user/setUserNameandPassword.php',
            data: {
                'user_id': user_id,
                'username': username_rst,
                'password': password_rst,
            },
            success: function (data) {
                alert('Reset success. \nPlese let user login again with password "changeme"');
            },
            error: function (jqxhr, status, exception) {
                alert('Exception:', exception);
            }
        });
    }
    
});