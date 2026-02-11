<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <title>NB Patho</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="NB Patho" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="image/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!--<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">-->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- datatable css -->
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css"> -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">


    <link href="https://cdn.datatables.net/v/dt/jszip-3.10.1/dt-1.13.7/af-2.6.0/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/cr-1.7.0/date-1.5.1/fc-4.3.0/fh-3.4.0/kt-2.11.0/r-2.5.0/rg-1.4.1/rr-1.4.1/sc-2.3.0/sb-1.6.0/sp-2.2.0/sl-1.7.0/sr-1.3.0/datatables.min.css" rel="stylesheet">

    <!--<link rel="stylesheet" href="https://cdn.datatables.net/searchpanes/2.0.0/css/searchPanes.dataTables.min.css">-->
    <!--<link href="cssdatatablesdotnet\searchpanes_2_0_0\searchPanes.dataTables.min.css" rel="stylesheet">-->

    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.4/css/select.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css">

    <!-- from old header -->
    <link rel="stylesheet" href="css/jquery.datetimepicker.min.css">
    <link rel="stylesheet" href="css/flowchart.css">

    <!-- Template Stylesheet -->
    <link href="css/style.css?v1xxxxxxxxxxxx" rel="stylesheet">

    <!--Selectize Js-->
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />


</head>

<body>
    <div class="container-fluid position-relative bg-white d-flex p-0">
        <!-- <div class="container-fluid position-relative bg-white d-flex p-0"> -->
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">กำลังโหลด</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Sidebar Start -->
        <div class="sidebar pe-2 pb-3">
            <nav class="navbar bg-blue-a navbar-light">
                <a href="patient.php" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-primary"><i class="fa-solid fa-microscope"></i> NB Patho</h3>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative" <?php if ($isCurUserPatho): ?>
                        ondblclick="window.location='<?= Url::getSubFolder1() ?>/user_sim_patho_admin.php'"
                        <?php endif; ?>>
                        <i class="fa-solid fa-hospital fa-lg"></i>
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3">
                        <?php if (isset($_SESSION['adminusername'])) {
                            echo '<h6 style="color:red;" class="mb-0"><b>Admin:' . $_SESSION['adminusername'] . '</b></h6>';
                        }  ?>
                        <h6 class="mb-0"><?= $_SESSION['username'] ?></h6>
                        <span><?= isset($_SESSION['usergroup']->ugroup) ? $_SESSION['usergroup']->ugroup : "" ?></span>
                    </div>
                </div>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <b><span style=" font-size: 1em;">Time Remain <span id="sessioncountdown" style=" color:red;"></span></span></b>
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    <?php if ($isCurUserNB): ?>
                    <a id="addpatienttab" class="nav-item nav-link" href="patient_add.php"><i class="fa-solid fa-bed"></i>เพิ่มข้อมูลผู้รักษา</a>
                    <?php endif; ?>
                    <a id="patienttab" class="nav-item nav-link" href="patient.php"><i class="fa-solid fa-bed"></i>ข้อมูลผู้รักษา</a>
                    <?php if ($isCurUserAdmin): ?>
                        <div class="nav-item dropdown">
                            <a href="" id="admin_tab" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa-solid fa-id-card-clip"></i>แอดมินเมนู</a>
                            <div class="dropdown-menu bg-transparent border-0">
                                <a id="user_sim_admin_tab" href="user_sim_admin.php" class="nav-item nav-link">จำลองผู้ใช้</a>
                                <a id="user_sim_admin_tab" href="curdate_sim_admin.php" class="nav-item nav-link">จำลองวันที่ปัจจุบัน</a>
                                <a id="datatable_patient_pn_rev1" href="datatable_patient_pn_rev1.php" class="nav-item nav-link">ดึงสรุปข้อมูลการออกผลPN</a>
                                <a id="chart_data_tab" href="chart_data.php" class="nav-item nav-link">chart data</a>
                                
                            </div>
                        </div>
                    <?php endif; ?>


                    <?php if ($isCurUserNB): ?>
                        <a id="patienttab_8000" class="nav-item nav-link" href="patient_monitor_8000.php"><i class="fa-solid fa-bed-pulse"></i>ตรวจพิเศษ</a>
                        <a id="patientconfirmtab" class="nav-item nav-link" href="patient_confirm.php"><i class="fa-solid fa-cart-flatbed-suitcase"></i>Double check by second pathologist</a>

                        <div class="nav-item dropdown">
                            <a href="" id="view_qn_table" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa-solid fa-id-card-clip"></i>แบบสอบถาม</a>
                            <div class="dropdown-menu bg-transparent border-0 finish_job_table_dropdown">
                                <a id="qn_sn_tab" href="questionare_sn_slide.php" class="nav-item nav-link"><i class="fa-solid fa-table-list"></i>แบบสอบถาม(SN)</a>
                                <a id="qn_cn_tab" href="questionare_cn_slide.php" class="nav-item nav-link"><i class="fa-solid fa-table-list"></i>แบบสอบถาม(CN)</a>
                            </div>
                        </div>

                        <div class="nav-item dropdown">
                            <a href="" id="view_qn_table" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa-solid fa-id-card-clip"></i>ดูประวัติการเข้าใช้</a>
                            <div class="dropdown-menu bg-transparent border-0 finish_job_table_dropdown">
                                <a id="qn_sn_tab" href="log_login_logout_datatable.php" class="nav-item nav-link"><i class="fa-solid fa-table-list"></i>ประวัติการล็อกอินล็อกเอ้าท์</a>
                            </div>
                        </div>

                        <!--job_daily-->

                        <div class="nav-item dropdown">
                            <a href="" id="view_job_table" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa-solid fa-id-card-clip"></i>รายการงาน</a>
                            <div class="dropdown-menu bg-transparent border-0 finish_job_table_dropdown">
                                <a id="job_tab" href="job.php" class="nav-item nav-link"><i class="fa-solid fa-table-list"></i>ดูรายการงาน</a>
                                <a id="job_tab_jobdaily" href="job_daily.php" class="nav-item nav-link"><i class="fa-solid fa-table-list"></i>จำนวนงานแต่ละวัน</a>
                            </div>
                        </div>

                        <!-- <a href="home.php" id="home" class="nav-item nav-link"><i class="fa-solid fa-chart-line"></i>แดชบอร์ด</a> -->
                        <!--<a id="specimentab" href="specimen.php" class="nav-item nav-link"><i class="fa-solid fa-disease"></i>ข้อมูลสิ่งส่งตรวจ</a>-->
                        <!-- <a href="print.php" class="nav-item nav-link"><i class="fa-solid fa-print"></i>พิมพ์ข้อมูล</a> -->

                        <div class="nav-item dropdown">
                            <a href="" id="finish_job_table" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa-solid fa-id-card-clip"></i>ลงเวลางานเสร็จสิ้น</a>
                            <div class="dropdown-menu bg-transparent border-0 finish_job_table_dropdown">
                                <a id="" class="nav-item nav-link" href="job1_finish.php">ลงเวลาตัดเนื้อเสร็จ</a>
                                <a id="" class="nav-item nav-link" href="job2_finish.php">ลงเวลาผู้ช่วยตัดเนื้อเสร็จ</a>
                                <a id="" class="nav-item nav-link" href="job3_finish.php">ลงเวลาเตรียมสไลด์เสร็จ</a>
                            </div>
                        </div>


                        <div class="nav-item dropdown">
                            <a href="" id="manage_table" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa-solid fa-id-card-clip"></i>จัดการตาราง</a>
                            <div class="dropdown-menu bg-transparent border-0 manage_table_dropdown">
                                <a id="user_add" class="nav-item nav-link" href="user_add.php">เพื่มผู้ใช้งานระบบ</a>
                                <a id="user" class="nav-item nav-link" href="user.php">ดูผู้ใช้งานระบบ</a>
                                <a class="nav-item nav-link" id="hospital" href="hospital.php">ดูโรงพยาบาล</a>
                                <a class="nav-item nav-link" id="hospital_add" href="hospital_add.php">เพิ่มโรงพยาบาล</a>
                                <a id="fluid" href="labfluid.php" class="nav-item nav-link">ดูแลปเซลล์วิทยา</a>
                                <!--<a id="price_tab" href="nb_price.php" class="nav-item nav-link">ดูและจัดการรายการค่าบริการ6คอลั่ม</a>-->
                                <a id="price_tab_8c" href="nb_price_8c.php" class="nav-item nav-link">ดูและจัดการรายการค่าบริการ8คอลั่ม</a>
                                <a id="price_tab_9c" href="nb_price_9c.php" class="nav-item nav-link">ดูและจัดการรายการค่าบริการ9คอลั่ม</a>
                                <a id="price_tab_view" href="nb_price_view.php" class="nav-item nav-link">ดูรายการค่าบริการ</a>
                            </div>
                        </div>
                        <div class="nav-item dropdown">
                            <a href="" id="manage_bill" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa-solid fa-id-card-clip"></i>ออกใบแจ้งหนี้</a>
                            <div class="dropdown-menu bg-transparent border-0 " id="manage_bill_dropdown">
                                <a id="billing_pdf_tab" href="billing_pdf.php" class="nav-item nav-link">ออกใบแจ้งหนี้(PDF)</a>
                                <a id="billing_pdf_tab" href="billing_pdf_2.php" class="nav-item nav-link">ออกใบแจ้งหนี้ V2(PDF)</a>
                                <a id="billing_pdf_patho_tab" href="billing_by_patho_pdf.php" class="nav-item nav-link">กรองข้อมูลตามพยาธิแพทย์(PDF)</a>
                                <a id="billing_pdf_cyto_tab" href="billing_by_cytologist_pdf.php" class="nav-item nav-link">กรองข้อมูลตามนักเซลล์(PDF)</a>
                                <a id="billing_tab" href="billing.php" class="nav-item nav-link">ดูรายการใบแจ้งหนี้ในแต่ละผู้รักษา(ดึงตามวันรับเข้า)</a>
                                <a id="billing_by_billingdate_tab" href="billing_by_billingdate.php" class="nav-item nav-link">ดูรายการใบแจ้งหนี้ในแต่ละผู้รักษา(ดึงตามวันให้บริการ)</a>
                            </div>
                        </div>
                        <a id="template_report" href="templateReport.php" class="nav-item nav-link"><i class="fa-solid fa-table-list"></i>เท็มเพลต</a>
                        <a id="generate_label" href="generate_label.php" class="nav-item nav-link"><i class="fa-solid fa-water"></i>สร้างสติกเกอร์สไลด์</a>
                    <?php endif; ?>
                    <!-- <a href="log.php" class="nav-item nav-link"><i class="fa-solid fa-bars-staggered"></i>ข้อมูลการใช้งานระบบ</a> -->
                    <div class="nav-item dropdown">
                        <a href="about.php" id="about_main" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa-solid fa-id-card-clip"></i>เกี่ยวกับ</a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a class="dropdown-item" id="about" href="about.php">เกียวกับเรา</a>
                            <a class="dropdown-item" id="webfeature" href="webfeature.php">เกียวกับเว็บแอฟฟลิเคชั่น</a>
                            <?php if ($isCurUserNB): ?>
                            <a id="featurehistory_tab" href="changehistory.php" class="nav-item nav-link">Web Change History</a>
                            <?php endif; ?>
                            <a class="dropdown-item" id="docdownload_tab" href="doc_download.php">ดาวน์โหลดเอกสาร</a>
                            <a class="dropdown-item" id="stuff" href="stuff.php">บุคลากร</a>
                            <a class="dropdown-item" id="service" href="service.php">บริการของเรา</a>
                            <a class="dropdown-item" id="learning_clip" target="_black" href="https://youtube.com/playlist?list=PLdCsOsPc8bjqp11uosw7eyTE1QJoHqCAl&si=EUg6ySKsrBLKb4yJ">ดูคลิปสอนการใช้งาน</a>
                        </div>
                    </div>

                </div>
            </nav>
        </div>
        <!-- Sidebar End -->


        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start sticky-top-->
            <nav id="nb_navbar_top" class="navbar navbar-expand bg-nb bg-blue-a navbar-light  sticky-top  px-3 py-2 mb-2">


                <a href="patient.php" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0">NB</h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <!-- <form class="d-none d-md-flex ms-4">
                        <input class="form-control border-0" type="search" placeholder="Search">
                    </form> -->

                <div class="navbar-nav align-items-center ms-auto">
                    <span id="red_notice_text" style="color:red; font-weight:bold;"></span>
                    <span id="top_free_text"></span>

                    <!-- <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fa fa-envelope me-lg-2"></i>
                                <span class="d-none d-lg-inline-flex">Message</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end bg-nb bg-blue-a border-0 rounded-0 rounded-bottom m-0">
                                <a href="#" class="dropdown-item">
                                    <div class="d-flex align-items-center">
                                        <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                                        <div class="ms-2">
                                            <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                            <small>15 minutes ago</small>
                                        </div>
                                    </div>
                                </a>
                                <hr class="dropdown-divider">
                                <a href="#" class="dropdown-item">
                                    <div class="d-flex align-items-center">
                                        <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                                        <div class="ms-2">
                                            <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                            <small>15 minutes ago</small>
                                        </div>
                                    </div>
                                </a>
                                <hr class="dropdown-divider">
                                <a href="#" class="dropdown-item">
                                    <div class="d-flex align-items-center">
                                        <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                                        <div class="ms-2">
                                            <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                            <small>15 minutes ago</small>
                                        </div>
                                    </div>
                                </a>
                                <hr class="dropdown-divider">
                                <a href="#" class="dropdown-item text-center">See all message</a>
                            </div>
                        </div> -->
                    <!-- <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fa fa-bell me-lg-2"></i>
                                <span class="d-none d-lg-inline-flex">Notificatin</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end bg-nb bg-blue-a border-0 rounded-0 rounded-bottom m-0">
                                <a href="#" class="dropdown-item">
                                    <h6 class="fw-normal mb-0">Profile updated</h6>
                                    <small>15 minutes ago</small>
                                </a>
                                <hr class="dropdown-divider">
                                <a href="#" class="dropdown-item">
                                    <h6 class="fw-normal mb-0">New user added</h6>
                                    <small>15 minutes ago</small>
                                </a>
                                <hr class="dropdown-divider">
                                <a href="#" class="dropdown-item">
                                    <h6 class="fw-normal mb-0">Password changed</h6>
                                    <small>15 minutes ago</small>
                                </a>
                                <hr class="dropdown-divider">
                                <a href="#" class="dropdown-item text-center">See all notifications</a>
                            </div>
                        </div> -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-hospital fa-lg"></i>
                            <span class="d-none d-lg-inline-flex"><?= $_SESSION['username'] ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-nb bg-blue-a border-0 rounded-0 rounded-bottom m-0">
                            <a href="user_edit.php?id=<?= $_SESSION['user']->id ?>" class="dropdown-item">My Profile</a>
                            <a href="user_change_password.php" class="dropdown-item">Change Password.</a>
                            <!--   <a href="#" class="dropdown-item">Settings</a> -->
                            <a href="logout.php" class="dropdown-item">Log Out</a>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Navbar End -->
            <!-- <div class="container-fluid pt-4 px-4">
                        <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center mx-0"> -->

            <?php $hidden_data2dom = true; ?>
            <li class="sesstion_timelimit_int_sec" tabindex="<?= Auth::$sesstion_timelimit_int_sec ?>" style="<?= $hidden_data2dom ? "display: none;" : "" ?>">Auth::$sesstion_timelimit_int_sec :: <?= Auth::$sesstion_timelimit_int_sec ?> </li>

            <?php // var_dump($_SESSION); 
            ?>