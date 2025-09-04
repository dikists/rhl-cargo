<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\PengirimanModel;
use App\Models\ViewPengirimanSummaryModel;
use CodeIgniter\API\ResponseTrait;

class Pengiriman extends BaseController
{
    use ResponseTrait;

    protected $PengirimanModel;

    public function __construct()
    {
        $this->PengirimanModel = new ViewPengirimanSummaryModel();
    }

    public function all()
    {
        $data = $this->PengirimanModel->all();

        if (!$data) {
            return $this->failNotFound("Data pengiriman tidak ditemukan");
        }

        return $this->respond($data, 200);
    }
    public function show($id = null)
    {
        $data = $this->PengirimanModel->find($id);

        if (!$data) {
            return $this->failNotFound("Data pengiriman dengan ID $id tidak ditemukan");
        }

        return $this->respond($data, 200);
    }

    public function track($no = null)
    {
        if (!$no) {
            return $this->fail('Parameter tracking kosong');
        }
        $data = $this->PengirimanModel->cari($no);

        if (empty($data)) {
            return $this->failNotFound("Pengiriman dengan kode '$no' tidak ditemukan.");
        }

        return $this->respond([
            'status' => true,
            'message' => 'Data pengiriman ditemukan',
            'data' => $data
        ], 200);
    }
}
