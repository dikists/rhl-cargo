<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanModel extends Model
{
    public function getData($start, $end, $shipper, $consignee, $performance)
    {
        $db = \Config\Database::connect('db2');
        $builder = $db->table('tb_pengiriman tp');

        $builder->select('
        tsj.no_surat_jalan,
        to2.no_ref,
        to2.tanggal_order,
        tp2.kota AS origin,
        tp3.nama_penerima AS dest,
        tl.leadtime,
        tk.nopol,
        tl.layanan,
        IF(do2.total_hitung < tl.minimum, tl.minimum, do2.total_hitung) AS total_hitung,
        do2.total_qty,
        do2.total_berat,
        do2.total_volume,
        do2.satuan,
        do2.total_biaya_packing,
        tp.tanggal_terima AS arrival_date,
        tp.dto AS receipt_name,
        (
             DATEDIFF(tp.tanggal_terima, to2.tanggal_order) -- Total hari tanpa tanggal kirim
            - FLOOR((DATEDIFF(tp.tanggal_terima, to2.tanggal_order) + WEEKDAY(to2.tanggal_order)) / 7) -- Kurangi jumlah minggu penuh (hari Minggu)
            - CASE 
                WHEN WEEKDAY(tp.tanggal_terima) = 6 THEN 1 -- Jika tanggal terima adalah hari Minggu
                ELSE 0
            END
        ) AS leadtime_actual,
        CASE 
            WHEN 
            (
                 DATEDIFF(tp.tanggal_terima, to2.tanggal_order) -- Total hari tanpa tanggal kirim
                - FLOOR((DATEDIFF(tp.tanggal_terima, to2.tanggal_order) + WEEKDAY(to2.tanggal_order)) / 7) -- Kurangi jumlah minggu penuh (hari Minggu)
                - CASE 
                    WHEN WEEKDAY(tp.tanggal_terima) = 6 THEN 1 -- Jika tanggal terima adalah hari Minggu
                    ELSE 0
                END
            )
            <= tl.leadtime THEN "Ontime" 
            WHEN 
            (
                 DATEDIFF(tp.tanggal_terima, to2.tanggal_order) -- Total hari tanpa tanggal kirim
                - FLOOR((DATEDIFF(tp.tanggal_terima, to2.tanggal_order) + WEEKDAY(to2.tanggal_order)) / 7) -- Kurangi jumlah minggu penuh (hari Minggu)
                - CASE 
                    WHEN WEEKDAY(tp.tanggal_terima) = 6 THEN 1 -- Jika tanggal terima adalah hari Minggu
                    ELSE 0
                END
            )
            > tl.leadtime THEN "Not Ontime" 
            ELSE "Pending" 
        END AS performance,
        tl.biaya_paket AS cost,
        ((IF(do2.total_hitung < tl.minimum, tl.minimum, do2.total_hitung) * tl.biaya_paket) + do2.total_biaya_packing) AS total_cost,
        MONTH(to2.tanggal_order) AS month,
        tp.remark
    ');

        $builder->join('tb_surat_jalan tsj', 'tp.no_surat_jalan = tsj.no_surat_jalan', 'left');
        $builder->join('tb_order to2', 'tsj.id_order = to2.id_order', 'left');
        $builder->join('tb_pelanggan tp2', 'to2.id_pelanggan = tp2.id_pelanggan', 'left');
        $builder->join('tb_penerima tp3', 'to2.id_penerima = tp3.id_penerima', 'left');
        $builder->join('tb_layanan tl', 'to2.id_layanan = tl.id_layanan', 'left');
        $builder->join('tb_kurir tk', 'tsj.kode_kurir = tk.kode_kurir', 'left');
        $builder->join('shipment_statuses ss', 'ss.status_id = tp.status_id', 'left');
        $builder->join('(SELECT 
            tb_order.id_order,
            SUM(tb_detail_order.jumlah) AS total_qty,
            SUM(tb_detail_order.berat * tb_detail_order.jumlah) AS total_berat,
            SUM(ROUND(tb_detail_order.volume / tb_layanan.divider, 0) *tb_detail_order.jumlah) AS total_volume,
            SUM(
                CASE 
                    WHEN tb_detail_order.berat * tb_detail_order.jumlah > ROUND(tb_detail_order.volume / tb_layanan.divider, 0) * tb_detail_order.jumlah
                    THEN tb_detail_order.berat * tb_detail_order.jumlah 
                    ELSE ROUND(tb_detail_order.volume / tb_layanan.divider, 0) * tb_detail_order.jumlah 
                END
            ) AS total_hitung,
            MAX(tb_barang.satuan) AS satuan,
            SUM(CASE
                WHEN biaya_tambahan.id_biaya = 9 THEN
                    biaya_tambahan.nominal * CASE
                        WHEN (CASE
                            WHEN tb_detail_order.berat > ROUND(tb_detail_order.volume / tb_layanan.divider, 0) * tb_detail_order.jumlah
                            THEN tb_detail_order.berat * tb_detail_order.jumlah
                            ELSE ROUND(tb_detail_order.volume / tb_layanan.divider, 0) * tb_detail_order.jumlah
                        END) > tb_layanan.minimum
                        THEN (CASE
                            WHEN tb_detail_order.berat > ROUND(tb_detail_order.volume / tb_layanan.divider, 0) * tb_detail_order.jumlah
                            THEN tb_detail_order.berat * tb_detail_order.jumlah
                            ELSE ROUND(tb_detail_order.volume / tb_layanan.divider, 0) * tb_detail_order.jumlah
                        END)
                        ELSE tb_layanan.minimum
                    END
                ELSE 0
            END) AS total_biaya_packing
        FROM 
            tb_order
        LEFT JOIN tb_detail_order ON tb_order.id_order = tb_detail_order.id_order
        LEFT JOIN tb_barang ON tb_detail_order.id_barang = tb_barang.id_barang
        LEFT JOIN tb_layanan ON tb_order.id_layanan = tb_layanan.id_layanan
        LEFT JOIN detail_order_biaya ON tb_detail_order.id_detail_order = detail_order_biaya.id_detail_order AND detail_order_biaya.id_biaya = 9
        LEFT JOIN biaya_tambahan ON detail_order_biaya.id_biaya = biaya_tambahan.id_biaya
        GROUP BY tb_order.id_order
    ) do2', 'to2.id_order = do2.id_order', 'left');

        // 7 adalah id untuk status "Cancel"
        $builder->groupStart();
        $builder->where('ss.status_id !=', 7);
        $builder->orWhere('ss.status_id IS NULL');
        $builder->groupEnd();

        if (!empty($start) && !empty($end)) {
            $builder->where('to2.tanggal_order >=', $start);
            $builder->where('to2.tanggal_order <=', $end);
        }
        if (!empty($shipper)) {
            $builder->where('tp2.id_pelanggan', $shipper);
        }
        if (!empty($consignee)) {
            $builder->where('tp3.id_penerima', $consignee);
        }
        if (!empty($performance)) {
            $builder->having('performance', $performance);
        }

        $builder->orderBy('to2.tanggal_order', 'ASC');
        $query = $builder->get();

        return $query->getResultArray();
    }
}
