<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailInvoiceModel extends Model
{
     protected $table            = 'detail_invoices';
     protected $primaryKey       = 'id';
     protected $allowedFields    = ['invoice_id', 'pengiriman_id', 'created_at', 'updated_at', 'deleted_at'];
     protected $useTimestamps    = true;
     protected $useSoftDeletes   = true;

     public function insertDetail($invoiceId, $pengirimanId)
     {
          return $this->insert([
               'invoice_id'    => $invoiceId,
               'pengiriman_id' => $pengirimanId,
               'created_at'    => date('Y-m-d H:i:s'),
               'updated_at'    => date('Y-m-d H:i:s')
          ]);
     }

     public function getByInvoice($id)
     {
          $subqueryKoli = $this->db->table('tb_detail_order')
               ->selectSum('jumlah')
               ->where('id_order', 'to2.id_order', false);
          $subqueryBerat = $this->db->table('tb_detail_order tdo')
               ->select("
                GREATEST(
                    tl.minimum,
                    SUM(
                        CASE
                            WHEN CEIL(tdo.volume / tl.divider) > berat 
                                THEN (CEIL(tdo.volume / tl.divider)) * tdo.jumlah
                            ELSE berat * tdo.jumlah
                        END
                    )
                ) AS berat
            ", false)
               ->join('tb_layanan tl', 'tl.id_layanan = to2.id_layanan', 'left')
               ->where('tdo.id_order', 'to2.id_order', false);
          $subqueryBiayaLain = $this->db->table('transaction_extra_charge')
               ->select("
                         JSON_ARRAYAGG(
                              JSON_OBJECT(
                                   'id', transaction_extra_charge.id,
                                   'jenis_biaya', biaya_tambahan.jenis_biaya,
                                   'charge_value', transaction_extra_charge.charge_value
                              )
                         ) AS extra_charges
               ", false)
               ->join('biaya_tambahan', 'biaya_tambahan.id_biaya = transaction_extra_charge.biaya_id', 'left')
               ->where('transaction_extra_charge.pengiriman_id', 'tp.id_pengiriman', false)
               ->where('transaction_extra_charge.deleted_at IS NULL');

          $query = $this->db->table($this->table)
               ->select([
                    'detail_invoices.id as id_detail_invoice',
                    'detail_invoices.pengiriman_id',
                    'tp.*',
                    'tsj.no_surat_jalan',
                    'to2.no_order',
                    'to2.tanggal_order',
                    'billto.nama_pelanggan as billto',
                    'billto.alamat_pelanggan as alamat_billto',
                    'tp2.nama_pelanggan',
                    'cl.choice_name as layanan',
                    'tp3.nama_penerima',
                    'tp.created_at',
                    'tl.biaya_paket as price',
                    'tl.bill_type as bill_type',
                    '(
                         SELECT 
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
                              where tb_order.id_order = to2.id_order
                              GROUP BY tb_order.id_order
                         )as biaya_packing',
                    "({$subqueryKoli->getCompiledSelect()}) AS koli",
                    "({$subqueryBerat->getCompiledSelect()}) AS berat",
                    "({$subqueryBiayaLain->getCompiledSelect()}) AS extra_charges",
               ], false)
               // ->select(
               //      "JSON_ARRAYAGG(
               //                JSON_OBJECT(
               //                     'id', transaction_extra_charge.id,
               //                     'jenis_biaya', biaya_tambahan.jenis_biaya,
               //                     'charge_value', transaction_extra_charge.charge_value
               //                )
               //           ) AS extra_charges",
               //      false // false = jangan escape SQL-nya
               // )
               ->join('tb_pengiriman tp', 'tp.id_pengiriman = detail_invoices.pengiriman_id', 'left')
               // ->join('transaction_extra_charge', 'transaction_extra_charge.pengiriman_id = tp.id_pengiriman AND transaction_extra_charge.deleted_at IS NULL', 'left')
               // ->join('biaya_tambahan', 'biaya_tambahan.id_biaya = transaction_extra_charge.biaya_id', 'left')
               ->join('tb_surat_jalan tsj', 'tsj.id_surat_jalan = tp.surat_jalan_id', 'left')
               ->join('tb_order to2', 'to2.id_order = tsj.id_order', 'left')
               ->join('tb_pelanggan billto', 'billto.id_pelanggan = to2.id_billto', 'left')
               ->join('tb_pelanggan tp2', 'tp2.id_pelanggan = to2.id_pelanggan', 'left')
               ->join('tb_penerima tp3', 'tp3.id_penerima = to2.id_penerima', 'left')
               ->join('tb_layanan tl', 'tl.id_layanan = to2.id_layanan', 'left')
               ->join('choice_list cl', 'cl.id = tl.layanan', 'left')
               ->where('tp.deleted_at IS NULL')
               ->where('detail_invoices.deleted_at IS NULL')
               ->where('tp.surat_jalan_id !=', 0)
               ->where('detail_invoices.invoice_id =', $id)
               ->orderBy('to2.tanggal_order', 'ASC');

          // echo $query->getCompiledSelect(); // Debug query
          // exit;
          return $query->get()->getResultArray();
     }
}
