<?php

namespace App\Controllers\Api;

use App\Models\AdminModel;
use App\Models\PengirimanModel;
use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;
use App\Models\ViewPengirimanSummaryModel;

class Login extends BaseController
{
    use ResponseTrait;

    protected $adminModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
    }

    public function index()
    {
        $request = $this->request->getJSON(true); // ambil data JSON sebagai array
        $username = $request['username'] ?? null;
        $password = $request['password'] ?? null;

        if (!$username || !$password) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Username dan password harus diisi'
            ]);
        }

        $cek = $this->adminModel->getDataAdmin($username);

        if (!$cek) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Username tidak ditemukan'
            ]);
        }

        $pass = $cek['password'];
        if (!password_verify($password, $pass)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Username atau password salah'
            ]);
        }

        // jika login berhasil
        $session = session();
        $ses_data = [
            'id'        => $cek['id'],
            'username'  => $cek['username'],
            'role'      => $cek['role_name'],
            'logged_in' => true,
            'admin'     => $cek['role_name'] === 'Admin' ? 'Y' : 'N'
        ];
        $session->set($ses_data);

        // kirim response JSON sesuai permintaan client
        return $this->response->setJSON([
            'success' => true,
            'user' => [
                'id'       => $cek['id'],
                'username' => $cek['username'],
                'nama'     => $cek['full_name'], // bisa diganti dengan field nama asli jika ada
                'role'     => $cek['role_name'],
            ]
        ]);
    }
}
