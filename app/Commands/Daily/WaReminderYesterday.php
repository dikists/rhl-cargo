<?php

namespace App\Commands\Daily;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\BaseConnection;

class WaReminderYesterday extends BaseCommand
{
    protected $group       = 'Cronjob';
    protected $name        = 'cron:wa-reminder';
    protected $description = 'Kirim WA reminder untuk pengiriman yang dikirim kemarin.';

    protected $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function run(array $params)
    {
        helper('wa');
        $db = db_connect();
        $yesterday = date('Y-m-d', strtotime('-1 day'));

        $query = $db->table('view_pengiriman_summary')
            ->where('tanggal_kirim', $yesterday)
            ->get();

        $results = $query->getResult();

        if (empty($results)) {
            CLI::write("Tidak ada data pengiriman untuk tanggal $yesterday", 'yellow');
            return;
        }

        // Hanya kirim ke satu nomor
        // $phone = '085881763942';
        $phone = env('GROUP_TEST');
        $message = $this->buildMessage($results); // kirim semua data sekaligus
        sendWaGroup($phone, $message);

        CLI::write("WA reminder berhasil dikirim.", 'green');
    }


    protected function buildMessage(array $rows)
    {
        $text = "📦 *Reminder Pengiriman - " . date('d M Y', strtotime('-1 day')) . "*\n\n";

        $no = 1;
        foreach ($rows as $data) {
            $berat = round($data->berat);
            $text .= <<<MSG
            {$no}. *{$data->no_order}* / *{$data->no_surat_jalan}*
            Pengirim : {$data->nama_pelanggan}
            Penerima : {$data->nama_penerima}
            Jumlah : {$data->koli} Koli
            Berat : {$berat} KG
            --------------------

            MSG;
            $no++;
        }

        $text .= "\nMohon pastikan status pengiriman diperbarui. Terima kasih 🙏";

        return $text;
    }

}
