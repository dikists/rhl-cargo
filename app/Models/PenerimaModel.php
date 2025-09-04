<?php

namespace App\Models;

use CodeIgniter\Model;

class PenerimaModel extends Model
{
     protected $table = 'tb_penerima';
     protected $primaryKey = 'id_penerima';

     protected $allowedFields = [
          'id_pelanggan',
          'email_penerima',
          'nama_penerima',
          'telepon_penerima',
          'alamat_penerima',
          'provinsi_id',
          'kabupaten_id',
          'kecamatan_id',
          'desa_id'
     ];

     public function getPenerimaByCustomerId($id)
     {
          return $this->select('tb_penerima.*, wilayah_kabupaten.nama as kabupaten')
               ->join('wilayah_kabupaten', 'wilayah_kabupaten.id = tb_penerima.kabupaten_id')
               ->where('tb_penerima.id_pelanggan', $id)
               ->findAll();
     }
     public function getPenerima($id = null)
     {
          if ($id) {
               return $this->select('tb_penerima.*, tb_pelanggan.nama_pelanggan')
                    ->join('tb_pelanggan', 'tb_pelanggan.id_pelanggan = tb_penerima.id_pelanggan')
                    ->where('id_penerima', $id)
                    ->findAll();
          }

          return $this->select('tb_penerima.*, tb_pelanggan.nama_pelanggan')
               ->join('tb_pelanggan', 'tb_pelanggan.id_pelanggan = tb_penerima.id_pelanggan')
               ->findAll();
     }
     public function getPenerimaByPengirim($id)
     {
          return $this->select('tb_penerima.*, tb_pelanggan.nama_pelanggan,wilayah_provinsi.nama as provinsi, wilayah_kabupaten.nama as kabupaten, wilayah_kecamatan.nama as kecamatan')
          ->join('tb_pelanggan', 'tb_pelanggan.id_pelanggan = tb_penerima.id_pelanggan')
          ->join('wilayah_provinsi', 'wilayah_provinsi.id = tb_penerima.provinsi_id')
          ->join('wilayah_kabupaten', 'wilayah_kabupaten.id = tb_penerima.kabupaten_id')
          ->join('wilayah_kecamatan', 'wilayah_kecamatan.id = tb_penerima.kecamatan_id')
          ->where('tb_pelanggan.id_pelanggan', $id)
          ->get()->getResultArray();
     }
     public function getPengirimPenerima($pengirim, $penerima)
     {
          return $this->select('tb_penerima.*, tb_pelanggan.nama_pelanggan,wilayah_provinsi.nama as provinsi, wilayah_kabupaten.nama as kabupaten, wilayah_kecamatan.nama as kecamatan')
          ->join('tb_pelanggan', 'tb_pelanggan.id_pelanggan = tb_penerima.id_pelanggan')
          ->join('wilayah_provinsi', 'wilayah_provinsi.id = tb_penerima.provinsi_id')
          ->join('wilayah_kabupaten', 'wilayah_kabupaten.id = tb_penerima.kabupaten_id')
          ->join('wilayah_kecamatan', 'wilayah_kecamatan.id = tb_penerima.kecamatan_id')
          ->where('tb_pelanggan.id_pelanggan', $pengirim)
          ->where('tb_penerima.id_penerima', $penerima)
          ->get()->getResultArray();
     }
}
