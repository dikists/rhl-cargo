<?php

namespace App\Controllers;

class Home extends BaseController
{
    // protected $userModel;
    // public function __construct()
    // {
    //     $this->userModel = new UserModel();
    // }
    public function index()
    {

        // cara konek db tanpa model
        // $db = \Config\Database::connect();
        // $builder = $db->table('tb_pelanggan');
        // $builder->select('*');
        // $query = $builder->get();
        // $data['pelanggan'] = $query->getResult();
        // dd($data['pelanggan']);
        
        $user = $this->userModel->findAll();
        $data = [
            'title' => 'tes',
            'users' => $user
        ];
        echo view('tes',$data);
    }
}
