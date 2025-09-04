<?php

namespace App\Models;

use CodeIgniter\Model;

class JournalModel extends Model
{
    protected $table = 'journal_entries';
    protected $allowedFields = ['journal_date', 'reference', 'journal_account_id', 'account_name', 'debit', 'credit', 'description'];

    public function getDatatables($params)
    {
        $builder = $this->db->table('journal_entries');
        $builder->select('journal_entries.*, journal_accounts.name as account_name, journal_accounts.account_code, journal_accounts.type as account_type');
        $builder->join('journal_accounts', 'journal_entries.journal_account_id = journal_accounts.id');

        // Tanggal filter
        if (!empty($params['date_start']) && !empty($params['date_end'])) {
            $builder->where('journal_entries.journal_date >=', $params['date_start']);
            $builder->where('journal_entries.journal_date <=', $params['date_end']);
        }

        // Search
        if (!empty($params['search'])) {
            $builder->groupStart()
                ->like('journal_entries.reference', $params['search'])
                ->orLike('journal_accounts.name', $params['search'])
                ->orLike('journal_accounts.account_code', $params['search'])
                ->groupEnd();
        }

        // Count total
        $totalFiltered = $builder->countAllResults(false);

        // Order
        if (!empty($params['order'])) {
            $builder->orderBy($params['order'][0]['column'], $params['order'][0]['dir']);
        }

        $builder->orderBy('journal_entries.journal_date', 'ASC');

        // Paging
        if (isset($params['length']) && $params['length'] != -1) {
            $builder->limit($params['length'], $params['start']);
        }

        // echo $builder->getCompiledSelect();
        // exit;

        $query = $builder->get();

        return [
            'data' => $query->getResult(),
            'recordsFiltered' => $totalFiltered
        ];
    }

    public function getJournal($get = null)
    {
        $builder = $this->db->table('journal_entries');
        $builder->select('journal_date, reference, journal_account_id, debit, credit, description, journal_accounts.id as account_id, journal_accounts.account_code as account_code, journal_accounts.name as account_name, journal_accounts.type as account_type');
        $builder->join('journal_accounts', 'journal_accounts.id = journal_entries.journal_account_id', 'left');
        $builder->orderBy('journal_entries.journal_date', 'ASC');
        if (isset($get['date_start']) && isset($get['date_end'])) {
            $builder->where('journal_entries.journal_date >=', $get['date_start']);
            $builder->where('journal_entries.journal_date <=', $get['date_end']);
        }
        return $builder->get()->getResultArray();
    }

    public function getBalanceSheet()
    {
        // Join dengan tabel akun untuk dapatkan tipe
        return $this->select('journal_accounts.type, journal_accounts.name, journal_accounts.account_code, 
                              SUM(journal_entries.debit) as total_debit, 
                              SUM(journal_entries.credit) as total_credit')
            ->join('journal_accounts', 'journal_accounts.id = journal_entries.journal_account_id')
            ->groupBy('journal_entries.journal_account_id')
            ->orderBy('journal_accounts.account_code', 'ASC')
            ->findAll();
    }
    public function getProfitLoss()
    {
        return $this->select('journal_accounts.type, journal_accounts.name, 
                          SUM(journal_entries.debit) as total_debit, 
                          SUM(journal_entries.credit) as total_credit')
            ->join('journal_accounts', 'journal_accounts.id = journal_entries.journal_account_id')
            ->whereIn('journal_accounts.type', ['revenue', 'expense'])
            ->groupBy('journal_entries.journal_account_id')
            ->orderBy('journal_accounts.name', 'ASC')
            ->findAll();
    }
}
