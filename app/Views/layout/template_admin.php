<!DOCTYPE html>
<html lang="en">

<head>

     <meta charset="utf-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
     <meta name="description" content="">
     <meta name="author" content="">

     <title><?= $title; ?> - <?= getenv('COMPANY_NAME'); ?></title>

     <!-- Favicons -->
     <link href="<?= base_url('') ?>assets/img/favicon.png" rel="icon">
     <link href="<?= base_url('') ?>assets/img/apple-touch-icon.png" rel="apple-touch-icon">
     <!-- Custom fonts for this template-->
     <link href="<?= base_url(); ?>assets/admin/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
     <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

     <!-- Custom styles for this template-->
     <link href="<?= base_url(); ?>css/sb-admin-2.min.css" rel="stylesheet">
     <link href="<?= base_url(); ?>css/timeline.css" rel="stylesheet">
     <link href="<?= base_url(); ?>assets/admin/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
     <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.12.1/af-2.4.0/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/r-2.3.0/sc-2.0.7/datatables.min.css" />

     <link rel="stylesheet" href="<?= base_url(); ?>assets/flatpickr/css/flatpickr.min.css">
     <link rel="stylesheet" href="<?= base_url(); ?>assets/flatpickr/plugin/month_select_style.css">
     <!-- select2 -->
     <link rel="stylesheet" href="<?= base_url(); ?>assets/select2/css/select2.css">

     <!-- script -->
     <!-- Bootstrap core JavaScript-->
     <script src="<?= base_url(); ?>assets/admin/jquery/jquery.min.js"></script>
     <script src="<?= base_url(); ?>assets/admin/bootstrap/js/bootstrap.bundle.min.js"></script>

     <!-- Core plugin JavaScript-->
     <script src="<?= base_url(); ?>assets/admin/jquery-easing/jquery.easing.min.js"></script>

     <!-- datatable -->
     <script src="<?= base_url(); ?>assets/admin/datatables/jquery.dataTables.min.js"></script>
     <script src="<?= base_url(); ?>assets/admin/datatables/dataTables.bootstrap4.min.js"></script>
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
     <script src="<?= base_url(); ?>assets/js/jquery.number.min.js"></script>
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

     .loading {
          position: relative;
     }

     .loading::after {
          content: "Loading...";
          position: absolute;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
          background: rgba(255, 255, 255, 0.8);
          padding: 10px;
          border-radius: 5px;
     }

     .font-small {
          font-size: 13px;
     }

     table td,
     table th {
          text-align: center;
          vertical-align: middle !important;
     }
</style>

<body id="page-top">

     <!-- Page Wrapper -->
     <div id="wrapper">

          <!-- Sidebar -->
          <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

               <!-- Sidebar - Brand -->
               <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('admin/dashboard'); ?>">
                    <div class="sidebar-brand-icon rotate-n-15">
                         <i class="fas fa-truck"></i>
                    </div>
                    <div class="sidebar-brand-text mx-3">RHL Cargo<sup>2</sup></div>
               </a>

               <!-- Render Menu -->
               <?php
               function render_menu($menu_items, $parent_id = 0, $id = '', $role = '')
               {
                    $html = '';
                    foreach ($menu_items as $item) {
                         if ($item['menu_parent'] == $parent_id) {
                              $has_children = has_children($menu_items, $item['menu_id']);
                              $main_menu_checked = (isset($item[$id]) && $item[$id] == 'Y') ? 'show' : '';
                              $bold = ($parent_id == 0) ? 'font-weight:bold; color:blue;' : '';

                              $html .= '<li class="nav-item" style="' . $bold . '">';

                              if ($has_children) {
                                   // Heading menu
                                   $html .= '<hr class="sidebar-divider">';
                                   $html .= '<div class="sidebar-heading">'. $item['menu_name'] . '</div>';

                                   if ($role == 'PIC RELASI') {
                                        // Tampilkan langsung submenu tanpa collapse
                                        $html .= '<ul class="nav flex-column">';
                                        foreach ($menu_items as $sub_item) {
                                             if ($sub_item['menu_parent'] == $item['menu_id']) {
                                                  $html .= '<li class="nav-item">';
                                                  $html .= '<a class="nav-link" href="' . base_url('admin/' . $sub_item['menu_link']) . '">';
                                                  $html .= '<i class="fas fa-fw mr-2 ' . $sub_item['icon_class'] . '"></i>';
                                                  $html .= '<span>' . $sub_item['menu_name'] . '</span>';
                                                  $html .= '</a>';
                                                  $html .= '</li>';
                                             }
                                        }
                                        $html .= '</ul>';
                                   } else {
                                        // Role lain tetap pakai collapse
                                        $html .= '<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menu_' . $item['menu_id'] . '" aria-expanded="true" aria-controls="menu_' . $item['menu_id'] . '">';
                                        $html .= '<i class="fas fa-fw mr-2 ' . $item['icon_class'] . '"></i>';
                                        $html .= '<span>' . $item['menu_name'] . '</span>';
                                        $html .= '</a>';
                                        $html .= '<div id="menu_' . $item['menu_id'] . '" class="collapse ' . $main_menu_checked . '">';
                                        $html .= render_sub_menu($menu_items, $item['menu_id'], $item['menu_name']);
                                        $html .= '</div>';
                                   }
                              } else {
                                   // Menu tanpa anak, langsung link
                                   $html .= '<a class="nav-link" href="' . base_url('admin/' . $item['menu_link']) . '">';
                                   $html .= '<i class="fas fa-fw mr-2 ' . $item['icon_class'] . '"></i>';
                                   $html .= '<span>' . $item['menu_name'] . '</span>';
                                   $html .= '</a>';
                              }

                              $html .= '</li>';
                              $html .= '<hr class="sidebar-divider my-0">';
                         }
                    }
                    return $html;
               }

               function has_children($menu_items, $parent_id)
               {
                    foreach ($menu_items as $item) {
                         if ($item['menu_parent'] == $parent_id) {
                              return true;
                         }
                    }
                    return false;
               }

               function render_sub_menu($menu_items, $parent_id, $parent_name)
               {
                    $html = '<h6 class="collapse-header">' . $parent_name . ' :</h6>';
                    foreach ($menu_items as $item) {
                         if ($item['menu_parent'] == $parent_id) {
                              // Sub-menu item
                              $html .= '<a class="collapse-item" href="' . base_url('admin/' . $item['menu_link']) . '">';
                              $html .= '<i class="fas fa-fw mr-2 ' . $item['icon_class'] . '"></i>';
                              $html .= $item['menu_name'] . '</a>';
                         }
                    }
                    return $html ? '<div class="bg-white py-2 collapse-inner rounded">' . $html . '</div>' : '';
               }

               $userModel = new \App\Models\AdminModel();
               $user_login = $userModel->getDataAdmin(session()->get('username'));
               $kolom = 'role_' . $user_login['role_id'];
               $builder = db_connect()->table('menu');
               $builder->where('menu_status', 'Y');
               $builder->where($kolom, 'Y');
               $builder->where('menu_name !=', 'Home');
               $builder->orderBy('menu_order', 'ASC');

               // Eksekusi query dan ambil hasilnya
               $menu = $builder->get()->getResultArray();

               $img = base_url() . 'assets/img/users/' . $user_login['foto'];
               if ($user_login['foto'] == null) {
                    $img = base_url() . 'assets/img/users/default.jpg';
               }
               ?>
               <!-- End render menu -->

               <!-- Divider -->
               <hr class="sidebar-divider my-0">
               <li class="nav-item active">
                    <a class="nav-link" href="<?= base_url('admin/dashboard'); ?>">
                         <i class="fas fa-fw fa-tachometer-alt"></i>
                         <span>Dashboard</span></a>
               </li>
               <hr class="sidebar-divider my-0">
               <?php
               $html = render_menu($menu, 0, '', session()->get('role'));
               echo $html;
               ?>


               <hr class="sidebar-divider">
               <div class="sidebar-heading">
                    Account
               </div>

               <li class="nav-item">
                    <a class="nav-link" href="/logoutAdmin">
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
                              <div class="topbar-divider d-none d-sm-block"></div>

                              <!-- Nav Item - User Information -->
                              <li class="nav-item dropdown no-arrow">
                                   <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= strtoupper(session()->get('username')); ?></span>
                                        <img class="img-profile rounded-circle" src="<?= $img; ?>">
                                   </a>
                                   <!-- Dropdown - User Information -->
                                   <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                        <a class="dropdown-item" href="<?= base_url('admin/profile'); ?>">
                                             <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                             Profile
                                        </a>
                                        <a class="dropdown-item" href="<?= base_url('admin/ubah-password'); ?>">
                                             <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                             Settings
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="/logoutAdmin" data-toggle="modal" data-target="#logoutModal">
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
                              <span>Copyright &copy; <?= getenv('COMPANY_NAME'); ?> <?= date('Y') ?></span>
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
                         <a class="btn btn-primary" href="/logoutAdmin">Logout</a>
                    </div>
               </div>
          </div>
     </div>

     <!-- Page level plugins -->
     <script src="<?= base_url(); ?>assets/admin/chart.js/Chart.min.js"></script>
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
               $(".select2_multiple").select2({
                    tags: true,
                    tokenSeparators: [',', ' ']
               })
               $('.dataTable').DataTable();

          });

          // function formatRupiah(angka) {
          //      if (isNaN(angka)) {
          //           return angka;
          //      }
          //      return angka.toLocaleString('id-ID', {
          //           style: 'currency',
          //           currency: 'IDR',
          //           minimumFractionDigits: 0,
          //           maximumFractionDigits: 0
          //      });
          // }

          function formatRupiah(number) {
               return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
               }).format(number);
          }

          function cleanNumber(str) {
               if (typeof str === 'string') {
                    return parseFloat(str.replace(/\./g, '').replace(',', '.')) || 0;
               }
               return parseFloat(str) || 0;
          }
          // $(".datepicker").flatpickr({
          //     dateFormat: "Y-m-d"
          // });
          $('.number').number(true, 0);
          $('.number_2').number(true, 2);
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

          function convertDate(date, fromFormat = 'DD-MM-YYYY', toFormat = 'YYYY-MM-DD') {
               if (!date) {
                    return moment().format(toFormat); // Kembalikan tanggal hari ini
               }
               return moment(date, fromFormat).format(toFormat);
          }

          $('.datetime').each(function() {
               $(this).datetimepicker({
                    uiLibrary: 'bootstrap4',
                    size: 'small',
                    format: 'dd-mm-yyyy HH:MM',
                    footer: true,
                    modal: true
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
          $(".timepicker").flatpickr({
               enableTime: true,
               noCalendar: true,
               dateFormat: "H:i",
               time_24hr: true
          });
     </script>

</body>

</html>