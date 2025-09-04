<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionExtraChargeModel extends Model
{
    protected $table            = 'transaction_extra_charge';
    protected $primaryKey       = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'pengiriman_id',
        'biaya_id',
        'charge_value',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $useTimestamps = true; // aktifkan timestamps
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $useSoftDeletes = true;

    public function extraCharge($pengirimanId)
    {
        return $this->select('transaction_extra_charge.*, biaya_tambahan.jenis_biaya, biaya_tambahan.nominal, biaya_tambahan.keterangan, biaya_tambahan.tipe_tagih')
            ->join('biaya_tambahan', 'biaya_tambahan.id_biaya = transaction_extra_charge.biaya_id', 'left')
            ->where('transaction_extra_charge.pengiriman_id', $pengirimanId)
            ->findAll();
    }
}
