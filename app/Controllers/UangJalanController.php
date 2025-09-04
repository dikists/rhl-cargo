<?php

namespace App\Controllers;

use App\Models\UangJalanModel;
use CodeIgniter\Controller;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Format;

class UangJalanController extends Controller
{
    protected $ujModel;

    public function __construct()
    {
        $this->ujModel = new UangJalanModel();
    }

    public function index()
    {
        $data['uang_jalan'] = $this->ujModel->findAll();
        return view('uang_jalan/index', $data);
    }

    public function create()
    {
        return view('uang_jalan/create');
    }

    public function getUangJalan()
    {
        $result = $this->ujModel->getUangJalanDatatable($this->request->getPost());

        // Contoh debug:
        // echo "<pre>"; print_r($result); echo "</pre>"; die;

        $totalRecords = $result['recordsFiltered'];
        $data = [];
        $no = 1;
        foreach ($result['data'] as $row) {
            $print = 'Cetak';
            if($row->is_printed == 1){
                $print = 'Reprint';
            }

            $aksi = ' <a target="_blank" href="/admin/uang_jalan/pdf/' . $row->id . '"><i class="fa fa-file-pdf mr-2 text-danger"> '.$print.'</i></a>';
            if ($row->status == 'pending') {
                $status = '<span class="badge bg-warning text-dark">Pending</span>';

                if(session()->get('role') != 'DRIVER'){
                    $aksi = '<div class="btn-group dropleft">
                                                    <a href="#" data-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa fa-bars" aria-hidden="true"></i>
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" target="_blank" href="/admin/uang_jalan/pdf/' . $row->id . '"><i class="fa fa-file-pdf mr-2 text-danger"> '.$print.'</i></a>
                                                        <a class="dropdown-item" href="/admin/uang_jalan/edit/' . $row->id . '"><i class="fa fa-edit mr-2"> Edit</i></a>
                                                        <a class="dropdown-item" href="/admin/uang_jalan/delete/' . $row->id . '" onclick="return confirm(\'Apakah anda yakin?\')"><i class="fa fa-trash mr-2 text-danger"> Delete</i></a>
                                                    </div>
                                            </div>
                                        ';
                }
                if(session()->get('role') == 'Admin'){
                    $aksi = '<div class="btn-group dropleft">
                                                    <a href="#" data-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa fa-bars" aria-hidden="true"></i>
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" target="_blank" href="/admin/uang_jalan/pdf/' . $row->id . '"><i class="fa fa-file-pdf mr-2 text-danger"> '.$print.'</i></a>
                                                        <a class="dropdown-item" href="/admin/uang_jalan/approve/' . $row->id . '"><i class="fa fa-check mr-2 text-success"> Approve</i></a>
                                                        <a class="dropdown-item" href="/admin/uang_jalan/reject/' . $row->id . '"><i class="fa fa-times mr-2 text-danger"> Reject</i></a>
                                                        <a class="dropdown-item" href="/admin/uang_jalan/edit/' . $row->id . '"><i class="fa fa-edit mr-2"> Edit</i></a>
                                                        <a class="dropdown-item" href="/admin/uang_jalan/delete/' . $row->id . '" onclick="return confirm(\'Apakah anda yakin?\')"><i class="fa fa-trash mr-2 text-danger"> Delete</i></a>
                                                    </div>
                                            </div>
                                        ';
                }

            } else if ($row->status == 'approved') {
                $status = '<span class="badge bg-success text-white">Approved</span>';
            } else if ($row->status == 'rejected') {
                $status = '<span class="badge bg-danger text-white">Rejected</span>';
            }
            $data[] = [
                $no++,
                $row->reference,
                date('d-m-Y', strtotime($row->tanggal)),
                ucwords($row->driver_name),
                $row->tujuan,
                formatRupiah($row->jumlah),
                $row->keterangan,
                $status,
                $aksi
            ];
        }

        return $this->response->setJSON([
            'draw' => intval($this->request->getPost('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ]);
    }

    public function approveUangJalan($id)
    {
        $uj = $this->ujModel->getUangJalan($id);

        if (!$uj) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Data tidak ditemukan");
        }

        // Update status jadi approved
        $this->ujModel->update($id, [
            'status' => 'approved',
            'approved_at' => date('Y-m-d H:i:s'),
        ]);

        // Dapatkan ID akun dari master akun
        $akunUangJalan = 10; // ID akun "Beban OPS Kurir, Driver Kantor"
        $akunKas       = 1; // ID akun "Kas/Bank"

        // Catat ke jurnal
        $jurnalModel = new \App\Models\JournalModel();

        // Debit: Uang Jalan
        $jurnalModel->insert([
            'journal_date'       => $uj['tanggal'],
            'reference'          => $uj['reference'],
            'journal_account_id' => $akunUangJalan,
            'debit'              => hilangkanKoma($uj['jumlah']),
            'credit'             => 0,
            'description'        => 'Uang jalan ' . $uj['driver_name'] . ' ke ' . $uj['tujuan'] . ' (' . $uj['keterangan'] . ')',
        ]);

        // Kredit: Kas/Bank
        $jurnalModel->insert([
            'journal_date'       => $uj['tanggal'],
            'reference'          => $uj['reference'],
            'journal_account_id' => $akunKas,
            'debit'              => 0,
            'credit'             => hilangkanKoma($uj['jumlah']),
            'description'        => 'Pembayaran uang jalan ' . $uj['driver_name'] . ' ke ' . $uj['tujuan'] . ' (' . $uj['keterangan'] . ')',
        ]);
        return redirect()->back()->with('success', 'Permintaan disetujui dan jurnal dibuat');
    }

    public function rejectUangJalan($id)
    {
        $uj = $this->ujModel->getUangJalan($id);

        if (!$uj) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Data tidak ditemukan");
        }

        $this->ujModel->update($id, [
            'status' => 'rejected',
        ]);

        return redirect()->back()->with('success', 'Permintaan ditolak');
    }

    public function save()
    {
        $data = $this->request->getPost();
        $user_id = $data['user_id'];
        $username = $data['username'];
        
        if(session()->get('role') == 'DRIVER') {
            $username = session()->get('username');
            $user_id = session()->get('id');
        }

        // Simpan ke tabel uang_jalan
        $ujData = [
            'tanggal'       => $data['tanggal'],
            'user_id'         => $user_id,
            'tujuan'        => $data['tujuan'],
            'jumlah'        => hilangkanKoma($data['jumlah']),
            // 'metode_pembayaran' => $data['metode_pembayaran'],
            'keterangan'    => $data['keterangan'],
            'reference'     => 'UJ-' . date('ymdHi'),
        ];       
        $this->ujModel->insert($ujData);
 
        $phone = env('GROUP_TEST');
        $message = "*[PERMINTAAN UANG JALAN]*\n\n"
                . "📆 *Tanggal*  : " . date('d-m-Y', strtotime($data['tanggal'])) . "\n"
                . "👤 *Kurir*    : " . $username . "\n"
                . "🎯 *Tujuan*   : " . $data['tujuan'] . "\n"
                . "💰 *Jumlah*   : " . formatRupiah(hilangkanKoma($data['jumlah'])) . "\n"
                . "📝 *Keterangan*: " . $data['keterangan'] . "\n"
                . "🔖 *Ref*      : " . 'UJ-' . date('ymdHi') . "\n\n"
                . "Mohon untuk segera diproses oleh pihak finance 🙏";

        // Kirim Pesan Whatsap setelah data disimpan
        sendWaGroup($phone, $message);


        return redirect()->to('admin/uang_jalan')->with('success', 'Data uang jalan berhasil disimpan');
    }

    public function edit($id)
    {
        $data['uang_jalan'] = $this->ujModel->find($id);
        return view('uang_jalan/edit', $data);
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $this->ujModel->update($id, [
            'tanggal' => $this->request->getPost('tanggal'),
            'user_id' => $this->request->getPost('user_id'),
            'tujuan' => $this->request->getPost('tujuan'),
            'jumlah' => hilangkanKoma($this->request->getPost('jumlah')),
            'keterangan' => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to('/admin/uang_jalan')->with('success', 'Data uang jalan berhasil diubah');
    }

    public function delete($id)
    {
        $this->ujModel->delete($id);
        return redirect()->to('/admin/uang_jalan')->with('success', 'Data uang jalan berhasil dihapus');
    }
}
