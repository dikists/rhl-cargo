<?php
// app/Controllers/CronjobWeb.php
namespace App\Controllers;

use CodeIgniter\Controller;

class CronjobWeb extends Controller
{
    public function waReminder($token = null)
    {
        helper('wa');
        // 🔐 Keamanan pakai token
        if ($token !== 'RAHASIA123') {
            return $this->response->setStatusCode(403)->setBody('Unauthorized');
        }

        $db = db_connect();

        // 1. Ambil semua vendor
        $vendorBuilder = $db->table('tb_vendor');
        $vendorBuilder->select('vendor_id, vendor_name, vendor_phone, vendor_group_phone');
        $vendorBuilder->where('vendor_id !=', 3);
        $vendors = $vendorBuilder->get()->getResultArray();

        foreach ($vendors as $vendor) {

            // 2. Ambil data pengiriman utk vendor ini
            $shipBuilder = $db->table('view_pengiriman_summary');
            $shipBuilder->select(
                'id_pengiriman, 
                no_surat_jalan, 
                nama_pelanggan,
                nama_penerima,
                koli,
                dest, 
                status, 
                tanggal_kirim,
                DATEDIFF(CURDATE(), DATE_ADD(tanggal_kirim, INTERVAL leadtime DAY)) AS delay_days
                '
            );
            $shipBuilder->groupStart()
                ->where('vendor_id', $vendor['vendor_id'])
                ->orWhere('sub_vendor_id', $vendor['vendor_id'])
                ->groupEnd()
                ->where('status', 'On Process')
                ->where('tanggal_order > "2025-07-31"')
                ->where('DATE_ADD(tanggal_kirim, INTERVAL leadtime DAY) < CURDATE()');

            $shipments = $shipBuilder->get()->getResultArray();

            if (!empty($shipments)) {
                // 1. Siapkan array untuk menampung shipment yang delay >= 5
                $delayedShipments = [];

                foreach ($shipments as $s) {
                    if ($s['delay_days'] >= 1) {
                        $delayedShipments[] = $s;
                    }
                }

                // 2. Jika ada shipment yang delay >= 5, baru buat pesan & kirim WA
                if (!empty($delayedShipments)) {
                    // 3. Susun pesan
                    $message = "Halo *" . $vendor['vendor_name'] . ",*\n";
                    $message .= "Mohon bantuan untuk pengecekan status pengiriman berikut:\n\n";

                    foreach ($delayedShipments as $s) {
                        $message .= "-----------------------------------\n";
                        $message .= "No. SJ       : " . $s['no_surat_jalan'] . "\n";
                        $message .= "Pengirim     : " . $s['nama_pelanggan'] . "\n";
                        $message .= "Penerima     : " . $s['nama_penerima'] . "\n";
                        $message .= "Tujuan       : " . $s['dest'] . "\n";
                        $message .= "Jumlah Koli  : " . $s['koli'] . "\n";
                        // $message .= "Delay        : " . $s['delay_days'] . " hari\n";
                        $message .= "Tanggal Kirim: " . date('d/m/Y', strtotime($s['tanggal_kirim'])) . "\n";
                        $message .= "-----------------------------------\n\n";
                    }

                    $message .= "Atas perhatian dan kerjasamanya, kami ucapkan terima kasih. \n\n";
                    $message .= "Ini adalah pesan otomatis, mohon untuk tidak membalas pesan ini.";

                    // 4. Kirim WA
                    if ($vendor['vendor_group_phone']) {
                        sendWaGroup($vendor['vendor_group_phone'], $message);
                        // sendWaGroup('120363420263199198', $message);
                    }

                    echo "<pre>Kirim ke {$vendor['vendor_group_phone']}:\n$message</pre>";
                }
            }
        }


        // // 🛠️ Kirim WA
        // sendWa('085881763942', 'Tes Pesan WA dari Cronjob Pada ' . date('Y-m-d H:i:s'));

        // // 🛠️ Logika cronjob
        // echo "WA reminder dikirim pada " . date('Y-m-d H:i:s');
        // // Atau tulis log ke file
        // file_put_contents(WRITEPATH . 'logs/wa_reminder.txt', "Jalan: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
    }
    public function lateReasonDelivery($token = null)
    {
        if ($token !== 'RAHASIA123') {
            return $this->response->setStatusCode(403)->setBody('Unauthorized');
        }

        $db = db_connect();
        $builder = $db->table('view_pengiriman_summary');
        $builder->select('id_pengiriman');
        $builder->where("CAST(performance AS CHAR CHARACTER SET utf8mb4) =", 'Not Ontime');
        $data = $builder->get()->getResultArray();

        $remarks = ['Delay', 'Transit'];

        foreach ($data as $value) {

            $remark = $remarks[array_rand($remarks)];

            $builder = $db->table('tb_pengiriman');
            $builder->where('id_pengiriman', $value['id_pengiriman']);
            $builder->update(['remark' => $remark]);
        }
    }
}
