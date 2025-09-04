<?php

namespace App\Models;

use CodeIgniter\Model;

class PengirimanModel extends Model
{
    protected $table            = 'tb_pengiriman';
    protected $primaryKey       = 'id_pengiriman';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'surat_jalan_id',
        'no_surat_jalan',
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
        'insurance',
        'surcharge',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Soft Deletes
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function getPengirimanId($id)
    {
        $subquery = $this->db->table('shipment_status_log ssl2')
            ->select('ssl2.shipment_id, MAX(ssl2.log_id) as last_log_id')
            ->groupBy('ssl2.shipment_id');

        $builder = $this->db->table('tb_pengiriman');

        $builder->select([
            'tb_pengiriman.id_pengiriman',
            'to2.id_order as id_order',
            'to2.tanggal_order',
            'tp3.id_pengambilan as id_pengambilan',
            'tp3.tanggal_ambil',
            'tp3.waktu_ambil',
            'tb_pengiriman.tanggal_kirim',
            'tb_pengiriman.waktu_kirim',
            'tb_pengiriman.tanggal_terima',
            'tb_pengiriman.waktu_terima',
            'tb_pengiriman.dto',
            'tsj.no_surat_jalan',
            'ss.status_name',
            'tp.nama_pelanggan as shipper',
            'tp2.nama_penerima as consignee',
            'tu.username as driver',
            'to2.no_order'
        ]);

        // Join ke subquery
        $builder->join("({$subquery->getCompiledSelect()}) as latest_logs", 'latest_logs.shipment_id = tb_pengiriman.id_pengiriman', 'left', false);

        // Join ke tabel lainnya
        $builder->join('shipment_status_log ssl', 'ssl.log_id = latest_logs.last_log_id', 'left');
        $builder->join('shipment_statuses ss', 'ss.status_id = ssl.status_id', 'left');
        $builder->join('tb_surat_jalan tsj', 'tsj.id_surat_jalan = tb_pengiriman.surat_jalan_id', 'left');
        $builder->join('tb_order to2', 'to2.id_order = tsj.id_order', 'left');
        $builder->join('tb_pelanggan tp', 'tp.id_pelanggan = to2.id_pelanggan', 'left');
        $builder->join('tb_penerima tp2', 'tp2.id_penerima = to2.id_penerima', 'left');
        $builder->join('tb_user tu', 'tu.id = tb_pengiriman.kode_kurir', 'left');
        $builder->join('tb_pengambilan tp3', 'tp3.id_surat_jalan = tb_pengiriman.surat_jalan_id AND tp3.deleted_at IS NULL', 'left', false);

        // Where condition
        $builder->where('tb_pengiriman.id_pengiriman', $id);

        // echo $builder->getCompiledSelect();
        // exit;

        // Eksekusi query
        return $builder->get()->getRowArray();
    }
    public function getPengirimanIdSJ($id)
    {
        $subquery = $this->db->table('shipment_status_log ssl2')
            ->select('ssl2.shipment_id, MAX(ssl2.log_id) as last_log_id')
            ->groupBy('ssl2.shipment_id');

        $builder = $this->db->table('tb_pengiriman');

        $builder->select([
            'tb_pengiriman.id_pengiriman',
            'to2.id_order as id_order',
            'to2.tanggal_order',
            'tp3.id_pengambilan as id_pengambilan',
            'tp3.tanggal_ambil',
            'tp3.waktu_ambil',
            'tb_pengiriman.tanggal_kirim',
            'tb_pengiriman.waktu_kirim',
            'tb_pengiriman.tanggal_terima',
            'tb_pengiriman.waktu_terima',
            'tb_pengiriman.dto',
            'tsj.no_surat_jalan',
            'ss.status_name',
            'tp.nama_pelanggan as shipper',
            'tp2.nama_penerima as consignee',
            'tu.username as driver',
            'to2.no_order'
        ]);

        // Join ke subquery
        $builder->join("({$subquery->getCompiledSelect()}) as latest_logs", 'latest_logs.shipment_id = tb_pengiriman.id_pengiriman', 'left', false);

        // Join ke tabel lainnya
        $builder->join('shipment_status_log ssl', 'ssl.log_id = latest_logs.last_log_id', 'left');
        $builder->join('shipment_statuses ss', 'ss.status_id = ssl.status_id', 'left');
        $builder->join('tb_surat_jalan tsj', 'tsj.id_surat_jalan = tb_pengiriman.surat_jalan_id', 'left');
        $builder->join('tb_order to2', 'to2.id_order = tsj.id_order', 'left');
        $builder->join('tb_pelanggan tp', 'tp.id_pelanggan = to2.id_pelanggan', 'left');
        $builder->join('tb_penerima tp2', 'tp2.id_penerima = to2.id_penerima', 'left');
        $builder->join('tb_user tu', 'tu.id = tb_pengiriman.kode_kurir', 'left');
        $builder->join('tb_pengambilan tp3', 'tp3.id_surat_jalan = tb_pengiriman.surat_jalan_id AND tp3.deleted_at IS NULL', 'left', false);

        // Where condition
        $builder->where('tsj.id_surat_jalan', $id);

        // echo $builder->getCompiledSelect();
        // exit;

        // Eksekusi query
        return $builder->get()->getRowArray();
    }

    public function getPengirimanByCustomerId($customerId)
    {
        $subquery = "(SELECT ssl2.shipment_id, MAX(ssl2.log_id) AS last_log_id
                      FROM shipment_status_log ssl2
                      GROUP BY ssl2.shipment_id) as latest_logs";

        $builder = $this->db->table($this->table);
        $builder->select('tb_pengiriman.*, ss.status_name, tp.nama_pelanggan AS shipper, tp2.nama_penerima AS consignee, tk.nama_kurir AS driver, tk.nopol, to2.no_order');
        $builder->join("$subquery", 'latest_logs.shipment_id = tb_pengiriman.id_pengiriman', 'left', false);
        $builder->join('shipment_status_log ssl', 'ssl.log_id = latest_logs.last_log_id', 'left');
        $builder->join('shipment_statuses ss', 'ss.status_id = ssl.status_id', 'left');
        $builder->join('tb_surat_jalan tsj', 'tsj.no_surat_jalan = tb_pengiriman.no_surat_jalan', 'left');
        $builder->join('tb_order to2', 'to2.id_order = tsj.id_order', 'left');
        $builder->join('tb_pelanggan tp', 'tp.id_pelanggan = to2.id_pelanggan', 'left');
        $builder->join('tb_penerima tp2', 'tp2.id_penerima = to2.id_penerima', 'left');
        $builder->join('tb_kurir tk', 'tk.kode_kurir = tb_pengiriman.kode_kurir', 'left');
        $builder->where('to2.id_pelanggan', $customerId);

        return $builder->get()->getResultArray();
    }
    public function getShipmentStats($customerId, $isAdmin = 'N')
    {
        $builder = $this->db->table($this->table);

        $builder->select('
            COUNT(DISTINCT tb_pengiriman.surat_jalan_id) as total_pengiriman,
            SUM(tdo.berat * tdo.jumlah) as total_berat,
            SUM(ROUND(tdo.volume * tdo.jumlah / tl.divider, 0)) as total_volume,
            SUM(tdo.jumlah) as total_koli
        ');

        $builder->join('tb_surat_jalan tsj', 'tsj.id_surat_jalan = tb_pengiriman.surat_jalan_id', 'left');
        $builder->join('tb_order to2', 'to2.id_order = tsj.id_order', 'left');
        $builder->join('tb_pelanggan tp2', 'tp2.id_pelanggan = to2.id_pelanggan', 'left');
        $builder->join('tb_detail_order tdo', 'tdo.id_order = to2.id_order', 'left');
        $builder->join('tb_layanan tl', 'tl.id_layanan = to2.id_layanan', 'left');
        $builder->join('shipment_statuses ss', 'ss.status_id = tb_pengiriman.status_id', 'left');

        if ($isAdmin == 'N') {
            $builder->where('tp2.id_pelanggan', $customerId);
        }

        $builder->where('YEAR(to2.tanggal_order)', 'YEAR(CURDATE())', false);
        $builder->where('MONTH(to2.tanggal_order) >= 08');
        $builder->groupStart();
        $builder->where('ss.status_id !=', 7);
        $builder->orWhere('ss.status_id IS NULL');
        $builder->groupEnd();
        $builder->where('to2.deleted_at IS NULL');
        $builder->where('tsj.deleted_at IS NULL');
        // echo $builder->getCompiledSelect();
        $query = $builder->get();
        return $query->getRow();
    }
    public function getSummaryByYear($id)
    {
        $year = date('Y');
        $role = session()->get('role');
        $cond = '';

        if ($role == 'PIC RELASI') {
            $db = \Config\Database::connect();
            // Subquery
            $user_relation = $db->table('relation_user')
                ->select('relation_id')
                ->where('user_id', $id);

            $cond = ' AND id_pelanggan IN (' . $user_relation->getCompiledSelect() . ')';
        }

        $sql = "
            SELECT
                COUNT(*) AS total_pengiriman,
                SUM(ROUND(berat, 0)) AS total_berat,
                SUM(volume) AS total_volume,
                SUM(koli) AS total_koli
            FROM
                view_pengiriman_summary
            WHERE
                YEAR(tanggal_order) = '$year'
                $cond
        ";

        // echo session()->get('role');
        // echo '<br>';
        // echo $sql;

        return $this->db->query($sql)->getRow();
    }
    public function getShipmentStatusSummaryNew($id, $month = null, $year = null)
    {
        if (!$month && !$year) {
            $year = date('Y');
            $month = date('m');
        }
        $role = session()->get('role');
        $cond = '';

        // kalo tidak ada role artinya dia login langsung sebagai pelanggan
        if (!$role) {
            $cond = ' AND id_pelanggan = ' . $id;
        }

        if ($role == 'PIC RELASI') {
            $db = \Config\Database::connect();
            // Subquery
            $user_relation = $db->table('relation_user')
                ->select('relation_id')
                ->where('user_id', $id);

            $cond = ' AND id_pelanggan IN (' . $user_relation->getCompiledSelect() . ')';
        }

        $sql = "
            WITH summary_cte AS (
                SELECT * 
                FROM view_pengiriman_summary 
                WHERE YEAR(tanggal_order) = '$year' AND MONTH(tanggal_order) = '$month' $cond
            )
            SELECT
                performance AS status,
                COUNT(*) AS count
            FROM summary_cte
            WHERE performance IN (
                'Not Ontime' COLLATE utf8mb4_unicode_ci,
                'Ontime' COLLATE utf8mb4_unicode_ci,
                'On Process' COLLATE utf8mb4_unicode_ci
            )
            GROUP BY performance;
        ";

        // echo session()->get('role');
        // echo '<br>';
        // echo $sql;
        // die;

        return $this->db->query($sql)->getResultArray();
    }
    public function getMonthlyShipments($customerId, $isAdmin = 'N')
    {
        $builder = $this->db->table($this->table);

        $builder->select('
            MONTH(to2.tanggal_order) AS month, 
            COUNT(*) AS total_shipments
        ');
        $builder->join('tb_surat_jalan tsj', 'tsj.no_surat_jalan = tb_pengiriman.no_surat_jalan', 'left');
        $builder->join('tb_order to2', 'to2.id_order = tsj.id_order', 'left');
        $builder->join('shipment_statuses ss', 'ss.status_id = tb_pengiriman.status_id', 'left');
        $builder->where('YEAR(to2.tanggal_order)', date('Y'));
        if ($isAdmin == 'N') {
            $builder->where('to2.id_pelanggan', $customerId);
        }
        $builder->where('to2.deleted_at IS NULL');
        $builder->groupStart();
        $builder->where('ss.status_id !=', 7);
        $builder->orWhere('ss.status_id IS NULL');
        $builder->groupEnd();
        $builder->groupBy('MONTH(to2.tanggal_order)');
        $builder->orderBy('month', 'ASC');

        $query = $builder->get();
        // $db = \Config\Database::connect();
        // echo $db->getLastQuery();
        return $query->getResultArray();
    }

    public function get_data_performance($id, $bulan, $tahun)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);

        $qry = "
        SELECT
            COUNT(to2.id_order) AS total_order,
            SUM(CASE
					WHEN (
							DATEDIFF(tp.tanggal_terima, to2.tanggal_order)
							- FLOOR((DATEDIFF(tp.tanggal_terima, to2.tanggal_order) + WEEKDAY(to2.tanggal_order)) / 7)
							- CASE 
								WHEN WEEKDAY(tp.tanggal_terima) = 6 THEN 1
							ELSE 0
							END
						) > tl.leadtime THEN 1
					ELSE 0
				END) AS not_ontime,
			SUM(CASE
					WHEN (
							DATEDIFF(tp.tanggal_terima, to2.tanggal_order)
							- FLOOR((DATEDIFF(tp.tanggal_terima, to2.tanggal_order) + WEEKDAY(to2.tanggal_order)) / 7)
							- CASE 
								WHEN WEEKDAY(tp.tanggal_terima) = 6 THEN 1
							ELSE 0
							END
						) <= tl.leadtime THEN 1
					ELSE 0
				END) AS ontime,
			SUM(CASE
					WHEN tp.tanggal_terima IS NULL THEN 1
					ELSE 0
				END) AS pending,
			ROUND(
			(SUM(CASE 
				WHEN tp.tanggal_terima IS NOT NULL AND DATEDIFF(tp.tanggal_terima, to2.tanggal_order)
												- FLOOR((DATEDIFF(tp.tanggal_terima, to2.tanggal_order) + WEEKDAY(to2.tanggal_order)) / 7)
												- CASE 
													WHEN WEEKDAY(tp.tanggal_terima) = 6 THEN 1
												ELSE 0
												END <= tl.leadtime THEN 1 
				ELSE 0 
			END) * 100 / COUNT(to2.id_order)), 2) AS delivery_performance
        FROM
            tb_pengiriman tp
            LEFT JOIN tb_surat_jalan tsj ON tp.no_surat_jalan = tsj.no_surat_jalan
            LEFT JOIN tb_order to2 ON tsj.id_order = to2.id_order
            LEFT JOIN tb_layanan tl ON to2.id_layanan = tl.id_layanan
            LEFT JOIN shipment_statuses ss ON tp.status_id = ss.status_id
        WHERE
            (ss.status_id != 7 OR ss.status_id IS NULL) AND
            to2.id_pelanggan = ?
            AND MONTH(to2.tanggal_order) = ?
            AND YEAR(to2.tanggal_order) = ?
        ";

        $query = $db->query($qry, [$id, $bulan, $tahun]);
        return $query->getResultArray();
    }
    public function getPengirimanByNoSuratJalan($no_surat_jalan)
    {
        $db = \Config\Database::connect();
        // $builder = $this->db->table('tb_surat_jalan tsj');
        $builder = $db->table($this->table);
        $builder->select('*');
        $builder->where('no_surat_jalan', $no_surat_jalan);
        $query = $builder->get();
        return $query->getRow();
    }
    public function getTrackingData($tracking)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_surat_jalan');
        // Select fields
        $selectFields = 'tb_surat_jalan.no_surat_jalan, tb_surat_jalan.tgl_pembuatan, tb_surat_jalan.waktu_pembuatan,
                	 tod.*,
                     plg.nama_pelanggan as nama_pelanggan,
                     plg.alamat_pelanggan as alamat_pelanggan,
                     plg.kota as kota,
                     pnrm.nama_penerima as nama_penerima,
                     pnrm.alamat_penerima as alamat_penerima,
                     kab.nama as kota_penerima, 
                     pengambilan.tanggal_ambil,
                     pengambilan.waktu_ambil,
                     pengiriman.tanggal_kirim,
                     pengiriman.waktu_kirim,
                     pengiriman.tanggal_terima,
                     pengiriman.waktu_terima,
                     pengiriman.dto,
                     CASE 
                        WHEN pengiriman.tanggal_kirim IS NOT NULL THEN "departed from transit center"
                        WHEN pengambilan.tanggal_ambil IS NOT NULL THEN "receiver at sorting center"
                        ELSE "ON PROCCESS"
                     END AS status_data';

        $builder->select($selectFields, false); // Use false to avoid escaping

        // Joins
        $builder->join('tb_order tod', 'tb_surat_jalan.id_order = tod.id_order', 'left');
        $builder->join('tb_pelanggan plg', 'plg.id_pelanggan = tod.id_pelanggan', 'left');
        $builder->join('tb_penerima pnrm', 'pnrm.id_penerima = tod.id_penerima', 'left');
        $builder->join('wilayah_kabupaten kab', 'kab.id = pnrm.kabupaten_id', 'left');
        $builder->join('tb_pengambilan pengambilan', 'pengambilan.id_surat_jalan = tb_surat_jalan.id_surat_jalan', 'left');
        $builder->join('tb_pengiriman pengiriman', 'pengiriman.surat_jalan_id = tb_surat_jalan.id_surat_jalan', 'left');

        // Where condition
        $builder->where('tb_surat_jalan.no_surat_jalan', $tracking);
        $builder->where('tb_surat_jalan.deleted_at is null');

        // echo $builder->getCompiledSelect();
        // die;

        // Execute query
        $query = $builder->get();

        return $query->getRow();
    }
    public function getShipmentStatusLog($tracking)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('shipment_status_log as sslog');

        // Select fields
        $builder->select('tsj.no_surat_jalan, ss.status_name, sslog.changed_at');

        // Joins
        $builder->join('shipment_statuses as ss', 'ss.status_id = sslog.status_id', 'left');
        $builder->join('tb_pengiriman as tp', 'tp.id_pengiriman = sslog.shipment_id', 'left');
        $builder->join('tb_surat_jalan as tsj', 'tsj.id_surat_jalan = tp.surat_jalan_id', 'left');

        // Where condition
        $builder->where('tsj.no_surat_jalan', $tracking);

        // echo $builder->getCompiledSelect();
        // die;

        // Execute query
        $query = $builder->get();

        return $query->getResultArray();
    }
    public function getDetailOrder($id_order, $id_layanan)
    {
        $db = \Config\Database::connect();
        // Create a new query builder instance
        $builder = $db->table('tb_detail_order as tdo');
        // Build the query
        $builder->select('tb_barang.satuan as satuan, tb_barang.nama_barang as barang, tdo.jumlah as jumlah, tdo.berat as berat, tdo.panjang as panjang, tdo.lebar as lebar, tdo.tinggi as tinggi, tdo.volume as volume, tl.divider as divider');
        $builder->join('tb_barang', 'tdo.id_barang = tb_barang.id_barang');
        $builder->join('tb_layanan as tl', 'tl.id_layanan = ' . $id_layanan);
        $builder->where('tdo.id_order', $id_order);
        // Execute the query
        // echo $builder->getCompiledSelect();
        // exit;

        $query = $builder->get();
        return $query->getResultArray();
    }
    public function getDetailInvoices($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tb_pengiriman tp');

        $builder->select('
                tsj.no_surat_jalan, 
                to2.tanggal_order, 
                tp2.kota AS origin, 
                tp3.nama_penerima AS dest, 
                IF(do2.total_hitung < tl.minimum, tl.minimum, do2.total_hitung) AS total_hitung, 
                do2.total_qty, 
                do2.total_berat, 
                do2.total_volume, 
                do2.satuan,
                do2.total_biaya_packing,
                tp.tanggal_terima AS arrival_date,  
                tl.biaya_paket AS cost, 
                ((IF(do2.total_hitung < tl.minimum, tl.minimum, do2.total_hitung) * tl.biaya_paket) + do2.total_biaya_packing + tp.surcharge) AS total_cost,
                tp.surcharge,
                tl.layanan
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
        $builder->where('to2.tanggal_order >=', '2024-06-01');

        // Mendapatkan daftar no_surat_jalan dari detail_invoices
        $noSuratJalanList = $this->db->table('detail_invoices')
            ->select('no_surat_jalan')
            ->where('invoice_id', $id)
            ->where('deleted_at IS NULL')
            ->get()
            ->getResultArray();

        // Ekstrak no_surat_jalan menjadi array
        $noSuratJalanArray = array_column($noSuratJalanList, 'no_surat_jalan');

        $builder->whereIn('tsj.no_surat_jalan', $noSuratJalanArray);

        $builder->orderBy('to2.tanggal_order', 'ASC');
        $query = $builder->get();
        // echo $this->db->getLastQuery(); // Menampilkan query terakhir

        return $query->getResultArray();
    }
    public function getPengiriman($date_start = false, $date_end = false, $pengirim = false, $penerima = false, $layanan = false, $performance = false)
    {
        $role = session()->get('role');
        if ($role == 'PIC RELASI') {
            $db = \Config\Database::connect();

            // Subquery
            $user_relation = $db->table('relation_user')
                ->select('relation_id')
                ->where('user_id', session()->get('id'));
        }

        $subqueryCekNoInvoice = $this->db->table('detail_invoices as di')
            ->select('i.invoice_number')
            ->join('invoices as i', 'i.id = di.invoice_id', 'left')
            ->where('di.pengiriman_id', 'tp.id_pengiriman', false)
            ->where('di.deleted_at IS NULL');
        $subqueryCekInvoice = $this->db->table('detail_invoices as di')
            ->selectCount('di.pengiriman_id')
            ->where('di.pengiriman_id', 'tp.id_pengiriman', false)
            ->where('di.deleted_at IS NULL');
        $subqueryKoli = $this->db->table('tb_detail_order')
            ->selectSum('jumlah')
            ->where('id_order', 'to2.id_order', false);
        $subqueryBerat = $this->db->table('tb_detail_order tdo')
            ->select("GREATEST(
                        SUM(
                            GREATEST(
                                berat * jumlah, 
                                CEIL(volume / divider) * jumlah
                                )
                            ),
                            tl.minimum
                        ) AS berat", false)
            ->join('tb_layanan tl', 'tl.id_layanan = to2.id_layanan', 'left')
            ->where('tdo.id_order', 'to2.id_order', false);
        $subqueryVolume = $this->db->table('tb_detail_order tdo')
            ->select("SUM(
                 (ceil(tdo.volume / tl.divider))* tdo.jumlah 
            ) AS volume", false)
            ->join('tb_layanan tl', 'tl.id_layanan = to2.id_layanan', 'left')
            ->where('tdo.id_order', 'to2.id_order', false);
        $subqueryAdditional = $this->db->table('transaction_extra_charge tec')
            ->select("SUM(
                           tec.charge_value
                        ) AS additional", false)
            ->where('tec.pengiriman_id', 'tp.id_pengiriman', false)
            ->where('tec.deleted_at IS NULL');

        $query = $this->db->table($this->table . ' tp')
            ->select([
                'tp.*',
                'tsj.no_surat_jalan',
                'to2.id_order',
                'to2.no_order',
                'to2.no_ref',
                'to2.tanggal_order',
                'tp2.nama_pelanggan',
                'tp2.kota as origin',
                'cl.choice_name as layanan',
                'tl.leadtime',
                'tl.biaya_paket',
                'tl.divider',
                'tl.minimum',
                'tl.bill_type',
                'tp3.nama_penerima',
                'tp.created_at',
                'driver.username as driver_name',
                'tr.plate_number',
                'tv.short_name as vendor_name',
                'tv2.short_name as sub_vendor_name',
                '(
                    select MAX(tb_barang.satuan) as satuan
                    from tb_detail_order
                    LEFT JOIN tb_barang ON tb_detail_order.id_barang = tb_barang.id_barang
                    where tb_detail_order.id_order = to2.id_order
                )as satuan',
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
                    FROM tb_order
                        LEFT JOIN tb_detail_order ON tb_order.id_order = tb_detail_order.id_order
                        LEFT JOIN tb_barang ON tb_detail_order.id_barang = tb_barang.id_barang
                        LEFT JOIN tb_layanan ON tb_order.id_layanan = tb_layanan.id_layanan
                        LEFT JOIN detail_order_biaya ON tb_detail_order.id_detail_order = detail_order_biaya.id_detail_order AND detail_order_biaya.id_biaya = 9
                        LEFT JOIN biaya_tambahan ON detail_order_biaya.id_biaya = biaya_tambahan.id_biaya
                    where tb_order.id_order = to2.id_order
                    GROUP BY tb_order.id_order
                )as biaya_packing',
                'CASE 
                    WHEN (
                        DATEDIFF(tp.tanggal_terima, to2.tanggal_order)
                        - FLOOR((DATEDIFF(tp.tanggal_terima, to2.tanggal_order) + WEEKDAY(to2.tanggal_order)) / 7)
                        - CASE 
                            WHEN WEEKDAY(tp.tanggal_terima) = 6 THEN 1
                        ELSE 0
                        END
                        ) <= tl.leadtime THEN "Ontime" 
                    WHEN (
                        DATEDIFF(tp.tanggal_terima, to2.tanggal_order)
                        - FLOOR((DATEDIFF(tp.tanggal_terima, to2.tanggal_order) + WEEKDAY(to2.tanggal_order)) / 7)
                        - CASE 
                            WHEN WEEKDAY(tp.tanggal_terima) = 6 THEN 1
                        ELSE 0
                        END
                        ) > tl.leadtime THEN "Not Ontime" 
                    ELSE "On Process" 
                END AS performance',
                '(
                    DATEDIFF(tp.tanggal_terima, to2.tanggal_order)
                    - FLOOR((DATEDIFF(tp.tanggal_terima, to2.tanggal_order) + WEEKDAY(to2.tanggal_order)) / 7)
                    - CASE 
                        WHEN WEEKDAY(tp.tanggal_terima) = 6 THEN 1
                    ELSE 0
                    END
                ) AS lt_actual',
                "({$subqueryKoli->getCompiledSelect()}) AS koli",
                "({$subqueryBerat->getCompiledSelect()}) AS berat",
                "({$subqueryVolume->getCompiledSelect()}) AS volume",
                "({$subqueryCekInvoice->getCompiledSelect()}) AS in_invoice",
                "({$subqueryCekNoInvoice->getCompiledSelect()}) AS no_invoice",
                "({$subqueryAdditional->getCompiledSelect()}) AS additional_cost",
            ], false)
            ->join('tb_surat_jalan tsj', 'tsj.id_surat_jalan = tp.surat_jalan_id AND tsj.deleted_at IS NULL', 'left')
            ->join('tb_order to2', 'to2.id_order = tsj.id_order', 'left')
            ->join('tb_pelanggan tp2', 'tp2.id_pelanggan = to2.id_pelanggan', 'left')
            ->join('tb_penerima tp3', 'tp3.id_penerima = to2.id_penerima', 'left')
            ->join('tb_layanan tl', 'tl.id_layanan = to2.id_layanan', 'left')
            ->join('choice_list cl', 'cl.id = tl.layanan', 'left')
            ->join('tb_user driver', 'driver.id = tp.kode_kurir', 'left')
            ->join('trucks AS tr', 'tr.id = tsj.truck_id', 'left')
            ->join('tb_manifest_detail tmd', 'tmd.surat_jalan_id = tsj.id_surat_jalan and tmd.status = 1', 'left')
            ->join('tb_manifest tm', 'tm.manifest_id = tmd.manifest_id', 'left')
            ->join('tb_vendor tv', 'tv.vendor_id = tm.vendor_id', 'left')
            ->join('tb_vendor tv2', 'tv2.vendor_id = tmd.sub_vendor_id', 'left')
            ->where('tp.deleted_at IS NULL')
            ->where('tp.surat_jalan_id !=', 0);

        if ($date_start && $date_end) {
            $query->where('to2.tanggal_order >=', $date_start)
                ->where('to2.tanggal_order <=', $date_end);
        }

        if ($pengirim) {
            $query->where('to2.id_pelanggan', $pengirim);
        }

        if ($penerima) {
            $query->where('to2.id_penerima', $penerima);
        }

        if ($layanan) {
            $query->where('tl.layanan', $layanan);
        }
        if (!empty($performance)) {
            $query->having('performance', $performance);
        }

        if ($role == 'PIC RELASI') {
            $query->whereIn('tp2.id_pelanggan', $user_relation);
        }

        $query->orderBy('tp.tanggal_kirim', 'DESC');

        // echo $query->getCompiledSelect();
        // exit;
        return $query->get()->getResultArray();
    }
    public function update_shipment($id, $data)
    {
        $builder = $this->db->table($this->table);
        $builder->where('id_pengiriman', $id);
        $builder->update($data);
    }
    public function getPengirimanToInvoice($id)
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

        $query = $this->db->table($this->table . ' tp')
            ->select([
                'tp.*',
                'tsj.no_surat_jalan',
                'to2.no_order',
                'tp2.nama_pelanggan',
                'cl.choice_name as layanan',
                'tp3.nama_penerima',
                'tp.created_at',
                'driver.username as driver_name',
                'tv.short_name as vendor_name',
                'tv2.short_name as sub_vendor_name',
                "({$subqueryKoli->getCompiledSelect()}) AS koli",
                "({$subqueryBerat->getCompiledSelect()}) AS berat",
                'tl.minimum'
            ], false)
            ->join('tb_surat_jalan tsj', 'tsj.id_surat_jalan = tp.surat_jalan_id', 'left')
            ->join('tb_order to2', 'to2.id_order = tsj.id_order', 'left')
            ->join('tb_pelanggan tp2', 'tp2.id_pelanggan = to2.id_pelanggan', 'left')
            ->join('tb_penerima tp3', 'tp3.id_penerima = to2.id_penerima', 'left')
            ->join('tb_layanan tl', 'tl.id_layanan = to2.id_layanan', 'left')
            ->join('choice_list cl', 'cl.id = tl.layanan', 'left')
            ->join('tb_user driver', 'driver.id = tp.kode_kurir', 'left')
            ->join('tb_manifest_detail tmd', 'tmd.surat_jalan_id = tsj.id_surat_jalan and tmd.status = 1', 'left')
            ->join('tb_manifest tm', 'tm.manifest_id = tmd.manifest_id', 'left')
            ->join('tb_vendor tv', 'tv.vendor_id = tm.vendor_id', 'left')
            ->join('tb_vendor tv2', 'tv2.vendor_id = tmd.sub_vendor_id', 'left')
            ->join('detail_invoices di', 'di.pengiriman_id = tp.id_pengiriman and di.deleted_at is null', 'left')
            ->where('tp.deleted_at IS NULL')
            ->where('di.id IS NULL')
            ->where('tp.surat_jalan_id !=', 0)
            ->where('to2.id_billto =', $id);

        // echo $query->getCompiledSelect(); // Debug query
        // exit;
        return $query->get()->getResultArray();
    }

    public function getMonthlyShipmentsNew($year)
    {
        $id = session()->get('id');
        $role = session()->get('role');
        $cond = '';

        // kalo tidak ada role artinya dia login langsung sebagai pelanggan
        if (!$role) {
            $cond = ' AND id_pelanggan = ' . $id;
        }

        if ($role == 'PIC RELASI') {
            $db = \Config\Database::connect();
            // Subquery
            $user_relation = $db->table('relation_user')
                ->select('relation_id')
                ->where('user_id', $id);

            $cond = ' AND id_pelanggan IN (' . $user_relation->getCompiledSelect() . ')';
        }

        $sql = "
            WITH months AS (
                SELECT 1 AS month UNION ALL
                SELECT 2 UNION ALL
                SELECT 3 UNION ALL
                SELECT 4 UNION ALL
                SELECT 5 UNION ALL
                SELECT 6 UNION ALL
                SELECT 7 UNION ALL
                SELECT 8 UNION ALL
                SELECT 9 UNION ALL
                SELECT 10 UNION ALL
                SELECT 11 UNION ALL
                SELECT 12
            )
            SELECT 
                m.month,
                COALESCE(COUNT(v.id_pengiriman), 0) AS total_pengiriman,
                COALESCE(SUM(v.koli), 0) AS total_koli,
                COALESCE(SUM(ROUND(v.berat, 0)), 0) AS total_kg
            FROM months m
            LEFT JOIN view_pengiriman_summary v 
                ON MONTH(v.tanggal_order) = m.month
            AND YEAR(v.tanggal_order) = '$year'
            AND month(v.tanggal_order) >= 08
            $cond
            GROUP BY m.month
            ORDER BY m.month
        ";

        // echo session()->get('role');
        // echo '<br>';
        // echo $sql;
        // die;

        return $this->db->query($sql)->getResultArray();
    }

    public function getPengirimanLama($start, $end, $shipper, $consignee, $layanan, $performance)
    {
        $role = session()->get('role');
        if ($role == 'PIC RELASI') {
            $db1 = \Config\Database::connect();

            // Subquery
            $user_relation = $db1->table('relation_user')
                ->select('relation_id')
                ->where('user_id', session()->get('id'))
                ->get()
                ->getResultArray();

            // Ubah ke array 1 dimensi
            $user_relation = array_column($user_relation, 'relation_id');
        }

        $db = \Config\Database::connect('db2');
        $builder = $db->table('tb_pengiriman tp');

        $builder->select('
            0 AS in_invoice,
            tsj.no_surat_jalan,
            to2.no_ref,
            to2.tanggal_order,
            tp2.kota AS origin,
            tp3.nama_penerima,
            tl.leadtime,
            tk.nopol AS plate_number,
            tl.layanan,
            tl.biaya_paket,
            IF(do2.total_hitung < tl.minimum, tl.minimum, do2.total_hitung) AS total_hitung,
            do2.total_qty AS koli,
            do2.total_berat AS berat,
            do2.total_volume AS volume,
            do2.satuan,
            do2.total_biaya_packing AS biaya_packing,
            tp.id_pengiriman,
            tp.tanggal_terima,
            tp.waktu_terima,
            tp.tanggal_kirim,
            tp.insurance,
            tp.surcharge,
            tp.dto,
            (
                DATEDIFF(tp.tanggal_terima, to2.tanggal_order) -- Total hari tanpa tanggal kirim
                - FLOOR((DATEDIFF(tp.tanggal_terima, to2.tanggal_order) + WEEKDAY(to2.tanggal_order)) / 7) -- Kurangi jumlah minggu penuh (hari Minggu)
                - CASE 
                    WHEN WEEKDAY(tp.tanggal_terima) = 6 THEN 1 -- Jika tanggal terima adalah hari Minggu
                    ELSE 0
                END
            ) AS lt_actual,
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
        if ($performance == "On Process") {
            $performance = "Pending";
        }
        if (!empty($performance)) {
            $builder->having('performance', $performance);
        }

        if ($role == 'PIC RELASI') {
            $builder->whereIn('tp2.id_pelanggan', $user_relation);
        }

        $builder->orderBy('to2.tanggal_order', 'ASC');

        // $sql = $builder->getCompiledSelect();

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        // exit;

        $query = $builder->get();

        return $query->getResultArray();
    }
}
