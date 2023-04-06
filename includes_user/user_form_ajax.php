
<div id="slide_prep_section" class="container-fluid pt-4 px-4">
    <div class="bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
        <div><b>แก้ไขค่ายูเซอร์</b></div>
        <hr>
        <div class="row pt-3 mb-3 g-5 align-items-center">
            <div class="col-6 col-md-4">
                
                <?php  $can_edit_user_status =  $isCurUserAdmin; ?>
                <label for="user_status">สถานะผู้ใช้งาน</label><span style="color:red"> </span>
                <select name="user_status" id="user_status" class="form-select" <?= $can_edit_user_status ? "" : " disabled readonly " ?>>

                    <option value="1" <?= $user[0]['user_status'] == 1 ? "selected" : "" ?> >Active</option> 
                    <option value="0" <?= $user[0]['user_status'] == 0 ? "selected" : "" ?> >In-Active</option> 

                </select>
            </div>
        </div>
        <div><button id="save_user_status" type="submit" name="save_user_status" class="btn btn-primary" <?= $can_edit_user_status ? "" : " disabled readonly " ?>>Save</button></div>
     
    </div>
</div>
