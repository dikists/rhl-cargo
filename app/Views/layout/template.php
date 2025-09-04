<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= $title; ?> - Wahana Elangcargo Perkasa</title>

    <!-- Favicons -->
    <link href="<?= base_url('') ?>assets/img/favicon.png" rel="icon">
    <link href="<?= base_url('') ?>assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <!-- Custom fonts for this template-->
    <link href="<?= base_url(); ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url(); ?>css/sb-admin-2.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>css/timeline.css" rel="stylesheet">
    <link href="<?= base_url(); ?>vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.12.1/af-2.4.0/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/r-2.3.0/sc-2.0.7/datatables.min.css" />

    <link rel="stylesheet" href="<?= base_url(); ?>assets/flatpickr/css/flatpickr.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/flatpickr/plugin/month_select_style.css">
    <!-- select2 -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/select2/css/select2.css">

    <!-- script -->
    <!-- Bootstrap core JavaScript-->
    <script src="<?= base_url(); ?>vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url(); ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url(); ?>vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- datatable -->
    <script src="<?= base_url(); ?>vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url(); ?>vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.12.1/af-2.4.0/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/r-2.3.0/sc-2.0.7/datatables.min.js"></script>

    <!-- datepicker -->
    <link rel="stylesheet" href="<?= base_url('assets/'); ?>time/css/gijgo.css">

    <!-- flatpicker -->
    <script src="<?= base_url(); ?>assets/flatpickr/js/flatpickr.js"></script>
    <script src="<?= base_url(); ?>assets/flatpickr/plugin/month_select.js"></script>

    <!-- Moment -->
    <script src="<?= base_url(); ?>assets/moment/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/id.min.js"></script>

    <!-- select2 -->
    <script src="<?= base_url(); ?>assets/select2/js/select2.full.min.js"></script>
</head>
<style>
    .bg-pending {
        background-color: #ffcccb !important;
    }

    .bg-ontime {
        background-color: #CAF4FF !important;
    }

    .buttons-html5 {
        margin: 10px;
        border-radius: 8px 8px 8px 8px !important;
    }
</style>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-truck"></i>
                </div>
                <div class="sidebar-brand-text mx-3">WEP <sup>Cargo</sup></div>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item active">
                <a class="nav-link" href="/dashboard">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
            <hr class="sidebar-divider">
            <div class="sidebar-heading">
                Pages
            </div>
            <li class="nav-item">
                <a class="nav-link" href="/tracking">
                    <i class="fas fa-fw fa-truck"></i>
                    <span>Tracking Shipment</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/laporan_pengiriman">
                    <i class="fas fa-fw fa-file"></i>
                    <span>Laporan Pengiriman</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/laporan_performance">
                    <i class="fas fa-fw fa-file"></i>
                    <span>Laporan Performance</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/invoice">
                    <i class="fas fa-fw fa-dollar-sign"></i>
                    <span>Invoice</span></a>
            </li>
            <hr class="sidebar-divider">
            <div class="sidebar-heading">
                Account
            </div>
            <li class="nav-item">
                <a class="nav-link" href="/profile">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Profile</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/logout">
                    <i class="fas fa-fw fa-sign-out-alt "></i>
                    <span>Sign Out</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>


                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - Messages -->
                        <!-- <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <span class="badge badge-danger badge-counter">7</span>
                            </a>
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Message Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="<?= base_url() ?>img/undraw_profile_1.svg" alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div class="font-weight-bold">
                                        <div class="text-truncate">Hi there! I am wondering if you can help me with a
                                            problem I've been having.</div>
                                        <div class="small text-gray-500">Emily Fowler · 58m</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_2.svg" alt="...">
                                        <div class="status-indicator"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">I have the photos that you ordered last month, how
                                            would you like them sent to you?</div>
                                        <div class="small text-gray-500">Jae Chun · 1d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_3.svg" alt="...">
                                        <div class="status-indicator bg-warning"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Last month's report looks great, I am very happy with
                                            the progress so far, keep up the good work!</div>
                                        <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60" alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Am I a good boy? The reason I ask is because someone
                                            told me that people say this to all dogs, even if they aren't good...</div>
                                        <div class="small text-gray-500">Chicken the Dog · 2w</div>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                            </div>
                        </li> -->

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $user['nama_pelanggan']; ?></span>
                                <img class="img-profile rounded-circle" src="<?= base_url() ?>img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="/profile">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="/ubah-password">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/logout" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->
                <?= $this->renderSection('content') ?>
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Wahana Elangcargo Perkasa <?= date('Y') ?></span>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="/logout">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Page level plugins -->
    <script src="<?= base_url(); ?>vendor/chart.js/Chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@1.0.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <script src="<?= base_url('assets/'); ?>time/js/gijgo.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="<?= base_url(); ?>js/sb-admin-2.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?= base_url(); ?>js/shipment-chart-new.js"></script>
    <script src="<?= base_url(); ?>js/shipment-status-chart-new.js"></script>
    <script>
        // locale.moment('id');
        $(document).ready(function() {
            $('.mySelect2').select2();
            $('.dataTable').DataTable();
        });

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(number);
        }
        $('.datepicker').each(function() {
            $(this).datepicker({
                format: 'yyyy-mm-dd',
                uiLibrary: 'bootstrap4'
            });
        });
        $('.datepicker_id').each(function() {
            $(this).datepicker({
                format: 'dd-mm-yyyy',
                uiLibrary: 'bootstrap4'
            });
        });
        $(".flatpicker_fy").flatpickr({
            dateFormat: "F Y",
            plugins: [new monthSelectPlugin({
                shorthand: false,
                dateFormat: "F Y",
                altFormat: "Y-m"
            })]
        });

        function cleanNumber(str) {
            if (typeof str === 'string') {
                return parseFloat(str.replace(/\./g, '').replace(',', '.')) || 0;
            }
            return parseFloat(str) || 0;
        }

        function convertDate(date, fromFormat = 'DD-MM-YYYY', toFormat = 'YYYY-MM-DD') {
            if (!date) {
                return moment().format(toFormat); // Kembalikan tanggal hari ini
            }
            return moment(date, fromFormat).format(toFormat);
        }
    </script>

</body>

</html>