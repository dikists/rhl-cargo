<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthTokenFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // --- Ambil Authorization header dari CI4 Request ---
        $authHeader = $request->getHeaderLine('Authorization');

        // --- Fallback 1: SERVER variable standar ---
        if (!$authHeader && isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        }

        // --- Fallback 2: Server tertentu (mis. nginx + fastcgi) ---
        if (!$authHeader && isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $authHeader = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        }

        // --- Fallback 3: apache_request_headers (kadang lowercase) ---
        if (!$authHeader && function_exists('apache_request_headers')) {
            $headers = apache_request_headers();

            // normalisasi semua key ke lowercase
            $normalized = [];
            foreach ($headers as $key => $value) {
                $normalized[strtolower($key)] = $value;
            }

            if (isset($normalized['authorization'])) {
                $authHeader = $normalized['authorization'];
            }
        }

        // --- Validasi format "Bearer <token>" ---
        if (!$authHeader || !preg_match('/Bearer\s+(\S+)/i', $authHeader, $matches)) {
            return service('response')
                ->setJSON([
                    'status'     => false,
                    'message'    => 'Token tidak ditemukan atau format salah',
                    'authHeader' => $authHeader, // debug
                ])
                ->setStatusCode(401);
        }

        $token = $matches[1];

        // --- Validasi token dengan environment ---
        if ($token !== getenv('API_ACCESS_TOKEN')) {
            return service('response')
                ->setJSON([
                    'status'  => false,
                    'message' => 'Token tidak valid',
                ])
                ->setStatusCode(401);
        }

        // Token valid → lanjut request
        return;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu diubah
    }
}
