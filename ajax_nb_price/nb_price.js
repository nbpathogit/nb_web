$("#nb_price_hospital_select").on("change", function () {
    $('#nb_price_tbl tbody tr').remove();
    $("#nb_price_type option").filter(function () {
        //may want to use $.trim in here
        return $(this).val() == 0;
    }).prop('selected', true);

});




$("#nb_price_type").on("change", function () {
    var type_id = $('#nb_price_type option').filter(':selected').attr('value');
    var hospital_id = $('#nb_price_hospital_select option').filter(':selected').attr('value');
    if (type_id == 0) {
        $('#nb_price_tbl tbody tr').remove();
        return;
    }


//    alert(type_id + '  ' + hospital_id);


    $.ajax({
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: '/ajax_nb_price/get_specimen.php',
        data: {

            'type_id': type_id,
            'hospital_id': hospital_id,

        },
        success: function (data) {
            if (data[0] != "[") {
                alert(data);
                console.log(data);
                return;
            }
            var datajson = JSON.parse(data);
            if(datajson.length == 0){
                alert("No record for this hospital");
                return;
            }
            
            
            $('#nb_price_tbl tbody tr').remove();
            for (var i in datajson)
            {
                /*
                 <tr>
                 <td> <?= $p['id'] ?> </td>
                 <td> <?= $p['hospital_id'] ?> </td>
                 <td> <?= $p['jobtype'] ?> </td>
                 <td> <?= $p['speciment_num'] ?> </td>
                 <td> <?= $p['specimen'] ?> </td>
                 <td> <?= $p['price'] ?> </td>
                 <td> <?= $p['comment'] ?> </td>
                 <td> <?= $p['create_date'] ?> </td>
                 <td> <?= $p['add_user_id'] ?> </td>
                 <td> <?= $p['edit_user_id'] ?> </td>
                 </tr>
                 
                 */
                //repaintTblhire1(data);
                var str = '<tr>' +
                        '<td>' + datajson[i].id + '</td>' +
                        '<td>' + datajson[i].hospital_id + '</td>' +
                        '<td>' + datajson[i].jobtype + '</td>' +
                        '<td>' + datajson[i].speciment_num + '</td>' +
                        '<td>' + datajson[i].specimen + '</td>' +
                        '<td>' + datajson[i].price + '</td>' +
                        '<td>' + datajson[i].comment + '</td>' +
                        '<td>' + datajson[i].create_date + '</td>' +
                        '<td>' + datajson[i].add_user_id + '</td>' +
                        '<td>' + datajson[i].edit_user_id + '</td>' +
                        '</tr>';
                $('#nb_price_tbl tbody').append(str);

            }
        },
        error: function (jqxhr, status, exception) {
            alert('Exception:', exception);
        }
    });













});
