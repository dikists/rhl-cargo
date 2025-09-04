<?php

namespace App\Controllers;

use App\Models\LayananModel;
use App\Models\PelangganModel;
use App\Models\ChoiceListModel;
use App\Models\RelationUserModel;
use CodeIgniter\Validation\Validation;
use CodeIgniter\RESTful\ResourceController;


class LayananController extends ResourceController
{
     protected $layananModel;
     protected $pelangganModel;
     protected $relationUser;
     protected $choiceListModel;

     public function __construct()
     {
          $this->layananModel = new LayananModel();
          $this->pelangganModel = new PelangganModel();
          $this->relationUser = new RelationUserModel();
          $this->choiceListModel = new ChoiceListModel();
     }

     public function index()
     {
          $pengirim = $this->pelangganModel->getPelanggan();
          if (session()->get('role') == 'PIC RELASI') {
               $pengirim = $this->relationUser->get_user_relation(session()->get('id'));
          }
          $data = [
               'title' => 'Daftar Biaya Layanan',
               'pengirim' => $pengirim,
               'layanan' => $this->choiceListModel->getChoicesByType('layanan')
          ];

          echo view('transaksi/layanan/index', $data);
     }
     public function getLayanan()
     {
          $id = false;
          $pengirim = $this->request->getGet('pengirim');
          $penerima = $this->request->getGet('penerima');
          $layanan = $this->request->getGet('layanan');

          $data = $this->layananModel->getLayanan($id, $pengirim, $penerima, $layanan);
          $no = 1;
          foreach ($data as $index => $item) {
               $delete = '';

               if ($item['in_order'] == 0) {
                    $delete = '
                    <form class="d-inline" action="/admin/biaya/' . $item['id_layanan'] . '" method="post" onsubmit="return confirm(\'Apakah Anda yakin ingin menghapus data ini?\');">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-danger btn-sm m-1"><i class="fa fa-times"></i> Hapus</button>
                    </form>
                    ';
                }                

               $data[$index]['no'] = $no++;
               $data[$index]['pengirim'] = ($item['nama_pelanggan'] == null) ? '-' : $item['nama_pelanggan'];
               $data[$index]['penerima'] = ($item['nama_penerima'] == null) ? '-' : $item['nama_penerima'];
               $data[$index]['minimum'] = ($item['minimum'] == null) ? '-' : $item['minimum'];
               $data[$index]['harga'] = 'Rp ' . number_format($item['biaya_paket'], 0, ',', '.');
               $data[$index]['aksi'] = '<div class="text-center">
                                            <a href="' . base_url('admin/biaya/' . $item['id_layanan']) . '" class="btn btn-primary btn-sm m-1"><i class="fa fa-edit"></i> Edit</a>
                                            '.$delete.'
                                       </div>
                                  ';
          }
          return $this->response->setJSON($data);
     }
     public function create()
     {
          $data = [
               'id_pelanggan' => $this->request->getVar('pengirim'),
               'id_penerima' => $this->request->getVar('penerima'),
               'kabupaten_id' => $this->request->getVar('kab'),
               'kecamatan_id' => $this->request->getVar('kec'),
               'layanan' => $this->request->getVar('layanan'),
               'minimum' => $this->request->getVar('minimum'),
               'leadtime' => $this->request->getVar('leadtime'),
               'biaya_paket' => intval(str_replace(",", "", $this->request->getVar(index: 'harga'))),
               'divider' => intval(str_replace(",", "", $this->request->getVar('divider'))),
               'bill_type' => $this->request->getVar('jenis_tagihan'),
          ];

          if ($this->layananModel->save($data)) {
               return redirect()->to('admin/biaya')->with('success', 'Data berhasil disimpan.');
          } else {
               return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data.');
          }
     }
     public function update($id_layanan = null)
     {
          if (!$this->validate([
               'pengirim' => 'required|integer',
               'penerima' => 'required|integer',
               'kab' => 'required|integer',
               'kec' => 'required|integer',
               'layanan' => 'required|string|max_length[255]',
               'minimum' => 'required|integer',
               'leadtime' => 'required|integer',
               'harga' => 'required|string',
               'divider' => 'required|string',
               'jenis_tagihan' => 'required|string|max_length[50]',
          ])) {
               return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
          }


          $data = [
               'id_pelanggan' => $this->request->getVar('pengirim'),
               'id_penerima' => $this->request->getVar('penerima'),
               'kabupaten_id' => $this->request->getVar('kab'),
               'kecamatan_id' => $this->request->getVar('kec'),
               'layanan' => $this->request->getVar('layanan'),
               'minimum' => $this->request->getVar('minimum'),
               'leadtime' => $this->request->getVar('leadtime'),
               'biaya_paket' => intval(str_replace(",", "", $this->request->getVar(index: 'harga'))),
               'divider' => intval(str_replace(",", "", $this->request->getVar('divider'))),
               'bill_type' => $this->request->getVar('jenis_tagihan'),
          ];

          $update = $this->layananModel->update($id_layanan, $data);
          if ($update) {
               return redirect()->to('admin/biaya')->with('success', 'Data berhasil diubah.');
          } else {
               return redirect()->back()->withInput()->with('error', 'Gagal mengubah data.');
          }
     }

     public function delete($id_layanan = null){
          $delete = $this->layananModel->delete($id_layanan);
          if ($delete) {
               return redirect()->to('admin/biaya')->with('success', 'Data berhasil dihapus.');
          } else {
               return redirect()->back()->withInput()->with('error', 'Gagal menghapus data.');
          }
     }
}
