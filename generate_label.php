<?php
//session_start();
require 'includes/init.php';
$conn = require 'includes/db.php';
Auth::requireLogin();
require 'user_auth.php';
//var_dump($_POST);





//Add record to database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    

    
    if (isset($_POST['add'])) {

        $labelPrint = new LabelPrint();

        $labelPrint->userid = $_POST['userid'];
        $labelPrint->sn_num = $_POST['sn_num'];
        $labelPrint->hn_num = $_POST['hn_num'];
        $labelPrint->patho_abbreviation = $_POST['patho_abbreviation'];
        $labelPrint->speciment_abbreviation = $_POST['speciment_abbreviation'];
        $labelPrint->accept_date = $_POST['accept_date'];

        if ($labelPrint->create($conn)) {

            Url::redirect("/generate_label.php");
        } else {
            echo '<script>alert("Add user fail. Please verify again")</script>';
        }
    }
    
    
    
    if (isset($_POST['delAll'])) {


        if (LabelPrint::deleteAllbyUserID($conn, $_POST['userid'])) {
            Url::redirect("/generate_label.php");
        } else {
            echo '<script>alert("Delete fail. Please verify again")</script>';
        }
    }
    
    
//    if (isset($_POST['viewpdf1'])) {
//        Url::redirect("/patient_sn_pdf1.php");
//    }
}




//Get Specific Row from Table for generate pdf
$labelPrints = LabelPrint::getAllbyUserID($conn, $_SESSION['userid']);

//
$patientLists = Patient::getAllJoin_forlableprint($conn, 1);

//var_dump($patientLists);


if (!$labelPrints) {
    // Skip show table
}
?>


<?php require 'includes/header.php'; ?>

<style>

    /*    th,td{
            border: 1px solid black;
            border-collapse: collapse;
            text-align: center;
            font-size: 1rem;
            font-weight: 400;
            line-height: 0.5;
        }*/
</style>



<div id="patient_plan_section" class="container-fluid pt-4 px-4">

    <div class="bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
        <h1>Table List to print out label</h1>
        
        <style>
            table {
              border-collapse: collapse;
            }
            th, td {
              border: 1px solid black;

              text-align: center;
            }
            th {
              background-color: #f2f2f2;
            }
        </style>
        
        <table id="tableforprintlabel"> 
            <tr>
                <th>id</td>
                <th>User_ID </th>
                <th>SN_Num</th>
                <th>HN_Num</th>
                <th>PathoAbrev</th>
                <th>SpeciAbrev</th>
                <th>admit_date</th>
                <th>Org.</th>

            </tr>
            <?php foreach ($labelPrints as $labelprint) : ?>
                <tr id="" border="1">
                    <td border="1"> <?= $labelprint['id'] ?> </td>
                    <td> <?= $labelprint['userid'] ?> </td>
                    <td> <?= $labelprint['sn_num'] ?> </td>
                    <td> <?= $labelprint['hn_num'] ?> </td>
                    <td> <?= $labelprint['patho_abbreviation'] ?> </td>
                    <td> <?= $labelprint['speciment_abbreviation'] ?> </td>
                    <td> <?= $labelprint['accept_date'] ?> </td>
                    <td> <?= $labelprint['company_name'] ?> </td>
                </tr>
            <?php endforeach; ?>

        </table>
        
        <form action="" method="post" class="">
            <div class="">
                <button name="delAll" type="submit" class="btn btn-primary" >&nbsp;&nbsp;Remove all list&nbsp;&nbsp;</button>
            </div>
            <input type="hidden" name="userid"  readonly="readonly" value="<?= $_SESSION['userid'] ?>">
        </form>
        
        
    </div>

    <br>

    <div id="insert_label_list_section"  class="bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
        <h1>Fill in data for insert to list</h1>
        <form action="" id="form_add_record" method="post" class="">
            <div class="row <?= $isBorder ? "border" : "" ?>">
                
                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
                    <label for="target_accept_date" class="form-label">accept date: </label>
                    <input type="text" name="target_accept_date" id="target_accept_date" class="form-control">
                </div>
                
                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
                    <label for="button_accept_date" class="form-label">Retrive: </label><br>
                    <a name="button_accept_date" type="" id="button_accept_date"  class="btn btn-primary" onclick="retrivepnumbyacceptdate()">&nbsp;&nbsp;Retrive&nbsp;&nbsp;</a>
                </div>
                
                
                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
                    <span id="pnum_id_span">
                    <label for="pnum_id" class="">SN Number: </label>
                    <select name="pnum_id" id="pnum_id" class="" required>
                        <option value=""></option>
                        <?php foreach ($patientLists as $patient) : ?>
                            <option value="<?= htmlspecialchars($patient['pid']); ?>" 
                                    p_pnum="<?= htmlspecialchars($patient['p_pnum']); ?>" 
                                    patho_abbreviation="<?= htmlspecialchars($patient['ab_patho']); ?>" 
                                    accept_date="<?= htmlspecialchars($patient['accept_date']); ?>" 
                                    ><?= htmlspecialchars($patient['p_pnum']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    </span>
                </div>
                
                

                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
                    <label for="patho_abbreviation" class="form-label">patho_Abbreviation: </label>
                    <input type="text" name="patho_abbreviation" id="patho_abbreviation" class="form-control" value="" readonly required>
                </div>

                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
                    <label for="accept_date" class="form-label">accept_date: </label>
                    <input type="text" name="accept_date" id="accept_date" class="form-control" value="" readonly required>
                </div>
            
            
                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
                    <label for="hn_num" class="form-label">hn_num: </label>
                    <input type="text" name="hn_num" id="hn_num" class="form-control" value="" readonly required>
                </div>
                
            </div>
            <br>
            
            
            
            <hr>
            <div class="row <?= $isBorder ? "border" : "" ?>">
                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
                    <!--<label for="" class="form-label">Select Letter::</label>-->
                    Select Letter::
                    <?php
                    echo '<select name="letter" id="letter" required>';
                    //echo '<option value=""></option>';
                    foreach (range('A', 'Z') as $letter) {
                        echo '<option value="' . $letter . '">' . $letter . '</option>';
                    }
                    echo '</select>';
                    ?>
                </div>
                
                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
                    <!--<label for="" class="form-label">           Number from::</label>-->
                    Number from::
                    <?php
                    echo '<select name="start_num" id="start_num"  required>';
                    //echo '<option value=""></option>';
                    for ($i = 1; $i <= 99; $i++) {
                        echo '<option value="' . $i . '">' . $i . '</option>';
                    }
                    echo '</select>';
                    ?>
                </div>
                    
                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
                    <!--<label for="" class="form-label"> Number to::</label>-->
                    Number to::
                    <?php
                    echo '<select name="end_num" id="end_num" required>';
                    //echo '<option value=""></option>';
                    for ($i = 1; $i <= 99; $i++) {
                        echo '<option value="' . $i . '">' . $i . '</option>';
                    }
                    echo '</select>';
                    ?>
                </div>
                
            </div>
            
            <br>
         
            
            <div class="">
                <button name="add" type="submit" class="btn btn-primary" >&nbsp;&nbsp;Add to list&nbsp;&nbsp;</button>
            </div>
            <input type="hidden" name="userid" id="userid" readonly="readonly" value="<?= $_SESSION['userid'] ?>">


        </form>

        <br>

        </div>
    <br>
    
    
    

    <!-- ===============================================
    button to show pdf section =========================
    ====================================================-->
    <hr>
    <h1>
        Generate Sticker Slide
    </h1>
    <div id="insert_label_list_section"  class="bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
        <h3>1. Generate PDF (Paper A4) (Sticker Label 2.3x2.0 cm)  </h3>
        <div class="">
            
            <label for="1A">A:</label>
            <input type="text" id="1A" name="1A" value="2.5">

            <label for="1B">B:</label>
            <input type="text" id="1B" name="1B"  value="2.5">

            <label for="1X">X:</label>
            <input type="text" id="1X" name="1X"  value="3.6">

            <label for="1Y">Y:</label>
            <input type="text" id="1Y" name="1Y"  value="6.0">
            
            <br><br>
                <button name="viewpdf1" id="viewpdf1a" class="btn btn-primary" onclick="onBtnViewPdf1A()">&nbsp;&nbsp;Generate with grid line&nbsp;&nbsp;</button>
                <button name="viewpdf1" id="viewpdf1b" class="btn btn-primary" onclick="onBtnViewPdf1B()">&nbsp;&nbsp;Generate with no grid line&nbsp;&nbsp;</button>
        </div>
    </div>

        
    <div id="insert_label_list_section"  class="bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">    
        <h3>2. Generate PDF (Paper 76mm x 20mm) (Sticker Label 2.3x2.0cm x3 per row)  </h3>
        <div class="">
            <a href="<?= Url::currentURL() ?>/sn_pdf2.php?userid=<?= $_SESSION['userid'] ?>"  target="_blank">
                <button name="viewpdf2" id="viewpdf2" type="submit" class="btn btn-primary" >&nbsp;&nbsp;Generate with grid line&nbsp;&nbsp;</button>
            </a>
            <a href="<?= Url::currentURL() ?>/sn_pdf2.php?userid=<?= $_SESSION['userid'] ?>&ishideborder"  target="_blank">
                <button name="viewpdf2" id="viewpdf2" type="submit" class="btn btn-primary" >&nbsp;&nbsp;Generate with no grid line&nbsp;&nbsp;</button>
            </a>
        </div>
    </div>
    

    

    <!-- ===============================================
    button to show pdf section =========================
    ====================================================-->
    <hr>
    <h1>
        Generate Sticker Specimen (Print x2 per record)
    </h1>
    <div id="insert_label_list_section"  class="bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
        <h3>1. Generate PDF (Paper 5.0cmx2.5cm) (Sticker Label 5.0cmx2.5cm x1 per row)  </h3>
        <div class="">
            <a href="<?= Url::currentURL() ?>/sn_sp_pdf1.php?userid=<?= $_SESSION['userid'] ?>"  target="_blank">
                <button name="viewpdf3" id="viewpdf3" type="submit" class="btn btn-primary" >&nbsp;&nbsp;Generate with grid line&nbsp;&nbsp;</button>
            </a>
            <a href="<?= Url::currentURL() ?>/sn_sp_pdf1.php?userid=<?= $_SESSION['userid'] ?>&ishideborder"  target="_blank">
                <button name="viewpdf3" id="viewpdf3" type="submit" class="btn btn-primary" >&nbsp;&nbsp;Generate with no grid line&nbsp;&nbsp;</button>
            </a>
        </div>
    </div>
    <div id="insert_label_list_section"  class="bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
        <h3>2. Generate PDF (Paper 5.0cmx1.9cm) (Sticker Label 5.0cmx1.9cm x1 per row)  </h3>
        <div class="">
            <a href="<?= Url::currentURL() ?>/sn_sp_pdf2.php?userid=<?= $_SESSION['userid'] ?>"  target="_blank">
                <button name="viewpdf3" id="viewpdf3" type="submit" class="btn btn-primary" >&nbsp;&nbsp;Generate with grid line&nbsp;&nbsp;</button>
            </a>
            <a href="<?= Url::currentURL() ?>/sn_sp_pdf2.php?userid=<?= $_SESSION['userid'] ?>&ishideborder"  target="_blank">
                <button name="viewpdf3" id="viewpdf3" type="submit" class="btn btn-primary" >&nbsp;&nbsp;Generate with no grid line&nbsp;&nbsp;</button>
            </a>
        </div>
    </div>
    
    <hr>
    <div id="insert_label_list_section"  class="bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
        <h1>Configuration</h1>
        <img src="generate_label/configuration.png" alt="Configuration Image">
    </div>
</div>


<?php $hidden_data2dom=TRUE; ?>
<!-- Container where we will add the form -->
<div id="formContainer"></div>

<?php if (isset( $patientLists   )): ?>  
    <ul class="patientlist" id="patientlist" style="<?= $hidden_data2dom ? "display: none;" : "" ?>" >
        <?php foreach ($patientLists as $patient) : ?>
            <li 
                tabindex="<?= $patient['pid'] ?>" 
                pnum="<?= htmlspecialchars($patient['p_pnum']); ?>" 
                hn_num="<?= htmlspecialchars($patient['p_phospital_num']); ?>" 
                patho_abbreviation="<?= htmlspecialchars($patient['ab_patho']); ?>" 
                accept_date="<?= htmlspecialchars($patient['accept_date']); ?>" 
                >
                tabindex="<?= $patient['pid'] ?>" 
                pnum="<?= htmlspecialchars($patient['p_pnum']); ?>" 
                hn_num="<?= htmlspecialchars($patient['p_phospital_num']); ?>" 
                patho_abbreviation="<?= htmlspecialchars($patient['ab_patho']); ?>" 
                accept_date="<?= htmlspecialchars($patient['accept_date']); ?>" 
            </li>
        <?php endforeach; ?> 
    </ul>     
<?php endif; ?>



<?php require 'includes/footer.php'; ?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<!--<script src="https://code.jquery.com/jquery-3.6.0.js"></script>-->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script type="text/javascript">
    
    var resultArray;
    var targetpatient;
    var cur_user_id = "<?= $_SESSION['userid'] ?>";
    
    function retrivepnumbyacceptdate() {
        //let tardate = $("#target_accept_date").val();
        //alert("tardate::"+tardate);
        drawSelectionAndDOM();
    }
    
    
    $(function() {
        $("#target_accept_date").datepicker({
            dateFormat: 'yy-mm-dd'
        });

    });
    
    function openPdf1(x,y,a,b,ishideborder){
        if(ishideborder){
            window.open("sn_pdf1.php?userid="+cur_user_id+"&a="+a+"&b="+b+"&x="+x+"&y="+y+"&ishideborder", "_blank");
        }else{
            window.open("sn_pdf1.php?userid="+cur_user_id+"&a="+a+"&b="+b+"&x="+x+"&y="+y+"", "_blank");
        }
    }
    function onBtnViewPdf1A(){
        //alert("btn1a");
        let a = $("#1A").val();
        let b = $("#1B").val();
        let x = $("#1X").val();
        let y = $("#1Y").val();
        console.log('a::'+a);
        console.log('b::'+b);
        console.log('x::'+x);
        console.log('y::'+y);
//        alert('a');
        openPdf1(x,y,a,b,true);
    }
    function onBtnViewPdf1B(){
        //alert("btn1b");
        let a = $("#1A").val();
        let b = $("#1B").val();
        let x = $("#1X").val();
        let y = $("#1Y").val();
        console.log('a::'+a);
        console.log('b::'+b);
        console.log('x::'+x);
        console.log('y::'+y);
//        alert('a');
        openPdf1(x,y,a,b,true);
    }
    
    
    function drawSelectionAndDOM(){
        //$patientLists = Patient::getAllJoin_forlableprint($conn, 1);
        let user_id = $('#userid').val();
        let accept_date = $('#target_accept_date').val();
        let pnumjson;
        // get data from database
        $.ajax({
            'async': false,
            type: 'POST',
            'global': false,
            type: 'POST',
            url: 'ajax_data/generate_label_get_SN_by_date.php',
            data: {
                'user_id': user_id,
                'accept_date': accept_date,
            },
            success: function (data) {
                console.log("data::");
                console.log(data);//print json string
                //{"pid":"35101","p_pnum":"CN2600061","p_phospital_num":"489883","name_patho":"\u0e08\u0e38\u0e25\u0e34\u0e19\u0e17\u0e23\u0e2a\u0e33\u0e23\u0e32\u0e0d","ab_patho":"JS.","accept_date":"2026-01-13"}
                pnumjson = JSON.parse(data); //convert String to JS Object
                for (var i in pnumjson)
                {
                    //{"id":"195","userid":"2","sn_num":"CN2501854","hn_num":"","patho_abbreviation":"AC.","speciment_abbreviation":"B1","accept_date":"31\/12\/2025","company_name":"N.B.Pathology","create_date":null},
                    console.log("pnumjson[i].pid"+pnumjson[i].pid);
                }

            },
            error: function (jqxhr, status, exception) {
                alert('Exception:', exception);
            }
        });
        
        //====== print selection.=====
        //==Expect output==
        //------before selectize---------
        //<div class="col-xl-4 col-md-6">
        //    <label for="pnum_id" class="">SN Number: </label>
        //    <select name="pnum_id" id="pnum_id" class="" required>
        //        <option value=""></option>
        //       
        //            <option value="pid" 
        //                    p_pnum="p_pnum" 
        //                    patho_abbreviation="ab_patho" 
        //                    accept_date="accept_date" 
        //                    >
        //            </option>
        //    </select>
        //</div>
        //-------------After apply selectize------------
        ////<select name="pnum_id" id="pnum_id" class="selectized" tabindex="-1" style="display: none;">
        //	<option value="" selected="selected"></option>
        //</select>
        //<div class="selectize-control single">
        //	<div class="selectize-input items required invalid not-full has-options">
        //		<input type="select-one" autocomplete="off" tabindex="" id="pnum_id-selectized" required="" style="width: 4px; opacity: 1; position: relative; left: 0px;">
        //	</div>
        //	<div class="selectize-dropdown single" style="display: none; visibility: visible; width: 380.067px; top: 34.1333px; left: 0px;">
        //		<div class="selectize-dropdown-content">
        //			<div class="option" data-selectable="" data-value="35325">LN2600004</div>
        //			<div class="option" data-selectable="" data-value="35324">LN2600003</div>
        //			<div class="option" data-selectable="" data-value="35323">SN2600599</div>
        //			<div class="option" data-selectable="" data-value="34299">CN2501822</div>
        //		</div>
        //	</div>
        //</div>
        //--------------------------

        // Step 1: Select the dropdown
        let $select_pnum_id_span = $("#pnum_id_span");

        // Step 2: Clear existing options
        $select_pnum_id_span.empty();
        
        // Create and append a new label element with attributes
        // <label for="pnum_id" class="">SN Number: </label>
        let newLabel = $("<label>", {
          for: "pnum_id",
          class: "",
          text: "SN Number: " // text inside the label
        });
        $select_pnum_id_span.append(newLabel);
        
        // Create and append a new select element with attributes
        // <select name="pnum_id" id="pnum_id" class="" required>
        let newselectBox = $("<select>", {
          name: "pnum_id",
          id: "pnum_id",
          class: "",       // empty class here
          required: true   // required attribute
        });
        // Optionally add a default option
        newselectBox.append($("<option>", {
          value: "",
          text: "-- Select SN --"
        }));
        
        $select_pnum_id_span.append(newselectBox);

        // Step 3: Loop through JSON and add new options
        //{"pid":"34796","p_pnum":"PN2600008","p_phospital_num":"7995","name_patho":"\u0e07","ab_patho":"AC.","accept_date":"2026-01-07"}
                  
        $.each(pnumjson, function(index, item) {
//            console.log();
//            console.log("item.pid"+item.pid);
//            console.log("item.p_pnum"+item.p_pnum);
//            console.log("item.p_phospital_num"+item.p_phospital_num);
//            console.log("item.name_patho"+item.name_patho);
//            console.log("item.ab_patho"+item.ab_patho);
//            console.log("item.accept_date"+item.accept_date);
            //-------------Draw following structure-------------
            //            <option value="pid" 
            //                    p_pnum="p_pnum" 
            //                    patho_abbreviation="ab_patho" 
            //                    accept_date="accept_date" 
            //                    >
            //            </option>
            //-------------------------------------------------
            newselectBox.append($("<option>", {
              value: item.pid,
              p_pnum: item.p_pnum,
              p_phospital_num: item.p_phospital_num,
              name_patho: item.name_patho,
              ab_patho: item.ab_patho,
              accept_date: item.accept_date,
              text: item.p_pnum,
            }));
        });
        
        $('#pnum_id').selectize({
//            sortField: 'text'
        });
        

        
        
        
        //====== print to DOM========
        //----Expect data to DOM---
        //<ul class="patientlist" style="display: none">
        //        <li 
        //            tabindex=" $patient['pid'] " 
        //            pnum=" ($patient['p_pnum']); " 
        //            hn_num=" ($patient['p_phospital_num']); " 
        //            patho_abbreviation=" ($patient['ab_patho']); " 
        //            accept_date=" ($patient['accept_date']); " 
        //            >
        //            tabindex=" $patient['pid'] " 
        //            pnum=" ($patient['p_pnum']); " 
        //            hn_num=" ($patient['p_phospital_num']); " 
        //            patho_abbreviation=" ($patient['ab_patho']); " 
        //            accept_date=" ($patient['accept_date']); " 
        //        </li>
        //</ul>  
        //--------------------------------------
        let uldom = $("#patientlist");
        uldom.empty();
        
        $.each(pnumjson, function(index, item) {
//            console.log();
//            console.log("item.pid"+item.pid);
//            console.log("item.p_pnum"+item.p_pnum);
//            console.log("item.p_phospital_num"+item.p_phospital_num);
//            console.log("item.name_patho"+item.name_patho);
//            console.log("item.ab_patho"+item.ab_patho);
//            console.log("item.accept_date"+item.accept_date);

            uldom.append($("<li>", {
              tabindex: item.pid,
              pnum: item.p_pnum,
              hn_num: item.p_phospital_num,
              patho_abbreviation: item.ab_patho,
              ab_patho: item.ab_patho,
              accept_date: item.accept_date,
              text: (item.p_pnum+'::'+item.p_phospital_num+'::'+item.name_patho+'::'+item.ab_patho+'::'+item.accept_date),
            }));
        });
        //============================================================================
        inifunction();
        
        //alert("pause");
        
    }
    
    
    function drawtableforprintlabel() {
        let user_id = $('#userid').val();
        let datajson;
        // get data from database
        $.ajax({
            'async': false,
            type: 'POST',
            'global': false,
            type: 'POST',
            url: 'ajax_data/generate_label_get_record.php',
            data: {
                'user_id': user_id,
            },
            success: function (data) {
                console.log(data);//print json string
                datajson = JSON.parse(data); //convert String to JS Object
                for (var i in datajson)
                {
                    //{"id":"195","userid":"2","sn_num":"CN2501854","hn_num":"","patho_abbreviation":"AC.","speciment_abbreviation":"B1","accept_date":"31\/12\/2025","company_name":"N.B.Pathology","create_date":null},
                    console.log(datajson[i].id);
                }

            },
            error: function (jqxhr, status, exception) {
                alert('Exception:', exception);
            }
        });

        
        // print to table
        // Get the table element by ID 
        let $tablelabel = $("#tableforprintlabel"); 
        
        // Remove everything inside the table (rows, cells, etc.) 
        $tablelabel.empty();
        
        //Append a new row 
        $tablelabel.append("<tr> <th>id</td> <th>User_ID </th> <th>SN_Num</th> <th>HN_Num</th> <th>PathoAbrev</th> <th>SpeciAbrev</th> <th>admit_date</th> <th>Org.</th> </tr>");
        for (var i in datajson)
        {
            let id =datajson[i].id;
            //{"id":"195","userid":"2","sn_num":"CN2501854","hn_num":"","patho_abbreviation":"AC.","speciment_abbreviation":"B1","accept_date":"31\/12\/2025","company_name":"N.B.Pathology","create_date":null},
            $tablelabel.append("<tr>"+
                "<td>"+datajson[i].id+"</td>"+
                "<td>"+datajson[i].userid+" </td>"+
                "<td>"+datajson[i].sn_num+"</td>"+
                "<td>"+datajson[i].hn_num+"</td>"+
                "<td>"+datajson[i].patho_abbreviation+"</td>"+
                "<td>"+datajson[i].speciment_abbreviation+"</td>"+
                "<td>"+datajson[i].accept_date+"</td>"+
                "<td>"+datajson[i].company_name+"</td>"+
                "</tr>");
        
            console.log(datajson[i].id);
        }
        
    }

    
    function convertDateFormat(dateStr) {
        // Split the string into parts
        const parts = dateStr.split("-"); // ["2025", "12", "08"]

        const year = parts[0];
        const month = parts[1];
        const day = parts[2];

        // Return in DD/MM/YYYY format
        return `${day}/${month}/${year}`;
    }
    
    function inifunction(){
        
        //=============Add record to database===========================================
        $("#form_add_record").on("submit", function(e) {
            e.preventDefault(); // prevent normal form submission

            // prepare all parameter
            let patient_id = targetpatient.tabindex;
            let userid = $('#userid').val();
            let sn_num = targetpatient.pnum;
            let hn_num = targetpatient.hn_num;
            let patho_abbrev = targetpatient.patho_abbreviation;
            let accept_date = convertDateFormat(targetpatient.accept_date);
            let company_name = "N.B.Pathology";
            let letter = $('#letter').selectize()[0].selectize.getValue();
            let start_num = $('#start_num').selectize()[0].selectize.getValue();
            let end_num = $('#end_num').selectize()[0].selectize.getValue();

            // Print to console
            console.log("patient_id:", patient_id);
            console.log("userid:", userid);
            console.log("sn_num:", sn_num);
            console.log("hn_num:", hn_num);
            console.log("patho_abbrev:", patho_abbrev);
            console.log("accept_date:", accept_date);
            console.log("company_name:", company_name);
            console.log("letter:", letter);
            console.log("start_num:", start_num);
            console.log("end_num:", end_num);

            //alert("submit");
      

            $.ajax({
                'async': false,
                type: 'POST',
                'global': false,
                type: 'POST',
                url: 'ajax_data/generate_label_add_record.php',
                data: {
                    'patient_id':  patient_id    ,
                    'userid':      userid        ,
                    'sn_num':      sn_num        ,
                    'hn_num':      hn_num        ,
                    'patho_abbrev':patho_abbrev  ,
                    'accept_date': accept_date   ,
                    'company_name':company_name  ,
                    'letter':      letter        ,
                    'start_num':   start_num     ,
                    'end_num':     end_num       ,
                },
                success: function (data) {
                    console.log(data);
                },
                error: function (jqxhr, status, exception) {
                    alert('Exception:', exception);
                }
            });
            
            drawtableforprintlabel();
 
        });


        //========Update related input field=====================
        $("#pnum_id").change(function () {

            console.log('\n\n\n===================================================================\n');
            console.log('==========================pnum_change==============================\n');
            console.log('===================================================================\n\n\n');

            let selectize = $('#pnum_id').selectize()[0].selectize;            // Initialize Selectize
            let pnum_id_selected = selectize.getValue();             // Get value when needed
            
            console.log('selectizeValue::'+pnum_id_selected)

            targetpatient = resultArray.find(obj => obj.tabindex === pnum_id_selected);
            
            console.log(targetpatient);
            
            console.log('tabindex::'+targetpatient.tabindex);
            console.log('pnum::'+targetpatient.pnum);
            console.log('hn_num::'+targetpatient.hn_num);
            console.log('patho_abbreviation::'+targetpatient.patho_abbreviation);
            console.log('accept_date::'+targetpatient.accept_date);

            // Set values with jQuery
            $('#patho_abbreviation').val(targetpatient.patho_abbreviation);
            $('#accept_date').val(targetpatient.accept_date);
            $('#hn_num').val(targetpatient.hn_num);

            let patient_id = targetpatient.tabindex;
            let userid = $('#userid').val();
            let sn_num = targetpatient.pnum;
            let hn_num = targetpatient.hn_num;
            let patho_abbrev = targetpatient.patho_abbreviation;
            let accept_date = convertDateFormat(targetpatient.accept_date);
            let company_name = "N.B.Pathology";
            let letter = $('#letter').selectize()[0].selectize.getValue();
            let start_num = $('#start_num').selectize()[0].selectize.getValue();
            let end_num = $('#end_num').selectize()[0].selectize.getValue();
 
            // Print to console
            console.log("patient_id:", patient_id);
            console.log("userid:", userid);
            console.log("sn_num:", sn_num);
            console.log("hn_num:", hn_num);
            console.log("patho_abbrev:", patho_abbrev);
            console.log("accept_date:", accept_date);
            console.log("company_name:", company_name);
            console.log("letter:", letter);
            console.log("start_num:", start_num);
            console.log("end_num:", end_num);

            //alert('pnum_id:'+pnum_id);

        });
        
    }


    $(document).ready(function () {
        $("#generate_label").addClass("active");

        $('select').selectize({
//            sortField: 'text'
        });
        
        

        //=============Print table of row record selected=============================
        drawtableforprintlabel();
        

        // Select all <li> inside the ul.patientlist
        // Then keep as global variable resultArray
        const items = document.querySelectorAll("ul.patientlist li");

        // Convert NodeList to array and map attributes
        resultArray = Array.from(items).map(li => {
          return {
            tabindex: li.getAttribute("tabindex"),
            pnum: li.getAttribute("pnum"),
            hn_num: li.getAttribute("hn_num"),
            patho_abbreviation: li.getAttribute("patho_abbreviation"),
            accept_date: li.getAttribute("accept_date")
          };
        });
        
        
        
        //============================================================================
        inifunction();
        
        

    });
    
    
    
    
    

</script>