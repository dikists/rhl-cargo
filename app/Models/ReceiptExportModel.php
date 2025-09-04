<?php

namespace App\Models;

use CodeIgniter\Model;

class ReceiptExportModel extends Model
{
    protected $table            = 'receipt_export';
    protected $primaryKey       = 'receipt_export_id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'receipt_export_source',
        'receipt_export_date',
        'receipt_export_detail',
        'receipt_export_create_at',
        'receipt_export_create_by'
    ];

    public function getExportReceipt()
    {
        $sql = "SELECT re.*, (
            SELECT GROUP_CONCAT(invoice_number SEPARATOR ', ')
            FROM invoices AS i
            WHERE FIND_IN_SET(i.id, REPLACE(re.receipt_export_detail, '|', ',')) > 0
        ) AS invoice_numbers
        FROM receipt_export re
        WHERE re.receipt_export_source = 'coretax' AND
        re.receipt_export_detail != ''
        order by re.receipt_export_id desc
        ";

        $query = $this->db->query($sql);
        return $query->getResultArray();
    }
}
