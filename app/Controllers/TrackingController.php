<?php

namespace App\Controllers;

use App\Models\PenerimaModel;
use App\Models\ShipmentModel;
use App\Models\TrackingModel;
use App\Models\PengirimanModel;
use App\Controllers\BaseController;

class TrackingController extends BaseController
{
     protected $PengirimanModel;
     protected $PenerimaModel;

     public function __construct()
     {
          $this->PengirimanModel = new PengirimanModel();
          $this->PenerimaModel = new PenerimaModel();
     }

     public function index()
     {
          $data['title'] = 'Tracking Result';

          $data['no_resi'] = session()->get('no_resi');
          $data['tracking'] = session()->get('tracking');
          $data['status'] = session()->get('status');
          $data['detail'] = session()->get('detail');

          if (!$data['tracking']) {
               $data['title'] = 'Tracking';
               return view('pages/track_form', $data);
          } else {
               return view('pages/track_result', $data);
          }
     }

     public function track()
     {
          $no_resi = $this->request->getVar('no_resi');

          $data['tracking'] = $this->PengirimanModel->getTrackingData($no_resi);
          $data['status'] = $this->PengirimanModel->getShipmentStatusLog($no_resi);

          if (!$data['tracking']) {
               return redirect()->to('tracking-package-view')->with('error', 'No Resi tidak ditemukan');
          }

          $id_order = $data['tracking']->id_order;
          $id_layanan = $data['tracking']->id_layanan;
          $data['detail'] = $this->PengirimanModel->getDetailOrder($id_order, $id_layanan);

          $data['title'] = 'Tracking Result';
          $data['user'] = $this->currentUser;

          // echo "<pre>";
          // print_r($data);
          // echo "</pre>";
          // die;

          session()->set([
               'no_resi' => $no_resi,
               'tracking' => $data['tracking'],
               'status' => $data['status'],
               'detail' => $data['detail']
           ]);

          // Redirect ke halaman tracking
          return redirect()->to('tracking-package-view');
     }
}
