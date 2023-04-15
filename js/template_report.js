$(document).ready(function () {

    var rawdata;

    // table data
    var table = $('#template_report_table').DataTable({
        "ajax": "data/template_report_ajax.php?skey=" + skey + "&range=1m"+"&user_id="+user_id,
        responsive: true,
        dom: 'lBrtip',


        // target       | 0      |                |     1             |     2        |      3      |    4           |     5
        // colun name   | id     |                |      ชื่อ           |  ชนิด ออกผล   |  ชือเท็มเพลต  | เท็มเพล็ต         |   จัดการ
        // index name   | tid    |    rid    uid  | uname   ulastname | rname        | tname       | tdescription   |    -
        // Incex number | 0      |    1      2    | 3       4         |  5           |  6          | 7	            |     -

        columnDefs: [{
                "render": function (data, type, row) {
                    let html = row[0];
                    return html
                },
                "targets": 0
            },
            {
                "render": function (data, type, row) {
                    let html = row[3] + " " + row[4];
                    return html
                },
                "targets": 1
            },
            {
                "render": function (data, type, row) {
                    let html = row[5];
                    return html
                },
                "targets": 2
            },
            {
                "render": function (data, type, row) {
                    let html = row[6];
                    return html
                },
                "targets": 3
            },
            {
                "render": function (data, type, row) {
                    let html = row[7];
                    return html
                },
                "targets": 4
            },
             {
                "render": function (data, type, row) {
                    let html = '<a href="templateReport_edit.php?id='+row[0]+'" class="btn btn-outline-primary btn-sm me-1 edit"><i class="fa-solid fa-marker"></i> Edit</a>';
                    return html
                },
                "targets": 5
            },
            {
                visible: false,
                targets: []
            },
        ],
    });

    table.on('draw', function () {
        // console.log(table.rows().data());
        rawdata = table.rows().data();
        // console.log(rawdata.length);
    });


    //set active tab
    $("#template_report").addClass("active");

});