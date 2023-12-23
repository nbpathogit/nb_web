
<div id="slide_prep_section" class="container-fluid pt-4 px-4">
    <div class="bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1 border border-secondary">
        <div><b>แก้ไขหน้าที่รับผิดชอบ<span style="color:red">(กำลังพัฒนา ยังไม่พร้อมใช้งาน)</span></b></div>
        <hr>
        <div class="row pt-3 mb-3 g-5 align-items-center">
            <div class="col-6 col-md-4">
                
                <?php  $can_edit_user_role =  $isCurUserAdmin ; ?>
                
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" checked <?= $can_edit_user_role ? '' : 'disabled'; ?> >
                  <label class="form-check-label" for="flexCheckDefault">
                    1 ตัดชิ้นเนื้อ (Cross Section)
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked"  <?= $can_edit_user_role ? '' : 'disabled'; ?> >
                  <label class="form-check-label" for="flexCheckChecked">
                    2 คนจด (Cross Section Assistant)
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked"  <?= $can_edit_user_role ? '' : 'disabled'; ?> >
                  <label class="form-check-label" for="flexCheckChecked">
                    3 เตรียมสไลด์ (Slide Prepareation)
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked"  <?= $can_edit_user_role ? '' : 'disabled'; ?> >
                  <label class="form-check-label" for="flexCheckChecked">
                    4 เตรียมสไล์พิเศษ (Special Slide Prepareation)
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked"  <?= $can_edit_user_role ? '' : 'disabled'; ?> >
                  <label class="form-check-label" for="flexCheckChecked">
                    5 แพทย์ผู้ออกผล (Pathologist)
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked"  <?= $can_edit_user_role ? '' : 'disabled'; ?> >
                  <label class="form-check-label" for="flexCheckChecked">
                    6 แพทย์ผู้คอนเฟริ์มผล (Second Pathologist)
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked"  <?= $can_edit_user_role ? '' : 'disabled'; ?> >
                  <label class="form-check-label" for="flexCheckChecked">
                    7 นักเซลวิทยา (Cycologist)
                  </label>
                </div>

            </div>
        </div>
        <div><button id="save_user_status" type="submit" name="save_user_role" class="btn btn-primary" <?= $can_edit_user_role ? "" : " disabled readonly " ?>>Save</button></div>
     
    </div>
</div>
