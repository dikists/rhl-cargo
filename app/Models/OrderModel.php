<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'tb_order';               // Nama tabel di database
    protected $primaryKey = 'id_order';          // Primary key dari tabel

    // Kolom yang dapat diisi secara massal
    protected $allowedFields = [
        'tanggal_order',
        'no_order',
        'no_ref',
        'id_billto',
        'id_pelanggan',
        'id_penerima',
        'id_layanan',
        'created_at',
        'deleted_at'
    ];

    // Konfigurasi penggunaan soft delete
    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';

    // Timestamps (optional)
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Method untuk mendapatkan semua data order atau berdasarkan ID
     *
     * @param int|false $id_order
     * @return array|object|null
     */
    public function getOrder($id_order = false)
    {
        if ($id_order === false) {
            return $this->findAll();
        }

        return $this->find($id_order);
    }
    public function getAllOrder($id_order = false, $date_start = false, $date_end = false, $pengirim = false, $penerima = false, $layanan = false)
    {
        $role = session()->get('role');
        if ($role == 'PIC RELASI') {
            $db = \Config\Database::connect();

            // Subquery
            $user_relation = $db->table('relation_user')
                ->select('relation_id')
                ->where('user_id', session()->get('id'));
        }
        $builder = $this->db->table($this->table);
        $builder->select('tb_order.*, 
        cl.choice_name as layanan, 
        tl.divider, 
        tl.minimum, 
        pelanggan.nama_pelanggan, 
        pelanggan.id_pelanggan, 
        penerima.nama_penerima, 
        penerima.id_penerima, 
        billto.nama_pelanggan as billto, 
        billto.id_pelanggan as id_billto, 
        (select count(*) from tb_surat_jalan tsj where tsj.id_order = tb_order.id_order and tsj.deleted_at is null) as in_surat_jalan');
        $builder->join('tb_pelanggan AS billto', 'billto.id_pelanggan = tb_order.id_billto', 'left');
        $builder->join('tb_pelanggan AS pelanggan', 'pelanggan.id_pelanggan = tb_order.id_pelanggan', 'left');
        $builder->join('tb_penerima AS penerima', 'penerima.id_penerima = tb_order.id_penerima', 'left');
        $builder->join('tb_layanan AS tl', 'tl.id_layanan = tb_order.id_layanan', 'left');
        $builder->join('choice_list cl', 'cl.id = tl.layanan', 'left');
        $builder->where('tb_order.deleted_at IS NULL');
        $builder->where('tb_order.created_at IS NOT NULL');
        if ($date_start && $date_end) {
            $builder->where('tb_order.tanggal_order >= "' . $date_start . '"');
            $builder->where('tb_order.tanggal_order <= "' . $date_end . '"');
        }
        if ($pengirim) {
            $builder->where('tb_order.id_pelanggan', $pengirim);
        }
        if ($penerima) {
            $builder->where('tb_order.id_penerima', $penerima);
        }
        if ($layanan) {
            $builder->where('tl.layanan', $layanan);
        }
        if($role == 'PIC RELASI'){
            $builder->whereIn('pelanggan.id_pelanggan', $user_relation);
        }
        if ($id_order !== false) {
            $builder->where('tb_order.id_order', $id_order);
        }
        $builder->orderBy('id_order', 'DESC');

        // echo $builder->getCompiledSelect();
        // exit;

        if ($id_order !== false) {
            return $builder->get()->getRowArray();
        } else {
            return $builder->get()->getResultArray();
        }
    }
    public function getNoOrder()
    {
        $query = $this->db->table($this->table)
            ->selectMax('no_order', 'ord')
            ->get()
            ->getRowArray();

        $jmlkd = isset($query['ord']) ? substr($query['ord'], 0, 3) : 0;
        $jmlkd++;
        $no_order = sprintf("%03s", $jmlkd);

        return $no_order;
    }

    public function getOrderNewSj()
    {
        $builder = $this->db->table($this->table . ' to2');
        $builder->select('to2.*, pelanggan.nama_pelanggan, pelanggan.alamat_pelanggan, penerima.nama_penerima, penerima.alamat_penerima, tl.layanan');
        $builder->select("COALESCE(JSON_ARRAYAGG(
            CASE 
                WHEN tdo.id_detail_order IS NOT NULL 
                THEN JSON_OBJECT('id_detail_order', tdo.id_detail_order, 'id_order', tdo.id_order, 'produk', tb.nama_barang, 'jumlah', tdo.jumlah, 'berat', tdo.berat, 'panjang', tdo.panjang, 'lebar', tdo.lebar, 'tinggi', tdo.tinggi, 'volume', tdo.volume, 'divider', tl.divider, 'minimum', tl.minimum)
                ELSE NULL 
            END
        ), '[]') as detail_order", false);
        $builder->join('tb_surat_jalan as tsj', 'to2.id_order = tsj.id_order and tsj.deleted_at IS NULL', 'left');
        $builder->join('tb_pelanggan AS pelanggan', 'pelanggan.id_pelanggan = to2.id_pelanggan', 'left');
        $builder->join('tb_penerima AS penerima', 'penerima.id_penerima = to2.id_penerima', 'left');
        $builder->join('tb_layanan AS tl', 'tl.id_layanan = to2.id_layanan', 'left');
        $builder->join('tb_detail_order tdo', 'tdo.id_order = to2.id_order', 'left');
        $builder->join('tb_barang tb', 'tb.id_barang = tdo.id_barang', 'left');
        $builder->where('to2.deleted_at', null);
        $builder->where('tsj.id_order', null);
        $builder->groupBy('to2.id_order, to2.tanggal_order, to2.no_order, to2.id_pelanggan, to2.id_penerima, to2.id_layanan, to2.created_at, to2.updated_at, to2.deleted_at, pelanggan.nama_pelanggan, pelanggan.alamat_pelanggan, penerima.nama_penerima, penerima.alamat_penerima, tl.layanan');
        $builder->orderBy('to2.id_order', 'DESC');

        $query = $builder->get();
        // echo $this->db->showLastQuery();
        return $query->getResultArray();
    }
    public function getOrderNewPickup()
    {
        $builder = $this->db->table($this->table . ' to2');

        $builder->select('
        to2.*, 
        tsj.no_surat_jalan, 
        tsj.id_surat_jalan, 
        pelanggan.nama_pelanggan, 
        pelanggan.alamat_pelanggan, 
        penerima.nama_penerima, 
        penerima.alamat_penerima, 
        tl.layanan, 
        tu.full_name as driver, 
        tu.id as id_user
    ');

        $builder->select("
        COALESCE(
            JSON_ARRAYAGG(
                CASE 
                    WHEN tdo.id_detail_order IS NOT NULL 
                    THEN JSON_OBJECT(
                        'id_detail_order', tdo.id_detail_order,
                        'id_order', tdo.id_order,
                        'produk', tb.nama_barang,
                        'jumlah', tdo.jumlah,
                        'berat', tdo.berat,
                        'panjang', tdo.panjang,
                        'lebar', tdo.lebar,
                        'tinggi', tdo.tinggi,
                        'volume', tdo.volume,
                        'divider', tl.divider,
                        'minimum', tl.minimum
                    )
                    ELSE NULL 
                END
            ), '[]'
        ) as detail_order
    ", false);

        $builder->join('tb_surat_jalan as tsj', 'to2.id_order = tsj.id_order AND tsj.deleted_at IS NULL', 'left');
        $builder->join('tb_pengambilan as tp', 'tp.id_order = to2.id_order AND tp.deleted_at IS NULL', 'left');
        $builder->join('tb_pelanggan AS pelanggan', 'pelanggan.id_pelanggan = to2.id_pelanggan', 'left');
        $builder->join('tb_penerima AS penerima', 'penerima.id_penerima = to2.id_penerima', 'left');
        $builder->join('tb_layanan AS tl', 'tl.id_layanan = to2.id_layanan', 'left');
        $builder->join('tb_detail_order tdo', 'tdo.id_order = to2.id_order', 'left');
        $builder->join('tb_barang tb', 'tb.id_barang = tdo.id_barang', 'left');
        $builder->join('tb_user tu', 'tu.id = tsj.kode_kurir', 'left');

        // Kondisi untuk data yang tidak ada di `tb_pengambilan`
        $builder->where('to2.deleted_at', null);
        $builder->where('tp.id_order', null); // Pastikan data tidak ada di tabel pengambilan
        $builder->where('tsj.id_surat_jalan IS NOT NULL'); // Surat jalan harus ada
        $builder->where('tu.id is not null '); // Surat jalan harus ada
        $builder->where('to2.created_at >=', "2025-01-01");

        // Grouping untuk JSON aggregation
        $builder->groupBy('
        to2.id_order, 
        to2.tanggal_order, 
        to2.no_order, 
        to2.id_pelanggan, 
        to2.id_penerima, 
        to2.id_layanan, 
        to2.created_at, 
        to2.updated_at, 
        to2.deleted_at, 
        pelanggan.nama_pelanggan, 
        pelanggan.alamat_pelanggan, 
        penerima.nama_penerima, 
        penerima.alamat_penerima, 
        tl.layanan
    ');

        $builder->orderBy('to2.id_order', 'DESC');

        // echo $builder->getCompiledSelect();
        // exit;

        // Eksekusi query
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getOrderNewSjEdit($id_order)
    {
        $builder = $this->db->table($this->table . ' to2');
        $builder->select('to2.*, pelanggan.nama_pelanggan, pelanggan.alamat_pelanggan, penerima.nama_penerima, penerima.alamat_penerima, tl.layanan');
        $builder->select("COALESCE(JSON_ARRAYAGG(
            CASE 
                WHEN tdo.id_detail_order IS NOT NULL 
                THEN JSON_OBJECT('id_detail_order', tdo.id_detail_order, 'id_order', tdo.id_order, 'produk', tb.nama_barang, 'jumlah', tdo.jumlah, 'berat', tdo.berat, 'panjang', tdo.panjang, 'lebar', tdo.lebar, 'tinggi', tdo.tinggi, 'volume', tdo.volume, 'divider', tl.divider, 'minimum', tl.minimum)
                ELSE NULL 
            END
        ), '[]') as detail_order", false);
        $builder->join('tb_surat_jalan as tsj', 'to2.id_order = tsj.id_order', 'left');
        $builder->join('tb_pelanggan AS pelanggan', 'pelanggan.id_pelanggan = to2.id_pelanggan', 'left');
        $builder->join('tb_penerima AS penerima', 'penerima.id_penerima = to2.id_penerima', 'left');
        $builder->join('tb_layanan AS tl', 'tl.id_layanan = to2.id_layanan', 'left');
        $builder->join('tb_detail_order tdo', 'tdo.id_order = to2.id_order', 'left');
        $builder->join('tb_barang tb', 'tb.id_barang = tdo.id_barang', 'left');
        $builder->where('to2.deleted_at', null);
        $builder->where('to2.id_order', $id_order);
        $builder->groupBy('to2.id_order, to2.tanggal_order, to2.no_order, to2.id_pelanggan, to2.id_penerima, to2.id_layanan, to2.created_at, to2.updated_at, to2.deleted_at, pelanggan.nama_pelanggan, pelanggan.alamat_pelanggan, penerima.nama_penerima, penerima.alamat_penerima, tl.layanan');
        $builder->orderBy('to2.id_order', 'DESC');

        $query = $builder->get();
        // echo $this->db->showLastQuery();
        return $query->getRowArray();
    }
}
