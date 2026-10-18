<?php

namespace User\Controllers;

use User\Models\UserModel;

class Settings extends UserController
{
    public $data = [];
    public $model, $db;


    public function __construct()
    {
        parent::__construct();
        $this->model = new UserModel();
    }

    public function index()
    {
        $this->data['item'] = $this->db->table('users')->where('id', session('uid'))->get()->getRow();
        $this->template->view('settings/profile', $this->data)->render();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param $id
     */

    public function update($id = null)
    {
        $validation = \Config\Services::validation();
        if (post('type') == 'account') {
            $validation->setRules([
                'email'          => 'required|valid_email',
                'first_name'     => 'required|min_length[2]',
                'last_name'      => 'required|min_length[2]',
                'avatar'         => 'required'
            ]);
            $data = [
                'first_name' => post('first_name'),
                'last_name'  => post('last_name'),
                'avatar'     => post('avatar'),
            ];
        } elseif (post('type') == 'password') {

            $validation->setRules([
                'old_password'    => 'required',
                'password' => 'required|differs[old_password]|min_length[5]',
                'confirm_password' => [
                    'rules'  => 'required|matches[password]',
                    'errors' => [
                        'matches' => 'Confirm Password field must match with Password field.',
                    ],
                ],
            ]);
            $data = [
                'password' => password_hash(post('password'), PASSWORD_BCRYPT)
            ];

            if (!$this->model->verify_access(post('old_password'))) {
                ms(['status' => 'error', 'message' => 'Old Password is incorrect']);
            }
        }

        if (!$validation->withRequest($this->request)->run()) {
            $message = '';
            foreach ($validation->getErrors() as $va) {
                $message .= $va . '<br>';
            }
            ms(['status' => 'error', 'message' => $message]);
        }

        $result = $this->model->where('id', session('uid'))->builder()->update($data);

        if ($result) {
            ms(['status' => 'success', 'message' => 'Changed successfully']);
        } else {
            $message = '';
            foreach ($this->model->errors() as $va) {
                $message .= $va . '<br>';
            }
            ms(['status' => 'error', 'message' => $message]);
        }
    }
    public function twoFactorEnable()
    {
        _is_ajax();
        $user = $this->db->table('users')->where('id', session('uid'))->get()->getRow();

        $more_info = $user->more_information;
        $jsonArray = json_decode($more_info, true);
        $jsonArray['two_factor'] = '1';
        $data['more_information'] = json_encode($jsonArray);
        $result = $this->model->where('id', session('uid'))->builder()->update($data);

        ms(['redirect_url' => current_url(), 'status' => 'success', 'message' => 'Two Factor Enabled Successfully']);
    }
    public function twoFactorDisable()
    {
        _is_ajax();
        $user = $this->db->table('users')->where('id', session('uid'))->get()->getRow();
        $more_info = $user->more_information;
        $jsonArray = json_decode($more_info, true);
        $jsonArray['two_factor'] = '0';
        $data['more_information'] = json_encode($jsonArray);
        $result = $this->model->where('id', session('uid'))->builder()->update($data);

        ms(['redirect_url' => current_url(), 'status' => 'success', 'message' => 'Two Factor Disabled Successfully']);
    }
    public function twoFactor($type = '')
    {
        $user = $this->db->table('users')->where('id', session('uid'))->get()->getRow();

        if ($type == 'email') {
            if (empty(post('email'))) {
                ms(['status' => 'error', 'message' => 'Enter A Valid Email Please.']);
            }
            if (post('type')) {
                if (!empty(post('otp'))) {
                    if (session('mmm') == post('otp')) {

                        $more_info = $user->more_information;
                        $jsonArray = json_decode($more_info, true);
                        $jsonArray['two_factor'] = '1';
                        $jsonArray['two_factor_mode'] = 'email';
                        $jsonArray['two_factor_data'] = post('email');
                        $data['more_information'] = json_encode($jsonArray);
                        $result = $this->model->where('id', session('uid'))->builder()->update($data);
                        ms(['reload' => 'true', 'status' => 'success', 'message' => 'Your OTP is verified Successfully']);
                    }
                    ms(['status' => 'error', 'message' => 'Your OTP is verification Failed']);
                } else {
                    ms(['status' => 'error', 'message' => 'Please insert OTP.']);
                }
            }


            $otp = trxId();
            set_session('mmm', $otp);
            $content = "Your OTP : " . $otp;
            $message = $this->model->sendMail('Two Factor Authentication for Mail', $content, post('email'), 'general', false);
            if (!empty($message)) {
                ms(['status' => 'error', 'message' => $message]);
            }

            ms(['status' => 'success', 'message' => 'We have sent you an Email with OTP.']);
        } elseif ($type == 'sms') {
            ms(['status' => 'error', 'message' => 'Sorry! Currently SMS is Off']);
        }
    }
    public function kyc()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return view('User\Views\settings\kyc_update', $this->data);
        } else {
            $this->data['item'] = $this->model->get('more_information', 'users', ['id' => session('uid')]);
            $this->data['items'] = $this->model->fetch('*', 'kyc', ['uid' => session('uid')], 'id', '', '', '', true);

            return $this->template->view('settings/kyc', $this->data)->render();
        }
    }
    public function kycUpdate()
    {
        _is_ajax();
        if (!empty(post('submit'))) {
            $rules = [
                'doc'     => 'required',
                'type'     => 'required',
            ];
            if (!$this->validate($rules)) {
                $errors = $this->validator->listErrors();
                ms(['status' => 'error', 'message' => $errors]);
            }
            $data = [
                'ids'    => ids(),
                'uid'    => session('uid'),
                'params' => json_encode(['type' => post('type'), 'file' => post('doc')]),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $this->db->table('kyc')->insert($data);
            $this->db->close();
            ms(['redirect' => previous_url(), 'status' => 'success', 'message' => 'Your Document Submitted Successfully']);
        } else {

            return view('User\Views\settings\kyc_update', $this->data);
        }
    }

    public function affiliates()
    {
        return $this->template->view('settings/affiliates', $this->data)->render();
    }
}
