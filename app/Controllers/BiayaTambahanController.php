<?php

namespace App\Controllers;

use App\Models\BiayaTambahanModel;

class BiayaTambahanController extends BaseController
{
    protected $biayaTambahan;
    public function __construct()
    {
        $this->biayaTambahan = new BiayaTambahanModel();
    }
    public function index()
    {
        $model = new BiayaTambahanModel();
        $data['title'] = 'Biaya Tambahan';
        $data['biaya'] = $model->findAll();
        return view('transaksi/biaya_tambahan/index', $data);
    }
    public function save(){
        $data = [
            'jenis_biaya'    => $this->request->getPost('jenis_biaya'),
            'nominal'        => hilangkanKoma($this->request->getPost('nominal')),
            'tanggal_input'  => get_time(),
            'keterangan'     => $this->request->getPost('keterangan'),
        ];

        $this->biayaTambahan->insert($data);
        return redirect()->to(base_url('admin/biaya_tambahan'))->with('success', 'Data Berhasil Disimpan');
    }
}
