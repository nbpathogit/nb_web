<?php
//คนเช่วยตัด

require 'includes/init.php';

$conn = require 'includes/db.php';
?>
<?php require 'user_auth.php'; ?>
<?php require 'includes/header.php'; ?>
<div class="container-fluid pt-4 px-4">
    <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">


        <?php if (!Auth::isLoggedIn()) : ?>
            You are not login.<br>
            คุณไม่ได้ล็อกอิน กรุณาล็อกอินก่อนเข้าใช้งาน
        <?php elseif (($isCurUserClinicianCust || $isCurUserHospitalCust)) : //  เจ้าหน้าที่รับผล(ลูกค้า) เข้าดูไม่ได้  
            ?>
            You have no authorize to view this content. <br>
            คุณไม่มีสิทธิ์ในการเข้าดูส่วนนี้
        <?php else : ?>

        </div>
    </div>

    <div class="container-fluid pt-4 px-4">
        <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">
            
           <h1 align="center"><span id="">หน้าลงเวลาผู้ช่วยตัดเนื้อ</span></h1>
           <h3 align="center"><span id="title_page_message">กำลังแสดงจากข้อมูลย้อนหลัง 1 เดือน</span></h3>


<table class="table table-hover table-striped" id="job2_finish_table" style="width:100%">
                <thead>
                    <tr>
                        <th scope="col">#</th>                
                        <th scope="col">1</th>      
                        <th scope="col">2</th>       
                        <th scope="col">3</th>          
                        <th scope="col">4</th>          
                        <th scope="col">5</th>          
                        <th scope="col">6</th>             
                        <th scope="col">7</th>         
                        <th scope="col">8</th>          
                        <th scope="col">9</th>              
                        <th scope="col">10</th>          
                        <th scope="col">11</th>          
                        <th scope="col">12</th> 

                    </tr>
                </thead>
            </table>

        <?php endif; ?>

    </div>
</div>

<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    var skey = "<?= $_SESSION["skey"] ?>";
    var domain = "<?= Url::currentURL() ?>";
    
    $(document).ready(function () {

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
            angsa : {
                normal: domain+'/fonts/angsau.ttf',
                bold: domain+'/fonts/angsaub.ttf',
                italics: domain+'/fonts/angsaui.ttf',
                bolditalics: domain+'/fonts/angsauz.ttf',
            }
        };
        // modify the PDF to use a different default font:
        doc.defaultStyle.font = "angsa";
        var i = 1;
    }



    // add color class for priority 
    function colorAdd() {
  
        var count = $('.table').children('tbody').children('tr:first-child').children('td').length;
        tablee = document.getElementById("job2_finish_table");
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




    }




    // datatable
    var table = $('#job2_finish_table').DataTable({
        "ajax": "data/job2_finish.php?skey=" + skey + "&range=1m",
        responsive: true,
        dom: 'BPlfrtip',
        
//=======================================
//["pid"]=> string(3) "200" ["p_pnum"]=> string(9) "SN2323116" ["p_hn"]=> string(0) "" ["p_accept"]=> string(19) "2023-11-04 23:04:27" 
//---0---------------------------1--------------------------------2------------------------3-----------------------------------------------
//
//["p_patient"]=> string(24) "Mrs.เอบี wcdcwdc" ["j_id"]=> string(3) "225" ["j_patient_id"]=> string(3) "200" ["j_user_id"]=> string(2) "40" 
//-------4--------------------------------------5-------------------------------6--------------------------------7-----------------------
//
//["j_job_role_id"]=> string(1) "2" , $patient['j_jobname'], $patient['j_owners'],$patient['j_qty'], $patient['finish_date']];
//-------8-----------------------------------------9---------------------10------------------11------------------12--------
//

        columns: [
        { title: '#' },
        { title: 'SN' },
        { title: 'HN' },
        { title: 'admit date' },
        { title: 'Patient' },
        { title: 'j_id' },
        { title: 'j_patient_id' },
        { title: 'j_user_id' },
        { title: 'j_job_role_id' },
        { title: 'j_jobname' },
        { title: 'j_owners' },
        { title: 'qty' },
        { title: 'finish_date' }
        ],
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
            },
            
            {
            extend: 'collection',
            text: 'ระยะเวลาย้อนหลัง',
            autoClose: true,
            buttons: [{
                text: '1 เดือนล่าสุด',
                action: function (e, dt, node, config) {
                    dt.ajax.url("data/job2_finish.php?skey=" + skey + "&range=1m").load();
                    $('#title_page_message').text('กำลังแสดงจากข้อมูลย้อนหลัง 1 เดือน');

                }
            },
            {
                text: '3 เดือนล่าสุด',
                action: function (e, dt, node, config) {
                    dt.ajax.url("data/job2_finish.php?skey=" + skey + "&range=3m").load();
                    $('#title_page_message').text('กำลังแสดงจากข้อมูลย้อนหลัง 3 เดือน');
                }
            },
            {
                text: '6 เดือนล่าสุด',
                action: function (e, dt, node, config) {
                    dt.ajax.url("data/job2_finish.php?skey=" + skey + "&range=6m").load();
                    $('#title_page_message').text('กำลังแสดงจากข้อมูลย้อนหลัง 6 เดือน');
                }
            },
            {
                text: '1 ปีล่าสุด',
                action: function (e, dt, node, config) {
                    dt.ajax.url("data/job2_finish.php?skey=" + skey + "&range=1y").load();
                    $('#title_page_message').text('กำลังแสดงจากข้อมูลย้อนหลัง 1 ปี');
                }
            },
            {
                text: '2 ปีล่าสุด',
                action: function (e, dt, node, config) {
                    dt.ajax.url("data/job2_finish.php?skey=" + skey + "&range=2y").load();
                    $('#title_page_message').text('กำลังแสดงจากข้อมูลย้อนหลัง 2 ปี');
                }
            },
            {
                text: 'ทั้งหมด',
                action: function (e, dt, node, config) {
                    dt.ajax.url("data/job2_finish.php?skey=" + skey + "").load();
                    $('#title_page_message').text('กำลังแสดงจากข้อมูลทั้งหมด');
                }
            }
            ]
        },],
        "order": [
            [0, "desc"]
        ],
        searchPanes: {
            initCollapsed: true,
        },
        columnDefs: [
        {
            searchPanes: {
                show: true
            },
            targets: [ 3,10]
        },
        {
            searchPanes: {
                show: false
            },
            targets: [0, 1, 2,4, 5,6,7,8 ,9,11,12]
        },
        {
            visible: true,
            targets: [0, 1, 2, 3,4, 9,10,11,12]
        },
        {
            visible: false,
            targets: [5,6,7,8]
        },
         

        {
            "render": function (data, type, row) {
                return row[2];
            },
            "targets": 2
        }
        ],
        "initComplete": colorAdd,
    });

    // add color when reload
    table.on('draw', colorAdd);


    // delete user
    $('#patient_table tbody').on('click', 'a.delete', function (e) {
        var data = table.row($(this).parents('tr')).data();

        e.preventDefault();
        if (confirm("Item will move to trash. Are you sure?")) {
            let frm = $("<form>");

            frm.attr('method', 'post');
            frm.attr('action', "patient.php");

            $('<input>', {
                type: 'hidden',
                id: 'foo',
                name: 'delete_id',
                value: data[0]
            }).appendTo(frm);

            frm.appendTo("body");
            frm.submit();
        }
    });

    // trash patient
    $('#patient_table tbody').on('click', 'a.trash', function (e) {
        var data = table.row($(this).parents('tr')).data();

        e.preventDefault();
        if (confirm("Item will move to trash. Are you sure?")) {
            let frm = $("<form>");

            frm.attr('method', 'post');
            frm.attr('action', "patient.php");

            $('<input>', {
                type: 'hidden',
                id: 'foo',
                name: 'trash_id',
                value: data[0]
            }).appendTo(frm);

            frm.appendTo("body");
            frm.submit();


        }

    });

    // set active tab
    $("#patienttab").addClass("active");


});
</script>
<!--<script src="<?= Url::getSubFolder1() ?>/js/job2_finish.js?v0"></script>-->