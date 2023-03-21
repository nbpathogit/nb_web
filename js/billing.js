$(document).ready(function () {

    var rawdata;

    // table data
    var table = $('#billing_table').DataTable({
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();
 
            // Remove the formatting to get integer data for summation
            var intVal = function (i) {
                return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            };
 
            // Total over all pages
            total = api
                .column(15)
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
 
            // Total over this page
            pageTotal = api
                .column(15, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
 
            // Update footer
            $(api.column(15).footer()).html('$' + pageTotal + ' ( $' + total + ' total)');
        },
        "ajax": "data/billing.php?skey=" + skey + "&range=1m",
        responsive: true,
        dom: 'PlfBrtip',
        buttons: [{
            extend: 'collection',
            text: 'ระยะเวลาย้อนหลัง',
            autoClose: true,
            buttons: [{
                text: '1 เดือนล่าสุด',
                action: function (e, dt, node, config) {
                    dt.ajax.url("data/billing.php?skey=" + skey + "&range=1m").load();
                }
            },
            {
                text: '3 เดือนล่าสุด',
                action: function (e, dt, node, config) {
                    dt.ajax.url("data/billing.php?skey=" + skey + "&range=3m").load();
                }
            },
            {
                text: '6 เดือนล่าสุด',
                action: function (e, dt, node, config) {
                    dt.ajax.url("data/billing.php?skey=" + skey + "&range=6m").load();
                }
            },
            {
                text: '1 ปีล่าสุด',
                action: function (e, dt, node, config) {
                    dt.ajax.url("data/billing.php?skey=" + skey + "&range=1y").load();
                }
            },
            {
                text: '2 ปีล่าสุด',
                action: function (e, dt, node, config) {
                    dt.ajax.url("data/billing.php?skey=" + skey + "&range=2y").load();
                }
            },
            {
                text: 'ทั้งหมด',
                action: function (e, dt, node, config) {
                    dt.ajax.url("data/billing.php?skey=" + skey + "").load();
                }
            }]
        },],
        columnDefs: [{
            "render": function (data, type, row) {
                let html = '<b>No.: </b><a href="patient_edit.php?id=' + row[2] + '">' + row[3] + '</a>';
                return html;
            },
            "targets": 3
        },
        {
            "render": function (data, type, row) {
                let html = row[4] + " " + row[5];
                return html;
            },
            "targets": 4
        },
        {
            visible: false,
            targets: [0, 1, 2, 5, 6, 7, 8, 10, 11, 14, 16]
        },
        {
            searchPanes: {
                show: true
            },
            targets: [9, 11]
        },],
        searchPanes: {
            initCollapsed: true,
        },
        
    });

    table.on('draw', function () {
        // console.log(table.rows().data());
        rawdata = table.rows().data();
        // console.log(rawdata);
    });

    $("#csvdownload").click(function () {
        var data = [
            ['id', 'specimen_id', 'patient_id', 'number', 'name', 'lastname', 'slide_type', 'code_description', 'description', 'import_date', 'report_date', 'hospital', 'hn', 'send_doctor', 'pathologist', 'cost', 'comment']
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
        link.setAttribute("download", "billingdata_" + rawdata[rawdata.length - 1][9] + "_to_" + rawdata[0][9] + ".csv");
        document.body.appendChild(link);
        link.click();
    });


    //set active tab
    $("#billing_tab").addClass("active");

});