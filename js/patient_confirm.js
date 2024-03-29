$(document).ready(function () {


    // add color class for priority 
    function colorAdd() {
        var count = $('.table').children('tbody').children('tr:first-child').children('td').length;
        tablee = document.getElementById("patient_table");
        tr = tablee.getElementsByTagName("tr");
        // Loop through all table rows, and hide those who don't match the search query
        for (i = 1; i < tr.length; i++) {
            for (j = 0; j < count; j++) {
                td = tr[i].getElementsByTagName("td")[j];
                if (td.innerHTML.indexOf('ออกผลแล้ว') > -1) {
                    tr[i].classList.add('table-success');
                    break;
                } else if (td.innerHTML.indexOf('ด่วน') > -1) {
                    tr[i].classList.add('table-danger');
                } else {
                    //DO NOTHING
                }
            }
        }

        if (ugroup_id == '5000' || ugroup_id == '5100') {
            table.column(12).visible( false );
        }


    }


    // datatable
    var table = $('#patient_table').DataTable({
        "ajax": "data/patient.php?skey=" + skey + "&range=1m",
        responsive: true,
        dom: 'PlfBrtip',
        buttons: [{
            extend: 'collection',
            text: 'ระยะเวลาย้อนหลัง',
            autoClose: true,
            buttons: [{
                text: '1 เดือนล่าสุด',
                action: function (e, dt, node, config) {
                    dt.ajax.url("data/patient.php?skey=" + skey + "&range=1m").load();
                }
            },
            {
                text: '3 เดือนล่าสุด',
                action: function (e, dt, node, config) {
                    dt.ajax.url("data/patient.php?skey=" + skey + "&range=3m").load();
                }
            },
            {
                text: '6 เดือนล่าสุด',
                action: function (e, dt, node, config) {
                    dt.ajax.url("data/patient.php?skey=" + skey + "&range=6m").load();
                }
            },
            {
                text: '1 ปีล่าสุด',
                action: function (e, dt, node, config) {
                    dt.ajax.url("data/patient.php?skey=" + skey + "&range=1y").load();
                }
            },
            {
                text: '2 ปีล่าสุด',
                action: function (e, dt, node, config) {
                    dt.ajax.url("data/patient.php?skey=" + skey + "&range=2y").load();
                }
            },
            {
                text: 'ทั้งหมด',
                action: function (e, dt, node, config) {
                    dt.ajax.url("data/patient.php?skey=" + skey + "").load();
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
            targets: [4, 5, 6, 7, 8, 9, 10]
        },
        {
            searchPanes: {
                show: false
            },
            targets: [0, 1, 2, 3]
        },
        {
            "render": function (data, type, row) {
                var renderdata = '';

                if (ugroup_id == '5000' || ugroup_id == '5100') {
                    if (row[9] == "ยังไม่ออกผล") {
                        renderdata += '<p class="btn btn-secondary btn-sm me-1 edit"><i class="fa-solid fa-marker"></i> Edit</p>';
                    } else {
                        renderdata += '<a href="patient_edit.php?id=' + row[0] + '&pnum=' + row[1] + '" class="btn btn-outline-primary btn-sm me-1 edit"><i class="fa-solid fa-marker"></i> Edit</a>';
                    }
                }
                else
                    renderdata += '<a href="patient_edit.php?id=' + row[0] + '&pnum=' + row[1] + '" class="btn btn-outline-primary btn-sm me-1 edit"><i class="fa-solid fa-marker"></i> Edit</a>';


                if (isCurUserAdmin) {
                    renderdata += '<a href="patient_del.php?id=' + row[0] + '&pnum=' + row[1] + '" class="btn btn-outline-dark btn-sm delete"><i class="fa-solid fa-trash-can"></i> Delete</a>';
                }

                return renderdata;
            },
            "targets": -1
        },
        {
            "render": function (data, type, row) {
                var renderdata = '';

                if (ugroup_id == '5000' || ugroup_id == '5100') {
                    if (row[9] == "ยังไม่ออกผล") {
                        renderdata += '<p class="btn btn-secondary btn-sm me-1 pdf"><i class="fa-solid fa-file-pdf"></i>PDF</p>';
                    } else {
                        renderdata += '<a href="patient_pdf.php?id=' + row[0] + '" class="btn btn-outline-danger btn-sm me-1 pdf" target="_blank"><i class="fa-solid fa-file-pdf"></i>PDF</a>';
                    }
                }
                else {
                    renderdata += '<a href="patient_pdf.php?id=' + row[0] + '" class="btn btn-outline-danger btn-sm me-1 pdf" target="_blank"><i class="fa-solid fa-file-pdf"></i>PDF</a>';
                }

                return renderdata;
            },
            "targets": 11
        },
        {
            "render": function (data, type, row) {


                if ((ugroup_id == '5000' || ugroup_id == '5100')) {
                    if (row[9] == "ยังไม่ออกผล") {
                        var data = '<div><h5>' + data;
                    } else {
                        var data = '<div><h5><a href="patient_edit.php?id=' + row[0] + '&pnum=' + row[1] + '">' + data + '</a>';
                    }
                }
                else {
                    var data = '<div><h5><a href="patient_edit.php?id=' + row[0] + '&pnum=' + row[1] + '">' + data + '</a>';
                }


                if (row[10] == "ด่วน") {
                    data += ' <span class="badge bg-danger">' + row[10] + '</span>';
                }
                data += '</h5></div>';

                if (row[8] == "รับเข้า" || row[8] == "วางแผนงาน") {
                    data += '<span class="badge bg-dark">' + row[8] + '</span>';
                } else if (row[8] == "วินิจฉัย(อ่านไสลด์)") {
                    data += '<span class="badge bg-secondary">' + row[8] + '</span>';
                } else if (row[8] == "เตรียมชิ้นเนื้อ(ศัลยพยาธิ)" || row[8] == "เตรียมสไลด์(จุลพยาธิวิทยา)") {
                    data += '<span class="badge bg-info text-dark">' + row[8] + '</span>';
                } else if (row[8] == "เสร็จสิ้น") {
                    data += '<span class="badge bg-success">' + row[8] + '</span>';
                } else {
                    data += '<span class="badge bg-secondary">' + row[8] + '</span>';
                }
                return data;
            },
            "targets": 1
        },
        {
            "render": function (data, type, row) {
                if (row[9] == "ยังไม่ออกผล") {
                    data = '<h5><span class="badge bg-secondary">' + row[9] + '</span></h5>';
                } else if (row[9] == "ออกผลเบื้องต้น") {
                    data = '<h5><span class="badge bg-info text-dark">' + row[9] + '</span></h5>';
                } else if (row[9] == "ออกผลแล้ว") {
                    data = '<h5><span class="badge bg-success">' + row[9] + '</span></h5>';
                } else {
                    data = '<h5><span class="badge bg-secondary">' + row[9] + '</span></h5>';
                }
                return data;
            },
            "targets": 9
        },
        {
            "render": function (data, type, row) {
                return row[2] + '<br>' + row[3];
            },
            "targets": 2
        },
        {
            "render": function (data, type, row) {
                if (row[11] == "0") {
                    data = '';
                } else if (row[11] == "1") {
                    data = '<h5><span class="badge bg-warning text-dark">ร้องขอ</span></h5>';
                } else if (row[11] == "2") {
                    data = '<h5><span class="badge bg-success">เสร็จสิ้น</span></h5>';
                } 
                return data;
            },
            "targets": 8
        },
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
            visible: false,
            targets: [0, 3,  10]
        },
        ],
        "initComplete": colorAdd,
    });

    // add color when reload
    table.on('draw', colorAdd);


    // delete user
    $('#patient_table tbody').on('click', 'a.delete', function (e) {
        var data = table.row($(this).parents('tr')).data();

        e.preventDefault();
        if (confirm("Are you sure?")) {
            var frm = $("<form>");
            frm.attr('method', 'post');
            frm.attr('action', "patient_del.php?id=" + data[0]);
            frm.appendTo("body");
            frm.submit();
        }
    });

    // set active tab
    $("#patientconfirmtab").addClass("active");


});