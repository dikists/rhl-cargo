<?php

if (!function_exists('sendWa')) {
    function sendWa(string $phone, string $message)
    {
        $token = env('WABLAS_TOKEN');
        $url = 'https://jkt.wablas.com/api/send-message';

        $payload = [
            'phone' => $phone,
            'message' => $message,
        ];

        $headers = [
            'Authorization: ' . $token,
            'Content-Type: application/json'
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_POST, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}

if (!function_exists('sendWaGroup')) {
    function sendWaGroup(string $phone, string $message)
    {
        $token = env('WABLAS_TOKEN');
        $url = 'https://jkt.wablas.com/api/send-message';

        $payload = [
            'phone' => $phone,
            'message' => $message,
            'isGroup' => "true"
        ];

        $headers = [
            'Authorization: ' . $token,
            'Content-Type: application/json'
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}
