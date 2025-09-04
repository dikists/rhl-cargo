<?php

namespace App\Services;

use App\Models\ReceivableModel;
use App\Models\JournalModel;

class JurnalService
{
    protected $receivableModel;
    protected $journalModel;

    public function __construct()
    {
        $this->receivableModel = new ReceivableModel();
        $this->journalModel = new JournalModel();
    }

    public function generateJurnalPiutang()
    {
        $receivables = $this->receivableModel->getReceivableJournal();

        foreach ($receivables as $item) {
            $ref = $item['invoice_number'];

            // Cek apakah jurnal sudah ada
            $exists = $this->journalModel->where('reference', $ref)->first();
            if ($exists) {
                continue;
            }

            $this->journalModel->insertBatch([
                [
                    'journal_date' => $item['invoice_date'],
                    'reference' => $ref,
                    'journal_account_id' => 2, // Piutang Usaha
                    'debit' => $item['total_amount'],
                    'credit' => 0,
                    'description' => 'Invoice ke ' . $item['nama_pelanggan']
                ],
                [
                    'journal_date' => $item['invoice_date'],
                    'reference' => $ref,
                    'journal_account_id' => 3, // Pendapatan Penjualan
                    'debit' => 0,
                    'credit' => $item['total_amount'],
                    'description' => 'Invoice ke ' . $item['nama_pelanggan']
                ]
            ]);

            $this->receivableModel->update($item['id'], ['is_journaled' => 1]);
        }
    }
}
