<?php

namespace App\Controllers;

use App\Models\LaporanModel;
use App\Models\PayableModel;
use App\Models\PenerimaModel;
use App\Models\PengirimanModel;
use App\Models\ReceivableModel;
use App\Controllers\BaseController;
use App\Models\JournalModel;

class LaporanController extends BaseController
{
  protected $LaporanModel;
  protected $PengirimanModel;
  protected $PenerimaModel;
  protected $Jurnal;
  public function __construct()
  {
    $this->LaporanModel = new LaporanModel();
    $this->PengirimanModel = new PengirimanModel();
    $this->PenerimaModel = new PenerimaModel();
    $this->Jurnal = new JournalModel();
  }
  public function laporan_pengiriman()
  {
    $data['title'] = 'Laporan Pengiriman';
    return view('laporan/pengiriman', $data);
  }
  public function hutangPiutang()
  {
    $payableModel = new PayableModel();
    $receivableModel = new ReceivableModel();

    $data['payables'] = $payableModel->getWithSupplier();
    $data['receivables'] = $receivableModel->getWithCustomer();
    $data['title'] = 'Laporan Hutang Piutang';

    return view('laporan/hutang_piutang', $data);
  }

  public function jurnal()
  {
    $data['title'] = 'Laporan Jurnal';
    return view('laporan/jurnal', $data); // hanya return view
  }

  public function jurnalDatatable()
  {
    $params = [
      'date_start' => $this->request->getPost('date_start'),
      'date_end'   => $this->request->getPost('date_end'),
      'search'     => $this->request->getPost('search')['value'] ?? '',
      'length'     => $this->request->getPost('length'),
      'start'      => $this->request->getPost('start'),
    ];

    $result = $this->Jurnal->getDatatables($params);
    $totalRecords = $result['recordsFiltered'];

    $data = [];
    $no = 1;
    foreach ($result['data'] as $row) {
      $data[] = [
        $no++,
        date('d-m-Y', strtotime($row->journal_date)),
        '['.$row->account_code.'] '.$row->account_name . ' ('. $row->account_type.')',
        $row->reference,
        $row->description,
        formatRupiah($row->debit),
        formatRupiah($row->credit),
      ];
    }

    return $this->response->setJSON([
      "draw" => intval($this->request->getPost('draw')),
      "recordsTotal" => $totalRecords,
      "recordsFiltered" => $totalRecords,
      "data" => $data,
    ]);
  }

  public function neraca()
  {
    $accounts = $this->Jurnal->getBalanceSheet();

    // Kelompokkan akun berdasarkan type
    $neraca = [
      'assets'     => [],
      'liabilities' => [],
      'equity'     => [],
    ];

    foreach ($accounts as $akun) {
      $saldo = 0;
      if ($akun['type'] === 'asset') {
        $saldo = $akun['total_debit'] - $akun['total_credit'];
        $neraca['assets'][] = ['name' => $akun['name'], 'saldo' => $saldo];
      } elseif ($akun['type'] === 'liability') {
        $saldo = $akun['total_credit'] - $akun['total_debit'];
        $neraca['liabilities'][] = ['name' => $akun['name'], 'saldo' => $saldo];
      } elseif ($akun['type'] === 'equity') {
        $saldo = $akun['total_credit'] - $akun['total_debit'];
        $neraca['equity'][] = ['name' => $akun['name'], 'saldo' => $saldo];
      }
    }

    $data['neraca'] = $neraca;
    $data['title'] = 'Laporan Neraca';

    return view('laporan/neraca', $data);
  }
  public function labaRugi()
  {
    $accounts = $this->Jurnal->getProfitLoss();

    $data = [
      'pendapatan' => [],
      'beban'      => [],
      'total_pendapatan' => 0,
      'total_beban'      => 0,
      'laba_bersih'      => 0,
    ];

    foreach ($accounts as $row) {
      if ($row['type'] == 'revenue') {
        $saldo = $row['total_credit'] - $row['total_debit'];
        $data['pendapatan'][] = ['name' => $row['name'], 'saldo' => $saldo];
        $data['total_pendapatan'] += $saldo;
      } elseif ($row['type'] == 'expense') {
        $saldo = $row['total_debit'] - $row['total_credit'];
        $data['beban'][] = ['name' => $row['name'], 'saldo' => $saldo];
        $data['total_beban'] += $saldo;
      }
    }

    $data['laba_bersih'] = $data['total_pendapatan'] - $data['total_beban'];
    $data['title'] = 'Laporan Laba Rugi';

    return view('laporan/laba_rugi', $data);
  }
}
