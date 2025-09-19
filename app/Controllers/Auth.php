<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Auth extends BaseController
{
    public function index()
    {
        return view('layout/page-wrapper', [
            'title'   => 'Clothing Login',
            'content' => 'login/signin',
            'extra'   => 'login/js/_js_index'
        ]);
    }

    public function loginProcess()
    {
        $validation = $this->validation;
        $validation->setRules([
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]|max_length[255]',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            log_message('error', '[LOGIN] Validasi gagal: ' . json_encode($validation->getErrors()));

            return redirect()->back()
                ->withInput()
                ->with('error', $validation->getErrors());
        }

        $post = $this->request->getPost();
        $payload = [
            'email'       => esc($post['email']),
            'password'    => $post['password'], // jangan di-escape
            'ip_address'  => $this->request->getIPAddress(),
            'remember_me' => isset($post['remember_me']) ? 1 : 0,
        ];

        // 📝 Logging payload sebelum dikirim
        log_message('debug', '[LOGIN] Payload: ' . json_encode($payload));

        $response = call_api('POST', URLAPI . '/login', $payload);

        // 📝 Logging response dari API
        log_message('debug', '[LOGIN] Response API: ' . json_encode($response));

        if ($response && $response->code === 200 && isset($response->data['access_token'])) {
            log_message('info', '[LOGIN] Berhasil login untuk email: ' . $payload['email']);

            // Set data user ke session
            $this->session->set('user', $response->data['user'] ?? []);
            $this->session->set('access_token', $response->data['access_token']);
            $this->session->set('refresh_token', $response->data['refresh_token']);

            // 🔍 Logging isi session setelah set
            log_message('debug', '[LOGIN] Session user: ' . json_encode($this->session->get('user')));
            log_message('debug', '[LOGIN] Session access_token: ' . substr($this->session->get('access_token'), 0, 40) . '...');
            log_message('debug', '[LOGIN] Session refresh_token: ' . substr($this->session->get('refresh_token'), 0, 40) . '...');

            // Access Token cookie
            $accessTokenResult = setcookie('access_token', $response->data['access_token'], [
                'expires'  => time() + ($response->data['expires_in'] ?? 3600),
                'path'     => '/',
                'secure'   => true,
                'httponly' => true,
                'samesite' => 'Strict',
            ]);
            log_message('debug', '[LOGIN] Setcookie access_token: ' . ($accessTokenResult ? 'OK' : 'FAILED'));

            // Refresh Token cookie
            $refreshTokenResult = setcookie('refresh_token', $response->data['refresh_token'], [
                'expires'  => time() + ($response->data['remember_for'] ?? 86400),
                'path'     => '/',
                'secure'   => true,
                'httponly' => true,
                'samesite' => 'Strict',
            ]);
            log_message('debug', '[LOGIN] Setcookie refresh_token: ' . ($refreshTokenResult ? 'OK' : 'FAILED'));

            // 🔍 Logging sebelum redirect
            $targetUrl = site_url('members/dashboard');
            log_message('debug', '[LOGIN] Redirecting ke: ' . $targetUrl);

            return redirect()->to($targetUrl);
        }

        log_message('error', '[LOGIN] Gagal login. Response: ' . json_encode($response));

        return redirect()->back()
            ->withInput()
            ->with('error', $response->message ?? 'Login failed. Please try again.');
    }

    public function logoutProcess()
    {
        $accessToken = $_COOKIE['access_token'] ?? null;

        if ($accessToken) {
            $headers = [
                'Authorization: Bearer ' . $accessToken,
                'Accept: application/json',
            ];
            call_api('POST', URLAPI . '/logout', [], $headers);
        }

        $this->clearAuthCookies();
        return redirect()->to(site_url('/'));
    }

    public function signupProcess()
    {
        $validation = $this->validation;
        $validation->setRules($this->signupRules());

        if (!$validation->withRequest($this->request)->run()) {
            log_message('error', '[SIGNUP] Validasi gagal: ' . json_encode($validation->getErrors()));

            return redirect()->back()
                ->withInput()
                ->with('error', $validation->getErrors());
        }

        $post = $this->request->getPost();
        $payload = [
            'tenant_name' => esc($post['tenant_name']),
            'email'       => esc($post['email']),
            'username'    => esc($post['username']),
            'password'    => $post['password'], // password jangan di-escape
        ];

        // 📝 Logging payload
        log_message('debug', '[SIGNUP] Payload: ' . json_encode($payload));

        $response = call_api('POST', URLAPI . '/register', $payload);

        // 📝 Logging response
        log_message('debug', '[SIGNUP] Response API: ' . json_encode($response));

        // ✅ Sesuaikan sama struktur baru call_api
        if ($response && $response->code >= 200 && $response->code < 300) {
            log_message('info', '[SIGNUP] Registrasi berhasil untuk email: ' . $payload['email']);

            // simpan email sementara untuk OTP
            $this->session->set('email_for_otp', esc($post['email']));
            log_message('debug', '[SIGNUP] Session email_for_otp: ' . $this->session->get('email_for_otp'));

            $targetUrl = site_url('signup-verif');
            log_message('debug', '[SIGNUP] Redirecting ke: ' . $targetUrl);

            return redirect()->to($targetUrl)
                ->with('success', $response->message ?? 'Registrasi berhasil. Silakan cek email untuk OTP.');
        }

        // ❌ Jika gagal
        $errorMsg = $response->message ?? 'Gagal mendaftar. Silakan coba lagi.';
        log_message('error', '[SIGNUP] Gagal registrasi. Response: ' . json_encode($response));

        return redirect()->back()
            ->withInput()
            ->with('error', $errorMsg);
    }

    public function otpActivation()
    {
        $validation = $this->validation;
        $validation->setRules([
            'otp' => [
                'label' => 'Kode OTP',
                'rules' => 'required|numeric|min_length[4]|max_length[4]',
                'errors' => [
                    'required'   => '{field} wajib diisi.',
                    'numeric'    => '{field} harus berupa angka.',
                    'min_length' => '{field} harus 4 digit.',
                    'max_length' => '{field} harus 4 digit.'
                ]
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()
                ->withInput()
                ->with('error', $validation->getErrors());
        }

        $otp = $this->request->getPost('otp');
        $response = call_api('POST', URLAPI . '/activate-otp', ['otp' => esc($otp)]);

        // --- Logging untuk debug ---
        log_message('debug', '[OTP_ACTIVATION] Request OTP: ' . $otp);
        log_message('debug', '[OTP_ACTIVATION] Response Code: ' . $response->code);
        log_message('debug', '[OTP_ACTIVATION] Response Message: ' . print_r($response->message, true));
        log_message('debug', '[OTP_ACTIVATION] Response Data: ' . print_r($response->data, true));
        log_message('debug', '[OTP_ACTIVATION] Response Error: ' . print_r($response->error, true));

        if ($response->code === 200 && $response->error === null) {
            session()->remove('email_for_otp');
            return redirect()->to(site_url('/'))
                ->with('success', $response->message ?? 'Akun berhasil diaktivasi. Silakan login!');
        }

        // Kalau error
        // $errorMsg = $response->message ?? $response->error ?? 'Gagal aktivasi OTP';
        // return redirect()->back()
        //     ->withInput()
        //     ->with('error', 'Gagal aktivasi OTP: ' . $errorMsg);

        // Ambil kode error
        $errorCode = $response->error 
                ?? ($response->data['status'] ?? 'Error');

        // Ambil pesan error yang sebenarnya dari data API
        if (!empty($response->data['messages']['message'])) {
            $errorMsg = $response->data['messages']['message'];
        } elseif (!empty($response->data['message'])) {
            $errorMsg = $response->data['message'];
        } elseif (!empty($response->message) && $response->message !== 'Unknown error') {
            $errorMsg = $response->message;
        } else {
            $errorMsg = 'Gagal aktivasi OTP';
        }

        $flashError = "Error Code: {$errorCode} {$errorMsg}";

        return redirect()->back()
            ->withInput()
            ->with('error', $flashError);
    }

    public function resendOtp()
    {
        $email = session()->get('email_for_otp');
        if (!$email) {
            return redirect()->to(site_url('signup'))->with('error', 'Session expired, silakan daftar ulang.');
        }

        $response = call_api('POST', URLAPI . '/resend-otp', ['email' => esc($email)]);

        if ($response->code === 200) {
            return redirect()->back()->with('success', 'OTP baru telah dikirim ke email.');
        }

        return redirect()->back()
            ->with('error', 'Gagal kirim ulang OTP: ' . ($response->error ?? json_encode($response->message ?? [])));
    }

    public function forgotOtpView()
    {
        return view('layout/page-wrapper', [
            'title'   => 'Forgot Password',
            'content' => 'login/forgot-password',
        ]);
    }

    public function resetPasswordOtpView()
    {
        return view('layout/page-wrapper', [
            'title'   => 'Reset Password',
            'content' => 'login/reset-password',
            'extra'   => 'login/js/_js_index'
        ]);
    }

    public function forgotPasswordOtpProcess()
    {
        $validation = $this->validation;
        $validation->setRules([
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'valid_email' => '{field} tidak valid.'
                ]
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()
                ->withInput()
                ->with('error', $validation->getErrors());
        }

        $email = $this->request->getPost('email');
        $response = call_api('POST', URLAPI . '/forgot-password', ['email' => esc($email)]);

        if ($response->code === 200) {
            return redirect()->to(site_url('reset-password'))
                ->with('success', $response->message['message'] ?? 'OTP sent to email.');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $response->message['message'] ?? $response->error ?? 'Failed to send OTP.');
    }

    public function resetPasswordOtpProcess()
    {
        $validation = $this->validation;
        $validation->setRules($this->resetPasswordRules());

        // Validasi input
        if (!$validation->withRequest($this->request)->run()) {
            $errors = $validation->getErrors();
            log_message('error', '[RESET_PASSWORD] Validation failed: ' . json_encode($errors));
            return redirect()->back()
                ->withInput()
                ->with('error', $errors);
        }

        $payload = [
            'email'    => esc($this->request->getPost('email')),
            'otp'      => esc($this->request->getPost('otp')),
            'new-password' => $this->request->getPost('password'), // Jangan escape password
        ];

        $response = call_api('POST', URLAPI . '/reset-password', $payload);

        // Jika sukses
        if ($response->code >= 200 && $response->code < 300) {
            // Pakai pesan spesifik sendiri, bukan dari API
            $successMsg = 'Password reset successful! Please login with new password.';
            log_message('info', "[RESET_PASSWORD] Success: {$successMsg}");
            return redirect()->to(site_url('/'))
                ->with('success', $successMsg);
        }
        // if ($response->code >= 200 && $response->code < 300) {
        //     $successMsg = $response->data['message'] ?? 'Password reset successful. Please login with new password.';
        //     log_message('info', "[RESET_PASSWORD] Success: {$successMsg}");
        //     return redirect()->to(site_url('/'))
        //         ->with('success', $successMsg);
        // }

        // --- ERROR HANDLING: ambil code + pesan sebenarnya ---
        $errorCode = $response->error ?? ($response->data['status'] ?? 'Error');

        if (!empty($response->data['messages']['message'])) {
            $errorMsg = $response->data['messages']['message'];
        } elseif (!empty($response->data['message'])) {
            $errorMsg = $response->data['message'];
        } elseif (!empty($response->message) && $response->message !== 'Unknown error') {
            $errorMsg = $response->message;
        } else {
            $errorMsg = 'Failed to reset password';
        }

        $flashError = "Error Code: {$errorCode} {$errorMsg}";

        // Logging error untuk trace
        log_message('error', "[RESET_PASSWORD] API Error: Code={$errorCode}, Message={$errorMsg}, Payload=" . json_encode($payload));

        return redirect()->back()
            ->withInput()
            ->with('error', $flashError);
    }
    // public function resetPasswordOtpProcess()
    // {
    //     $validation = $this->validation;
    //     $validation->setRules($this->resetPasswordRules());

    //     if (!$validation->withRequest($this->request)->run()) {
    //         return redirect()->back()
    //             ->withInput()
    //             ->with('error', $validation->getErrors());
    //     }

    //     $payload = [
    //         'email'    => esc($this->request->getPost('email')),
    //         'otp'      => esc($this->request->getPost('otp')),
    //         'password' => $this->request->getPost('password'), // Don't escape password
    //     ];

    //     $response = call_api('POST', URLAPI . '/reset-password', $payload);

    //     if ($response->code === 200) {
    //         return redirect()->to(site_url('/'))
    //             ->with('success', $response->message['message'] ?? 'Password reset successful. Please login with new password.');
    //     }

    //     return redirect()->back()
    //         ->withInput()
    //         ->with('error', $response->message['message'] ?? $response->error ?? 'Failed to reset password.');
    // }

    public function signUpView()
    {
        return view('layout/page-wrapper', [
            'title'   => 'Sign Up Clothing',
            'content' => 'login/signup',
            'extra'   => 'login/js/_js_index'
        ]);
    }

    public function signUpVerifView()
    {
        return view('layout/page-wrapper', [
            'title'   => 'Sign Up Clothing',
            'content' => 'login/signup-verif',
        ]);
    }

    private function clearAuthCookies()
    {
        // Hapus access_token
        setcookie('access_token', '', [
            'expires'  => time() - 3600,
            'path'     => '/',
            'secure'   => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);

        // Hapus refresh_token
        setcookie('refresh_token', '', [
            'expires'  => time() - 604800,
            'path'     => '/',
            'secure'   => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);

        $this->session->destroy();
    }

    /**
     * Validation rules untuk Signup
     */
    private function signupRules(): array
    {
        return [
            'tenant_name' => [
                'label' => 'Nama Tenant',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'min_length' => '{field} minimal 3 karakter.',
                    'max_length' => '{field} maksimal 100 karakter.'
                ]
            ],
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'valid_email' => '{field} tidak valid.'
                ]
            ],
            'username' => [
                'label' => 'Username',
                'rules' => 'required|min_length[3]|max_length[50]|alpha_numeric',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'min_length' => '{field} minimal 3 karakter.',
                    'max_length' => '{field} maksimal 50 karakter.',
                    'alpha_numeric' => '{field} hanya boleh berisi huruf dan angka.'
                ]
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'min_length' => '{field} minimal 8 karakter.'
                ]
            ],
            'password_confirmation' => [
                'label' => 'Konfirmasi Password',
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'matches' => '{field} tidak sama dengan password.'
                ]
            ]
        ];
    }

    /**
     * Validation rules untuk Login
     */
    private function loginRules(): array
    {
        return [
            'username' => [
                'label' => 'Username',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} wajib diisi.'
                ]
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} wajib diisi.'
                ]
            ]
        ];
    }

    /**
     * Validation rules untuk Reset Password
     */
    private function resetPasswordRules(): array
    {
        return [
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'valid_email' => '{field} tidak valid.'
                ]
            ],
            'otp' => [
                'label' => 'Kode OTP',
                'rules' => 'required|numeric|min_length[4]|max_length[4]',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'numeric' => '{field} harus berupa angka.',
                    'min_length' => '{field} harus 4 digit.',
                    'max_length' => '{field} harus 4 digit.'
                ]
            ],
            'password' => [
                'label' => 'Password Baru',
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'min_length' => '{field} minimal 8 karakter.'
                ]
            ],
            // 'password_confirmation' => [
            //     'label' => 'Konfirmasi Password',
            //     'rules' => 'required|matches[password]',
            //     'errors' => [
            //         'required' => '{field} wajib diisi.',
            //         'matches' => '{field} tidak sama dengan password.'
            //     ]
            // ]
        ];
    }
}