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

<!-- data table js -->
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/searchpanes/2.0.0/js/dataTables.searchPanes.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.3.4/js/dataTables.select.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<!-- Template Javascript -->
<script src="js/main.js"></script>

<!-- from old header -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.js">
</script>
<script src="/js/jquery.datetimepicker.full.min.js"></script>
<script src="/js/script.js?v13xxxxxxxxxxxxxxx"></script>

<script src="/ajax_user/user.js?v0xxxxxxxxxxx"></script>
<script src="/ajax_read_dom/readdom.js?v2xxxxxxxx"></script>




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