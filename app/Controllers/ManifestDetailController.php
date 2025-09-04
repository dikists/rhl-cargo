<?php

namespace App\Controllers;

use App\Models\PengirimanModel;
use App\Controllers\BaseController;
use App\Models\ManifestDetailModel;
use App\Models\SuratJalanModel;

class ManifestDetailController extends BaseController
{
     protected $manifestDetailModel;
     protected $pengirimanModel;
     protected $suratJalanModel;

     public function __construct()
     {
          $this->manifestDetailModel = new ManifestDetailModel();
          $this->pengirimanModel = new PengirimanModel();
          $this->suratJalanModel = new SuratJalanModel();
     }

     public function getDetailManifest($id)
     {
          $data = $this->manifestDetailModel->getDetailManifest($id);
          $no = 1;
          foreach ($data as $index => $item) {
               $data[$index]['no'] = $no++;
               $data[$index]['no_surat_jalan'] = $item['no_surat_jalan'];
               $data[$index]['pengirim'] = $item['nama_pelanggan'];
               $data[$index]['penerima'] = $item['nama_penerima'];
          }
          return $this->response->setJSON($data);
     }

     public function add()
     {
          $id = $this->request->getPost('manifest_id');
          $date = $this->request->getPost('date');
          $surat_jalan = $this->request->getPost('surat_jalan');
          $vendor = $this->request->getPost('sub_vendor');

          $dataSJ = $this->suratJalanModel->find($surat_jalan);

          $data = [
               'manifest_id' => $id,
               'surat_jalan_id' => $surat_jalan,
               'sub_vendor_id' => $vendor
          ];

          $dataPengiriman = [
               'surat_jalan_id' => $surat_jalan,
               'kode_kurir' => $dataSJ['kode_kurir'],
               'tanggal_kirim' => $date,
               'waktu_kirim' => date('H:i:s'),
          ];

          $this->pengirimanModel->save($dataPengiriman);

          $save = $this->manifestDetailModel->save($data);

          if ($save) {
               echo "ok";
          } else {
               echo "no ok";
          }
     }
     public function delete()
     {
          $id = $this->request->getPost('id');

          $detailManifest = $this->manifestDetailModel->find($id);
          $sj_id = $detailManifest['surat_jalan_id'];

          // update pengiriman
          $this->pengirimanModel->set('deleted_at', date('Y-m-d H:i:s'))
               ->where('surat_jalan_id', $sj_id)
               ->update();

          $this->manifestDetailModel->update($id, ['status' => 0]);
          $hapus = $this->manifestDetailModel->delete($id);

          if ($hapus) {
               echo 'ok';
          } else {
               echo 'not ok';
          }
     }
}
