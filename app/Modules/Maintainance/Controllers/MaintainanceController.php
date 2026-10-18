<?php

namespace Maintainance\Controllers;

use Admin\Models\AdminModel;
use App\Controllers\BaseController;

class MaintainanceController extends BaseController
{
    public $data = [];
    public $model;

    public function __construct()
    {
        $this->model = new AdminModel();
    }

    public function index()
    {
        $this->template->set_layout('maintainance');
        $this->template->view('index',$this->data)->render(); 
    }
    public function access()
    {
        $this->template->set_layout('auth');
        $this->template->view('access',$this->data)->render(); 
    }

    public function attempt_access() {
        if (!get_option("is_maintenance_mode")) {
            return redirect()->to(url_to('home.index'));
        }
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
                $this->setLogin(); 
                ms(['status'=>'success','message'=>"You have logged in Successfully"]);
            }     
            ms(['status'=>'error','message'=>"Something went wrong! Your account may be deactivated"]);

        }
    }
    private function setLogin() {

        $remember = post("remember");
        
        if (!empty($remember)) {
            set_cookie("a_cookie_email", encrypt_encode(post("email")), 1209600);
            set_cookie("a_cookie_pass", encrypt_encode(post("password")), 1209600);

        } else {
            delete_cookie("a_cookie_email");
            delete_cookie("a_cookie_pass");
        }
        set_session('sid',1); 
        set_cookie("verify_maintenance_mode", encrypt_encode("verified"), 1209600);
        
    }

}
