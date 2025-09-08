<?php

namespace App\Controllers;

use App\Models\LaporanModel;
use App\Models\PenerimaModel;
use App\Models\PelangganModel;
use App\Models\ChoiceListModel;
use App\Models\PengirimanModel;

class Laporan extends BaseController
{
  protected $LaporanModel;
  protected $PengirimanModel;
  protected $PenerimaModel;
  protected $pelangganModel;
  protected $choiceListModel;
  public function __construct()
  {
    $this->LaporanModel = new LaporanModel();
    $this->PengirimanModel = new PengirimanModel();
    $this->PenerimaModel = new PenerimaModel();
    $this->pelangganModel = new PelangganModel();
    $this->choiceListModel = new ChoiceListModel();
  }
  public function laporan_pengiriman()
  {
    if (!$this->currentUser) {
      return redirect()->to('/login');
    }

    $id = session()->get('id');
    $data['pengirim'] = $this->pelangganModel->getPelanggan($id);

    $data['penerima'] = $this->PenerimaModel->getPenerimaByCustomerId($id);
    $data['pengiriman'] = $this->PengirimanModel->getPengirimanByCustomerId($id);
    $data['layanan'] = $this->choiceListModel->getChoicesByType('layanan');

    $data['title'] = 'Laporan Pengiriman';
    $data['user'] = $this->currentUser;
    return view('dashboard/laporan_pengiriman', $data);
  }
  public function laporan_performance()
  {
    if (!$this->currentUser) {
      return redirect()->to('/login');
    }

    $data['title'] = 'Laporan Pengiriman';
    $data['user'] = $this->currentUser;
    return view('dashboard/laporan_performance', $data);
  }
  public function get_data()
  {
    $start = $this->request->getVar('start');
    $end = $this->request->getVar('end');
    $shipper = session()->get('id');
    $consignee = $this->request->getVar('consignee');
    $performance = $this->request->getVar('performance');

    $data = $this->LaporanModel->getData($start, $end, $shipper, $consignee, $performance);

    // Debug: Output data untuk memastikan data valid
    // echo '<pre>';
    // print_r($data);
    // echo '</pre>';
    // exit();

    return $this->response->setJSON($data);
  }
  public function export_pdf()
  {
    helper('pdf');
    $db = \Config\Database::connect();

    // Mengambil data perusahaan
    $query = $db->table('tb_perusahaan')->get();
    $company = $query->getRowArray();

    $start = $this->request->getGet('start');
    $end = $this->request->getGet('end');
    $shipper = session()->get('id');
    $consignee = $this->request->getGet('consignee');
    $performance = $this->request->getGet('performance');

    $awal = date('d-m-y', strtotime($start));
    $akhir = date('d-m-y', strtotime($end));

    $data = $this->LaporanModel->getData($start, $end, $shipper, $consignee, $performance);

    $data = [
      'title' => 'Laporan Pengiriman ' . $awal . ' - ' . $akhir,
      'data' => $data,
      'company' => $company
    ];

    $html = view('laporan/pdf_pengiriman', $data);
    create_pengiriman_pdf($html, 'Laporan Pengiriman ' . $awal . ' - ' . $akhir . '.pdf');
  }
  public function get_data_performance()
  {
    $id = session()->get('id');
    $bulan = $this->request->getGet('bulan');
    $tahun = $this->request->getGet('tahun');

    $data['chart_data']  = $this->PengirimanModel->getShipmentStatusSummaryNew($id, $bulan, $tahun);
    $data['performance'] = $this->PengirimanModel->get_data_performance($id, $bulan, $tahun);

    return $this->response->setJSON($data);
  }
}
