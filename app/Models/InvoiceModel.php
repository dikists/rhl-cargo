<?php

namespace App\Models;

use CodeIgniter\Model;

class InvoiceModel extends Model
{
    protected $table            = 'invoices';
    protected $primaryKey       = 'id';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'invoice_number',
        'id_pelanggan',
        'issue_date',
        'due_date',
        'total_amount',
        'notes',
        'tax_invoice_number',
        'rtax_id',
        'rtaxpph_id',
        'account_id',
        'ppn',
        'pph',
        'status',
        'source',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';

    // Method untuk mendapatkan semua invoice
    public function getInvoices($id = null, $get = null)
    {
        if (isset($get['type']) && $get['type'] == 'export_coretax') {
            $sql = "WITH RECURSIVE
                        SplitCTE AS (
                            SELECT
                                receipt_export_id,
                                CAST(
                                    SUBSTRING_INDEX(receipt_export_detail, '|', 1) AS UNSIGNED
                                ) AS detail,
                                SUBSTRING(
                                    receipt_export_detail,
                                    LENGTH(
                                        SUBSTRING_INDEX(receipt_export_detail, '|', 1)
                                    ) + 2
                                ) AS remaining_detail
                            FROM receipt_export
                            WHERE
                                receipt_export_source = 'coretax'
                                AND receipt_export.receipt_export_detail != ''
                            UNION ALL
                            SELECT
                                receipt_export_id,
                                CAST(
                                    SUBSTRING_INDEX(remaining_detail, '|', 1) AS UNSIGNED
                                ) AS detail,
                                SUBSTRING(
                                    remaining_detail,
                                    LENGTH(
                                        SUBSTRING_INDEX(remaining_detail, '|', 1)
                                    ) + 2
                                ) AS remaining_detail
                            FROM SplitCTE
                            WHERE
                                remaining_detail <> ''
                        )
                    SELECT
                        i.*, 
                        tp.nama_pelanggan,
                        (select rtax_value from relation_tax where rtax_id = i.rtax_id) AS ppn,
                        (select percent from relation_pph where id = i.rtaxpph_id) AS pph
                    FROM
                        invoices i
                    LEFT JOIN tb_pelanggan tp on tp.id_pelanggan = i.id_pelanggan
                    WHERE
                        i.deleted_at IS NULL AND
                        i.issue_date >= '2024-09-01' AND
                        id NOT IN (
                            SELECT
                                detail
                            FROM
                                SplitCTE
                            ORDER BY receipt_export_id
                        )
                    ORDER BY i.issue_date DESC";

            $query = $this->db->query($sql);
            return $query->getResultArray();
        } else {

            $role = session()->get('role');
            if ($role == 'PIC RELASI') {
                $db = \Config\Database::connect();

                // Subquery
                $user_relation = $db->table('relation_user')
                    ->select('relation_id')
                    ->where('user_id', session()->get('id'));
            }

            $subqueryPpn = $this->db->table('relation_tax rt')
                ->select('rtax_value')
                ->where('rt.rtax_id', 'i.rtax_id', false);
            $subqueryPph = $this->db->table('relation_pph rp')
                ->select('percent')
                ->where('rp.id', 'i.rtaxpph_id', false);
            $subqueryDetail = $this->db->table('detail_invoices dis')
                ->selectCount('dis.id')
                ->where('dis.invoice_id', 'i.id', false)
                ->where('dis.deleted_at is null');
            $builder = $this->db->table($this->table . ' i');
            $builder->select(
                [
                    '
                    i.*,
                    ba.bank_name as bank_name,
                    ba.account_holder_name as holder_name,
                    ba.account_number as bank_number,
                    ba.signatory as signatory,
                    tp.nama_pelanggan,
                    tp.alamat_pelanggan',
                    "({$subqueryPpn->getCompiledSelect()}) AS ppn",
                    "({$subqueryPph->getCompiledSelect()}) AS pph",
                    "({$subqueryDetail->getCompiledSelect()}) AS jumlah_detail"
                ],
                false
            );
            $builder->join('tb_pelanggan tp', 'tp.id_pelanggan = i.id_pelanggan', 'left');
            $builder->join('bank_accounts ba', 'ba.id = i.account_id', 'left');
            $builder->where('tp.status', 1);
            $builder->where('i.deleted_at is null');
            $builder->where('i.issue_date >=', '2024-09-01');

            if (isset($get['date_start']) && isset($get['date_end'])) {
                $builder->where('i.issue_date >=', $get['date_start']);
                $builder->where('i.issue_date <=', $get['date_end']);
            }
            if (isset($get['pengirim']) && $get['pengirim'] != '') {
                $builder->where('tp.id_pelanggan', $get['pengirim']);
            }

            if ($id) {
                $builder->where('i.id', $id);
            }

            if ($role == 'PIC RELASI') {
                $builder->whereIn('tp.id_pelanggan', $user_relation);
            }

            $builder->orderBy('i.issue_date', 'DESC');

            // Debug query
            // echo $builder->getCompiledSelect();
            // exit;

            return $id ? $builder->get()->getRowArray() : $builder->get()->getResultArray();
        }
    }

    // Method untuk mendapatkan invoice yang belum dihapus (non-soft deleted)
    public function getActiveInvoices()
    {
        return $this->where('deleted_at', null)->findAll();
    }
    // Method untuk mendapatkan invoice berdasarkan pelanggan yang aktif
    public function getInvoicePelanggan($id)
    {
        $builder = $this->db->table($this->table . ' i'); // Menggunakan properti $table
        $builder->select('i.*, tp.nama_pelanggan, rt.rt_number');
        $builder->join('tb_pelanggan tp', 'tp.id_pelanggan = i.id_pelanggan', 'left');
        $builder->join('receipt_tax rt', 'rt.rt_receipt_number = i.invoice_number', 'left');
        $builder->where('tp.status', 1);
        $builder->where('i.deleted_at is null');
        $builder->where('i.issue_date >=', '2024-09-01');
        $builder->where('i.id_pelanggan', $id);
        $builder->orderBy('i.issue_date', 'DESC');
        $query = $builder->get();

        return $query->getResultArray();
    }
    public function getDataInvoices($id)
    {
        return $this->select('i.*, tp.nama_pelanggan, tp.alamat_pelanggan, tp.telepon_pelanggan')
            ->from('invoices i')
            ->join('tb_pelanggan tp', 'tp.id_pelanggan = i.id_pelanggan', 'left')
            ->where('tp.status', 1)
            ->where('i.deleted_at', null)
            ->where('i.id', $id)
            ->first(); // Mengambil satu baris
    }
    public function getLastInvoiceNumberOnly()
    {
        $builder = $this->db->table('invoices');
        $builder->select("CAST(SUBSTRING_INDEX(invoice_number, '/', 1) AS UNSIGNED) AS last_number", false);
        $builder->where('YEAR(issue_date)', date('Y'));
        $builder->like('invoice_number', '%/%'); // hanya ambil format pakai '/'
        $builder->orderBy('id', 'DESC');
        $builder->limit(1);
        $query = $builder->get();

        return $query->getRowArray() ?? ['last_number' => 0];
    }

    public function getInvoiceExport($id)
    {
        $sql = "SELECT invoices.*, 
                    (select rt.rtax_value from relation_tax rt where rt.rtax_id = invoices.rtax_id) AS ppn,
                    (select percent from relation_pph where id = invoices.rtaxpph_id) AS pph,
                    tp.nama_pelanggan, 
                    tp.alamat_pelanggan, 
                    tp.kota, 
                    tp.telepon_pelanggan, 
                    tp.npwp
                FROM invoices
                left join tb_pelanggan tp on tp.id_pelanggan = invoices.id_pelanggan
                WHERE id in ('" . implode("','", $id) . "')";
        $query = $this->db->query($sql);
        return $query->getResultArray();
    }
}
