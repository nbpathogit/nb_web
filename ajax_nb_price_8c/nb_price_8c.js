var nb_price_8c_data = [];
var nb_price_8c_dt = null;

function clearPriceTable_dt() {
    nb_price_8c_data = [];
    if ($.fn.dataTable.isDataTable('#nb_price_tbl_8c')) {
        $('#nb_price_tbl_8c').DataTable().clear().draw();
    } else {
        $('#nb_price_tbl_8c tbody').empty();
    }
}

function drawPriceTable_sub(datajson){
    nb_price_8c_data = Array.isArray(datajson) ? datajson : [];

    if ($.fn.dataTable.isDataTable('#nb_price_tbl_8c')) {
        $('#nb_price_tbl_8c').DataTable().clear().destroy();
    }

    var $tbody = $('#nb_price_tbl_8c tbody');
    $tbody.empty();

    for (var i = 0; i < nb_price_8c_data.length; i++) {
        var row = nb_price_8c_data[i];
        var str = '<tr>' +
                '<td>' + (row.id ?? '') + '</td>' +
                '<td>' + (row.hospital_id ?? '') + '</td>' +
                '<td>' + (row.number ?? '') + '</td>' +
                '<td>' + (row.speciment_num ?? '') + '</td>' +
                '<td>' + (row.specimen ?? '') + '</td>' +
                '<td>' + (row.unit_count ?? '') + '</td>' +
                '<td>' + (row.price ?? '') + '</td>' +
                '<td>' + (row.effective_date ?? row.start_date ?? '-') + '</td>' +
                '<td>' + (row.price_id ?? 0) + '</td>' +
                '<td>' + (row.comment ?? '') + '</td>' +
                '<td>' + (row.jobtype ?? '') + '</td>' +
                '<td>' + (row.service_des ?? '') + '</td>' +
                '<td>' + (row.create_date ?? '') + '</td>' +
                '<td>' + (row.add_user_id ?? '') + '</td>' +
                '<td>' + (row.edit_user_id ?? '') + '</td>' +
                '<td>' + (row.stampdatecreate ?? '-') + '</td>' +
                '</tr>';
        $tbody.append(str);
    }

    const searchPanesCol = {
        show: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 13, 14, 15],
        hide: [0, 1, 12]
    };

    nb_price_8c_dt = $('#nb_price_tbl_8c').DataTable({
        responsive: true,
        orderCellsTop: true,
        fixedHeader: true,
        dom: 'BP<"toolbar">lfritp',
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "ทั้งหมด (All)"]],
        order: [[2, 'asc']], // Order by 'number' column
        searchPanes: {
            initCollapsed: true,
        },
        buttons: [],
        columnDefs: [
            {
                searchPanes: {
                    show: true
                },
                targets: searchPanesCol.show
            },
            {
                searchPanes: {
                    show: false
                },
                targets: searchPanesCol.hide
            },
            { targets: [0, 1, 12], visible: false, searchable: false }, // row_id, hospital_id, create_date
            { targets: [5, 9, 13, 14], visible: false }, // default hidden columns: unit, comment, add by, edit by
            { targets: [2, 3, 6, 8, 10, 13, 14], className: 'text-center' },
            { targets: [7, 8, 15], className: 'text-nowrap' }
        ],
        initComplete: function () {
            var api = this.api();

            api.columns().every(function (colIdx) {
                var column = this;
                var $headerCell = $('#nb_price_tbl_8c thead tr.filters th').eq(colIdx);
                var title = $headerCell.text().trim();

                // Skip invisible or system columns
                if (colIdx === 0 || colIdx === 1 || colIdx === 12) {
                    $headerCell.empty();
                    return;
                }

                // Add search input to each header cell in filter row
                var input = document.createElement('input');
                input.className = 'form-control form-control-sm text-center';
                input.placeholder = title;
                input.style.width = '100%';
                input.style.minWidth = '65px';
                input.style.fontSize = '0.78rem';
                input.style.padding = '2px 4px';
                $headerCell.html(input);

                $(input).on('keyup change clear', function () {
                    if (column.search() !== this.value) {
                        column.search(this.value).draw();
                    }
                }).on('click', function (e) {
                    e.stopPropagation();
                });
            });
        },
        language: {
            search: "ค้นหา (Search):",
            lengthMenu: "แสดง _MENU_ รายการต่อหน้า",
            info: "แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
            infoEmpty: "ไม่มีข้อมูลสำหรับแสดง",
            infoFiltered: "(กรองจากทั้งหมด _MAX_ รายการ)",
            zeroRecords: "ไม่พบข้อมูลที่ตรงกับคำค้นหา",
            paginate: {
                first: "หน้าแรก",
                last: "หน้าสุดท้าย",
                next: "ถัดไป",
                previous: "ก่อนหน้า"
            },
            searchPanes: {
                title: {
                    _: 'ตัวกรองที่เลือก (%d รายการ)',
                    0: 'ไม่มีตัวกรองที่เลือก',
                    1: 'ตัวกรองที่เลือก (1 รายการ)'
                },
                clearMessage: 'ล้างตัวกรองทั้งหมด',
                collapse: {0: 'ตัวกรอง (SearchPanes)', _: 'ตัวกรอง (SearchPanes (%d))'},
                count: '{total}',
                countFiltered: '{shown} ({total})',
                emptyPanes: 'ไม่มีข้อมูลสำหรับตัวกรอง'
            }
        }
    });

    var toolbarEl = document.querySelector('div.toolbar');
    if (toolbarEl) {
        toolbarEl.innerHTML = `<div class="text-center mb-3 mt-1"><b>ซ่อนคอลัมม์ : </b>
        <a href="#" class="toggle-vis text-primary text-decoration-none me-1" data-column="2">ลำดับ</a> -
        <a href="#" class="toggle-vis text-primary text-decoration-none me-1" data-column="3">โค้ด</a> -
        <a href="#" class="toggle-vis text-primary text-decoration-none me-1" data-column="4">รายการ</a> -
        <a href="#" class="toggle-vis text-primary text-decoration-none me-1 text-decoration-line-through text-muted" data-column="5">ต่อหน่วย</a> -
        <a href="#" class="toggle-vis text-primary text-decoration-none me-1" data-column="6">ราคา</a> -
        <a href="#" class="toggle-vis text-primary text-decoration-none me-1" data-column="7">วันที่มีผล</a> -
        <a href="#" class="toggle-vis text-primary text-decoration-none me-1" data-column="8">price id</a> -
        <a href="#" class="toggle-vis text-primary text-decoration-none me-1 text-decoration-line-through text-muted" data-column="9">คอมเม้นท์</a> -
        <a href="#" class="toggle-vis text-primary text-decoration-none me-1" data-column="10">ไอดีชนิด</a> -
        <a href="#" class="toggle-vis text-primary text-decoration-none me-1" data-column="11">ชนิดของรายการ</a> -
        <a href="#" class="toggle-vis text-primary text-decoration-none me-1 text-decoration-line-through text-muted" data-column="13">add by</a> -
        <a href="#" class="toggle-vis text-primary text-decoration-none me-1 text-decoration-line-through text-muted" data-column="14">edit by</a> -
        <a href="#" class="toggle-vis text-primary text-decoration-none me-1" data-column="15">วันที่บันทึกระบบ</a>
        </div>`;
    }
}




function drawPriceTable(){
//    alert('drawPriceTable()');
    let type_id = $('#nb_price_type_8c option').filter(':selected').attr('value');
    let hospital_id = $('#nb_price_hospital_select_8c option').filter(':selected').attr('value');
    let hospital_name = $('#nb_price_hospital_select_8c option').filter(':selected').text().trim();
    $(".nb_price_hospital_txt").text(hospital_name);
    $(".nb_price_hospital_txt_8c").text('(' + hospital_name + ')');
    clearPriceTable_dt();
    $.ajax({
        'async': false,
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: 'ajax_nb_price_8c/get_specimen_8c.php',
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
            let datajson = JSON.parse(data);
            if(datajson.length == 0){
                nb_price_8c_data = [];
                $('#nb_price_del_btn_8c').prop('disabled', true);
                $('#nb_price_excel_btn_8c').prop('disabled', true);
                clearPriceTable_dt();
                alert("No record for this hospital");
                return;
            }
//             alert('data for table'+data);
             console.log(data);
             $('#nb_price_excel_btn_8c').prop('disabled', false);
             drawPriceTable_sub(datajson);

        },
        error: function (jqxhr, status, exception) {
            alert( jqxhr.responseText);
        }
    });
    
    

}


$("#nb_price_hospital_select_8c").on("change", function () {
    
    $('#nb_price_add_btn_8c').prop('disabled', false);
    $('#nb_price_del_btn_8c').prop('disabled', false);
    $('#nb_price_add_txt_area_8c').prop('disabled', false);

    $(".nb_price_hospital_txt_8c").text('');
    $(".nb_price_type_txt_8c").text('');

    clearPriceTable_dt();
    $("#nb_price_type_8c option").filter(function () {
        //may want to use $.trim in here
        return $(this).val() == 0;
    }).prop('selected', true);
        
    drawPriceTable();    
    if ($('#nb_price_history_filter_8c').val() === 'selected') {
        loadExcelUploadHistory8c();
    }

});


$("#nb_price_type_8c").on("change", function () {
//    alert('nb_price_type_8c change');
    var type_id = $('#nb_price_type_8c option').filter(':selected').attr('value');
    var hospital_id = $('#nb_price_hospital_select_8c option').filter(':selected').attr('value');
//    if (type_id == 0) {
//        $('#nb_price_tbl tbody tr').remove();
//        $('#nb_price_add_btn_8c').prop('disabled',true);
//        $('#nb_price_del_btn_8c').prop('disabled',true);
//        $('#nb_price_add_txt_area_8c').prop('disabled',true);
//        
//        $(".nb_price_hospital_txt_8c").text('');
//        $(".nb_price_type_txt_8c").text('');
//        return;
//    }
    
    $('#nb_price_add_btn_8c').prop('disabled',false);
    $('#nb_price_del_btn_8c').prop('disabled',false);
    $('#nb_price_add_txt_area_8c').prop('disabled',false);

    $(".nb_price_hospital_txt_8c").text('('+$("#nb_price_hospital_select_8c option").filter(':selected').text()+ ')' );
    $(".nb_price_type_txt_8c").text('('+$("#nb_price_type_8c option").filter(':selected').text()+')');
//    alert(type_id + '  ' + hospital_id);

    drawPriceTable();
    



});


$("#nb_price_del_btn_8c").on("click", function (e) {
    if(!confirm("Are you sure to delete ?")){
        return ;
    }
    
    var type_id = $('#nb_price_type_8c option').filter(':selected').attr('value');
    var hospital_id = $('#nb_price_hospital_select_8c option').filter(':selected').attr('value');
    
    $.ajax({
        'async': false,
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: 'ajax_nb_price_8c/delSpecimen_8c.php',
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
                nb_price_8c_data = [];
                $('#nb_price_del_btn_8c').prop('disabled', true);
                $('#nb_price_excel_btn_8c').prop('disabled', true);
                clearPriceTable_dt();
                alert("Record empty now");
                return;
            }
            
           //drawPriceTable(datajson);
            
        },
        error: function (jqxhr, status, exception) {
            alert( jqxhr.responseText);
        }
    });

    drawPriceTable();
    
    
    
});


$("#nb_price_add_btn_8c").on("click", function (e) {
//    alert($("#nb_price_add_txt_area_8c").val());
    let txt = $("#nb_price_add_txt_area_8c").val();
    txt = txt.trim();
    txt = txt.replaceAll("\"", "");
    
    console.log("txt:: " + txt);
    
    let lines = txt.split('\n');
    let data = [];
//    let jobtype =  $('#nb_price_type option').filter(':selected').attr('value');
    var hospital_id = $('#nb_price_hospital_select_8c option').filter(':selected').attr('value');
    var add_user_id = get_cur_user_id();
    var edit_user_id = 0;
    
    for (var i = 0; i < lines.length; i++) {
        //code here using lines[i] which will give you each line

        let line_cur = lines[i];
        let line = line_cur.split('\t');
        if(line[0].localeCompare("") == 0) continue;
        //number   speciment_num  specimen    unit_count   price  comment  | jobtype  hospital_id create_date add_user_id edit_user_id
        //ลำดับ	   code	          รายการ	      หน่วยนับ	   ราคา    comment  | 

            var startDateInput = ($('#nb_price_start_date_8c').val() || '').trim();
            var startDateVal = (line[8] && line[8].trim() !== '') ? line[8].trim() : startDateInput;
            if (!startDateVal) {
                alert('กรุณาเลือก "มีผลตั้งแต่วันที่ (Effective Date)" ก่อนทำการเพิ่มข้อมูล');
                $('#nb_price_start_date_8c').focus();
                return;
            }
            let str = "{" +
                    " \"number\": \""+line[0]+"\"," +
                    " \"speciment_num\": \""+line[1]+"\"," +
                    " \"specimen\": \""+line[2]+"\"," +
                    " \"unit_count\": \""+line[3]+"\"," +
                    " \"price\": \""+line[4]+"\"," +
                    " \"start_date\": \""+startDateVal+"\"," +
                    " \"comment\": \""+line[5]+"\"," +
                    " \"jobtype\": \""+line[6]+"\"," +
                    " \"hospital_id\": \""+hospital_id+"\"," +
                    " \"add_user_id\": \""+add_user_id+"\"," +
                    " \"edit_user_id\": \""+edit_user_id+"\"," +
                    " \"service_des\": \""+line[7]+"\"" +
                    
                    "}";
            console.log(str);
            
            try{
                
                JSON.parse(str);
            }catch (e){
               
                alert(e + str);
            }
            data.push((str));
            

    }
    
//    console.log(data);
//    alert('data'+data);
    
    //C:\anuchit2\nb_web\ajax_nb_price\addMultRecordiSpecimen.php
    $.ajax({
        'async': false,
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: 'ajax_nb_price_8c/addMultRecordiSpecimen_8c.php',
        data: {

            'data': data,


        },
        success: function (data) {
            console.log(data);
            //alert(data);
            //return;
            
            if (data[0] != "[") {
                alert("Error::"+data);
                console.log(data);
                return;
            }
            var datajson = JSON.parse(data);
            if(datajson.length == 0){
                $('#nb_price_del_btn_8c').prop('disabled', true);
                clearPriceTable_dt();
                alert("No record added");
                return;
            }
//            drawPriceTable(datajson);
            $("#nb_price_add_txt_area_8c").val("");
            alert("Record added");
            
            

        },
        error: function (jqxhr, status, exception) {
            alert( jqxhr.responseText);
        }
    });
    
    drawPriceTable();
    
});


function xmlEscape8c(str) {
    return String(str == null ? '' : str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&apos;');
}

function colLetter8c(colIdx) {
    var letter = '';
    var temp = colIdx + 1;
    while (temp > 0) {
        var mod = (temp - 1) % 26;
        letter = String.fromCharCode(65 + mod) + letter;
        temp = Math.floor((temp - mod) / 26);
    }
    return letter;
}

function buildWorksheetXml8c(rows) {
    var xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' +
        '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>';
    for (var r = 0; r < rows.length; r++) {
        var rowNum = r + 1;
        xml += '<row r="' + rowNum + '">';
        for (var c = 0; c < rows[r].length; c++) {
            var cellRef = colLetter8c(c) + rowNum;
            var val = rows[r][c];
            if (typeof val === 'number' && isFinite(val)) {
                xml += '<c r="' + cellRef + '"><v>' + val + '</v></c>';
            } else {
                xml += '<c r="' + cellRef + '" t="inlineStr"><is><t xml:space="preserve">' + xmlEscape8c(val) + '</t></is></c>';
            }
        }
        xml += '</row>';
    }
    xml += '</sheetData></worksheet>';
    return xml;
}

$("#nb_price_excel_btn_8c").on("click", function (e) {
    var $rows = $('#nb_price_tbl_8c tbody tr');
    if ($rows.length === 0 && (!nb_price_8c_data || nb_price_8c_data.length === 0)) {
        alert("No record in table to download.");
        return;
    }

    var hospitalName = $('#nb_price_hospital_select_8c option:selected').text().trim() || 'hospital';
    var typeVal = $('#nb_price_type_8c option:selected').val();
    var typeName = (typeVal == '0') ? 'all_types' : ($('#nb_price_type_8c option:selected').text().trim() || 'type');

    var now = new Date();
    var yyyy = now.getFullYear();
    var mm = String(now.getMonth() + 1).padStart(2, '0');
    var dd = String(now.getDate()).padStart(2, '0');
    var cleanHosp = hospitalName.replace(/[\\/:*?"<>|]/g, '_');
    var cleanType = typeName.replace(/[\\/:*?"<>|]/g, '_');
    var fileName = 'nb_price_8c_' + cleanHosp + '_' + cleanType + '_' + yyyy + mm + dd + '.xlsx';

    // Lookup map between service_type id and service_type description from dropdown
    var typeIdToName = {};
    var typeNameToId = {};
    $('#nb_price_type_8c option').each(function () {
        var val = String($(this).val() || '').trim();
        var txt = $(this).text().trim();
        if (val !== '' && val !== '0') {
            typeIdToName[val] = txt;
            if (txt !== '') {
                typeNameToId[txt] = val;
            }
        }
    });

    // 8 Columns matching web table headers:
    // 1 ลำดับ(number)
    // 2 โค้ด(code)
    // 3 รายการ(detail)
    // 4 ต่อหน่วย(unit)
    // 5 ราคา(price)
    // 6 คอมเม้นท์(comment)
    // 7 ไอดีชนิด(type)
    // 8 ชนิดของรายการ(type Description)
    var exportRows = [
        [
            'ลำดับ(number)',
            'โค้ด(code)',
            'รายการ(detail)',
            'ต่อหน่วย(unit)',
            'ราคา(price)',
            'คอมเม้นท์(comment)',
            'ไอดีชนิด(type)',
            'ชนิดของรายการ(type Description)',
            'วันที่มีผล(effective date)'
        ]
    ];

    if (nb_price_8c_data && nb_price_8c_data.length > 0) {
        for (var i = 0; i < nb_price_8c_data.length; i++) {
            var item = nb_price_8c_data[i];
            var numberVal = (item.number !== null && item.number !== undefined && String(item.number).trim() !== '')
                ? (!isNaN(item.number) ? Number(item.number) : String(item.number).trim())
                : '';

            var codeVal = (item.speciment_num !== null && item.speciment_num !== undefined && String(item.speciment_num).trim() !== '')
                ? (!isNaN(item.speciment_num) ? Number(item.speciment_num) : String(item.speciment_num).trim())
                : '';

            var detailVal = (item.specimen !== null && item.specimen !== undefined) ? String(item.specimen) : '';

            var unitVal = (item.unit_count !== null && item.unit_count !== undefined && String(item.unit_count).trim() !== '')
                ? String(item.unit_count).trim()
                : '';

            var priceVal = (item.price !== null && item.price !== undefined && String(item.price).trim() !== '')
                ? (!isNaN(item.price) ? Number(item.price) : String(item.price).trim())
                : '';

            var commentVal = (item.comment !== null && item.comment !== undefined) ? String(item.comment) : '';

            var rawTypeId = (item.jobtype !== null && item.jobtype !== undefined) ? String(item.jobtype).trim() : '';
            var rawTypeDes = (item.service_des !== null && item.service_des !== undefined) ? String(item.service_des).trim() : '';

            if ((rawTypeId === '' || rawTypeId === '0') && rawTypeDes !== '' && typeNameToId[rawTypeDes]) {
                rawTypeId = typeNameToId[rawTypeDes];
            }
            if (rawTypeDes === '' && rawTypeId !== '' && typeIdToName[rawTypeId]) {
                rawTypeDes = typeIdToName[rawTypeId];
            }

            var typeIdVal = (rawTypeId !== '' && rawTypeId !== '0')
                ? (!isNaN(rawTypeId) ? Number(rawTypeId) : rawTypeId)
                : '';

            var startDateVal = (item.effective_date !== null && item.effective_date !== undefined && String(item.effective_date).trim() !== '')
                ? String(item.effective_date).trim()
                : ((item.start_date !== null && item.start_date !== undefined && String(item.start_date).trim() !== '')
                    ? String(item.start_date).trim()
                    : ($('#nb_price_start_date_8c').val() || '2020-01-01'));

            exportRows.push([
                numberVal,
                codeVal,
                detailVal,
                unitVal,
                priceVal,
                commentVal,
                typeIdVal,
                rawTypeDes,
                startDateVal
            ]);
        }
    } else {
        $rows.each(function (idx) {
            var $tds = $(this).find('td');
            var rawNumber = $tds.eq(2).text().trim();
            var rawCode = $tds.eq(3).text().trim();
            var detailVal = $tds.eq(4).text().trim();
            var unitVal = $tds.eq(5).text().trim();
            var rawPrice = $tds.eq(6).text().trim();
            var rawStartDate = $tds.eq(7).text().trim();
            var commentVal = $tds.eq(8).text().trim();
            var rawTypeId = $tds.eq(9).text().trim();
            var rawTypeDes = $tds.eq(10).text().trim();

            if ((rawTypeId === '' || rawTypeId === '0') && rawTypeDes !== '' && typeNameToId[rawTypeDes]) {
                rawTypeId = typeNameToId[rawTypeDes];
            }
            if (rawTypeDes === '' && rawTypeId !== '' && typeIdToName[rawTypeId]) {
                rawTypeDes = typeIdToName[rawTypeId];
            }

            exportRows.push([
                (rawNumber !== '' && !isNaN(rawNumber)) ? Number(rawNumber) : rawNumber,
                (rawCode !== '' && !isNaN(rawCode)) ? Number(rawCode) : rawCode,
                detailVal,
                unitVal,
                (rawPrice !== '' && !isNaN(rawPrice)) ? Number(rawPrice) : rawPrice,
                commentVal,
                (rawTypeId !== '' && rawTypeId !== '0' && !isNaN(rawTypeId)) ? Number(rawTypeId) : (rawTypeId !== '0' ? rawTypeId : ''),
                rawTypeDes,
                (rawStartDate !== '' && rawStartDate !== '-') ? rawStartDate : ($('#nb_price_start_date_8c').val() || '2020-01-01')
            ]);
        });
    }

    if (typeof XLSX !== 'undefined') {
        var wb = XLSX.utils.book_new();
        var ws = XLSX.utils.aoa_to_sheet(exportRows);
        XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');
        XLSX.writeFile(wb, fileName);
        return;
    }

    if (typeof JSZip !== 'undefined') {
        var zip = new JSZip();
        zip.file('[Content_Types].xml',
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' +
            '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">' +
            '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>' +
            '<Default Extension="xml" ContentType="application/xml"/>' +
            '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>' +
            '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>' +
            '</Types>'
        );
        zip.folder('_rels').file('.rels',
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' +
            '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' +
            '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>' +
            '</Relationships>'
        );
        var xl = zip.folder('xl');
        xl.file('workbook.xml',
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' +
            '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">' +
            '<sheets>' +
            '<sheet name="Sheet1" sheetId="1" r:id="rId1"/>' +
            '</sheets>' +
            '</workbook>'
        );
        xl.folder('_rels').file('workbook.xml.rels',
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' +
            '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' +
            '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>' +
            '</Relationships>'
        );
        var worksheets = xl.folder('worksheets');
        worksheets.file('sheet1.xml', buildWorksheetXml8c(exportRows));

        zip.generateAsync({
            type: 'blob',
            mimeType: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        }).then(function (blob) {
            var url = URL.createObjectURL(blob);
            var a = document.createElement('a');
            a.href = url;
            a.download = fileName;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            setTimeout(function () { URL.revokeObjectURL(url); }, 1000);
        });
    }
});

function renderExcelHistoryTable8c(responseData) {
    var $tbody = $('#nb_price_excel_history_tbl_8c tbody');
    $tbody.empty();

    var historyList = [];
    var legacyData = null;

    if (responseData && typeof responseData === 'object' && !Array.isArray(responseData)) {
        historyList = responseData.history || [];
        legacyData = responseData.legacy || null;
    } else if (Array.isArray(responseData)) {
        historyList = responseData;
    }

    if ((!Array.isArray(historyList) || historyList.length === 0) && !legacyData) {
        $tbody.append('<tr><td colspan="9" class="text-center text-muted py-2">ยังไม่มีประวัติการอัปโหลดไฟล์ Excel</td></tr>');
        return;
    }

    var isAdmin = (typeof isCurUserAdmin !== 'undefined') && isCurUserAdmin;

    for (var i = 0; i < historyList.length; i++) {
        var h = historyList[i];
        var hospName = h.resolved_hospital_name || h.hospital_name || (h.hospital_id == 0 ? 'ราคามาตรฐาน' : ('Hospital #' + h.hospital_id));
        var savedName = h.saved_filename || h.original_filename || '-';
        var rowCount = (h.row_count !== null && h.row_count !== undefined) ? h.row_count : '-';
        var effectiveDate = h.effective_date || h.start_date || '-';
        var uploader = h.resolved_user_name || h.upload_user_name || ('User #' + (h.upload_user_id || '-'));
        var uploadDate = h.upload_date || '-';
        var downloadUrl = 'ajax_nb_price_8c/download_excel_8c.php?id=' + encodeURIComponent(h.id);
        var movetotrash = parseInt(h.movetotrash || 0, 10);

        var statusBadge = '';
        var actionBtns = '';
        var rowClass = '';

        if (movetotrash === 0) {
            // Normal active
            statusBadge = '<span class="badge bg-success mb-1">ใช้งานปกติ</span>';
            actionBtns = '<button type="button" class="btn btn-sm btn-outline-warning text-dark btn-trash-excel" data-id="' + h.id + '" title="ย้ายลงถังขยะ">' +
                         '<i class="fa-solid fa-trash-can me-1"></i>Move to Trash</button>';
        } else if (movetotrash === 1) {
            // In trash
            rowClass = 'table-secondary opacity-75';
            statusBadge = '<span class="badge bg-warning text-dark mb-1"><i class="fa-solid fa-trash me-1"></i>อยู่ในถังขยะ</span>';
            if (isAdmin) {
                actionBtns = '<div class="btn-group btn-group-sm" role="group">' +
                             '<button type="button" class="btn btn-primary btn-restore-excel" data-id="' + h.id + '" title="กู้คืน">' +
                             '<i class="fa-solid fa-rotate-left me-1"></i>Restore</button>' +
                             '<button type="button" class="btn btn-danger btn-perm-del-excel" data-id="' + h.id + '" data-hosp="' + xmlEscape8c(hospName) + '" title="ลบถาวร">' +
                             '<i class="fa-solid fa-circle-xmark me-1"></i>Delete</button>' +
                             '</div>';
            } else {
                actionBtns = '<span class="small text-muted">รอ Admin จัดการ</span>';
            }
        } else if (movetotrash === 2) {
            // Permanently deleted (record preserved, price list cleared, file kept for backup)
            rowClass = 'table-light text-muted';
            statusBadge = '<span class="badge bg-danger mb-1"><i class="fa-solid fa-ban me-1"></i>Permanent Deleted</span>';
            actionBtns = '<span class="badge bg-secondary">ลบราคาถาวรแล้ว (เก็บไฟล์ Backup)</span>';
        }

        var nameDisplay = (movetotrash === 2) 
            ? '<span class="text-decoration-line-through text-muted">' + xmlEscape8c(savedName) + '</span>'
            : '<a href="' + downloadUrl + '" class="fw-bold text-decoration-none">' + xmlEscape8c(savedName) + '</a>';

        var tr = '<tr class="' + rowClass + '">' +
            '<td class="text-center px-2">' + (i + 1) + ' <span class="badge bg-secondary ms-1">ID: ' + h.id + '</span></td>' +
            '<td class="px-2">' + xmlEscape8c(hospName) + '</td>' +
            '<td class="px-2">' + nameDisplay + '</td>' +
            '<td class="text-center px-2">' + xmlEscape8c(rowCount) + '</td>' +
            '<td class="text-center px-2">' + xmlEscape8c(effectiveDate) + '</td>' +
            '<td class="px-2">' + xmlEscape8c(uploader) + '</td>' +
            '<td class="px-2">' + xmlEscape8c(uploadDate) + '</td>' +
            '<td class="text-center px-2">' +
                '<div>' + statusBadge + '</div>' +
                '<div>' + actionBtns + '</div>' +
            '</td>' +
            '<td class="text-center px-2">' +
                '<a href="' + downloadUrl + '" class="btn btn-sm btn-outline-success" title="ดาวน์โหลดไฟล์ ' + xmlEscape8c(savedName) + '">' +
                    '<i class="fa-solid fa-download me-1"></i>Download' +
                '</a>' +
            '</td>' +
            '</tr>';
        $tbody.append(tr);
    }

    // Append special row for Legacy Data (price_id = 0, No excel file) if present
    if (legacyData && legacyData.row_count > 0) {
        var legHospName = (legacyData.hospital_id == 0)
            ? 'ราคามาตรฐาน'
            : ($('#nb_price_hospital_select_8c option[value="' + legacyData.hospital_id + '"]').text().trim() || ('Hospital #' + legacyData.hospital_id));
        if (legacyData.hospital_id == -1) {
            legHospName = 'ทุกโรงพยาบาลที่มีข้อมูลเดิม';
        }

        var legActionBtn = '';
        if (legacyData.hospital_id == -1) {
            // When viewing all hospitals, do not show delete button for safety
            legActionBtn = '<span class="small text-muted">เลือกเฉพาะโรงพยาบาลเพื่อจัดการ</span>';
        } else if (isAdmin) {
            legActionBtn = '<button type="button" class="btn btn-sm btn-danger btn-del-legacy-price" data-hosp-id="' + legacyData.hospital_id + '" data-hosp-name="' + xmlEscape8c(legHospName) + '" data-count="' + legacyData.row_count + '" title="ลบรายการราคาเดิมทั้งหมด (price_id=0)">' +
                           '<i class="fa-solid fa-trash-can me-1"></i>Delete (Admin)</button>';
        } else {
            legActionBtn = '<span class="small text-muted">Admin Only</span>';
        }

        var legacyTr = '<tr class="table-warning border-top border-2 border-warning">' +
            '<td class="text-center px-2 fw-bold text-secondary"><span class="badge bg-dark">ID: 0</span></td>' +
            '<td class="px-2 fw-bold">' + xmlEscape8c(legHospName) + '</td>' +
            '<td class="px-2"><span class="fw-bold text-dark">legacy data (No excel file)</span> <span class="badge bg-secondary ms-1">price_id = 0</span></td>' +
            '<td class="text-center px-2 fw-bold">' + legacyData.row_count + '</td>' +
            '<td class="text-center px-2">' + xmlEscape8c(legacyData.effective_date) + '</td>' +
            '<td class="px-2 text-muted">Legacy System</td>' +
            '<td class="px-2 text-muted">-</td>' +
            '<td class="text-center px-2">' +
                '<div><span class="badge bg-secondary mb-1">Legacy Data</span></div>' +
                '<div>' + legActionBtn + '</div>' +
            '</td>' +
            '<td class="text-center px-2 text-muted"><span class="small text-muted">- ไม่มีไฟล์ -</span></td>' +
            '</tr>';
        $tbody.append(legacyTr);
    }
}

// Event handler: Move to trash
$(document).on('click', '.btn-trash-excel', function () {
    var id = $(this).attr('data-id');
    if (!id) return;
    if (!confirm('ต้องการย้ายไฟล์ Excel (ID: ' + id + ') และรายการราคาที่เกี่ยวข้องลงถังขยะใช่หรือไม่?\n(เมื่อย้ายลงถังขยะแล้ว ระบบคนไข้จะไม่นำราคานี้ไปใช้)')) {
        return;
    }
    $.ajax({
        type: 'POST',
        url: 'ajax_nb_price_8c/manage_excel_trash_8c.php',
        data: { 'action': 'trash', 'id': id },
        dataType: 'json',
        success: function (res) {
            if (res.status === 'success') {
                alert(res.message);
                loadExcelUploadHistory8c();
                drawPriceTable();
            } else {
                alert(res.message || 'เกิดข้อผิดพลาด');
            }
        },
        error: function (jqxhr) {
            alert('Error: ' + jqxhr.responseText);
        }
    });
});

// Event handler: Restore (Admin)
$(document).on('click', '.btn-restore-excel', function () {
    var id = $(this).attr('data-id');
    if (!id) return;
    if (!confirm('ต้องการกู้คืนไฟล์ Excel (ID: ' + id + ') และรายการราคากลับมาใช้งานตามปกติใช่หรือไม่?')) {
        return;
    }
    $.ajax({
        type: 'POST',
        url: 'ajax_nb_price_8c/manage_excel_trash_8c.php',
        data: { 'action': 'restore', 'id': id },
        dataType: 'json',
        success: function (res) {
            if (res.status === 'success') {
                alert(res.message);
                loadExcelUploadHistory8c();
                drawPriceTable();
            } else {
                alert(res.message || 'เกิดข้อผิดพลาด');
            }
        },
        error: function (jqxhr) {
            alert('Error: ' + jqxhr.responseText);
        }
    });
});

// Event handler: Permanent Delete (Admin)
$(document).on('click', '.btn-perm-del-excel', function () {
    var id = $(this).attr('data-id');
    var hosp = $(this).attr('data-hosp') || '';
    if (!id) return;
    if (!confirm('ยืนยันลบรายการราคา (Permanent Delete) สำหรับ Excel ID: ' + id + ' (' + hosp + ') ใช่หรือไม่?\n\nคำเตือน:\n- รายการราคาของชุดนี้ใน service_price_list จะถูกลบถาวร\n- ไฟล์ Excel ต้นฉบับจะยังคงถูกเก็บไว้เป็น Backup บน Server\n- สถานะจะถูกเปลี่ยนเป็น Permanent Deleted')) {
        return;
    }
    $.ajax({
        type: 'POST',
        url: 'ajax_nb_price_8c/manage_excel_trash_8c.php',
        data: { 'action': 'delete', 'id': id },
        dataType: 'json',
        success: function (res) {
            if (res.status === 'success') {
                alert(res.message);
                loadExcelUploadHistory8c();
                drawPriceTable();
            } else {
                alert(res.message || 'เกิดข้อผิดพลาด');
            }
        },
        error: function (jqxhr) {
            alert('Error: ' + jqxhr.responseText);
        }
    });
});

// Event handler: Permanent Delete Legacy Records (Admin, price_id = 0)
$(document).on('click', '.btn-del-legacy-price', function () {
    var hospId = $(this).attr('data-hosp-id');
    var hospName = $(this).attr('data-hosp-name') || '';
    var count = $(this).attr('data-count') || 0;
    if (hospId === undefined || hospId === null || hospId === '') return;

    if (!confirm('ยืนยันลบรายการราคาเดิม (Legacy data: price_id = 0) ของ ' + hospName + ' ทั้งหมดจำนวน ' + count + ' รายการ ใช่หรือไม่?\n\nคำเตือน:\n- รายการราคาเดิมทั้งหมดที่ไม่มีไฟล์ Excel (price_id = 0) จะถูกลบถาวรออกจาก service_price_list\n- ไม่สามารถกู้คืนได้')) {
        return;
    }

    $.ajax({
        type: 'POST',
        url: 'ajax_nb_price_8c/manage_excel_trash_8c.php',
        data: {
            'action': 'delete_legacy',
            'id': 0,
            'hospital_id': hospId
        },
        dataType: 'json',
        success: function (res) {
            if (res.status === 'success') {
                alert(res.message);
                loadExcelUploadHistory8c();
                drawPriceTable();
            } else {
                alert(res.message || 'เกิดข้อผิดพลาด');
            }
        },
        error: function (jqxhr) {
            alert('Error: ' + jqxhr.responseText);
        }
    });
});

function loadExcelUploadHistory8c() {
    var filterMode = $('#nb_price_history_filter_8c').val();
    var hospital_id = -1;
    if (filterMode === 'selected') {
        hospital_id = $('#nb_price_hospital_select_8c option:selected').val() || 0;
    }

    $.ajax({
        type: 'POST',
        url: 'ajax_nb_price_8c/get_excel_history_8c.php',
        data: { 'hospital_id': hospital_id },
        dataType: 'json',
        success: function (data) {
            renderExcelHistoryTable8c(data);
        },
        error: function (jqxhr) {
            console.error('loadExcelUploadHistory8c error:', jqxhr.responseText);
        }
    });
}

$('#nb_price_history_filter_8c').on('change', function () {
    loadExcelUploadHistory8c();
});

$('#nb_price_upload_btn_8c').on('click', function () {
    var startDate = ($('#nb_price_start_date_8c').val() || '').trim();
    if (!startDate) {
        alert('กรุณาเลือก "มีผลตั้งแต่วันที่ (Effective Date)" ก่อนทำการอัปโหลดไฟล์ Excel');
        $('#nb_price_start_date_8c').focus();
        return;
    }
    $('#nb_price_upload_input_8c').val('');
    $('#nb_price_upload_input_8c').trigger('click');
});

$('#nb_price_upload_input_8c').on('change', function (e) {
    var file = e.target.files && e.target.files[0];
    if (!file) {
        return;
    }

    var startDate = ($('#nb_price_start_date_8c').val() || '').trim();
    if (!startDate) {
        alert('กรุณาเลือก "มีผลตั้งแต่วันที่ (Effective Date)" ก่อนทำการอัปโหลดไฟล์ Excel');
        $('#nb_price_start_date_8c').focus();
        $(this).val('');
        return;
    }

    var hospital_id = $('#nb_price_hospital_select_8c option:selected').val() || '0';
    var hospital_name = $('#nb_price_hospital_select_8c option:selected').text().trim() || 'ราคามาตรฐาน';
    var type_id = $('#nb_price_type_8c option:selected').val() || '0';
    var replace_existing = '0';

    if (!confirm('ยืนยันอัปโหลดไฟล์ "' + file.name + '"\nสำหรับโรงพยาบาล: ' + hospital_name + '\nวันที่มีผลบังคับใช้ (Effective Date): ' + startDate + ' ?')) {
        $(this).val('');
        return;
    }

    function sendUploadRequest(parsedRowsJson) {
        var formData = new FormData();
        formData.append('excel_file', file);
        formData.append('hospital_id', hospital_id);
        formData.append('hospital_name', hospital_name);
        formData.append('type_id', type_id);
        formData.append('replace_existing', replace_existing);
        formData.append('start_date', $('#nb_price_start_date_8c').val() || '');
        if (parsedRowsJson) {
            formData.append('parsed_rows', parsedRowsJson);
        }

        $('#nb_price_upload_btn_8c').prop('disabled', true);

        $.ajax({
            type: 'POST',
            url: 'ajax_nb_price_8c/upload_excel_8c.php',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (resp) {
                $('#nb_price_upload_btn_8c').prop('disabled', false);
                $('#nb_price_upload_input_8c').val('');

                if (!resp || resp.status !== 'success') {
                    alert('Error: ' + (resp && resp.message ? resp.message : 'ไม่สามารถอัปโหลดไฟล์ได้'));
                    return;
                }

                alert(resp.message);
                drawPriceTable();
                loadExcelUploadHistory8c();
            },
            error: function (jqxhr) {
                $('#nb_price_upload_btn_8c').prop('disabled', false);
                $('#nb_price_upload_input_8c').val('');
                alert('Upload error: ' + jqxhr.responseText);
            }
        });
    }

    if (typeof XLSX !== 'undefined' && typeof FileReader !== 'undefined') {
        var reader = new FileReader();
        reader.onload = function (evt) {
            try {
                var data = new Uint8Array(evt.target.result);
                var workbook = XLSX.read(data, { type: 'array' });
                var firstSheetName = workbook.SheetNames[0];
                var worksheet = workbook.Sheets[firstSheetName];
                var rows = XLSX.utils.sheet_to_json(worksheet, { header: 1, defval: '' });

                // Client-side validation before uploading
                var validServiceTypeIds = {};
                var validServiceTypeIdsList = [];
                $('#nb_price_type_8c option').each(function () {
                    var v = String($(this).val() || '').trim();
                    if (v !== '' && v !== '0') {
                        validServiceTypeIds[v] = true;
                        validServiceTypeIdsList.push(v);
                    }
                });

                var validationErrors = [];
                for (var rIdx = 0; rIdx < rows.length; rIdx++) {
                    var r = rows[rIdx];
                    if (!Array.isArray(r)) continue;
                    var c0 = String(r[0] !== undefined && r[0] !== null ? r[0] : '').trim();
                    var c1 = String(r[1] !== undefined && r[1] !== null ? r[1] : '').trim();
                    var c2 = String(r[2] !== undefined && r[2] !== null ? r[2] : '').trim();
                    var c3 = String(r[3] !== undefined && r[3] !== null ? r[3] : '').trim();
                    var c4 = String(r[4] !== undefined && r[4] !== null ? r[4] : '').trim();
                    var c5 = String(r[5] !== undefined && r[5] !== null ? r[5] : '').trim();
                    var c6 = String(r[6] !== undefined && r[6] !== null ? r[6] : '').trim();
                    var c7 = String(r[7] !== undefined && r[7] !== null ? r[7] : '').trim();
                    var c8 = String(r[8] !== undefined && r[8] !== null ? r[8] : '').trim();

                    if (c0 === '' && c1 === '' && c2 === '' && c3 === '' && c4 === '' && c5 === '' && c6 === '' && c7 === '' && c8 === '') {
                        continue;
                    }

                    if (
                        rIdx === 0 && (
                            c0.indexOf('ลำดับ') !== -1 ||
                            c0.toLowerCase().indexOf('row_id') !== -1 ||
                            c0.toLowerCase().indexOf('number') !== -1 ||
                            c1.indexOf('โค้ด') !== -1 ||
                            c1.toLowerCase().indexOf('code') !== -1 ||
                            c2.indexOf('รายการ') !== -1
                        )
                    ) {
                        continue;
                    }

                    var rowErrs = [];
                    if (c0 === '' || !/^\d+$/.test(c0)) {
                        rowErrs.push("ลำดับ(number) ต้องเป็นตัวเลขเท่านั้น (พบ: '" + c0 + "')");
                    }
                    if (c1 === '' || !/^\d+$/.test(c1)) {
                        rowErrs.push("โค้ด(code) ต้องเป็นตัวเลขเท่านั้นและห้ามเว้นว่าง (พบ: '" + c1 + "')");
                    }
                    if (c2 === '') {
                        rowErrs.push("รายการ(detail) ห้ามเว้นว่าง");
                    }
                    var cleanP = c4.replace(/,/g, '');
                    if (cleanP === '' || !/^\d+(\.\d+)?$/.test(cleanP)) {
                        rowErrs.push("ราคา(price) ต้องเป็นตัวเลขเท่านั้นและห้ามเว้นว่าง (พบ: '" + c4 + "')");
                    }
                    if (c6 === '' || !/^\d+$/.test(c6)) {
                        rowErrs.push("ไอดีชนิด(type) ต้องเป็นตัวเลขเท่านั้นและห้ามเว้นว่าง (พบ: '" + c6 + "')");
                    } else if (!validServiceTypeIds[String(parseInt(c6, 10))]) {
                        rowErrs.push("ไอดีชนิด(type) '" + c6 + "' ไม่อยู่ในตาราง service_type (ค่าที่ใช้ได้: " + validServiceTypeIdsList.join(', ') + ")");
                    }

                    if (rowErrs.length > 0) {
                        validationErrors.push("แถวที่ " + (rIdx + 1) + ": " + rowErrs.join(' | '));
                    }
                }

                if (validationErrors.length > 0) {
                    var maxShow = 10;
                    var shown = validationErrors.slice(0, maxShow);
                    var msg = "พบข้อผิดพลาดของข้อมูลในไฟล์ Excel (ยกเลิกการอัปโหลด):\n- " + shown.join("\n- ");
                    if (validationErrors.length > maxShow) {
                        msg += "\n... และอีก " + (validationErrors.length - maxShow) + " แถว";
                    }
                    alert(msg);
                    $('#nb_price_upload_input_8c').val('');
                    return;
                }

                sendUploadRequest(JSON.stringify(rows));
            } catch (err) {
                console.warn('Client XLSX parse fallback to server:', err);
                sendUploadRequest('');
            }
        };
        reader.onerror = function () {
            sendUploadRequest('');
        };
        reader.readAsArrayBuffer(file);
    } else {
        sendUploadRequest('');
    }
});

$(document).ready(function () {

    $('#nb_price_add_btn_8c').prop('disabled', false);
    $('#nb_price_del_btn_8c').prop('disabled', false);
    $('#nb_price_excel_btn_8c').prop('disabled', false);
    $('#nb_price_upload_btn_8c').prop('disabled', false);
    $('#nb_price_add_txt_area_8c').prop('disabled', false);

    // Toggle column visibility like patient.php
    $(document).off('click', 'a.toggle-vis').on('click', 'a.toggle-vis', function (e) {
        e.preventDefault();
        if (!nb_price_8c_dt) return;
        var columnIdx = $(this).attr('data-column');
        var column = nb_price_8c_dt.column(columnIdx);
        column.visible(!column.visible());
        $(this).toggleClass('text-decoration-line-through text-muted');
    });

    drawPriceTable();
    loadExcelUploadHistory8c();

});