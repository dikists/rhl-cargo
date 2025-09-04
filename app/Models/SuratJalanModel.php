<?php

namespace App\Models;

use CodeIgniter\Model;

class SuratJalanModel extends Model
{
    protected $table = 'tb_surat_jalan';
    protected $primaryKey = 'id_surat_jalan';
    protected $useSoftDeletes = true;

    // Kolom yang diizinkan untuk operasi insert atau update
    protected $allowedFields = [
        'no_surat_jalan',
        'id_order',
        'id_kurir',
        'no_order',
        'kode_kurir',
        'truck_id',
        'tgl_pembuatan',
        'waktu_pembuatan',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Untuk otomatis mengelola waktu pembuatan dan pembaruan
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    public function getAllSuratJalan($id = false, $date_start = false, $date_end = false, $pengirim = false, $penerima = false, $layanan = false)
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
        $builder->select('*, cl.choice_name as layanan, tu.username as kurir, tr.plate_number, (
                                        select
                                            count(*)
                                        from
                                            tb_pengambilan tp
                                        where
                                            tp.id_surat_jalan = tb_surat_jalan.id_surat_jalan
                                            and tp.deleted_at is null) in_pickup');
        $builder->join('tb_order AS or2', 'or2.id_order = tb_surat_jalan.id_order', 'left');
        $builder->join('tb_pelanggan AS pelanggan', 'pelanggan.id_pelanggan = or2.id_pelanggan', 'left');
        $builder->join('tb_penerima AS penerima', 'penerima.id_penerima = or2.id_penerima', 'left');
        $builder->join('tb_layanan AS tl', 'tl.id_layanan = or2.id_layanan', 'left');
        $builder->join('choice_list cl', 'cl.id = tl.layanan', 'left');
        $builder->join('tb_user AS tu', 'tu.id = tb_surat_jalan.kode_kurir', 'left');
        $builder->join('trucks AS tr', 'tr.id = tb_surat_jalan.truck_id', 'left');
        $builder->where('or2.deleted_at IS NULL');
        $builder->where('tb_surat_jalan.deleted_at IS NULL');
        if($date_start && $date_end){            
            $builder->where('tb_surat_jalan.tgl_pembuatan >= "' . $date_start . '"');
            $builder->where('tb_surat_jalan.tgl_pembuatan <= "' . $date_end . '"');
        }
        if($pengirim){
            $builder->where('or2.id_pelanggan', $pengirim);
        }
        if($penerima){
            $builder->where('or2.id_penerima', $penerima);
        }
        if($layanan){
            $builder->where('tl.layanan', $layanan);
        }
        if ($id !== false) {
            $builder->where('tb_surat_jalan.id_surat_jalan', $id);
        }
        if($role == 'PIC RELASI'){
            $builder->whereIn('pelanggan.id_pelanggan', $user_relation);
        }
        $builder->orderBy('id_surat_jalan', 'DESC');

        if ($id !== false) {
            return $builder->get()->getRowArray();
        } else {
            return $builder->get()->getResultArray();
        }
    }

    public function getLastNoSuratJalan()
    {
        $builder = $this->db->table('tb_surat_jalan');
        $builder->select('no_surat_jalan');
        $builder->where('deleted_at', null); // Filter untuk soft delete
        $builder->orderBy('id_surat_jalan', 'DESC'); // Ambil data terbaru
        $builder->limit(1); // Hanya ambil satu data
        $query = $builder->get();

        $result = $query->getRow();

        // Jika ada hasil, format nomor dengan padding nol; jika tidak, return "000001"
        return $result ? str_pad($result->no_surat_jalan, 6, '0', STR_PAD_LEFT) : '000001';
    }
    public function generateNewNoSuratJalan()
    {
        $lastNoSuratJalan = $this->getLastNoSuratJalan();

        // Ambil angka terakhir dan tambahkan 1
        $newNumber = intval($lastNoSuratJalan) + 1;

        // Format nomor menjadi 6 karakter
        return str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }
    public function getSJManifest()
    {
        $builder = $this->db->table($this->table . ' tsj');
        $builder ->select('tsj.*, pelanggan.nama_pelanggan as pengirim, penerima.nama_penerima as penerima');

        $builder->join('tb_manifest_detail tmd', 'surat_jalan_id = tsj.id_surat_jalan and tmd.deleted_at is null and tmd.status = 1', 'left');
        $builder->join('tb_order AS or2', 'or2.id_order = tsj.id_order', 'left');
        $builder->join('tb_pelanggan AS pelanggan', 'pelanggan.id_pelanggan = or2.id_pelanggan', 'left');
        $builder->join('tb_penerima AS penerima', 'penerima.id_penerima = or2.id_penerima', 'left');
        $builder->where('tmd.surat_jalan_id is null');
        $builder->where('tsj.created_at is not null');
        $builder->where('tsj.deleted_at is null');
        // echo $builder->getCompiledSelect();
        // exit;
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getDatalabel($id){
        $subqueryKoli = $this->db->table('tb_detail_order')
            ->selectSum('jumlah')
            ->where('id_order', 'to2.id_order', false);
        
        $subqueryBerat = $this->db->table('tb_detail_order tdo')
            ->select("SUM(
                CASE
                    when ceil(tdo.volume / tl.divider) > berat then (ceil(tdo.volume / tl.divider))* tdo.jumlah 
                    ELSE berat * tdo.jumlah
                END
            ) AS berat", false)
            ->join('tb_layanan tl', 'tl.id_layanan = to2.id_layanan', 'left')
            ->where('tdo.id_order', 'to2.id_order', false);

        $subqueryVolume = $this->db->table('tb_detail_order tdo')
            ->select("SUM(tdo.volume / 1000000 * tdo.jumlah) AS volume", false)
            ->join('tb_layanan tl', 'tl.id_layanan = to2.id_layanan', 'left')
            ->where('tdo.id_order', 'to2.id_order', false);
        
        $layananQuery = $this->db->table('tb_layanan tl')
            ->select("cl.choice_name as layanan", false)
            ->join('choice_list cl', 'cl.id = tl.layanan', 'left')
            ->where('tl.id_layanan', 'to2.id_layanan', false);
        
        $query = $this->db->table($this->table . ' tsj')
            ->select([
                'tsj.id_surat_jalan',
                'tsj.created_at AS tanggal',
                'to2.id_order',
                'tsj.no_surat_jalan',
                'tp.nama_pelanggan',
                'tp.alamat_pelanggan',
                'tp.kota AS asal',
                'tp2.nama_penerima',
                'wk.nama AS tujuan',
                'tp2.alamat_penerima',
                "({$layananQuery->getCompiledSelect()}) AS layanan",
                "({$subqueryKoli->getCompiledSelect()}) AS koli",
                "({$subqueryBerat->getCompiledSelect()}) AS berat",
                "({$subqueryVolume->getCompiledSelect()}) AS volume"
            ], false)
            ->join('tb_order to2', 'to2.id_order = tsj.id_order', 'left')
            ->join('tb_pelanggan tp', 'tp.id_pelanggan = to2.id_pelanggan', 'left')
            ->join('tb_penerima tp2', 'tp2.id_penerima = to2.id_penerima', 'left')
            ->join('wilayah_kabupaten wk', 'wk.id = tp2.kabupaten_id', 'left')
            ->where('tsj.id_surat_jalan', $id);

        // echo $query->getCompiledSelect(true); 
        // die;

        $query = $query->get();
        
        return $query->getRowArray();
        
    }
}
