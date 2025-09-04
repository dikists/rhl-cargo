<?php

namespace App\Models;

use CodeIgniter\Model;

class ViewPengirimanSummaryModel extends Model
{
    protected $table = 'view_pengiriman_summary';
    protected $primaryKey = 'id_pengiriman'; // kalau ada PK, tulis di sini

    protected $allowedFields = [
        'id_pengiriman',
        'surat_jalan_id',
        'kode_kurir',
        'tanggal_kirim',
        'waktu_kirim',
        'tanggal_terima',
        'waktu_terima',
        'dto',
        'status',
        'signatures',
        'status_id',
        'remark',
        'surcharge',
        'insurance',
        'created_at',
        'updated_at',
        'deleted_at',
        'no_surat_jalan',
        'no_order',
        'no_ref',
        'tanggal_order',
        'id_pelanggan',
        'nama_pelanggan',
        'origin',
        'layanan',
        'leadtime',
        'biaya_paket',
        'bill_type',
        'id_penerima',
        'nama_penerima',
        'driver_name',
        'plate_number',
        'vendor_name',
        'sub_vendor_name',
        'satuan',
        'biaya_packing',
        'performance',
        'lt_actual',
        'koli',
        'berat',
        'volume',
        'in_invoice',
        'no_invoice',
        'biaya_kirim'
    ];

    // View biasanya hanya SELECT, jadi disable insert/update/delete
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    public $skipValidation = true;

    public function all(){
        return $this->findAll();
    }
    
    public function cari($keyword)
    {
        return $this->like('no_surat_jalan', $keyword)
            ->orLike('no_order', $keyword)
            ->orLike('no_ref', $keyword)
            ->findAll();
    }
}
