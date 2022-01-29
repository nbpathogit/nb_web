<h4>เพิ่มสถานพยาบาล</h4>


<form class="row g-2" id="checkForm" name="checkForm" method="post">

  <div class="mb-3">
    <label for="hospital_name" class="form-label">ชื่อสถานพยาบาล</label>
    <input name="hospital_name" type="text" class="form-control" id="hospital_name" value="<?= htmlspecialchars($hospital->hospital); ?>">
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
    <button name="Submit2" type="submit" class="btn btn-primary">บันทึก</button>
    <button name="Reset" type="reset" class="btn btn-primary" id="Reset">ยกเลิก</button>
  </div>

</form>

<br>