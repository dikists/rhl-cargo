<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class CorsFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Handle preflight request
        if ($request->getMethod() === 'options') {
            $response = service('response');
            $response->setHeader('Access-Control-Allow-Origin', '*')
                     ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE')
                     ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
            return $response;
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $response->setHeader('Access-Control-Allow-Origin', '*')
                 ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE')
                 ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    }
}
