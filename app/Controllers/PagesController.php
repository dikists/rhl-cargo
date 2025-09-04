<?php

namespace App\Controllers;

use App\Models\DesaModel;
use App\Models\RoleModel;
use App\Models\AdminModel;
use App\Models\OfferModel;
use App\Models\OrderModel;
use App\Models\TruckModel;
use App\Models\BarangModel;
use App\Models\ClauseModel;
use App\Models\VendorModel;
use App\Models\InvoiceModel;
use App\Models\LayananModel;
use App\Models\ManifestModel;
use App\Models\PenerimaModel;
use App\Models\ProvinsiModel;
use App\Models\KabupatenModel;
use App\Models\KecamatanModel;
use App\Models\PelangganModel;
use App\Models\UangJalanModel;
use App\Models\ChoiceListModel;
use App\Models\PengirimanModel;
use App\Models\SuratJalanModel;
use App\Models\BankAccountModel;
use App\Models\RelationPphModel;
use App\Models\RelationTaxModel;
use App\Models\OfferDetailsModel;
use App\Models\RelationUserModel;
use App\Models\ReceiptExportModel;
use App\Controllers\BaseController;
use App\Models\ManifestDetailModel;

class PagesController extends BaseController
{
     protected $barangModel;
     protected $choiceListModel;
     protected $pelangganModel;
     protected $provinsiModel;
     protected $penerimaModel;
     protected $kabModel;
     protected $kecModel;
     protected $desModel;
     protected $roleModel;
     protected $adminModel;
     protected $vendorModel;
     protected $orderModel;
     protected $layananModel;
     protected $sjModel;
     protected $manifestModel;
     protected $manifestDetailModel;
     protected $clauseModel;
     protected $offerModel;
     protected $offerDetailsModel;
     protected $rekeningModel;
     protected $invoiceModel;
     protected $pengirimanModel;
     protected $relationTax;
     protected $relationPphModel;
     protected $relationUser;
     protected $truckModel;
     protected $receiptExportModel;
     protected $ujModel;
     public function __construct()
     {
          $this->roleModel = new RoleModel();
          $this->barangModel = new BarangModel();
          $this->choiceListModel = new ChoiceListModel();
          $this->pelangganModel = new PelangganModel();
          $this->provinsiModel = new ProvinsiModel();
          $this->penerimaModel = new PenerimaModel();
          $this->kabModel = new KabupatenModel();
          $this->kecModel = new KecamatanModel();
          $this->desModel = new DesaModel();
          $this->adminModel = new AdminModel();
          $this->vendorModel = new VendorModel();
          $this->orderModel = new OrderModel();
          $this->layananModel = new LayananModel();
          $this->sjModel = new SuratJalanModel();
          $this->manifestModel = new ManifestModel();
          $this->manifestDetailModel = new ManifestDetailModel();
          $this->clauseModel = new ClauseModel();
          $this->offerModel = new OfferModel();
          $this->offerDetailsModel = new OfferDetailsModel();
          $this->rekeningModel = new BankAccountModel();
          $this->invoiceModel = new InvoiceModel();
          $this->pengirimanModel = new PengirimanModel();
          $this->relationTax = new RelationTaxModel();
          $this->relationPphModel = new RelationPphModel();
          $this->relationUser = new RelationUserModel();
          $this->truckModel = new TruckModel();
          $this->receiptExportModel = new ReceiptExportModel();
          $this->ujModel = new UangJalanModel();
     }
     public function viewBarang()
     {
          $data = [
               'title' => 'Daftar Barang',
               'satuan' => $this->choiceListModel->getChoicesByType('satuan')
          ];
          echo view('master/barang', $data);
     }
     public function viewRekening()
     {
          $data = [
               'title' => 'Daftar Rekening',
               'satuan' => $this->choiceListModel->getChoicesByType('satuan')
          ];
          echo view('master/rekening', $data);
     }
     public function viewPelanggan()
     {
          $data = [
               'title' => 'Daftar Pelanggan',
               'satuan' => $this->choiceListModel->getChoicesByType('satuan')
          ];
          echo view('master/pelanggan', $data);
     }
     public function viewPenerima()
     {
          $data = [
               'title' => 'Daftar Penerima'
          ];
          echo view('master/penerima', $data);
     }
     public function addPenerima()
     {
          $data = [
               'title' => 'Tambah Penerima',
               'pelanggan' => $this->pelangganModel->getPelanggan(),
               'provinsi' => $this->provinsiModel->getProvinsi()
          ];
          echo view('master/penerima_form', $data);
     }
     public function editPenerima($id)
     {
          $penerima = $this->penerimaModel->getPenerima($id);
          $data = [
               'title' => 'Edit Penerima',
               'penerima' => $penerima,
               'pelanggan' => $this->pelangganModel->getPelanggan(),
               'provinsi' => $this->provinsiModel->getProvinsi(),
               'kabupaten' => $this->kabModel->getKabupaten($penerima[0]['provinsi_id']),
               'kecamatan' => $this->kecModel->getKecamatan($penerima[0]['kabupaten_id']),
               'desa' => $this->desModel->getDesa($penerima[0]['kecamatan_id'])
          ];
          echo view('master/penerima_form', $data);
     }
     public function viewShipmentStatus()
     {
          $data = [
               'title' => 'Status Pengiriman'
          ];
          echo view('master/shipmentStatus', $data);
     }
     public function viewUsers()
     {
          $data = [
               'title' => 'Daftar Users'
          ];
          echo view('master/users', $data);
     }
     public function addUsers()
     {
          $data = [
               'title' => 'Tambah User',
               'role' => $this->roleModel->getRole(),
          ];

          echo view('master/users_form', $data);
     }
     public function editUsers($id)
     {
          $data = [
               'title' => 'Edit User',
               'role' => $this->roleModel->getRole(),
               'users' => $this->adminModel->getDataUsers($id)
          ];

          echo view('master/users_form', $data);
     }
     public function viewVendor()
     {
          $data = [
               'title' => 'Daftar Vendor'
          ];
          echo view('master/vendor', $data);
     }
     public function viewOrder()
     {
          $pengirim = $this->pelangganModel->getPelanggan();
          if (session()->get('role') == 'PIC RELASI') {
               $pengirim = $this->relationUser->get_user_relation(session()->get('id'));
          }

          $data = [
               'title' => 'Daftar Order',
               'pengirim' => $pengirim,
               'layanan' => $this->choiceListModel->getChoicesByType('layanan')
          ];
          echo view('transaksi/order/index', $data);
     }
     public function addOrder()
     {
          $data = [
               'title' => 'Tambah Order',
               'pengirim' => $this->pelangganModel->getPelanggan(),
          ];
          echo view('transaksi/order/add', $data);
     }
     public function editOrder($id)
     {
          $data = [
               'title' => 'Edit Order',
               'pengirim' => $this->pelangganModel->getPelanggan(),
               'vendor' => $this->vendorModel->getVendor(),
               'order' => $this->orderModel->getAllOrder($id)
          ];
          $data['layanan'] = $this->layananModel->getLayananNewOrder($data['order']['id_pelanggan'], $data['order']['id_penerima']);
          $data['penerima'] = $this->penerimaModel->getPenerimaByPengirim($data['order']['id_pelanggan']);
          echo view('transaksi/order/edit', $data);
     }
     public function detailOrder($id)
     {
          $data = [
               'title' => 'Detail Order',
               'order' => $this->orderModel->getAllOrder($id),
               'barang' => $this->barangModel->getBarang()
          ];
          echo view('transaksi/order/detail', $data);
     }
     public function viewDetailOrder($id)
     {
          $data = [
               'title' => 'View Detail Order',
               'order' => $this->orderModel->getAllOrder($id),
               'barang' => $this->barangModel->getBarang()
          ];
          echo view('transaksi/order/view_detail', $data);
     }
     public function viewSuratJalan()
     {
          $pengirim = $this->pelangganModel->getPelanggan();
          if (session()->get('role') == 'PIC RELASI') {
               $pengirim = $this->relationUser->get_user_relation(session()->get('id'));
          }
          $data = [
               'title' => 'Daftar Surat Jalan',
               'pengirim' => $pengirim,
               'layanan' => $this->choiceListModel->getChoicesByType('layanan')
          ];
          echo view('transaksi/surat_jalan/index', $data);
     }
     public function addSuratJalan()
     {
          $data = [
               'title' => 'Tambah Surat Jalan',
               'orders' => $this->orderModel->getOrderNewSj(),
               'drivers' => $this->adminModel->getDriver(),
               'no_sj' => $this->sjModel->generateNewNoSuratJalan(),
               'nopol' => $this->truckModel->findAll()
          ];

          echo view('transaksi/surat_jalan/add', $data);
     }
     public function editSuratJalan($id)
     {
          $sj = $this->sjModel->getAllSuratJalan($id);
          $data = [
               'title' => 'Edit Surat Jalan',
               'orders' => $this->orderModel->getOrderNewSjEdit($sj['id_order']),
               'drivers' => $this->adminModel->getDriver(),
               'no_sj' => $this->sjModel->generateNewNoSuratJalan(),
               'nopol' => $this->truckModel->findAll(),
               'sj' => $sj
          ];

          echo view('transaksi/surat_jalan/edit', $data);
     }
     public function viewPengambilan()
     {
          $data = [
               'title' => 'Daftar Pengambilan'
          ];
          echo view('transaksi/pengambilan/index', $data);
     }
     public function addPengambilan()
     {
          $data = [
               'title' => 'Tambah Pengambilan',
               'orders' => $this->orderModel->getOrderNewPickup(),
               'drivers' => $this->adminModel->getDriver(),
               'no_sj' => $this->sjModel->generateNewNoSuratJalan(),
          ];

          echo view('transaksi/pengambilan/add', $data);
     }
     public function viewManifest()
     {
          $data = [
               'title' => 'Daftar Manifest'
          ];
          echo view('transaksi/manifest/index', $data);
     }
     public function addManifest()
     {
          $data = [
               'title' => 'Tambah Manifest',
               'vendors' => $this->vendorModel->getVendor(),
               'drivers' => $this->adminModel->getDriver(),
               'no_sj' => $this->sjModel->generateNewNoSuratJalan(),
          ];

          echo view('transaksi/manifest/add', $data);
     }
     public function detailManifest($id)
     {
          $data = [
               'title' => 'Detail Manifest',
               'head' => $this->manifestModel->getManifest($id),
               'vendors' => $this->vendorModel->getVendor(),
               'drivers' => $this->adminModel->getDriver(),
               'no_sj' => $this->sjModel->generateNewNoSuratJalan(),
          ];

          echo view('transaksi/manifest/detail', $data);
     }
     public function editManifest($id)
     {
          $data = [
               'title' => 'Edit Manifest',
               'manifest' => $this->manifestModel->getManifest($id),
               'vendors' => $this->vendorModel->getVendor(),
          ];

          echo view('transaksi/manifest/edit', $data);
     }

     public function viewPengiriman()
     {
          $pengirim = $this->pelangganModel->getPelanggan();
          if (session()->get('role') == 'PIC RELASI') {
               $pengirim = $this->relationUser->get_user_relation(session()->get('id'));
          }
          $data = [
               'title' => 'Daftar Pengiriman',
               'pengirim' => $pengirim,
               'layanan' => $this->choiceListModel->getChoicesByType('layanan')
          ];
          echo view('transaksi/pengiriman/index', $data);
     }
     public function viewAdd_biaya()
     {
          $data = [
               'title' => 'Tambah Biaya Pengiriman',
               'pengirim' => $this->pelangganModel->getPelanggan_new(),
               'vendors' => $this->vendorModel->getVendor(),
               'drivers' => $this->adminModel->getDriver(),
               'no_sj' => $this->sjModel->generateNewNoSuratJalan(),
               'layanan' => $this->choiceListModel->getChoicesByType('layanan')
          ];

          echo view('transaksi/layanan/add', $data);
     }
     public function viewUangJalan()
     {
          $drivers = $this->adminModel->getDriver();

          // if(session()->get('role') == 'DRIVER'){
          //      $drivers =  $this->adminModel->getDriver(session()->get('id'));
          // }

          $data = [
               'title' => 'Uang Jalan',
               'drivers' => $drivers,
          ];

          echo view('transaksi/uang_jalan/index', $data);
     }
     public function viewAddUangJalan()
     {
          $data = [
               'title' => 'Tambah Uang Jalan',
               'drivers' => $this->adminModel->getDriver(),
          ];

          echo view('transaksi/uang_jalan/add', $data);
     }
     public function viewEditUangJalan($id)
     {
          $data = [
               'title' => 'Tambah Uang Jalan',
               'drivers' => $this->adminModel->getDriver(),
               'uang_jalan' => $this->ujModel->getUangJalan($id)
          ];

          echo view('transaksi/uang_jalan/edit', $data);
     }
     public function viewEdit_biaya($id = null)
     {
          $layananBiaya = $this->layananModel->getLayanan($id);
          $pengirim = $layananBiaya['id_pelanggan'];
          $penerima = $layananBiaya['id_penerima'];
          $infoPengirim = $this->penerimaModel->getPenerimaByPengirim($pengirim);

          $infoPenerima = $this->penerimaModel->getPengirimPenerima(pengirim: $pengirim, penerima: $penerima);

          $data = [
               'title' => 'Edit Biaya Pengiriman',
               'pengirim' => $this->pelangganModel->getPelanggan_new(),
               'layanan' => $this->choiceListModel->getChoicesByType('layanan'),
               'biaya' => $this->layananModel->getLayanan($id),
               'infoPengirim' => $infoPengirim,
               'infoPenerima' => $infoPenerima
          ];

          echo view('transaksi/layanan/edit', $data);
     }
     public function viewAddOffers()
     {
          $data = [
               'pelanggan' => $this->pelangganModel->getPelanggan_new(),
               'clause' => $this->clauseModel->findAll(),
               'title' => 'Tambah Penawaran'
          ];
          echo view('transaksi/penawaran/add', $data);
     }
     public function viewEditOffers($id)
     {
          $data = [
               'offer' => $this->offerModel->find($id),
               'pelanggan' => $this->pelangganModel->getPelanggan_new(),
               'clause' => $this->clauseModel->findAll(),
               'title' => 'Edit Penawaran'
          ];
          echo view('transaksi/penawaran/edit', $data);
     }
     public function viewDetailOffers($id)
     {

          $data = [
               'offer_id' => $id,
               'pelanggan' => $this->pelangganModel->getPelanggan_new(),
               'head' => $this->offerModel->find($id),
               'clause' => $this->clauseModel->findAll(),
               'details' => $this->offerDetailsModel->getOfferDetails($id),
               'title' => 'Detail Penawaran'
          ];
          echo view('transaksi/penawaran/detail', $data);
     }
     public function viewInvoice()
     {
          $data = [
               'title' => 'Daftar Invoice'
          ];
          echo view('transaksi/invoice/index', $data);
     }
     public function viewAddInvoice()
     {
          $data = [
               'pelanggan' => $this->pelangganModel->getPelanggan_new(),
               'rekening' => $this->rekeningModel->getRekening(),
               'clause' => $this->clauseModel->findAll(),
               'title' => 'Tambah Invoice'
          ];
          echo view('transaksi/invoice/add', $data);
     }
     public function viewEditInvoice($id)
     {
          $invoice = $this->invoiceModel->getInvoices($id);
          $id_pelanggan = $invoice['id_pelanggan'];
          // dd($invoice['id_pelanggan']);
          // dd($this->invoiceModel->getInvoices($id));
          $data = [
               'invoice'      => $this->invoiceModel->getInvoices($id),
               'pelanggan'    => $this->pelangganModel->getPelanggan_new(),
               'rekening'     => $this->rekeningModel->getRekening(),
               'getTax'     => $this->relationTax->getTax($id_pelanggan),
               'getTaxPph'     => $this->relationPphModel->getTaxPph($id_pelanggan),
               'title'        => 'Edit Invoice'
          ];
          echo view('transaksi/invoice/edit', $data);
     }
     public function viewDetailInvoice($id)
     {
          $data = [
               'invoice'      => $this->invoiceModel->getInvoices($id),
               'pelanggan'    => $this->pelangganModel->getPelanggan_new(),
               'rekening'     => $this->rekeningModel->getRekening(),
               'title'        => 'Detail Invoice'
          ];
          echo view('transaksi/invoice/detail', $data);
     }
     public function viewLaporanPengiriman()
     {
          $pengirim = $this->pelangganModel->getPelanggan();
          if (session()->get('role') == 'PIC RELASI') {
               $pengirim = $this->relationUser->get_user_relation(session()->get('id'));
          }

          $data = [
               'title' => 'Laporan Pengiriman',
               'pengirim' => $pengirim,
               'layanan' => $this->choiceListModel->getChoicesByType('layanan')
          ];

          $idList = array_column($pengirim, 'id_pelanggan');

          /**
           * 47 ADALAH ID LIUGONG
           * JIKA ADA LIUGONG MAKA AMBIL LAPORAN PENGIRIMAN UNTUK LIUGONG
           */

          if (in_array(47, $idList)) {
               echo view('laporan/pengiriman_req_liugong', $data);
          } else {
               echo view('laporan/pengiriman', $data);
          }
     }
     public function viewLaporanInvoice()
     {
          $pengirim = $this->pelangganModel->getPelanggan();
          if (session()->get('role') == 'PIC RELASI') {
               $pengirim = $this->relationUser->get_user_relation(session()->get('id'));
          }
          $data = [
               'title' => 'Laporan Invoice',
               'pengirim' => $pengirim,
               'layanan' => $this->choiceListModel->getChoicesByType('layanan')
          ];
          echo view('laporan/invoice', $data);
     }
     public function exportInvoiceCoretax()
     {
          $exportInvoice = $this->receiptExportModel->getExportReceipt();

          $data = [
               'title' => 'Export Coretax Pajak Invoice',
               'exportInvoice' => $exportInvoice
          ];
          echo view('transaksi/invoice/export_coretax', $data);
     }
}
