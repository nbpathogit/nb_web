
<form class="row g-2" id="checkForm" name="checkForm" method="post">

  <div class="mb-3">
    <label for="hospital_name" class="form-label">ชื่อสถานพยาบาล</label>
    <input name="hospital_name" type="text" class="form-control" id="hospital_name" value="<?= htmlspecialchars($hospital->hospital); ?>">
  </div>

  <div class="mb-3">
    <label for="tax_id" class="form-label">เลขที่ผู้เสียภาษี</label>
    <input name="tax_id" type="text" class="form-control" id="hospital_name" value="<?= htmlspecialchars($hospital->tax_id); ?>">
  </div>
    
  <div class="mb-3">
    <label for="hospital_address" class="form-label">ที่อยู่</label>
    <textarea name="hospital_address" class="form-control" id="hospital_address" rows="3"><?= htmlspecialchars($hospital->address); ?></textarea>
  </div>

  <div class="mb-3">
    <label for="hospital_address" class="form-label">รายละเอียด</label>
    <textarea name="hospital_detail" class="form-control" id="hospital_detail" rows="2"><?= htmlspecialchars($hospital->hdetail); ?></textarea>
  </div>

  <div class="d-grid gap-2 d-md-block">
      <button name="Submit2" id="save" type="submit" class="btn btn-primary">บันทึก</button>
      <button name="Reset" type="reset" class="btn btn-secondary" id="Reset">ยกเลิก</button>
  </div>

  <input type="hidden" name="create_by" value="<?= $cur_user->name . ' ' . $cur_user->lastname ?>">

</form>

<br>