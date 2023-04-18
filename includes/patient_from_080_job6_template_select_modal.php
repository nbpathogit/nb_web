
<!-- Modal -->
<div class="modal fade" id="add_modal_template_rs" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel6">เลือกเทมเพลต</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php //$userTechnic ?>      
                <div class="<?= $isBorder ? "border" : "" ?> ">
                    <label for="modal_tprs_select"  class="form-label">เลือกเทมเพลต</label>
                    <select name="modal_tprs_select" id="modal_tprs_select" class="form-select"  >
                        <span id="modal_tprs_select">
                            <option value="0"> Click for update </option>
                        </span>
                    </select> 
                </div> 
                <br>
                <div>
                    <textarea name="modal_txt_tps" cols="100" rows="5" class="form-control" id="modal_txt_tps" readonly></textarea>
                </div>
                <div>
                    <br>
                    <button type="button" id="add_txt_rs" onclick="add_selected_tp_2_txt_rs()" class="btn btn-primary" data-bs-dismiss="modal">Add</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
        <div class="modal-footer"></div>
    </div>
</div>

