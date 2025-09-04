<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TruckModel;

class TruckController extends BaseController
{
    protected $truckModel;

    public function __construct()
    {
        $this->truckModel = new TruckModel();
    }

    // Tampilkan semua data truck
    public function index()
    {
        $data['title'] = 'Daftar Truck';
        $data['trucks'] = $this->truckModel->findAll();
        return view('master/trucks/index', $data);
    }

    // Form tambah data truck
    public function create()
    {
        $data['title'] = 'Tambah Truck';
        return view('master/trucks/create', $data);
    }

    // Proses insert data truck
    public function store()
    {
        $this->truckModel->save([
            'plate_number' => $this->request->getPost('plate_number'),
            'type'         => $this->request->getPost('type'),
            'brand'        => $this->request->getPost('brand'),
            'year'         => $this->request->getPost('year'),
            'status'       => $this->request->getPost('status'),
        ]);

        return redirect()->to('admin/truck');
    }

    // Tampilkan form edit
    public function edit($id)
    {
        $data['title'] = 'Edit Truck';
        $data['truck'] = $this->truckModel->find($id);
        return view('master/trucks/edit', $data);
    }

    // Proses update
    public function update($id)
    {
        $this->truckModel->update($id, [
            'plate_number' => $this->request->getPost('plate_number'),
            'type'         => $this->request->getPost('type'),
            'brand'        => $this->request->getPost('brand'),
            'year'         => $this->request->getPost('year'),
            'status'       => $this->request->getPost('status'),
        ]);

        return redirect()->to('admin/truck');
    }

    // Hapus truck
    public function delete($id)
    {
        $this->truckModel->delete($id);
        return redirect()->to('admin/truck');
    }
}
