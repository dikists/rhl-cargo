<?php

namespace App\Controllers;

use App\Models\OfferModel;
use App\Models\ClauseModel;
use CodeIgniter\RESTful\ResourceController;

class ClauseController extends ResourceController
{
     protected $clauseModel;
     protected $offerModel;

     public function __construct()
     {
          $this->clauseModel = new ClauseModel();
          $this->offerModel = new OfferModel();
     }

     public function index()
     {
          $data = [
               'title' => 'Daftar Penawaran',
               'offers' => $this->offerModel->findAll()
          ];
          echo view('transaksi/penawaran/index', $data);
     }
}
