<!-- </div>
</div> -->
<!-- Footer Start -->
<div class="container-fluid mt-4 pt-3 px-4">
    <div class="bg-nb bg-blue-a rounded-top p-4">
        <div class="row">
            <div class="col-12 col-sm-6 text-center text-sm-start">
                &copy; <a href="/">NB Patho</a>, All Right Reserved.
            </div>
            <div class="col-12 col-sm-6 text-center text-sm-end">
                <!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
                Designed By <a href="https://htmlcodex.com">HTML Codex</a>
            </div>
        </div>
    </div>
</div>
<!-- Footer End -->
</div>
<!-- Content End -->


<!-- Back to Top -->
<a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
</div>

<?php if (isset($_SESSION['username'])): ?>
<?php
    $relogin_fullname = '';
    if (isset($_SESSION['user'])) {
        $relogin_fullname = trim((isset($_SESSION['user']->name) ? $_SESSION['user']->name : '') . ' ' . (isset($_SESSION['user']->lastname) ? $_SESSION['user']->lastname : ''));
    }
    $relogin_ugroup_name = isset($_SESSION['usergroup']->ugroup) ? $_SESSION['usergroup']->ugroup : '';
    $relogin_ugroup_id = isset($_SESSION['usergroup']->id) ? $_SESSION['usergroup']->id : '';
    $relogin_subfolder = isset($_SESSION['subfolder']) ? $_SESSION['subfolder'] : '';
    $relogin_skey = isset($_SESSION['skey']) ? $_SESSION['skey'] : '';
    $relogin_adminusername = isset($_SESSION['adminusername']) ? $_SESSION['adminusername'] : '';
?>
<!-- Session Timeout Re-login Modal -->
<style>
    /* ปิดพื้นหลังให้เป็นสีดำสนิท 100% ไม่ให้เห็นรายละเอียดหน้าเว็บเดิมเมื่อหมดเวลาเชื่อมต่อ */
    #sessionReloginModal {
        background-color: rgba(0, 0, 0, 0.98) !important;
    }
    .modal-backdrop.session-relogin-backdrop {
        background-color: #000000 !important;
        opacity: 1 !important;
        z-index: 99998 !important;
    }
</style>
<div class="modal fade" id="sessionReloginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="sessionReloginModalLabel" aria-hidden="true" style="z-index: 99999;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="sessionReloginModalLabel">
                    <i class="fa-solid fa-clock-rotate-left me-2"></i>หมดเวลาเชื่อมต่อ (Session Timeout)
                </h5>
            </div>
            <form id="sessionReloginForm" autocomplete="off" onsubmit="return false;">
                <!-- Dummy hidden input to deceive browser autofill heuristics -->
                <input type="text" name="fakeusernameremembered" style="display:none;" tabindex="-1" autocomplete="off">
                <input type="password" name="fakepasswordremembered" style="display:none;" tabindex="-1" autocomplete="off">

                <div class="modal-body p-4 bg-nb bg-blue-a">
                    <div class="alert alert-warning d-flex align-items-center mb-3 py-2" role="alert">
                        <i class="fa-solid fa-triangle-exclamation me-2 fs-5"></i>
                        <div class="small">
                            กรุณากรอกรหัสผ่านของบัญชีผู้ใช้เดิมเพื่อเข้าสู่ระบบและทำงานต่อ (ข้อมูลในหน้านี้ยังคงอยู่ครบถ้วน)
                        </div>
                    </div>

                    <div id="relogin_error_box" class="alert alert-danger py-2 small d-none" role="alert"></div>

                    <input type="hidden" id="relogin_subfolder" name="subfolder" value="<?= htmlspecialchars($relogin_subfolder) ?>">
                    <input type="hidden" id="relogin_skey" name="skey" value="<?= htmlspecialchars($relogin_skey) ?>">
                    <input type="hidden" id="relogin_adminusername" name="adminusername" value="<?= htmlspecialchars($relogin_adminusername) ?>">
                    <input type="hidden" id="relogin_ugroup_id" name="ugroup_id" value="<?= htmlspecialchars($relogin_ugroup_id) ?>">

                    <div class="mb-3">
                        <label for="relogin_username" class="form-label fw-bold mb-1">บัญชีผู้ใช้ (Username)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-user text-primary"></i></span>
                            <input type="text" class="form-control bg-light fw-bold" id="relogin_username" name="username" value="<?= htmlspecialchars($_SESSION['username']) ?>" readonly>
                        </div>
                        <?php if ($relogin_fullname !== '' || $relogin_ugroup_name !== ''): ?>
                        <div class="form-text text-muted mt-1">
                            <?= htmlspecialchars($relogin_fullname) ?>
                            <?php if ($relogin_ugroup_name !== ''): ?>
                                <span class="badge bg-secondary ms-1"><?= htmlspecialchars($relogin_ugroup_name) ?></span>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-2">
                        <label for="relogin_password" class="form-label fw-bold mb-1">รหัสผ่าน (Password)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fa-solid fa-key text-primary"></i></span>
                            <input type="password" class="form-control" id="relogin_password" name="nb_auth_session_pwd" placeholder="กรอกรหัสผ่านเพื่อทำงานต่อ" required autocomplete="new-password" data-lpignore="true" data-form-type="other" readonly onfocus="this.removeAttribute('readonly');">
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between bg-light">
                    <a href="logout.php" class="btn btn-outline-danger">
                        <i class="fa-solid fa-right-from-bracket me-1"></i>ออกจากระบบ
                    </a>
                    <button type="submit" id="btn_relogin_submit" class="btn btn-primary px-4">
                        <i class="fa-solid fa-unlock-keyhole me-1"></i>เข้าสู่ระบบเพื่อทำงานต่อ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<!-- <script src="lib/chart/chart.min.js"></script> -->
<!-- <script src="lib/easing/easing.min.js"></script> -->
<script src="lib/waypoints/waypoints.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>
<script src="lib/tempusdominus/js/moment.min.js"></script> -->
<script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
<script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>


<!-- data table js -->
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/searchpanes/2.0.0/js/dataTables.searchPanes.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.3.4/js/dataTables.select.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/v/dt/jszip-3.10.1/dt-1.13.7/af-2.6.0/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/cr-1.7.0/date-1.5.1/fc-4.3.0/fh-3.4.0/kt-2.11.0/r-2.5.0/rg-1.4.1/rr-1.4.1/sc-2.3.0/sb-1.6.0/sp-2.2.0/sl-1.7.0/sr-1.3.0/datatables.min.js"></script>


<!-- Template Javascript -->
<script src="<?= Url::getSubFolder1() ?>/js/main.js"></script>

<!-- from old header -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.js">
</script>
<script src="<?= Url::getSubFolder1() ?>/js/jquery.datetimepicker.full.min.js"></script>
<script src="<?= Url::getSubFolder1() ?>/js/script.js?v18"></script>
<script src="<?= Url::getSubFolder1() ?>/js/scriptutil.js?v1a"></script>

<script src="<?= Url::getSubFolder1() ?>/ajax_user/user.js?v0xxxxxxxxxxxxxxxxxxxx"></script>
<script src="<?= Url::getSubFolder1() ?>/ajax_read_dom/readdom.js?v3"></script>



</body>

</html>

<?php if (($isCurUserClinicianCust || $isCurUserHospitalCust)) : //  เจ้าหน้าที่รับผล(ลูกค้า) เข้าดูไม่ได้   
?>

    <script type="text/javascript">
   
        $(document).ready(function() {
            $('#patienttab_8000').remove();
            $('#patientconfirmtab').remove();
        });
        
        
        
        
    </script>

<?php endif; ?>