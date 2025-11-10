<?php
require 'includes/init.php';
// var_dump($_SESSION['user']->id);exit;
// Auth::requireLogin();
Auth::requireLogin("user_sim_admin.php");

$conn = require 'includes/db.php';
require 'user_auth.php';





//$ugroups = Ugroup::getAll($conn);
//$hospitals = Hospital::getAll($conn);
//var_dump($hospitals);




if (isset($_GET['id'])) {
    if ($isCurUserAdmin) {
        Auth::adminSimulatelogin($conn, $_GET['id']);
        Url::redirect('/login.php');
    }
}
?>
<?php // require 'includes/data2DOM.php';   
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
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">

        <table class="table table-hover table-striped text-center" id="DataTableID" style="width:100%">
            <thead>
                <tr>
                </tr>
            </thead>

        </table>



    </div>
</div>




<?php require 'includes/footer.php'; ?>

<!-- Include Moment.js and DateRangePicker CSS and JS -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.js"></script>

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
        var defaultColumns = [{
                title: 'Name',
                data: 'name'
            },
            {
                title: 'Surname',
                data: 'surname'
            },
            {
                title: 'PN',
                data: 'pn'
            },
            {
                title: 'Age',
                data: 'age'
            },
            {
                title: 'HN',
                data: 'hn'
            },
            {
                title: 'Hospital',
                data: 'Hospital'
            },
            {
                title: 'Specimen',
                data: 'Specimen'
            },
            {
                title: 'Source',
                data: 'ssource'
            },
            {
                title: 'Specimen Adequacy',
                data: 'specimen_adequacy'
            },
            {
                title: 'Interpretation',
                data: 'interpretation'
            },
            {
                title: 'Educational Notes',
                data: 'EDUCATIONAL_NOTES_AND_SUGGESTION'
            },
            {
                title: 'Date Received',
                data: 'Date_Received'
            },
            {
                title: 'Date Report',
                data: 'Date_Report'
            },
            {
                title: 'Pathologist',
                data: 'Pathologist'
            },
            {
                title: 'Cytologist',
                data: 'Cytologist'
            }
        ];

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
        $.ajax({
            url: "data/datatable_patient_pn_rev1_ajax.php?skey=" + skey,
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
            "startDate": moment(),
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
            reloadTable("data/datatable_patient_pn_rev1_ajax.php?skey=" + skey + "&startDate=" + startDate + "&endDate=" + endDate);
        });

        // Trigger initial callback to set the correct preset label
        var initialStart = moment();
        var initialEnd = moment();
        var initialLabel = 'วันนี้';
        $('#dateRangePicker').val(initialStart.format('DD/MM/YYYY') + ' - ' + initialEnd.format('DD/MM/YYYY'));

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
        $("#datatable_patient_pn_rev1").addClass("active");
        $("#admin_tab").addClass("active");
        $("#admin_tab").attr('aria-expanded', 'true');
        $("#SIDE_tab").addClass("active");
        $("#SUBSIDEBAR_tab").addClass("active");
    });
</script>