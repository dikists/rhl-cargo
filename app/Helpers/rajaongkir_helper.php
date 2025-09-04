<?php

function rajaongkir_request($endpoint, $params = [], $method = 'GET')
{
    $curl = curl_init();

    // Pakai env sesuai kebutuhan
    $apiKey = getenv('RAJAONGKIR_API_KEY');

    // Pilih base URL sesuai stage
    $baseUrl = 'https://rajaongkir.komerce.id/api/v1/'; // Production

    $url = $baseUrl . ltrim($endpoint, '/');

    if ($method === 'GET' && !empty($params)) {
        $url .= '?' . http_build_query($params);
    }

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => [
            "key: $apiKey",
            "Accept: application/json"
        ]
    ]);

    if ($method === 'POST') {
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
    }

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return ['error' => $err];
    } else {
        return json_decode($response, true);
    }
}
