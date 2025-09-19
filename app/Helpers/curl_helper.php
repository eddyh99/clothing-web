<?php

if (!function_exists('call_api')) {
    function call_api($method, $url, $data = [], $token = null, $options = [], $hasRefreshed = false)
    {
        $accessToken = $token ?? ($_COOKIE['access_token'] ?? null);

        // Build query string kalau GET
        if (strtoupper($method) === 'GET' && !empty($data)) {
            $url .= '?' . http_build_query($data);
        }

        $ch = curl_init($url);

        $headers = ['Accept: application/json', 'Content-Type: application/json'];
        if ($accessToken && is_string($accessToken)) {
            $headers[] = 'Authorization: Bearer ' . $accessToken;
        }

        if (isset($options['headers']) && is_array($options['headers'])) {
            foreach ($options['headers'] as $key => $value) {
                $headers[] = "$key: $value";
            }
        }

        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        // Method handler
        switch (strtoupper($method)) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                break;
        }

        $response  = curl_exec($ch);
        $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            return (object) [
                'code'    => 500,
                'error'   => "cURL Error: {$curlError}",
                'data'    => null,
                'message' => null,
            ];
        }

        $result = json_decode($response, true);

        // Kalau bukan JSON valid
        if (!is_array($result)) {
            return (object) [
                'code'    => $httpCode,
                'error'   => $httpCode >= 400 ? 'ApiError' : null,
                'data'    => null,
                'message' => $response,
            ];
        }

        // --- CASE SUCCESS ---
        if ($httpCode >= 200 && $httpCode < 300) {
            return (object) [
                'code'    => $httpCode,
                'error'   => null,
                'data'    => $result, // 🔥 seluruh isi response API disimpan
                'message' => $result['message'] ?? 'Success',
            ];
        }

        // --- CASE ERROR ---
        return (object) [
            'code'    => $httpCode,
            'error'   => $result['error'] ?? 'ApiError',
            'data'    => $result,
            'message' => $result['message'] ?? 'Unknown error',
        ];
    }
}
