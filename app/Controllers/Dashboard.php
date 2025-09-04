<?php

namespace App\Controllers;

use App\Models\PenerimaModel;
use App\Models\PelangganModel;
use App\Models\ChoiceListModel;
use App\Models\PengirimanModel;

class Dashboard extends BaseController
{
  protected $PengirimanModel;
  protected $PenerimaModel;
  protected $pelangganModel;
  protected $choiceListModel;
  public function __construct()
  {
    $this->PengirimanModel = new PengirimanModel();
    $this->PenerimaModel = new PenerimaModel();
    $this->pelangganModel = new PelangganModel();
    $this->choiceListModel = new ChoiceListModel();
  }
  public function index()
  {
    if (!$this->currentUser) {
      return redirect()->to('/login');
    }
    $id = session()->get('id');
    $admin = session()->get('admin');
    $data['title'] = 'Home';
    $data['stats'] =  $this->PengirimanModel->getShipmentStats($id, $admin);
    $data['user'] = $this->currentUser;
    return view('dashboard/index', $data);
  }
  public function tracking()
  {
    if (!$this->currentUser) {
      return redirect()->to('/login');
    }

    $id = session()->get('id');
    $data['pengirim'] = $this->pelangganModel->getPelanggan($id);
    $data['layanan'] = $this->choiceListModel->getChoicesByType('layanan');
    $data['pengiriman'] = $this->PengirimanModel->getPengirimanByCustomerId($id);
    $data['penerima'] = $this->PenerimaModel->getPenerimaByCustomerId($id);
    $data['title'] = 'Tracking';
    $data['user'] = $this->currentUser;
    return view('dashboard/tracking', $data);
  }
  public function monthlyShipments()
  {
    $id = session()->get('id');
    $admin = session()->get('admin');
    $monthlyShipments = $this->PengirimanModel->getMonthlyShipments($id, $admin);
    return $this->response->setJSON($monthlyShipments);
  }
  public function monthlyShipmentsNew()
  {
    $monthlyShipments = $this->PengirimanModel->getMonthlyShipmentsNew(date('Y'));
    return $this->response->setJSON($monthlyShipments);
  }
  public function shipmentStatusSummaryNew()
  {
    $id = session()->get('id');
    $summary  = $this->PengirimanModel->getShipmentStatusSummaryNew($id);
    // echo "<pre>";
    // print_r($summary);
    // echo "</pre>";
    // die;
    return $this->response->setJSON($summary);
  }
  public function shipmentStatusSummary()
  {
    $id = session()->get('id');
    $admin = session()->get('admin');
    $summary  = $this->PengirimanModel->getShipmentStatusSummary($id, '', '', $admin);
    return $this->response->setJSON($summary);
  }
  public function detail_tracking($no_surat_jalan)
  {
    $data['tracking'] = $this->PengirimanModel->getTrackingData($no_surat_jalan);
    $data['status'] = $this->PengirimanModel->getShipmentStatusLog($no_surat_jalan);
    $id_order = $data['tracking']->id_order;
    $id_layanan = $data['tracking']->id_layanan;

    $data['detail'] = $this->PengirimanModel->getDetailOrder(id_order: $id_order, id_layanan: $id_layanan);

    // echo "<pre>";
    // print_r($data['tracking']);
    // echo "</pre>";
    // die;

    $data['title'] = 'Detail Tracking ' . $no_surat_jalan;
    $data['user'] = $this->currentUser;
    return view('dashboard/detail_tracking', $data);
  }
}
