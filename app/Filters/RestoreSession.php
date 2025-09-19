<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class RestoreSession implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = Services::session();

        // 🔍 Ambil token dari session dulu
        $accessToken = $session->get('access_token');

        // 🔍 Kalau session kosong, coba cookie
        if (!$accessToken && isset($_COOKIE['access_token'])) {
            $accessToken = $_COOKIE['access_token'];
            log_message('debug', '[RESTORE] Ambil token dari COOKIE');
        } elseif ($accessToken) {
            log_message('debug', '[RESTORE] Ambil token dari SESSION');
        }

        // 🔍 Kalau masih kosong, coba dari Authorization Header
        if (!$accessToken) {
            $authHeader = $request->getHeaderLine('Authorization');
            if ($authHeader && str_starts_with($authHeader, 'Bearer')) {
                $accessToken = trim(substr($authHeader, 7));
                log_message('debug', '[RESTORE] Ambil token dari AUTH HEADER');
            }
        }

        // Kalau masih kosong → redirect ke halaman login
        if (!$accessToken) {
            log_message('error', '[RESTORE] Token tidak ditemukan di session/cookie/header');
            return redirect()->to('/');
        }

        log_message('debug', '[RESTORE] Access Token (prefix): ' . substr($accessToken, 0, 40) . '...');

        // 🔑 Pastikan secret JWT ada
        $secret = getenv('JWT_ACCESS_SECRET');
        if (!$secret) {
            log_message('error', '[RESTORE] JWT_ACCESS_SECRET kosong. Cek .env!');
            return redirect()->to('/');
        }

        // ✅ Decode token
        try {
            $decoded = JWT::decode($accessToken, new Key($secret, 'HS256'));

            // Simpan ke request biar bisa dipakai controller
            $request->user = $decoded;
            log_message('debug', '[RESTORE] Token valid untuk user_id: ' . ($decoded->user_id ?? 'N/A'));

        } catch (\Exception $e) {
            log_message('error', '[RESTORE] Token invalid: ' . $e->getMessage());
            return redirect()->to('/');
        }

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada aksi khusus setelah response
    }
}
