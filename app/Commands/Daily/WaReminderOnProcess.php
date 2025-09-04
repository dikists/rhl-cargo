<?php

namespace App\Commands\Daily;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\BaseConnection;

class WaReminderOnProcess extends BaseCommand
{
    protected $group       = 'Cronjob';
    protected $name        = 'cron:wa-reminder-on-process';
    protected $description = 'Kirim WA reminder untuk pengiriman yang dikirim bulan kemarin dan statusnya masih on process.';

    protected $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function run(array $params)
    {
        helper('wa');
        $db = db_connect();

        // Ambil bulan kemarin dan tahun sekarang
        $lastMonth = date('m', strtotime('first day of last month'));
        $thisYear  = date('Y');

        $query = $db->table('view_pengiriman_summary')
            ->where('MONTH(tanggal_order)', $lastMonth)
            ->where('YEAR(tanggal_order)', $thisYear)
            ->where('tanggal_terima IS NULL')
            ->get();

        $results = $query->getResult();

        if (empty($results)) {
            CLI::write("Tidak ada data pengiriman bulan $lastMonth tahun $thisYear yang belum diterima.", 'yellow');
            return;
        }

        // Hanya kirim ke satu nomor
        $phone = env('GROUP_TEST');
        $message = $this->buildMessage($results, $lastMonth); // kirim semua data sekaligus
        $send_message = sendWaGroup($phone, $message);

        if(!$send_message) {
            CLI::write("WA reminder gagal dikirim.", 'red');
            return;
        }

        CLI::write('WA reminder berhasil dikirim.', 'green');
    }


    protected function buildMessage(array $rows, $month)
    {
        $text = "📦 *Reminder Pengiriman Bulan ". $month ." Tahun ". date('Y') . "*\n\n";

        $no = 1;
        foreach ($rows as $data) {
            $text .= <<<MSG
            {$no}. *{$data->no_order}* / *{$data->no_surat_jalan}*
            Pengirim : {$data->nama_pelanggan}
            Penerima : {$data->nama_penerima}
            Jumlah : {$data->koli} Koli
            Berat : {$data->berat} KG
            --------------------

            MSG;
            $no++;
        }

        $text .= "\nMohon pastikan status pengiriman diperbarui. Terima kasih 🙏";

        return $text;
    }
}
