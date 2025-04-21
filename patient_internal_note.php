<!--internal note-->

<?php

$isBorder = true;
$intNotes = InternalNote::getAll($conn,$patient_id);

?>


<!-- Modal-->
<hr>
<div class="row <?= $isBorder ? "border" : "" ?>"></div>

<p align="center">
    <?php if (!$isCurUserCust): ?>
    <a class="btn btn-primary " data-bs-toggle="modal"  data-bs-target="#add_modal_internal_note" title="Add" ><i class="fa-sharp fa-solid fa-plus"></i>เพิ่มโน้ต</a>
    <?php endif; ?>
</p>
    

<!-- Modal -->
<div class="modal fade" id="add_modal_internal_note" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel5">เพิ่มโน้ต</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php //$userTechnic ?>      
                <div class="<?= $isBorder ? "border" : "" ?> ">
                   <textarea id="intNote_id" name="patient_id_name" style="width:100%;" 
                  ></textarea>
                </div>   
                <div>
                    <br>
                    <button type="button" onclick="addNewIntNote()" id="add_internal_note_btn" class="btn btn-primary" data-bs-dismiss="modal">Add</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
        <div class="modal-footer"></div>
    </div>
</div>

<div>
    <?php foreach ($intNotes as $intNote): ?>
        
        <hr>
        <b>Creater:</b><?= $intNote['creater'] ?> 
        <b>Editer:</b><?= $intNote['editer'] ?> 
        <b>Create Date:</b><?= $intNote['created_date'] ?> 
        <b>Edit Date:</b><?= $intNote['edit_date'] ?> 
        <br>
        <textarea id="intNote_<?= $intNote['id'] ?>" name="patient_id_<?= $intNote['patient_id'] ?>" style="width:100%;" disabled=""
                  ><?= $intNote['note'] ?></textarea>
    <?php endforeach; ?>

</div>

<?php

$isBorder = false;

?>
<script  type="text/javascript">

    
function addNewIntNote(){
    let patient_id = $(".cur_patient_id").attr('tabindex');
    let note = document.getElementById("intNote_id").value;
    let cur_user = $(".cur_user_id_name_lastname").attr('tabindex');
    let curDate = getCurTimeStamp();

    console.log("patient_id::"+patient_id);
    console.log("note::"+note);
    console.log("cur_user::"+cur_user);
    console.log("curDate::"+curDate);

    $.ajax({
        'async': false,
        type: 'POST',
        'global': false,
        type: 'POST',
        url: 'ajax_patient/patient_internal_note.php',
        data: {
            'patient_id': patient_id,
            'note': note,
            'creater': cur_user,
            'editer': cur_user,
            'created_date': curDate,
            'edit_date': curDate,
        },
        success: function (data) {
            console.log(data);
            alert('สำเร็จ');
            location.reload();
//            var datajson = JSON.parse(data); //convert String to JS Object
//            for (var i in datajson)
//            {
//                console.log(datajson[i].number);
//                console.log(datajson[i].number);
//            }

        },
        error: function (jqxhr, status, exception) {
            alert('Exception:', exception);
        }
    });

    
    return;
}

</script>