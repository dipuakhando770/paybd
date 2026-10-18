<?php

namespace User\Controllers;

use CodeIgniter\I18n\Time;
use User\Controllers\BaseController;
use User\Entities\User;
use User\Models\UserModel;

class AuthController extends BaseController
{
    public $data = [];

    /**
     * Constructor.
     *
     */
    protected $model;
    public function __construct()
    {
        $this->model = new UserModel();
        helper("files");
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return redirect()->to(user_url('dashboard'));
    }
    public function affiliates($ids)
    {
        set_session('refferal_id', $ids);
        return redirect()->to(base_url('sign-up'));
    }

    public function signin()
    {
        $this->template->set_layout('auth');
        $this->template->view('auth/signin', $this->data)->render();
    }

    public function attempt_signin()
    {
        _is_ajax();
        $validation = \Config\Services::validation();
        $validation->setRules([
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[5]',
        ]);
        if (!$validation->withRequest($this->request)->run()) {
            $message = '';
            foreach ($validation->getErrors() as $va) {
                $message .= $va . '<br>';
            }
            ms(['status' => 'error', 'message' => $message]);
        } else {

            if (!empty($data = $this->model->login()) && !empty($data->status) && $data->status == 1) {
                if (get_value($data->more_information, 'two_factor') == 1) {
                    set_session('two_factor', $data->id);
                    ms(['redirect' => base_url('two-factor'), 'status' => 'success', 'message' => "Two Factor Authentication Processing"]);
                }
                $this->setLogin($data->id);
                ms(['status' => 'success', 'message' => "You have logged in Successfully"]);
            }
            ms(['status' => 'error', 'message' => "Something went wrong! Your account may be deactivated"]);
        }
    }

    public function two_factor()
    {
        if (!empty(session('two_factor'))) {
            $redirect = session('ref_url') ?? user_url();

            $user = $this->model->get('email,more_information,id', 'users', ['id' => session('two_factor')]);

            if (!empty(post('otp'))) {
                if (session('mmm') == post('otp')) {
                    if (!empty(post('device_save'))) {
                        $jsonArray = json_decode($user->more_information, true);
                        $jsonArray['device_save'] = '1';
                        $data['more_information'] = json_encode($jsonArray);
                        $this->model->where('id', $user->id)->builder()->update($data);
                        set_cookie("device_key", encrypt_encode($user->email), 120960000);
                    }
                    $this->setLogin($user->id);
                    ms(['redirect' => $redirect, 'status' => 'success', 'message' => 'OTP is verified Successfully.']);
                }
                ms(['status' => 'error', 'message' => 'Your OTP is verification Failed']);
            }
            $device_key = get_cookie('device_key');
            if (get_value($user->more_information, 'device_save') == 1 && !empty($device_key)) {
                $this->setLogin(session('two_factor'));
                return redirect()->to($redirect);
            }
            if (get_value($user->more_information, 'two_factor_mode') == 'email') {
                if (!empty(post('send_otp'))) {
                    $otp = trxId();
                    set_session('mmm', $otp);
                    $content = "Your OTP : " . $otp;
                    $message = $this->model->sendMail('Two Factor Authentication for Mail', $content, get_value($user->more_information, $content, 'two_factor_data'), 'general', false);
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        if (!empty($message)) {
                            ms(['status' => 'error', 'message' => $message]);
                        }
                        ms(['status' => 'success', 'message' => 'We have sent you an Email with OTP.']);
                    }
                }
            } elseif (get_value($user->more_information, 'two_factor_mode') == 'sms') {
            } else {
                $this->data['empty'] = true;
            }
            $this->data['user'] = $user;
            $this->template->set_layout('auth');
            $this->template->view('auth/two_factor', $this->data)->render();
        } else {
            $this->show404();
        }
    }

    private function setLogin($id)
    {

        $remember = post("remember");

        if (!empty($remember)) {
            set_cookie("c_cookie_email", encrypt_encode(post("email")), 1209600);
            set_cookie("c_cookie_pass", encrypt_encode(post("password")), 1209600);
        } else {
            delete_cookie("c_cookie_email");
            delete_cookie("c_cookie_pass");
        }
        set_session('uid', $id);
        $this->model->setLogs('Signin', 'user');
    }
    public function signup()
    {
        $this->template->set_layout('auth');
        $this->template->view('auth/signup', $this->data)->render();
    }


    public function attempt_signup()
    {
        _is_ajax();

        $validation = \Config\Services::validation();
        $validation->setRules([
            'email'    => 'required|valid_email',
            'first_name'     => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'First Name is required field.',
                ],
            ],
            'last_name'     => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Last Name is required field.',
                ],
            ],
            'agree'     => [
                'rules'  => 'required',
                'errors' => [
                    'required' => 'You must agree with the terms & conditions',
                ],
            ],
            'phone'     => 'required|regex_match[/^\+?[0-9]{6,}$/]',
            'password' => 'required|min_length[5]',
            'c_password' => [
                'rules'  => 'required|matches[password]',
                'errors' => [
                    'required' => 'Confirm Password is required field.',
                    'matches' => 'Confirm Password field must match with Password field.',
                ],
            ],
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $message = '';
            foreach ($validation->getErrors() as $va) {
                $message .= $va . '<br>';
            }
            ms(['status' => 'error', 'message' => $message]);
        }

        $data = [
            'email' => post('email'),
            'phone' => post('phone'),
            'first_name' => post('first_name'),
            'last_name' => post('last_name'),
            'password' => post('password'),
        ];
        if (!empty($refferal_id = session('refferal_id'))) {
            $us_id = $this->model->get('id,ids', 'users', ['ref_key' => $refferal_id]);
            if (!empty($us_id)) {
                $data['ref_id'] = $us_id->id;
            }
        }

        $res = $this->set_signup($data);
        ms(['status' => $res['status'], 'message' => $res['message']]);
    }
    private function set_signup($data)
    {
        $error = "";
        $entity = new User();

        $userData = [
            'status'          => get_option('is_verification_new_account', 0) ? 0 : 1,
            "activation_key"  => create_random_string_key(25),
            "reset_key"       => create_random_string_key(32),
        ];

        $entity->fill(array_merge($userData, $data));

        if ($this->model->save($entity)) {
            $userId = $this->model->getInsertID();

            if (get_option('is_verification_new_account', 0)) {
                $check_send_email_issue = $this->model->sendMail(get_option('verification_email_subject', ''), get_option('verification_email_content', 0), $userId);
                if ($check_send_email_issue) {
                    $error = ['status' => 'error', 'message' => $check_send_email_issue];
                }


                $error = ['status' => 'success', 'message' => 'Please, check your Email and activate you account.'];
            } else {

                $this->setLogin($userId);
                /*----------  Check is send welcome email or not  ----------*/
                if (get_option("is_welcome_email", '')) {
                    $check_send_email_issue = $this->model->sendMail(get_option('email_welcome_email_subject', ''), get_option('email_welcome_email_content', 0), $userId);
                    if ($check_send_email_issue) {
                        $error = ['status' => 'error', 'message' => $check_send_email_issue];
                    }
                }
                /*----------  Send email notificaltion for Admin  ----------*/
                if (get_option("is_new_user_email", '')) {
                    $subject = get_option('email_new_registration_subject', '');
                    $subject = str_replace("{{site_name}}", get_option("site_name", "your site"), $subject);

                    $email_content = get_option('email_new_registration_content', '');
                    $email_content = str_replace("{{first_name}}", post('first_name'), $email_content);
                    $email_content = str_replace("{{last_name}}", post('last_name'), $email_content);
                    $email_content = str_replace("{{site_name}}", site_config("site_name", "your site"), $email_content);
                    $email_content = str_replace("{{email}}", post('email'), $email_content);

                    $this->model->sendMail($subject, $email_content, get_option('email_from'));
                }
            }

            $this->model->setNotification($userId, 'Hi,' . post('first_name') . ', your account is created Successfully', get_option('email_welcome_email_subject'), 0);

            $error = ['status' => 'success', 'message' => 'You have registered successfully'];
        } else {
            $message = '';
            foreach ($this->model->errors() as $va) {
                $message .= $va . '<br>';
            }
            $error = ['status' => 'error', 'message' => $message];
        }

        return $error;
    }
    public function logout()
    {
        unset_session('uid');
        set_session('ref_url', previous_url());
        set_flashdata('message', array('message' => 'Logout successfully', 'status' => 'warning'));

        return redirect()->to(url_to('user.signin'));
    }


    public function google_process()
    {

        $clientId = get_option('google_auth_clientId');
        $clientSecret = get_option('google_auth_clientSecret');
        $redirectUri = base_url("auth/google_process");

        // Step 1: Redirect the user to Google's OAuth consent page
        if (!isset($_GET['code'])) {
            $authUrl = 'https://accounts.google.com/o/oauth2/auth?' .
                'client_id=' . $clientId .
                '&redirect_uri=' . urlencode($redirectUri) .
                '&scope=openid email profile' .
                '&response_type=code';

            header('Location: ' . $authUrl);
            exit;
        }

        // Step 2: Handle the callback from Google
        if (isset($_GET['code'])) {
            $code = $_GET['code'];

            // Step 3: Exchange the code for an access token
            $tokenUrl = 'https://accounts.google.com/o/oauth2/token';
            $postData = [
                'code' => $code,
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri' => $redirectUri,
                'grant_type' => 'authorization_code',
            ];

            $ch = curl_init($tokenUrl);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            $response = curl_exec($ch);
            curl_close($ch);

            $tokenData = json_decode($response, true);

            // Step 4: Use the access token to get user info
            if (isset($tokenData['access_token'])) {
                $userInfoUrl = 'https://www.googleapis.com/oauth2/v1/userinfo?access_token=' . $tokenData['access_token'];

                $ch = curl_init($userInfoUrl);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $userInfo = curl_exec($ch);
                curl_close($ch);

                $userInfoData = json_decode($userInfo, true);

                $user = $this->model->get("*", 'users', "email = '{$userInfoData["email"]}'");
                if (empty($user)) {
                    // signup here...
                    $data = array(
                        'first_name' => $userInfoData['given_name'],
                        'last_name' => $userInfoData['family_name'],
                        'email' => $userInfoData['email'],
                        'password' => '123456'
                    );

                    $res = $this->set_signup($data);

                    set_flashdata('message', $res);

                    $avatar = save_web_image($userInfoData['picture'], "", 'user');

                    $data = [
                        'avatar' => $avatar,
                    ];
                    $this->model->where('email', $userInfoData['email'])->builder()->update($data);
                    return redirect()->to(base_url('sign-up'));
                } else {
                    if ($user->status != 1) {
                        set_flashdata('message', array('message' => 'Your account is deactivated. Contact with Administrator', 'status' => 'warning'));
                        return redirect()->to(base_url('sign-up'));
                    }

                    set_flashdata('message', array('message' => 'Login successfully', 'status' => 'success'));
                    $this->setLogin($user->id);
                    return redirect()->to(base_url('sign-up'));
                }
            } else {
                set_flashdata('message', array('message' => 'Authentication Failed', 'status' => 'error'));
                return redirect()->to(base_url('sign-up'));
            }
        }
    }

    //reset
    public function resetPassword()
    {
        $this->template->set_layout('auth');
        $this->template->view('auth/resetPass', $this->data)->render();
    }
    public function resetPasswordMail()
    {
        $email = post("email");

        if ($email == "") {
            ms(array(
                "status" => "error",
                "message" => lan("email_is_required"),
            ));
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            ms(array(
                "status" => "error",
                "message" => lan("invalid_email_format"),
            ));
        }
        $user = $this->model->get("*", 'users', "email = '{$email}'");
        if (!empty($user)) {
            $email_error = $this->model->sendMail(get_option("email_password_recovery_subject", ""), get_option("email_password_recovery_content", ""), $user->id);

            if ($email_error) {
                ms(array(
                    "status" => "error",
                    "message" => $email_error,
                ));
            }

            ms(array(
                "status" => "success",
                "message" => lan("we_have_send_you_a_link_to_reset_password_and_get_back_into_your_account_please_check_your_email"),
            ));
        } else {
            ms(array(
                "status" => "error",
                "message" => lan("the_account_does_not_exists"),
            ));
        }
    }

    public function checkResetPassword($reset_key)
    {
        /*----------  check users exists  ----------*/
        $user = $this->model->get("id, ids, email", 'users', "reset_key = '{$reset_key}'");
        if (!empty($user)) {
            $this->template->set_layout('auth');
            $this->template->view('auth/change_password', $this->data)->render();
        } else {
            set_flashdata('message', array('message' => 'Your link is invalid....', 'status' => 'warning'));
            return redirect()->to(url_to('user.signin'));
        }
    }

    public function setResetPassword($reset_key)
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'password' => 'required|min_length[5]',
            'c_password' => [
                'rules'  => 'required|matches[password]',
                'errors' => [
                    'matches' => 'Confirm Password field must match with Password field.',
                ],
            ],
        ]);


        if (!$validation->withRequest($this->request)->run()) {
            $message = '';
            foreach ($validation->getErrors() as $va) {
                $message .= $va . '<br>';
            }
            ms(['status' => 'error', 'message' => $message]);
        }
        $user = $this->model->get("id, ids,first_name, email", 'users', "reset_key = '{$reset_key}'");

        if (!empty($user)) {
            if ($this->model->verify_access(post('password'), $user->id)) {
                ms(['status' => 'error', 'message' => 'Old Password can\'t  be used']);
            }

            $data = [
                'password' => password_hash(post('password'), PASSWORD_BCRYPT),
                'reset_key' => create_random_string_key(32)
            ];
            $this->model->where('id', $user->id)->builder()->update($data);

            $this->setLogin($user->id);
            $this->model->setNotification($user->id, 'Hi,' . $user->first_name . ', your password reset Successfully', 'Password reset', 0);
            ms(['status' => 'success', 'message' => 'Your pasword updated successfully']);
        } else {
            set_flashdata('message', array('message' => 'Your link is invalid....', 'status' => 'warning'));
            return redirect()->to(url_to('user.signin'));
        }
    }
    public function activation($reset_key)
    {
        /*----------  check users exists  ----------*/
        $user = $this->model->get("id, ids, email", 'users', "activation_key = '{$reset_key}' && status!=1  ");
        if (!empty($user)) {
            $this->template->set_layout('auth');
            $this->template->view('auth/activation', $this->data)->render();
        } else {
            set_flashdata('message', array('message' => 'Your link is invalid....', 'status' => 'warning'));
            return redirect()->to(url_to('user.signin'));
        }
    }

    public function setActivation($activation_key)
    {
        $user = $this->model->get("id, ids,first_name,last_name, email", 'users', "activation_key = '{$activation_key}' && status!=1 ");

        if (!empty($user)) {

            $data = [
                'status' => 1,
                'activation_key' => create_random_string_key(25)
            ];
            $this->model->where('id', $user->id)->builder()->update($data);

            $this->setLogin($user->id);
            $this->model->setNotification($user->id, 'Hi,' . $user->first_name . ', your account activated Successfully', 'Account Activation', 0);
            ms(['status' => 'success', 'message' => 'Your account activated successfully']);
        } else {
            set_flashdata('message', array('message' => 'Your link is invalid....', 'status' => 'warning'));
            return redirect()->to(url_to('user.signin'));
        }
    }
}
