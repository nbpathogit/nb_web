<?php
require 'includes/init.php';
// var_dump($_SESSION['user']->id);exit;
// Auth::requireLogin();
Auth::requireLogin("user_sim_admin.php");

$conn = require 'includes/db.php';
require 'user_auth.php';

if (isset($_GET['id'])) {
    if ($isCurUserAdmin) {
        Auth::adminSimulatelogin($conn, $_GET['id']);
        Url::redirect('/login.php');
    }
}
?>

<?php require 'includes/header.php'; ?>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">

        <!-- Date Range Picker -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="dateRangePicker">เลือกช่วงวันที่: <span id="presetLabel" class="badge bg-primary">วันนี้</span></label>
                <input type="text" id="dateRangePicker" class="form-control" name="dateRangePicker" value="วันนี้" />
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between">

        </div>

    </div>
</div>

<div class="container-fluid pt-4 px-4">
    <!-- Chart Row -->
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 mb-4">
        <div class="col-12">
            <div class="bg-white rounded p-3 shadow-sm">
                <h5 class="text-center mb-3">Patient Count Chart</h5>
                <div class="chart-container" style="position: relative; height: 400px; width: 100%;">
                    <canvas id="patientChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table Row -->
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">
        <div class="col-12">
            <div class="bg-white rounded p-3 shadow-sm">
                <h5 class="text-center mb-3">Patient Data</h5>
                <table class="table table-hover table-striped text-center" id="DataTableID" style="width:100%">
                    <thead>
                        <tr>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>




<?php require 'includes/footer.php'; ?>

<!-- Include Moment.js and DateRangePicker CSS and JS -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.js"></script>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>

<script type="text/javascript">
    var skey = '<?= $_SESSION["skey"]; ?>';

    var datefrom = $("#startdate_billing").val();
    var dateto = $("#enddate_billing").val();

    $(document).ready(function() {
        function processDoc(doc) {
            //
            // https://pdfmake.github.io/docs/fonts/custom-fonts-client-side/
            //
            // Update pdfmake's global font list, using the fonts available in
            // the customized vfs_fonts.js file (do NOT remove the Roboto default):
            pdfMake.fonts = {
                Roboto: {
                    normal: 'Roboto-Regular.ttf',
                    bold: 'Roboto-Medium.ttf',
                    italics: 'Roboto-Italic.ttf',
                    bolditalics: 'Roboto-MediumItalic.ttf'
                },
                angsa: {
                    normal: domain + '/fonts/angsau.ttf',
                    bold: domain + '/fonts/angsaub.ttf',
                    italics: domain + '/fonts/angsaui.ttf',
                    bolditalics: domain + '/fonts/angsauz.ttf',
                }
            };
            // modify the PDF to use a different default font:
            doc.defaultStyle.font = "angsa";
            var i = 1;
        }

        var table;
        var chart;
        var defaultColumns = [{
                title: 'Date',
                data: 'date_in'
            },
            {
                title: 'Number of Patients',
                data: 'number_of'
            },
            {
                title: 'Names',
                data: 'names'
            },
            {
                title: 'Surnames',
                data: 'surnames'
            },
            {
                title: 'PNs',
                data: 'pns'
            },
            {
                title: 'Ages',
                data: 'ages'
            },
            {
                title: 'HNs',
                data: 'hns'
            },
            {
                title: 'Hospitals',
                data: 'hospitals'
            },
            {
                title: 'Pathologists',
                data: 'pathologists'
            },
            {
                title: 'Score Specimens',
                data: 'score_specimens'
            },
            {
                title: 'Score Stainings',
                data: 'score_stainings'
            },
            {
                title: 'Score Mountings',
                data: 'score_mountings'
            },
            {
                title: 'Score Labelings',
                data: 'score_labelings'
            },
            {
                title: 'Notes',
                data: 'notes'
            }
        ];

        // Initialize chart
        function initChart(chartData) {
            var ctx = document.getElementById('patientChart').getContext('2d');
            
            if (chart) {
                chart.destroy();
            }
            
            chart = new Chart(ctx, {
                type: 'line',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Patient Count Trend'
                        },
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                title: function(context) {
                                    // Format tooltip title to show only date, not time
                                    var date = new Date(context[0].parsed.x);
                                    return date.toLocaleDateString('en-GB', {
                                        day: '2-digit',
                                        month: '2-digit',
                                        year: 'numeric'
                                    });
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Patients'
                            }
                        },
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day',
                                displayFormats: {
                                    day: 'dd/MM/yyyy'
                                }
                            },
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        }
                    },
                    elements: {
                        point: {
                            radius: 5,
                            hoverRadius: 7,
                            backgroundColor: 'rgba(54, 162, 235, 1)',
                            borderColor: 'rgba(54, 162, 235, 1)'
                        },
                        line: {
                            tension: 0.4,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 2
                        }
                    }
                }
            });
        }

        // Load chart data
        function loadChartData() {
            var startDate, endDate;
            
            if (!$('#dateRangePicker').data('daterangepicker')) {
                // Date picker not initialized yet, use default dates (last 30 days)
                startDate = moment().subtract(29, 'days').format('YYYY-MM-DD');
                endDate = moment().format('YYYY-MM-DD');
            } else {
                // Use selected dates
                startDate = $("#dateRangePicker").data('daterangepicker').startDate.format('YYYY-MM-DD');
                endDate = $("#dateRangePicker").data('daterangepicker').endDate.format('YYYY-MM-DD');
            }
            
            var url = "data/chart_data_get.php?skey=" + skey + "&chart=1&startDate=" + startDate + "&endDate=" + endDate;
            
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log('Chart data response:', response);
                    if (response && response.chartData) {
                        initChart(response.chartData);
                    } else if (response && response.error) {
                        console.error('Chart data error:', response.error);
                        // Clear chart area or show error message
                        if (chart) {
                            chart.destroy();
                        }
                    } else {
                        console.error('Unexpected chart data response format');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading chart data:', error);
                    console.error('XHR status:', xhr.status);
                    console.error('XHR response:', xhr.responseText);
                    // Clear chart area or show error message
                    if (chart) {
                        chart.destroy();
                    }
                }
            });
        }

        // Initialize table immediately with default columns
        table = $('#DataTableID').DataTable({
            columns: defaultColumns,
            data: [],
            responsive: true,
            dom: 'BP<"toolbar">lfritip',
            pageLength: 100,
            buttons: [{
                    text: 'export excel',
                    extend: 'excel',
                },
                {
                    extend: 'csv',
                    text: 'export csv',
                    charset: 'utf-8',
                    extension: '.csv',
                    fieldSeparator: ',',
                    fieldBoundary: '',
                    filename: 'export',
                    bom: true
                },
                {
                    text: 'export pdf',
                    extend: 'pdf',
                    customize: function(doc) {
                        processDoc(doc);
                    }
                }
            ],
            searchPanes: {
                initCollapsed: true,
            },
            columnDefs: [{
                searchPanes: {
                    show: true
                },
                targets: defaultColumns.map((col, index) => index)
            }]
        });

        // Load initial data
        function loadInitialData() {
            var initialStartDate = moment().subtract(29, 'days').format('YYYY-MM-DD');
            var initialEndDate = moment().format('YYYY-MM-DD');
            
            $.ajax({
                url: "data/chart_data_get.php?skey=" + skey + "&startDate=" + initialStartDate + "&endDate=" + initialEndDate,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response && response.data && response.data.length > 0) {
                        // Clear and add new data
                        table.clear();
                        table.rows.add(response.data);
                        table.draw();

                        // Update columns if they exist in response
                        if (response.columns && response.columns.length > 0) {
                            table.destroy();
                            var targets = [];
                            for (var i = 0; i < response.columns.length; i++) {
                                targets.push(i);
                            }

                            table = $('#DataTableID').DataTable({
                                columns: response.columns,
                                data: response.data,
                                responsive: true,
                                dom: 'BP<"toolbar">lfritip',
                                pageLength: 100,
                                buttons: [{
                                        text: 'export excel',
                                        extend: 'excel',
                                    },
                                    {
                                        extend: 'csv',
                                        text: 'export csv',
                                        charset: 'utf-8',
                                        extension: '.csv',
                                        fieldSeparator: ',',
                                        fieldBoundary: '',
                                        filename: 'export',
                                        bom: true
                                    },
                                    {
                                        text: 'export pdf',
                                        extend: 'pdf',
                                        customize: function(doc) {
                                            processDoc(doc);
                                        }
                                    }
                                ],
                                searchPanes: {
                                    initCollapsed: true,
                                },
                                columnDefs: [{
                                    searchPanes: {
                                        show: true
                                    },
                                    targets: targets
                                }]
                            });
                        }
                    }
                }
            });

            // Load initial chart data
            loadChartData();
        }

        // Initialize Date Range Picker
        $('#dateRangePicker').daterangepicker({
            "locale": {
                "format": "DD/MM/YYYY",
                "separator": " - ",
                "applyLabel": "ยืนยัน",
                "cancelLabel": "ยกเลิก",
                "fromLabel": "จาก",
                "toLabel": "ถึง",
                "customRangeLabel": "กำหนดเอง",
                "weekLabel": "ส",
                "daysOfWeek": ["อา", "จ", "อ", "พ", "พฤ", "ศ", "ส"],
                "monthNames": ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"],
                "firstDay": 1
            },
            "startDate": moment().subtract(29, 'days'),
            "endDate": moment(),
            "ranges": {
                'วันนี้': [moment().startOf('day'), moment()],
                'เมื่อวาน': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '7 วันล่าสุด': [moment().subtract(6, 'days'), moment()],
                '30 วันล่าสุด': [moment().subtract(29, 'days'), moment()],
                'เดือนนี้': [moment().startOf('month'), moment().endOf('month')],
                'เดือนที่แล้ว': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                '3 เดือนล่าสุด': [moment().subtract(2, 'month').startOf('month'), moment()],
                'ปีนี้': [moment().startOf('year'), moment()]
            },
            "alwaysShowCalendars": true,
            "showDropdowns": true
        }, function(start, end, label) {
            // Update the input field with the selected range
            $('#dateRangePicker').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));

            // Update the preset label if it's a preset range
            if (label) {
                $('#presetLabel').text(label).removeClass('bg-secondary').addClass('bg-primary');
            } else {
                // For custom ranges, show "กำหนดเอง"
                $('#presetLabel').text('กำหนดเอง').removeClass('bg-primary').addClass('bg-secondary');
            }

            // Reload table with new date range
            var startDate = start.format('YYYY-MM-DD');
            var endDate = end.format('YYYY-MM-DD');
            reloadTable("data/chart_data_get.php?skey=" + skey + "&startDate=" + startDate + "&endDate=" + endDate);
            
            // Reload chart with new date range
            loadChartData();
        });

        // Trigger initial callback to set the correct preset label
        var initialStart = moment().subtract(29, 'days');
        var initialEnd = moment();
        var initialLabel = '30 วันล่าสุด';
        $('#dateRangePicker').val(initialStart.format('DD/MM/YYYY') + ' - ' + initialEnd.format('DD/MM/YYYY'));
        $('#presetLabel').text(initialLabel).removeClass('bg-secondary').addClass('bg-primary');

        // Load initial data after date picker is initialized
        loadInitialData();

        function reloadTable(url) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Always destroy and recreate for consistency
                    if (table) {
                        table.destroy();
                    }

                    var columns, data, targets;

                    // Handle response structure
                    if (response && response.columns && response.columns.length > 0) {
                        // Use columns from response
                        columns = response.columns;
                        data = response.data || [];
                    } else {
                        // Use default columns when no column structure in response
                        columns = defaultColumns;
                        data = [];
                    }

                    var columnCount = columns.length;
                    targets = [];
                    for (var i = 0; i < columnCount; i++) {
                        targets.push(i);
                    }

                    // Ensure data rows match column count
                    if (data.length > 0) {
                        // Validate each row has the correct number of columns
                        for (var j = 0; j < data.length; j++) {
                            var rowData = data[j];
                            if (Array.isArray(rowData) && rowData.length !== columnCount) {
                                // Fix row data to match column count
                                if (rowData.length < columnCount) {
                                    // Pad with empty values
                                    while (rowData.length < columnCount) {
                                        rowData.push('');
                                    }
                                } else {
                                    // Truncate to column count
                                    data[j] = rowData.slice(0, columnCount);
                                }
                            }
                        }
                    }

                    // Recreate table with validated data
                    table = $('#DataTableID').DataTable({
                        columns: columns,
                        data: data,
                        responsive: true,
                        dom: 'BP<"toolbar">lfritip',
                        pageLength: 100,
                        buttons: [{
                                text: 'export excel',
                                extend: 'excel',
                            },
                            {
                                extend: 'csv',
                                text: 'export csv',
                                charset: 'utf-8',
                                extension: '.csv',
                                fieldSeparator: ',',
                                fieldBoundary: '',
                                filename: 'export',
                                bom: true
                            },
                            {
                                text: 'export pdf',
                                extend: 'pdf',
                                customize: function(doc) {
                                    processDoc(doc);
                                }
                            }
                        ],
                        searchPanes: {
                            initCollapsed: true,
                        },
                        columnDefs: [{
                            searchPanes: {
                                show: true
                            },
                            targets: targets
                        }]
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error loading data:', error);
                    // Clear table on error
                    if (table) {
                        table.clear().draw();
                    }
                }
            });
        }

        // delete user
        //        $('#DataTableID tbody').on('click', 'a.delete', function(e) {
        //            var data = table.row($(this).parents('tr')).data();
        //
        //            e.preventDefault();
        //            if (confirm("Are you sure?")) {
        //                var frm = $("<form>");
        //                frm.attr('method', 'post');
        //                frm.attr('action', "user_del.php?id=" + data[0]);
        //                frm.appendTo("body");
        //                frm.submit();
        //            }
        //        });


        // set active tab
        $("#chart_data_tab").addClass("active");
        $("#admin_tab").addClass("active");
        $("#admin_tab").attr('aria-expanded', 'true');
        $("#SIDE_tab").addClass("active");
        $("#SUBSIDEBAR_tab").addClass("active");
    });
</script>