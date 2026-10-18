<?php

namespace Admin\Controllers;

use CodeIgniter\I18n\Time;
use Admin\Controllers\BaseController;
use Admin\Models\AdminModel;

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
        $this->model = new AdminModel();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return redirect()->to(admin_url('dashboard'));
    }

    public function signin() 
    {
        $this->template->set_layout('auth');
        $this->template->view('auth/signin',$this->data)->render();  
    }

    public function attempt_signin() 
    {
        // _is_ajax();
        $validation = \Config\Services::validation();
        $validation->setRules([
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[5]',
        ]);
        if (!$validation->withRequest($this->request)->run()) {
            $message='';
            foreach($validation->getErrors() as $va ){
                $message .=$va.'<br>'; 
            }
            ms(['status'=>'error','message'=>$message]);
        }else{
            if(!empty($data = $this->model->login()) && !empty($data->status) && $data->status==1){
                $this->setLogin($data->id); 
                ms(['status'=>'success','message'=>"You have logged in Successfully"]);
            }     
            ms(['status'=>'error','message'=>"Something went wrong! Your account may be deactivated"]);

        }
        
    }

    private function setLogin($id) {

        $remember = post("remember");
        
        if (!empty($remember)) {
            set_cookie("a_cookie_email", encrypt_encode(post("email")), 1209600);
            set_cookie("a_cookie_pass", encrypt_encode(post("password")), 1209600);

        } else {
            delete_cookie("a_cookie_email");
            delete_cookie("a_cookie_pass");
        }
        set_session('sid',$id);
        $this->model->setLogs("Login"); 
        
    }
  
    public function logout() {
        $this->model->setLogs("Logout"); 

        unset_session('sid');
        set_session('ref_url',previous_url());
        set_flashdata('message',array('message'=>'Logout successfully','status'=>'warning'));

        return redirect()->to(url_to('admin.signin'));        
    } 


    //reset
    public function resetPassword()
    {
        $this->template->set_layout('auth');
        $this->template->view('auth/resetPass',$this->data)->render(); 
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
        $user = $this->model->get("*", 'staffs', "email = '{$email}'");
        if (!empty($user)) {
            $email_error = $this->model->sendMail(get_option("admin_email_password_recovery_subject", ""), get_option("admin_email_password_recovery_content"), $user->email,'admin');

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

    public function checkResetPassword($reset_key) {
        /*----------  check users exists  ----------*/
        $user = $this->model->get("id, ids, email", 'staffs', "reset_key = '{$reset_key}'");
        if (!empty($user)) {
            $this->template->set_layout('auth');
            $this->template->view('auth/change_password',$this->data)->render(); 
        } else {
            set_flashdata('message',array('message'=>'Your link is invalid....','status'=>'warning'));
            return redirect()->to(url_to('admin.signin')); 
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
            $message='';
            foreach($validation->getErrors() as $va ){
                $message .=$va.'<br>'; 
            }
            ms(['status'=>'error','message'=>$message]);
        }
        $user = $this->model->get("id, ids, email", 'staffs', "reset_key = '{$reset_key}'");
        
        if (!empty($user)) {
            if($this->model->verify_access(post('password'),$user->id)){
                ms(['status'=>'error','message'=>'Old Password can\'t  be used']);
            }
            
            $data = [
                'password' => password_hash(post('password'), PASSWORD_BCRYPT),
                'reset_key' => create_random_string_key(32)
            ];
            $this->model->where('id', $user->id)->builder()->update($data);

            $this->setLogin($user->id);
            $this->model->setLogs("Password reset"); 

            ms(['status' => 'success', 'message' => 'Your pasword updated successfully']);
            
        } else {
            set_flashdata('message',array('message'=>'Your link is invalid....','status'=>'warning'));
            return redirect()->to(url_to('user.signin')); 
        }
        
    }
    public function activation($reset_key) {
        /*----------  check users exists  ----------*/
        $user = $this->model->get("id, ids, email", 'staffs', "activation_key = '{$reset_key}' && status!=1  ");
        if (!empty($user)) {
            $this->template->set_layout('auth');
            $this->template->view('auth/activation',$this->data)->render(); 
        } else {
            set_flashdata('message',array('message'=>'Your link is invalid....','status'=>'warning'));
            return redirect()->to(url_to('user.signin')); 
        }
    }

    public function setActivation($activation_key)
    {
        $user = $this->model->get("id, ids,name, email", 'staffs', "activation_key = '{$activation_key}' && status!=1 ");
        
        if (!empty($user)) {
            
            $data = [
                'status' => 1,
                'activation_key' => create_random_string_key(25)
            ];
            $this->model->where('id', $user->id)->builder()->update($data);

            $this->setLogin($user->id);
            $this->model->setLogs("Activate account"); 

            ms(['status' => 'success', 'message' => 'Your account activated successfully']);
            
        } else {
            set_flashdata('message',array('message'=>'Your link is invalid....','status'=>'warning'));
            return redirect()->to(url_to('user.signin')); 
        }
        
    }
 

}
