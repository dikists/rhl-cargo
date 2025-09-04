<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\PengirimanController;

/**
 * @var RouteCollection $routes
 */
$routes->setAutoRoute(true);
$routes->get('/', 'Pages::index');
// $routes->get('/about', 'Pages::about');

// Login
$routes->get('/login', 'Auth::login');
$routes->post('/loginAuth', 'Auth::loginAuth');
$routes->get('/logout', 'Auth::logout');

// Dashboard
$routes->get('/dashboard', 'Dashboard::index');
$routes->get('/tracking', 'Dashboard::tracking');
$routes->get('/tracking/detail/(:any)', 'Dashboard::detail_tracking/$1');

// Laporan
$routes->get('/laporan_pengiriman', 'Laporan::laporan_pengiriman');
$routes->get('/laporan_performance', 'Laporan::laporan_performance');
$routes->get('/laporan/get_data', 'Laporan::get_data');

// DATATABLES 
$routes->post('laporan/jurnal/datatables', 'LaporanController::jurnalDatatable');

$routes->get('admin/laporan_hutang_piutang', 'LaporanController::hutangPiutang');
$routes->get('admin/laporan_jurnal', 'LaporanController::jurnal');
$routes->get('admin/laporan_neraca', 'LaporanController::neraca');
$routes->get('admin/laporan_laba_rugi', 'LaporanController::labaRugi');

// CHART
$routes->get('/monthly-shipments', 'Dashboard::monthlyShipments');
$routes->get('/monthly-shipments-new', 'Dashboard::monthlyShipmentsNew');
$routes->get('/shipment-status-summary', 'Dashboard::shipmentStatusSummary');
$routes->get('/shipment-status-summary-new', 'Dashboard::shipmentStatusSummaryNew');

// ubah password
$routes->get('ubah-password', 'UserController::ubahPassword');
$routes->post('ubah-password', 'UserController::prosesUbahPassword');

$routes->get('pdf', 'PdfController::generatePdf');

$routes->get('export-excel', 'ExcelController::export');

// tracking-package
$routes->get('tracking-package-view', 'TrackingController::index');
$routes->post('tracking-package', 'TrackingController::track');

// Admin
$routes->get('loginAdmin', 'Auth::login_admin');
$routes->post('loginAuthAdmin', 'Auth::loginAuthAdmin');
$routes->get('logoutAdmin', 'Auth::logoutAdmin');


$routes->get('admin/dashboard', 'AdminController::dashboard');

// DATA
$routes->get('data/getKabupaten/(:num)', 'DataController::getKabupaten/$1');
$routes->get('data/getKecamatan/(:num)', 'DataController::getKecamatan/$1');
$routes->get('data/getDesa/(:num)', 'DataController::getDesa/$1');
$routes->get('data/get_penerima_by_pengirim/(:num)', 'DataController::get_penerima_by_pengirim/$1');
$routes->get('data/get_layanan_new_order', 'DataController::get_layanan_new_order');

// PAGES
$routes->get('admin/barang', 'PagesController::viewBarang');
$routes->get('admin/rekening', 'PagesController::viewRekening');
$routes->get('admin/pelanggan', 'PagesController::viewPelanggan');
$routes->get('admin/penerima', 'PagesController::viewPenerima');
$routes->get('admin/penerima/add', 'PagesController::addPenerima');
$routes->get('admin/penerima/edit/(:num)', 'PagesController::editPenerima/$1');
$routes->get('admin/shipmentStatus', 'PagesController::viewShipmentStatus');
$routes->get('admin/admin/users', 'PagesController::viewUsers');
$routes->get('admin/users/add', 'PagesController::addUsers');
$routes->get('admin/users/edit/(:num)', 'PagesController::editUsers/$1');
$routes->get('admin/Vendor', 'PagesController::viewVendor');
$routes->get('admin/order/all', 'PagesController::viewOrder');
$routes->get('admin/order/add', 'PagesController::addOrder');
$routes->get('admin/order/edit/(:num)', 'PagesController::editOrder/$1');
$routes->get('admin/order/barang/(:num)', 'PagesController::detailOrder/$1');
$routes->get('admin/order/detail/(:num)', 'PagesController::viewDetailOrder/$1');
$routes->get('admin/surat_jalan', 'PagesController::viewSuratJalan');
$routes->get('admin/surat_jalan/add', 'PagesController::addSuratJalan');
$routes->get('admin/surat_jalan/edit/(:num)', 'PagesController::editSuratJalan/$1');
$routes->get('admin/pengambilan', 'PagesController::viewPengambilan');
$routes->get('admin/pengambilan/add', 'PagesController::addPengambilan');
$routes->get('admin/pengambilan/edit/(:num)', 'PagesController::editPengambilan/$1');
$routes->get('admin/manifest', 'PagesController::viewManifest');
$routes->get('admin/manifest/add', 'PagesController::addManifest');
$routes->get('admin/manifest/detail/(:num)', 'PagesController::detailManifest/$1');
$routes->get('admin/manifest/edit/(:num)', 'PagesController::editManifest/$1');
$routes->get('admin/pengiriman', 'PagesController::viewPengiriman');
$routes->get('admin/biaya/add', 'PagesController::viewAdd_biaya');

// MASTER
// barang
$routes->get('data/getBarang', 'MasterController::getBarang');
$routes->post('data/saveBarang', 'MasterController::saveBarang');
$routes->post('data/updateBarang', 'MasterController::updateBarang');
$routes->delete('/data/deleteBarang/(:num)', 'MasterController::deleteBarang/$1');

// rekening
$routes->get('data/getRekening', 'MasterController::getRekening');
$routes->post('data/saveRekening', 'MasterController::saveRekening');
$routes->post('data/updateRekening', 'MasterController::updateRekening');
$routes->delete('/data/deleteRekening/(:num)', 'MasterController::deleteRekening/$1');

// Pelanggan
$routes->get('data/getPelanggan', 'MasterController::getPelanggan');
$routes->post('data/savePelanggan', 'MasterController::savePelanggan');
$routes->post('data/updatePelanggan', 'MasterController::updatePelanggan');
$routes->delete('/data/deletePelanggan/(:num)', 'MasterController::deletePelanggan/$1');

// Penerima
$routes->get('data/getPenerima', 'MasterController::getPenerima');
$routes->post('data/savePenerima', 'MasterController::savePenerima');
$routes->delete('/data/deletePenerima/(:num)', 'MasterController::deletePenerima/$1');

// shipmentStatus
$routes->get('data/getshipmentStatus', 'MasterController::getshipmentStatus');
$routes->post('data/saveStatus', 'MasterController::saveStatus');
$routes->post('data/updateStatus', 'MasterController::updateStatus');
$routes->delete('/data/deleteStatus/(:num)', 'MasterController::deleteStatus/$1');

// USER YANG BUKAN PELANGGAN
$routes->get('data/getUsers', 'MasterController::getUsers');
$routes->post('data/saveUsers', 'MasterController::saveUsers');
$routes->delete('/data/deleteUsers/(:num)', 'MasterController::deleteUsers/$1');

// VENDOR
$routes->get('data/getVendor', 'MasterController::getVendor');
$routes->post('data/saveVendor', 'MasterController::saveVendor');
$routes->post('data/updateVendor', 'MasterController::updateVendor');
$routes->delete('/data/deleteVendor/(:num)', 'MasterController::deleteVendor/$1');

/*
     TRANSAKSI
*/

// ORDER
$routes->get('data/getOrder', 'TransController::getOrder');
$routes->get('data/getDetailOrder/(:num)', 'TransController::getDetailOrder/$1');
$routes->get('data/deleteOrder/(:num)', 'TransController::deleteOrder/$1');
$routes->post('data/saveOrder', 'TransController::saveOrder');
$routes->post('data/editOrder', 'TransController::editOrder');
$routes->post('data/add_detail_order', 'TransController::add_detail_order');
$routes->post('data/update_detail_order', 'TransController::update_detail_order');
$routes->post('data/delete_detail_order', 'TransController::delete_detail_order');

// SURAT JALAN;
$routes->get('data/getSuratJalan', 'SuratJalanController::getSuratJalan');
$routes->post('data/saveSuratJalan', 'SuratJalanController::saveSuratJalan');
$routes->post('data/updateSuratJalan', 'SuratJalanController::updateSuratJalan');
$routes->get('pdf/cetak_resi/(:num)', 'SuratJalanController::cetak_resi/$1');
// $routes->get('pdf/cetak_label/(:num)/(:num)/(:num)', 'SuratJalanController::cetak_label/$1/$2/$3');
$routes->get('pdf/cetak_label', 'SuratJalanController::cetak_label');
// $routes->get('cetakResi/(:num)', 'SuratJalanController::cetakResi/$1');
$routes->get('data/surat_jalan/delete/(:num)', 'SuratJalanController::deleteSuratJalan/$1');

// PENGAMBILAN;
$routes->get('data/getPengambilan', 'PengambilanController::getPengambilan');
$routes->post('data/savePengambilan', 'PengambilanController::savePengambilan');
$routes->get('data/pengambilan/delete/(:num)', 'PengambilanController::deletePengambilan/$1');

// MANIFEST;
$routes->get('data/getManifest', 'ManifestController::getManifest');
$routes->post('data/saveManifest', 'ManifestController::saveManifest');
$routes->post('data/updateManifest', 'ManifestController::updateManifest');
$routes->get('data/deleteManifest/(:num)', 'ManifestController::deleteManifest/$1');
$routes->get('pdf/manifest/(:num)', 'ManifestController::pdf_manifest/$1');
$routes->get('pdf/manifest_rincian/(:num)', 'PdfController::manifest_rincian/$1');
$routes->get('pdf/manifest_sub/(:num)', 'PdfController::manifest_sub/$1');
// DETAIL MANIFEST
$routes->get('data/getDetailManifest/(:num)', 'ManifestDetailController::getDetailManifest/$1');
$routes->post('data/add_detail_manifest', 'ManifestDetailController::add');
$routes->post('data/delete_detail_manifest', 'ManifestDetailController::delete');
$routes->get('data/getSJManifest', 'SuratJalanController::getSJManifest');

// RELATION Tax
$routes->group('relation-tax', ['filter' => 'auth'], function ($routes) {
     $routes->get('/', 'RelationTaxController::index');     // GET semua data
     $routes->get('(:num)', 'RelationTaxController::show/$1'); // GET detail data
     $routes->post('/', 'RelationTaxController::create');  // POST tambah data
     $routes->put('(:num)', 'RelationTaxController::update/$1'); // PUT update data
     $routes->delete('(:num)', 'RelationTaxController::delete/$1'); // DELETE hapus data
});

// RELATION Tax PPH
$routes->group('relation-tax-pph', ['filter' => 'auth'], function ($routes) {
     $routes->get('(:num)', 'RelationPphController::show/$1'); // GET detail data
     $routes->post('/', 'RelationPphController::create');  // POST tambah data
     $routes->put('(:num)', 'RelationPphController::update/$1'); // PUT update data
     $routes->delete('(:num)', 'RelationPphController::delete/$1'); // DELETE hapus data
});

$routes->group('delivery-status', ['filter' => 'auth'], function ($routes) {
     $routes->get('(:num)', 'PengirimanController::show/$1'); // GET detail data
     $routes->post('/', 'PengirimanController::create');  // POST tambah data
     $routes->put('(:num)', 'RelationTaxController::update/$1'); // PUT update data
     $routes->delete('(:num)', 'RelationTaxController::delete/$1'); // DELETE hapus data
});

// Biaya
$routes->group('admin/biaya', ['filter' => 'auth'], function ($routes) {
     $routes->get('/', 'LayananController::index');     // GET semua data
     $routes->get('(:num)', 'PagesController::viewEdit_biaya/$1'); // GET detail data
     $routes->post('/', 'LayananController::create');  // POST tambah data
     $routes->put('(:num)', 'LayananController::update/$1'); // PUT update data
     $routes->delete('(:num)', 'LayananController::delete/$1'); // DELETE hapus data
});

// PENAWARAN
$routes->group('admin/offers', ['filter' => 'auth'], function ($routes) {
     $routes->get('/', 'OfferController::index');     // GET semua data
     $routes->get('(:num)', 'OfferController::viewEdit_biaya/$1'); // GET detail data
     $routes->post('/', 'OfferController::create');  // POST tambah data
     $routes->put('(:num)', 'OfferController::update/$1'); // PUT update data
});
$routes->post('admin/offers/generateInvoice', 'OfferController::generateInvoice', ['filter' => 'auth']);
$routes->get('admin/offers/add', 'PagesController::viewAddOffers', ['filter' => 'auth']);
$routes->get('data/getLayanan', 'LayananController::getLayanan');     // GET semua data

// PENGIRIMAN;
$routes->get('data/getPengiriman', 'PengirimanController::getPengiriman');
$routes->put('data/updateRemarkPengiriman/(:num)', 'PengirimanController::updateRemark/$1');
$routes->put('data/updateInsurancePengiriman/(:num)', 'PengirimanController::updateInsurance/$1');
$routes->put('data/updateSurchargePengiriman/(:num)', 'PengirimanController::updateSurcharge/$1');
// $routes->post('data/savePengambilan', 'PengambilanController::savePengambilan');
// $routes->get('data/pengambilan/delete/(:num)', 'PengambilanController::deletePengambilan/$1');

// DETAIL PENAWARAN
$routes->get('admin/offer-details/(:num)/(:num)/delete', 'OfferDetailsController::delete/$1/$2', ['filter' => 'auth']);
$routes->get('admin/offers/(:num)/delete', 'OfferController::delete/$1', ['filter' => 'auth']);
$routes->get('admin/offers/(:num)/edit', 'PagesController::viewEditOffers/$1', ['filter' => 'auth']);
$routes->get('admin/offer-details/(:num)', 'PagesController::viewDetailOffers/$1', ['filter' => 'auth']);
$routes->post('admin/offer-details/store', 'OfferDetailsController::store', ['filter' => 'auth']);
$routes->get('pdf/offers/(:num)', 'OfferController::offers_pdf/$1');

// INVOICE
$routes->get('admin/invoice', 'PagesController::viewInvoice', ['filter' => 'auth']);
$routes->get('data/getInvoice', 'InvoiceController::getInvoice', ['filter' => 'auth']);
$routes->get('admin/invoice/add', 'PagesController::viewAddInvoice', ['filter' => 'auth']);
$routes->post('admin/invoice', 'InvoiceController::create', ['filter' => 'auth']);
$routes->put('admin/invoice/(:num)', 'InvoiceController::update/$1', ['filter' => 'auth']);
$routes->get('admin/invoice/(:num)', 'PagesController::viewEditInvoice/$1', ['filter' => 'auth']);
$routes->get('admin/invoice/(:num)/delete', 'InvoiceController::delete/$1', ['filter' => 'auth']);
$routes->get('admin/invoice/(:num)/detail', 'PagesController::viewDetailInvoice/$1', ['filter' => 'auth']);
$routes->get('data/getPengirimanToInvoice/(:num)', 'PengirimanController::getPengirimanToInvoice/$1');
$routes->get('admin/invoice/(:num)/print', 'InvoiceController::printInvoice/$1', ['filter' => 'auth']);
$routes->get('admin/invoice/(:num)/print-02', 'InvoiceController::printInvoice_02/$1', ['filter' => 'auth']);
$routes->get('admin/invoice_offer/(:num)/(:any)/print', 'PdfController::printInvoiceOffer/$1/$2', ['filter' => 'auth']);
$routes->get('admin/invoice/(:num)/excel', 'ExcelController::exportInvoice/$1', ['filter' => 'auth']);
$routes->get('admin/invoice/(:num)/bayar', 'InvoiceController::payInvoice/$1', ['filter' => 'auth']);
$routes->get('admin/invoice/export', 'PagesController::exportInvoiceCoretax', ['filter' => 'auth']);
$routes->post('admin/invoice/export', 'InvoiceController::exportInvoiceCoretax', ['filter' => 'auth']);
$routes->get('admin/invoice/get_receipt_tax/', 'InvoiceController::getReceiptTax', ['filter' => 'auth']);
$routes->post('admin/invoice/action_receipt_cancel/', 'InvoiceController::cancelReceiptExport', ['filter' => 'auth']);
$routes->get('admin/invoice/export_to_xml/(:num)', 'InvoiceController::receiptExportToXml/$1', ['filter' => 'auth']);
// DETAIL INVOICE
$routes->post('data/insertDetailInvoice', 'DetailInvoiceController::insert', ['filter' => 'auth']);
$routes->post('data/deleteDetailInvoice/(:num)', 'DetailInvoiceController::delete/$1', ['filter' => 'auth']);
$routes->get('data/getDetailInvoice/(:num)', 'DetailInvoiceController::getByInvoice/$1', ['filter' => 'auth']);
// $routes->get('data/getPengirimanToInvoice/(:num)', 'PengirimanController::getPengirimanToInvoice/$1');

$routes->post('data/invoice/getTax', 'RelationTaxController::getRelationTax', ['filter' => 'auth']);
$routes->post('data/invoice/getTaxPph', 'RelationPphController::getRelationTax', ['filter' => 'auth']);

/**
 * LAPORAN NEW
 */
$routes->get('admin/laporan_pengiriman', 'PagesController::viewLaporanPengiriman', ['filter' => 'auth']);
$routes->get('admin/laporan_invoice', 'PagesController::viewLaporanInvoice', ['filter' => 'auth']);

/**
 * USER ROLE
 */
$routes->get('admin/settings/user_role', 'UserRoleController::index', ['filter' => 'auth']);
$routes->get('admin/settings/new_role', 'UserRoleController::create', ['filter' => 'auth']);
$routes->post('admin/settings/save_role', 'UserRoleController::store', ['filter' => 'auth']);
$routes->get('admin/settings/edit_role/(:num)', 'UserRoleController::edit/$1', ['filter' => 'auth']);
$routes->post('admin/settings/update_role/(:num)', 'UserRoleController::update/$1', ['filter' => 'auth']);
$routes->get('admin/settings/delete_role/(:num)', 'UserRoleController::delete/$1', ['filter' => 'auth']);

/**
 * USER
 */
$routes->get('admin/settings/user', 'UserController::index', ['filter' => 'auth']);
$routes->post('admin/settings/save_user', 'UserController::store', ['filter' => 'auth']);
$routes->post('admin/settings/update_user/(:num)', 'UserController::update/$1', ['filter' => 'auth']);
$routes->get('admin/settings/delete_user/(:num)', 'UserController::delete/$1', ['filter' => 'auth']);


/**
 * BIAYA TAMBAHAN
 */
$routes->get('admin/biaya_tambahan', 'BiayaTambahanController::index', ['filter' => 'auth']);
$routes->post('admin/biaya_tambahan/save', 'BiayaTambahanController::save', ['filter' => 'auth']);

$routes->get('pengiriman/additional_cost/(:num)', 'PengirimanController::additional_cost/$1', ['filter' => 'auth']);
$routes->post('admin/pengiriman/add_extra_cost', 'PengirimanController::add_extra_cost', ['filter' => 'auth']);
$routes->post('admin/pengiriman/delete_extra_cost/(:num)/(:num)', 'PengirimanController::delete_extra_cost/$1/$2', ['filter' => 'auth']);

/**
 * PROFILE
 */
$routes->get('admin/profile', 'Profile::profile', ['filter' => 'auth']);
$routes->post('admin/profile/upload_foto', 'Profile::update_foto', ['filter' => 'auth']);
$routes->post('admin/profile/update_profile', 'Profile::update_profile', ['filter' => 'auth']);
$routes->post('admin/profile/update_password', 'Profile::update_password', ['filter' => 'auth']);

$routes->get('admin/ubah-password', 'Profile::edit_password', ['filter' => 'auth']);


/**
 * RELATION USER
 */
$routes->post('admin/settings/update_relation_user/(:num)', 'UserController::update_relation_user/$1', ['filter' => 'auth']);

/**
 * TRUCK
 */
$routes->get('admin/truck', 'TruckController::index');
$routes->get('admin/truck/create', 'TruckController::create');
$routes->post('admin/truck/store', 'TruckController::store');
$routes->get('admin/truck/edit/(:num)', 'TruckController::edit/$1');
$routes->post('admin/truck/update/(:num)', 'TruckController::update/$1');
$routes->get('admin/truck/delete/(:num)', 'TruckController::delete/$1');


/**
 * 
 * LAPORAN PDF
 */
$routes->get('laporan_pengiriman/export_pdf_liugong', 'PdfController::pengiriman_req_liugong');
$routes->get('laporan_invoice/export_pdf', 'PdfController::invoice');
$routes->get('laporan_jurnal/export_pdf', 'PdfController::laporan_jurnal');

/**
 * 
 * LAPORAN EXCEL
 */
$routes->get('laporan_invoice/export_excel', 'ExcelController::laporan_invoice');

/** HUTANG  **/
$routes->get('admin/hutang', 'PayablesController::index');
$routes->get('admin/hutang/create', 'PayablesController::create');
$routes->post('admin/hutang/store', 'PayablesController::store');
$routes->get('admin/hutang/pay/(:num)', 'PayablesController::pay/$1');
$routes->post('admin/hutang/pay/(:num)', 'PayablesController::storePayment/$1');

$routes->post('data/hutang/datatables', 'PayablesController::hutangDataTable');


/** PIUTANG **/
$routes->get('admin/piutang', 'ReceivablesController::index');
$routes->get('admin/piutang/create', 'ReceivablesController::create');
$routes->post('admin/piutang/store', 'ReceivablesController::store');
$routes->get('admin/piutang/pay/(:num)', 'ReceivablesController::pay/$1');
$routes->post('admin/piutang/pay/(:num)', 'ReceivablesController::storePayment/$1');

$routes->post('data/piutang/datatables', 'ReceivablesController::piutangDataTable');

/** CRONJOB */
$routes->get('cronjob/wa-reminder/(:segment)', 'CronjobWeb::waReminder/$1');
$routes->get('cronjob/late-reason-delivery/(:segment)', 'CronjobWeb::lateReasonDelivery/$1');

/** RAJA ONGKIR */
$routes->get('admin/cekongkir', 'OngkirController::index');
$routes->get('cekongkir/provinces', 'OngkirController::getProvinces');
$routes->get('cekongkir/cities/(:num)', 'OngkirController::getCities/$1');
$routes->post('cekongkir/cost', 'OngkirController::getCost');
$routes->get('admin/cekongkir/history', 'OngkirController::history');

/** UANG JALAN */
$routes->get('admin/uang_jalan', 'PagesController::viewUangJalan');
$routes->get('admin/uang_jalan/add', 'PagesController::viewAddUangJalan');
$routes->get('admin/uang_jalan/edit/(:num)', 'PagesController::viewEditUangJalan/$1');
$routes->get('admin/uang_jalan/approve/(:num)', 'UangJalanController::approveUangJalan/$1');
$routes->get('admin/uang_jalan/reject/(:num)', 'UangJalanController::rejectUangJalan/$1');
$routes->get('admin/uang_jalan/delete/(:num)', 'UangJalanController::delete/$1');
$routes->post('admin/uang_jalan/save', 'UangJalanController::save');
$routes->post('admin/uang_jalan/update', 'UangJalanController::update');
$routes->post('data/uangjalan/datatables', 'UangJalanController::getUangJalan');
$routes->get('admin/uang_jalan/pdf/(:num)', 'PdfController::uang_jalan/$1');
// $routes->get('uang_jalan/save', 'UangJalanController::save');

$routes->group('api', ['filter' => 'authToken', 'namespace' => 'App\Controllers\Api'], function ($routes) {
     $routes->get('pengiriman/(:num)', 'Pengiriman::show/$1'); // berdasarkan id
     $routes->get('pengiriman/track/(:any)', 'Pengiriman::track/$1'); // tracking by no_surat_jalan / no_order / no_ref
});

$routes->group('api', ['namespace' => 'App\Controllers\Api'], function ($routes) {
     $routes->get('pengiriman', 'Pengiriman::all');
     $routes->post('login', 'Login::index');
});
