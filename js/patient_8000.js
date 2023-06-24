$(document).ready(function () {


    // datatable
    var table = $('#request_sp_sl_table').DataTable({
        "ajax": "data/patientSpSlReq.php?skey=" + skey + "&range=1m",
        responsive: true,
        dom: 'PlfBrtip',
        buttons: [{
            extend: 'collection',
            text: 'ระยะเวลาย้อนหลัง',
            autoClose: true,
            buttons: [{
                text: '1 เดือนล่าสุด',
                action: function (e, dt, node, config) {
                    dt.ajax.url("data/patientSpSlReq.php?skey=" + skey + "&range=1m").load();
                }
            },
            {
                text: '3 เดือนล่าสุด',
                action: function (e, dt, node, config) {
                    dt.ajax.url("data/patientSpSlReq.php?skey=" + skey + "&range=3m").load();
                }
            },
            {
                text: '6 เดือนล่าสุด',
                action: function (e, dt, node, config) {
                    dt.ajax.url("data/patientSpSlReq.php?skey=" + skey + "&range=6m").load();
                }
            },
            {
                text: '1 ปีล่าสุด',
                action: function (e, dt, node, config) {
                    dt.ajax.url("data/patientSpSlReq.php?skey=" + skey + "&range=1y").load();
                }
            },
            {
                text: '2 ปีล่าสุด',
                action: function (e, dt, node, config) {
                    dt.ajax.url("data/patientSpSlReq.php?skey=" + skey + "&range=2y").load();
                }
            },
            {
                text: 'ทั้งหมด',
                action: function (e, dt, node, config) {
                    dt.ajax.url("data/patientSpSlReq.php?skey=" + skey + "").load();
                }
            }
            ]
        },],
        "order": [
            [6, "desc"]
        ],
        searchPanes: {
            initCollapsed: true,
        },
        columnDefs: [{
            searchPanes: {
                show: true
            },
            targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
        },
        {
            searchPanes: {
                show: false
            },
            targets: [0]
        },
        {
            "render": function (data, type, row) {
                let renderdata = '';
                if(row[6]){
                    //If finish date is avalable
                    renderdata += '<a href="xxxx.php?id=' + row[0] + '" class="btn btn-success btn-sm " disabled><i class="fa-sharp fa-solid fa-check rid'+row[0]+'"></i> FINISHED rid'+ row[0] +' </a>';
                }else{
                    //If finished date is null
                    renderdata += '<a href="xxxx.php?id=' + row[0] + '" class="btn btn-warning btn-sm done"><i class="fa-sharp fa-solid fa-check  rid'+row[0]+'"></i> CLOSE rid'+ row[0] +' </a>';
                }
                return renderdata;
            },
            "targets": -1
        },
//        {
//            "render": function (data, type, row) {
//                let renderdata = row[0];
//                return renderdata;
//            },
//            "targets": 0
//        },
//        {
//            "render": function (data, type, row) {
//                let renderdata = row[1];
//                return renderdata;
//            },
//            "targets": 1
//        },
//        {
//            "render": function (data, type, row) {
//                return row[2];
//            },
//            "targets": 2
//        },
//        {
//            "render": function (data, type, row) {
//                return row[3];
//            },
//            "targets": 3
//        },
//        {
//            "render": function (data, type, row) {
//                return row[4];
//            },
//            "targets": 4
//        },
//        {
//            "render": function (data, type, row) {
//                return row[5];
//            },
//            "targets": 5
//        },
//        {
//            "render": function (data, type, row) {
//                return row[6];
//            },
//            "targets": 6
//        },
//        {
//            "render": function (data, type, row) {
//                return row[7];
//            },
//            "targets": 7
//        },
//        {
//            "render": function (data, type, row) {
//                return row[8];
//            },
//            "targets": 8
//        },
//        {
//            "render": function (data, type, row) {
//                return row[9];
//            },
//            "targets": 9
//        },
//        {
//            "render": function (data, type, row) {
//                return row[10];
//            },
//            "targets": 10
//        },
//        {
//            "render": function (data, type, row) {
//                return row[11];
//            },
//            "targets": 11
//        },
        {
            responsivePriority: 1,
            targets: 1
        },
        {
            responsivePriority: 2,
            targets: 2
        },
        {
            responsivePriority: 3,
            targets: -1
        },
        {
            responsivePriority: 10001,
            targets: 0
        },
        {
            visible: true,
             targets: [0, 4, 5, 6, 7, 8, 9, 10, 11]
        },
        {
            visible: false,
             targets: [1, 2, 3]
        },
        ],
//        "initComplete": colorAdd,
    });

    // add color when reload
//    table.on('draw', colorAdd);

//        table.on('draw', function() {
//            // console.log(table.rows().data());
//            rawdata = table.rows().data();
//            console.log(rawdata);
//            alert("rawdata print");
//        });

    // delete user
    $('#request_sp_sl_table tbody').on('click', 'a.done', function (e) {
        var data = table.row($(this).parents('tr')).data();

        e.preventDefault();
//        if (confirm("Are you sure?")) {
//            var frm = $("<form>");
//            frm.attr('method', 'post');
//            frm.attr('action', "patient_del.php?id=" + data[0]);
//            frm.appendTo("body");
//            frm.submit();
//        }
    });

    // set active tab
    $("#patienttab_8000").addClass("active");

//    setInterval( function () {
//        table.ajax.reload( null, false ); // user paging is not reset on reload
//    }, 10000 );

});