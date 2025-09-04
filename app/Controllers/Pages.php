<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function index()
    {
        $data['title'] ='Home';
        return view('pages/index', $data);
    }
    public function about()
    {
        $data['title'] ='About';
        return view('pages/about', $data);
    }
    public function services()
    {
        $data['title'] ='Services';
        return view('pages/services', $data);
    }
    public function contact()
    {
        $data['title'] ='Contact';
        return view('pages/contact', $data);
    }
    public function login()
    {
        if ($this->currentUser) {
            return redirect()->to('/dashboard');
          }
        $user = $this->userModel->findAll();
        $data = [
            'title' => 'Login',
            'users' => $user
        ];
        return view('pages/login', $data);
    }
}
