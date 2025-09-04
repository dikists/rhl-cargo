<?php

namespace App\Controllers;

use App\Models\OngkirModel;

class OngkirController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Cek Ongkir'
        ];
        return view('cekongkir/form', $data);
    }

    public function getProvinces()
    {
        $data = rajaongkir_request('destination/province');
        return $this->response->setJSON($data);
    }

    public function getCities($province_id)
    {
        $data = rajaongkir_request('destination/city/' . $province_id);
        return $this->response->setJSON($data);
    }

    // public function getCost()
    // {
    //     $origin = $this->request->getPost('origin');
    //     $destination = $this->request->getPost('destination');
    //     $weight = $this->request->getPost('weight');
    //     $courier = $this->request->getPost('courier');

    //     $data = rajaongkir_request('cost', [
    //         'origin' => $origin,
    //         'destination' => $destination,
    //         'weight' => $weight,
    //         'courier' => $courier
    //     ], 'POST');

    //     return $this->response->setJSON($data);
    // }
    public function getCost()
    {
        $origin = $this->request->getPost('origin');
        $destination = $this->request->getPost('destination');
        $weight = $this->request->getPost('weight');
        $courier = $this->request->getPost('courier');
        $kota_asal_name = $this->request->getPost('kota_asal_name');
        $kota_tujuan_name = $this->request->getPost('kota_tujuan_name');


        // echo "<pre>";
        // print_r($this->request->getPost());
        // echo "</pre>";

        // Panggil Komerce endpoint baru
        $rajaongkir = rajaongkir_request('calculate/district/domestic-cost', [
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => $courier,
            'price' => 'lowest'
        ], 'POST');

        $data_ongkir = [];

        // echo "<pre>";
        // print_r($rajaongkir);
        // echo "</pre>";
        // die;

        if (!isset($rajaongkir['error']) && !empty($rajaongkir['data'])) {
            $model = new OngkirModel();
            $weight_kg = $weight / 1000;

            foreach ($rajaongkir['data'] as $item) {
                $cost = $item['cost'];
                $etd = $item['etd'];
                $service = $item['service'];
                $courier_code = strtoupper($item['code']); // <-- INI YANG BENAR

                // Cek apakah sudah ada
                $exists = $model->where([
                    'origin_city_id' => $origin,
                    'destination_city_id' => $destination,
                    'courier' => $courier_code,
                    'service' => $service,
                    'weight' => $weight_kg
                ])->first();

                $data_ongkir[] = $exists;

                if (!$exists) {
                    $model->save([
                        'origin_city_id' => $origin,
                        'origin_city_name' => $kota_asal_name,
                        'destination_city_id' => $destination,
                        'destination_city_name' => $kota_tujuan_name,
                        'courier' => $courier_code,
                        'service' => $service,
                        'cost' => $cost,
                        'etd' => $etd,
                        'weight' => $weight_kg
                    ]);
                }
            }
        }

        $rajaongkir['debug'] = $data_ongkir;

        return $this->response->setJSON($rajaongkir);
    }

    public function history()
    {
        $model = new OngkirModel();
        $data['histories'] = $model->orderBy('created_at', 'DESC')->findAll();
        $data['title'] = 'History';
        return view('cekongkir/history', $data);
    }
}
