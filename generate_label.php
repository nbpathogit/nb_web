<?php
//session_start();
require 'includes/init.php';
$conn = require 'includes/db.php';
Auth::requireLogin();
require 'user_auth.php';
//var_dump($_POST);





//Add record to database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    var_dump($_POST);
    
    echo "  letter::".$_POST['letter'];
    echo "  start_number::".$_POST['start_num'];
    echo "  end_number::".$_POST['end_num'];
    echo "  userid::".$_POST['userid'];
    echo "  pnum_id::".$_POST['pnum_id'];
    echo "  patho_abbreviation::".$_POST['patho_abbreviation'];

    
    die();
    
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

<?php //Write Data to DOM pass value to java script ?>
<?php $hidden_data2dom = true; ?>
<li class="pautoscroll" tabindex="insert_label_list_section" style="<?= $hidden_data2dom ? "display: none;" : "" ?>"  >insert_label_list_section </li>


<div id="patient_plan_section" class="container-fluid pt-4 px-4">

    <div class="bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
        <h1>Table List to print out label</h1>
        <table class=""> 
            <tr>
                <th> row_id </td>
                <th> User ID </th>
                <th> SN Number </th>
                <th> HN Number </th>
                <th> Pathologist Abbreviation</th>
                <th> Speciment Abbreviation </th>
                <th> Income date </th>
                <th> Company Name </th>  

            </tr>
            <?php foreach ($labelPrints as $labelprint) : ?>
                <tr>
                    <td> <?= $labelprint['id'] ?> </td>
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
                </div>
                
                

                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
                    <label for="patho_abbreviation" class="form-label">patho_Abbreviation: </label>
                    <input type="text" name="patho_abbreviation" id="patho_abbreviation" class="form-control" value="" readonly required>
                </div>

                <div class="col-xl-4 col-md-6 <?= $isBorder ? "border" : "" ?> ">
                    <label for="accept_date" class="form-label">accept_date: </label>
                    <input type="text" name="accept_date" id="accept_date" class="form-control" value="" readonly required>
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
        <div id="insert_label_list_section"  class="bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
        <div class="">
            <a href="<?= Url::currentURL() ?>/sn_pdf1.php?userid=<?= $_SESSION['userid'] ?>"  target="_blank">
                <button name="viewpdf1" type="submit" class="btn btn-primary" >&nbsp;&nbsp;1) Generate PDF Label 2x2.3 cm&nbsp;&nbsp;</button>
            </a>
        </div>
            
            
    </div>

</div>


<?php $hidden_data2dom=FALSE; ?>
<!-- Container where we will add the form -->
<div id="formContainer"></div>

<?php if (isset( $patientLists   )): ?>  
    <ul class="patientlist" style="<?= $hidden_data2dom ? "display: none;" : "" ?>" >
        <?php foreach ($patientLists as $patient) : ?>
            <li 
                tabindex="<?= $patient['pid'] ?>" 
                pnum="<?= htmlspecialchars($patient['p_pnum']); ?>" 
                patho_abbreviation="<?= htmlspecialchars($patient['ab_patho']); ?>" 
                accept_date="<?= htmlspecialchars($patient['accept_date']); ?>" 
                >
                tabindex="<?= $patient['pid'] ?>" 
                pnum="<?= htmlspecialchars($patient['p_pnum']); ?>" 
                patho_abbreviation="<?= htmlspecialchars($patient['ab_patho']); ?>" 
                accept_date="<?= htmlspecialchars($patient['accept_date']); ?>" 
            </li>
        <?php endforeach; ?> 
    </ul>     
<?php endif; ?>



<?php require 'includes/footer.php'; ?>

<script type="text/javascript">
    
    var resultArray;
    var targetpatient;
    
    function convertDateFormat(dateStr) {
        // Split the string into parts
        const parts = dateStr.split("-"); // ["2025", "12", "08"]

        const year = parts[0];
        const month = parts[1];
        const day = parts[2];

        // Return in DD/MM/YYYY format
        return `${day}/${month}/${year}`;
    }

    
    $(document).ready(function () {
        $("#generate_label").addClass("active");

        $('select').selectize({
//            sortField: 'text'
        });
        
        
        //=============Add record to database===========================================
        $("#form_add_record").on("submit", function(e) {
            e.preventDefault(); // prevent normal form submission

            // prepare all parameter
            let patient_id = targetpatient.tabindex;
            let userid = $('#userid').val();
            let sn_num = targetpatient.pnum;
            let hn_num = "";
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

            alert("submit");
            
            
            
            

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

            
            
            
            
            
            
            
            return ; // to be continue update from below line

            var formData = new FormData(this);
            console.log('patient_id::'+patient_id);
            $.ajax({
              url: "pdf_attach_upload.php",       // server-side script
                type: "POST",
                'async': false,
                data: formData,
                contentType: false,      // important for file upload
                processData: false,      // prevent jQuery from processing data
                success: function(response) {
                    $("#pdfUploadStatus").html(response);
                },
                error: function() {
                    $("#pdfUploadStatus").html("File upload failed.");
                }
            });
          
        });



        //========Update related input field=====================
        $("#pnum_id").change(function () {

            console.log('\n\n\n===================================================================\n');
            console.log('======================pnum_change==============================\n');
            console.log('===================================================================\n\n\n');

            let selectize = $('#pnum_id').selectize()[0].selectize;            // Initialize Selectize
            let pnum_id_selected = selectize.getValue();             // Get value when needed
            
            console.log('selectizeValue::'+pnum_id_selected)

            targetpatient = resultArray.find(obj => obj.tabindex === pnum_id_selected);
            
            console.log(targetpatient);
            
            console.log('tabindex::'+targetpatient.tabindex);
            console.log('pnum::'+targetpatient.pnum);
            console.log('patho_abbreviation::'+targetpatient.patho_abbreviation);
            console.log('accept_date::'+targetpatient.accept_date);

            // Set values with jQuery
            $('#patho_abbreviation').val(targetpatient.patho_abbreviation);
            $('#accept_date').val(targetpatient.accept_date);

            let patient_id = targetpatient.tabindex;
            let userid = $('#userid').val();
            let sn_num = targetpatient.pnum;
            let hn_num = "";
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
        


        // Select all <li> inside the ul.patientlist
        // Then keep as global variable resultArray
        const items = document.querySelectorAll("ul.patientlist li");

        // Convert NodeList to array and map attributes
        resultArray = Array.from(items).map(li => {
          return {
            tabindex: li.getAttribute("tabindex"),
            pnum: li.getAttribute("pnum"),
            patho_abbreviation: li.getAttribute("patho_abbreviation"),
            accept_date: li.getAttribute("accept_date")
          };
        });

        //==Print out for debug===
        //console.log(resultArray);

//=========EXPECT OUT PUT===========
//        [
//          {
//            tabindex: "34609",
//            pnum: "SN2600001",
//            patho_abbreviation: "",
//            accept_date: "2026-01-05"
//          },
//          {
//            tabindex: "34605",
//            pnum: "CN2501855",
//            patho_abbreviation: "AC.",
//            accept_date: "2025-12-31"
//          },
//          {
//            tabindex: "34604",
//            pnum: "CN2501854",
//            patho_abbreviation: "AC.",
//            accept_date: "2025-12-31"
//          }
//        ]
//
//
//        const matches = resultArray.filter(obj => obj.tabindex === "34605");
//
//        console.log(matches[0].pnum); // "CN2501855"








    });
    
    
    
    
    

</script>