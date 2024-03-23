$(document).ready(function () {

    var rawdata;

    var start = moment().startOf('month');
    var end = moment().endOf('month');
    var startRange = start.format('YYYY-MM-DD');
    var endRange = end.format('YYYY-MM-DD');

    // table data
    var table = $('#billing_table').DataTable({
//        footerCallback: function (row, data, start, end, display) {
//            var api = this.api();
//
//            // Remove the formatting to get integer data for summation
//            var intVal = function (i) {
//                return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
//            };
//
//            // Total over all pages
//            total = api
//                .column(15)
//                .data()
//                .reduce(function (a, b) {
//                    return intVal(a) + intVal(b);
//                }, 0);
//
//            // Total over this page
//            pageTotal = api
//                .column(15, { page: 'current' })
//                .data()
//                .reduce(function (a, b) {
//                    return intVal(a) + intVal(b);
//                }, 0);
//
//            // Update footer
//            $(api.column(15).footer()).html('รวมหน้านี้ ' + pageTotal + ' บาท \t (รวมทั้งหมด ' + total + ' บาท)');
//        },
        
//============================================================
//$sql =  "SELECT p.id as pid                                                 $data[] = [$bill['pid']                   //0          <th scope="col">#</th>            <!--0-->
//        , b.id as b_id                                                              , $bill['b_id']                   //1          <th scope="col">b_id</th>         <!--1-->
//        , p.sn_type as p_sntype                                                     , $bill['p_sntype']               //2          <th scope="col">type</th>         <!--2-->
//        , p.pnum as p_sn_num                                                        , $bill['p_sn_num']               //3          <th scope="col">SN</th>           <!--3-->
//        , p.phospital_num as p_hn                                                   , $bill['p_hn']                   //4          <th scope="col">HN</th>           <!--4-->
//        , CONCAT(p.pname,' ',p.plastname) as p_pname                                , $bill['p_pname']                //5          <th scope="col">Patient</th>      <!--5-->
//        , CONCAT(user_cli.name,' ',user_cli.lastname) as user_clinicient            , $bill['user_clinicient']        //6          <th scope="col">Clinicient</th>   <!--6-->
//        , hp.hospital as hp_hospital                                                , $bill['hp_hospital']            //7          <th scope="col">Hospital</th>     <!--7-->
//        , CONCAT(j5.name,' ',j5.lastname) as j5_pathologist                         , $bill['j5_pathologist']         //8          <th scope="col">pathologist</th>  <!--8-->
//        , DATE(p.date_1000)	as p_accept_date                                      , $bill['p_accept_date']          //9          <th scope="col">Accept DAte</th>  <!--9-->
//        DATE(b.create_date) as b_billing_date,                                      , $bill['b_billing_date']          //10        <th scope="col">Billing Date</th> <!--10-->
//        , st.service_type as st_type                                                , $bill['st_type']                //11         <th scope="col">Service Type</th> <!--11-->
//        , b.code_description as b_code                                              , $bill['b_code']                 //12         <th scope="col">Code</th>         <!--12-->
//        , b.description as b_description                                            , $bill['b_description']          //13         <th scope="col">Description</th>  <!--13-->
//        , b.sp_slide_block as b_sp_slide_block                                      , $bill['b_sp_slide_block']       //14         <th scope="col">Block</th>        <!--14-->   
//        , b.cost as b_cost                                                          , $bill['b_cost']];               //15         <th scope="col">Cost</th>         <!--15-->
//        FROM  patient as p                                                                                                        
//         JOIN service_billing as b  ON  p.id = b.patient_id                            
//         LEFT JOIN service_type as st ON st.id = b.slide_type                    
//         LEFT JOIN job as j5 ON j5.patient_id = p.id and j5.job_role_id = 5      
//         LEFT JOIN user as user_cli ON user_cli.id = p.pclinician_id             
//         LEFT JOIN hospital as hp ON hp.id = p.phospital_id                      
//        WHERE  p.movetotrash = 0 ";                                              
//

//            
//===============================================================
        
        
        
        
        
        
        
        "ajax": "data/billing.php?skey=" + skey + "&start=" + startRange + "&end=" + endRange,
        responsive: true,
        dom: 'BPlfritp',
        
        buttons: [
            {
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
                customize: function (doc) {
                    processDoc(doc);
                    // Data URL generated by http://dataurl.net/#dataurlmaker
                }
            }
           
       ],
        columnDefs: [{
            "render": function (data, type, row) {
                let html = '<a href="patient_edit.php?id=' + row[0] + '">' + row[3] + '</a>';
                return html;
            },
            "targets": 3
        },

        {
            visible: false,
            targets: []
        },
        {
            searchPanes: {
                show: true
            },
            targets: []
        },],
        searchPanes: {
            initCollapsed: true,
        },
        //order: [[0, 'desc']],

    });

    table.on('draw', function () {
        // console.log(table.rows().data());
        rawdata = table.rows().data();
        // console.log(rawdata);
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    });

    $("#csvdownload").click(function () {
        var data = [
            ['bid', 'specimen_id', 'patient_id', 'number', 'name', 'lastname', 'slide_type', 'code_description', 'description', 'import_date', 'report_date', 'hospital', 'hn', 'send_doctor', 'pathologist', 'cost', 'comment']
        ];

        // console.log(label_history.length);return;

        for (var count = 0; count < rawdata.length; count++) {
            data.push(rawdata[count]);
        }

        let csvContent = data.map(e => e.join(",")).join("\n");

        var encodedUri = encodeURIComponent("\uFEFF" + csvContent);
        var link = document.createElement("a");
        link.setAttribute('href', 'data:text/csv; charset=utf-8,' + encodedUri);
        link.setAttribute("download", "billingdata_" + rawdata[rawdata.length - 1][9] + "_to_" + rawdata[0][9] + ".csv");
        document.body.appendChild(link);
        link.click();
    });

    function cbTimerange(starta, enda) {
        start=starta;
        end=enda;
        startRange = starta.format('YYYY-MM-DD');
        endRange = enda.format('YYYY-MM-DD');
        table.ajax.url("data/billing.php?skey=" + skey + "&start=" + startRange + "&end=" + endRange).load();
        $('#reportrange span').html(starta.format('MMMM D, YYYY') + ' - ' + enda.format('MMMM D, YYYY'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
            'Today': [moment().startOf('days'), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'Last 3 Month': [moment().subtract(2, 'month').startOf('month'), moment()],
            'This Years': [moment().startOf('year'), moment()]
        }
    }, cbTimerange);


    // cbTimerange(start, end);

    //set active tab
    $("#billing_tab").addClass("active");

});