<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Setting extends BaseController
{
    /**
     * Halaman Index Setting
     */
    public function index()
    {
        $session = session();
        $user = $session->get('user'); // ambil data user dari session

        $id = $user['id'] ?? null;
        if (empty($id) || !ctype_digit((string)$id)) {
            return redirect()->to(base_url('setting'))
                ->with('failed', 'ID pengguna tidak valid.');
        }

        // Ambil data lengkap user dari API agar semua field wajib tersedia
        $responseUser = call_api('GET', URLAPI . "/v1/user/$id");
        log_message('debug', 'Response user API: ' . print_r($responseUser, true));

        $userFull = $responseUser->code === 200 ? $responseUser->message : null;

        if (!$userFull) {
            log_message('error', 'User not found via API, ID: ' . $id);
            return redirect()->to(base_url('setting'))
                ->with('failed', 'Pengguna tidak ditemukan.');
        }

        // Ambil tenant_id dari data user yang sudah lengkap
        $tenant_id = $userFull['tenant_id'] ?? null;
        log_message('debug', 'Tenant ID from API user data: ' . var_export($tenant_id, true));

        $domain_name = '';
        if (!empty($tenant_id) && ctype_digit((string)$tenant_id)) {
            // Ambil data tenant (subdomain)
            $responseTenant = call_api('GET', URLAPI . "/v1/tenant/{$tenant_id}");
            log_message('debug', 'Response tenant API: ' . print_r($responseTenant, true));

            if ($responseTenant->code === 200) {
                $tenant = $responseTenant->message;
                $domain_name = $tenant['subdomain'] ?? '';
            } else {
                log_message('warning', 'Tenant not found or inactive, tenant_id: ' . $tenant_id);
            }
        } else {
            log_message('error', 'Invalid tenant ID from user data: ' . var_export($tenant_id, true));
        }

        $mdata = [
            'title'       => 'Pengaturan Akun',
            'content'     => 'setting/index',
            'breadcrumb'  => 'Pengaturan',
            'submenu'     => 'Setting',
            'extra'       => 'setting/js/_js_index', // JS tambahan jika ada
            'user'        => $userFull,               // data user lengkap dari API
            'domain_name' => $domain_name,            // prefill subdomain tab 2
        ];

        log_message('debug', 'Data untuk view: ' . print_r($mdata, true));

        return view('layout/wrapper', $mdata);
    }

    /**
     * Simpan perubahan password (Tab 1)
     */
    public function password_save_update()
    {
        $user = session()->get('user'); 
        $id   = $user['id'] ?? null;

        log_message('debug', 'User in session (password_save_update): ' . json_encode($user));

        if (empty($id) || !ctype_digit((string) $id)) {
            log_message('error', 'Invalid user ID in password_save_update: ' . var_export($id, true));
            return redirect()->to(base_url('setting'))
                ->with('failed', 'ID pengguna tidak valid.');
        }

        // Ambil data lengkap user dari API agar semua field wajib tersedia
        $responseUser = call_api('GET', URLAPI . "/v1/user/$id");
        $userFull = $responseUser->code === 200 ? $responseUser->message : null;

        if (!$userFull) {
            return redirect()->to(base_url('setting'))
                ->with('failed', 'Pengguna tidak ditemukan.');
        }

        // Validation hanya untuk password
        $validation = $this->validation;
        $validation->setRules($this->rulesGantiPassword());

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('failed', $validation->getErrors());
        }

        // Ambil data password dari form
        $postData = $this->getPostDataGantiPassword();

        // Tambahkan semua field wajib dari API
        $postData['username']  = $userFull['username'] ?? '';
        $postData['email']     = $userFull['email'] ?? '';
        $postData['role_id']   = $userFull['role_id'] ?? '';
        $postData['branch_id'] = $userFull['branch_id'] ?? '';

        // Kirim ke API
        $response = call_api('PUT', URLAPI . "/v1/user/$id", $postData);

        if ($response->code === 200) {
            return redirect()->to(base_url('setting'))
                ->with('success', 'Password berhasil diganti!');
        }

        return redirect()->back()->withInput()->with('failed', [$response->message ?? 'Gagal mengganti password.']);
    }

    /**
     * Rules validasi password saja
     */
    private function rulesGantiPassword(): array
    {
        $rules = [];
        $password = $this->request->getPost('new_password');

        if (!empty($password)) {
            $rules['new_password']     = 'required|min_length[8]|max_length[50]|regex_match[/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).+$/]';
            $rules['confirm_password'] = 'required|matches[new_password]';
        }

        return $rules;
    }

    /**
     * Ambil data password dari form
     */
    private function getPostDataGantiPassword(): array
    {
        $data = [];
        $password = $this->request->getPost('new_password');

        if (!empty($password)) {
            $data['password'] = $password; // hashing tetap dilakukan oleh API
        }

        return $data;
    }

    /**
     * Simpan perubahan subdomain (Tab 2)
     */
    public function app_save_update()
    {
        $user = session()->get('user'); 
        $id   = $user['id'] ?? null;

        if (empty($id) || !ctype_digit((string)$id)) {
            session()->setFlashdata('active_tab', 'appSettingTab');
            return redirect()->to(base_url('setting'))
                ->with('failed', 'ID pengguna tidak valid.');
        }

        // Ambil data lengkap user dari API agar dapat tenant_id yang benar
        $responseUser = call_api('GET', URLAPI . "/v1/user/$id");
        $userFull = $responseUser->code === 200 ? $responseUser->message : null;

        if (!$userFull) {
            session()->setFlashdata('active_tab', 'appSettingTab');
            return redirect()->to(base_url('setting'))
                ->with('failed', 'Pengguna tidak ditemukan.');
        }

        // Ambil tenant_id dari data user lengkap
        $tenant_id = $userFull['tenant_id'] ?? null;

        if (empty($tenant_id) || !ctype_digit((string)$tenant_id)) {
            session()->setFlashdata('active_tab', 'appSettingTab');
            return redirect()->to(base_url('setting'))
                ->with('failed', 'Tenant ID tidak valid.');
        }

        // Validation hanya untuk subdomain
        $validation = $this->validation;
        $validation->setRules($this->rulesGantiSubdomain());

        if (!$validation->withRequest($this->request)->run()) {
            session()->setFlashdata('active_tab', 'appSettingTab');
            return redirect()->to(base_url('setting'))
                ->withInput()
                ->with('failed', $validation->getErrors());
        }

        // Ambil data subdomain dari form
        $postData = $this->getPostDataGantiSubdomain();

        // Kirim ke API
        $response = call_api('PUT', URLAPI . "/v1/tenant/$tenant_id", $postData);

        session()->setFlashdata('active_tab', 'appSettingTab');

        if ($response->code === 200) {
            return redirect()->to(base_url('setting'))
                ->with('success', 'Subdomain berhasil diupdate!');
        }

        return redirect()->to(base_url('setting'))
            ->withInput()
            ->with('failed', [$response->message ?? 'Gagal update subdomain.']);
    }

    /**
     * Rules validasi subdomain saja
     */
    private function rulesGantiSubdomain(): array
    {
        return [
            'domain_name' => [
                'label' => 'Subdomain',
                'rules' => 'required|trim|max_length[100]|regex_match[/^[a-zA-Z0-9.-]+$/]',
                'errors' => [
                    'regex_match' => 'Subdomain hanya boleh mengandung huruf, angka, titik, dan dash (-).',
                ],
            ],
        ];
    }

    /**
     * Ambil data subdomain dari form
     */
    private function getPostDataGantiSubdomain(): array
    {
        $data = [];
        $domain = $this->request->getPost('domain_name');
        if (!empty($domain)) {
            $data['subdomain'] = strip_tags(trim($domain));
        }
        return $data;
    }
}
