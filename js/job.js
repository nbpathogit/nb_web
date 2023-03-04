$(document).ready(function() {

    var rawdata;

    // table data
    var table = $('#job_table').DataTable({
        "ajax": "data/job.php?skey=" + skey + "&range=1m",
        responsive: true,
        dom: 'lBrtip',
        buttons: [{
            extend: 'collection',
            text: 'ระยะเวลาย้อนหลัง',
            autoClose: true,
            buttons: [{
                    text: '1 เดือนล่าสุด',
                    action: function(e, dt, node, config) {
                        dt.ajax.url("data/job.php?skey=" + skey + "&range=1m").load();
                    }
                },
                {
                    text: '3 เดือนล่าสุด',
                    action: function(e, dt, node, config) {
                        dt.ajax.url("data/job.php?skey=" + skey + "&range=3m").load();
                    }
                },
                {
                    text: '6 เดือนล่าสุด',
                    action: function(e, dt, node, config) {
                        dt.ajax.url("data/job.php?skey=" + skey + "&range=6m").load();
                    }
                },
                {
                    text: '1 ปีล่าสุด',
                    action: function(e, dt, node, config) {
                        dt.ajax.url("data/job.php?skey=" + skey + "&range=1y").load();
                    }
                },
                {
                    text: '2 ปีล่าสุด',
                    action: function(e, dt, node, config) {
                        dt.ajax.url("data/job.php?skey=" + skey + "&range=2y").load();
                    }
                },
                {
                    text: 'ทั้งหมด',
                    action: function(e, dt, node, config) {
                        dt.ajax.url("data/job.php?skey=" + skey + "").load();
                    }
                }
            ]
        }, ],
        columnDefs: [{
                "render": function(data, type, row) {
                    let html = '<b>No.: </b><a href="patient_edit.php?id=' + row[2] + '" target="_blank">' + row[3] + '</a><br><b>Name: </b>' + row[5] + " " + row[6] + " " + row[7] + "<br>" + "<b>Job: </b>" + row[8];
                    html += '<br><a href="job_edit.php?id=' + row[0] + '" class="btn btn-outline-secondary btn-sm" target="_blank"><i class="fa-solid fa-pen-to-square"></i> Edit</a>';
                    return html
                },
                "targets": 3
            },
            {
                "render": function(data, type, row) {
                    return "<b>Create: </b>" + row[13] + "<br><b>Finish: </b>" + (row[12] == null ? "-" : row[12]);
                },
                "targets": 12
            },
            {
                "render": function(data, type, row) {
                    let html = "<b>#:</b>" + row[0];
                    html += "<br><b>Job role id:</b> " + row[1];
                    html += "<br><b>Patient id:</b> " + row[2];
                    html += "<br><b>User id:</b> " + row[4];
                    html += "<br><b>Pay:</b> " + row[9];
                    html += "<br><b>Cost count per day:</b> " + row[10];
                    html += "<br><b>Comment:</b> " + row[11];
                    return html;
                },
                "targets": 11
            },
            {
                visible: false,
                targets: [0, 1, 2, 4, 5, 6, 7, 8, 9, 10, 13]
            },
        ],
    });

    table.on('draw', function() {
        // console.log(table.rows().data());
        rawdata = table.rows().data();
        // console.log(rawdata.length);
    });

    $("#csvdownload").click(function() {
        var data = [
            ["id", "job_role_id", "patient_id", "patient_number", "user_id", "pre_name", "name", "lastname", "jobname", "pay", "cost_count_per_day", "comment", "finish_date", "insert_time"]
        ];

        // console.log(label_history.length);return;

        for (var count = 0; count < rawdata.length; count++) {
            data.push(rawdata[count]);
        }

        let csvContent = "data:text/csv;charset=utf-8," +
            data.map(e => e.join(",")).join("\n");

        var encodedUri = encodeURIComponent("\uFEFF" + csvContent);
        var link = document.createElement("a");
        link.setAttribute('href', 'data:text/csv; charset=utf-8,' + encodedUri);
        link.setAttribute("download", "jobdata_" + rawdata[rawdata.length - 1][13] + "_to_" + rawdata[0][13] + ".csv");
        document.body.appendChild(link);
        link.click();
    });


    //set active tab
    $("#job_tab").addClass("active");

});