<?php

namespace Admin\Controllers;

use Admin\Models\AdminModel;
use Admin\Models\UserModel;

class UserController extends AdminController
{
    public $admin_model;
    public function __construct()
    {
        parent::__construct();

        $this->controller_name = 'users';
        $this->path_views = 'users/';
        $this->main_model = new UserModel();
        $this->admin_model = new AdminModel();
        
        $this->columns = array(
            "users" => ['name' => 'User Profile', 'class' => ''],
            "funds" => ['name' => 'Balance', 'class' => 'text-center'],
            "created" => ['name' => 'Created', 'class' => 'text-center'],
            "status" => ['name' => 'Status', 'class' => 'text-center'],
        );

    }
    
    public function timeline($ids=''){
        $this->data['item'] = $this->main_model->get('*','users',['ids'=>$ids]);
        if(!empty($this->data['item'])){
            $params = [
                'id'         => $this->data['item']->id,
                'start_date' => post('start_date')??'',
                'end_date'   => post('end_date')??'',
            ];


            $this->data['items'] = $this->main_model->get_item($params,['task'=>'get-users-activity']);
            
        }else{
            $this->show404();
        }
        $this->template->view('users/timeline',$this->data)->render();
    }

    public function create($ids='')
    {
        _is_ajax();
        $item = null;
        $item_infor =null;
        
        if (!empty($ids)) {
            $this->params = ['ids' => $ids];
            $item = $this->main_model->get("*", 'users', ['ids' => $ids], '', '', true);
            $item_infor = $item['more_information'];
        }
        $this->data = [
            "controller_name" => $this->controller_name,
            "item" => $item,
            "item_infor" => $item_infor,
        ];
        echo view('Admin\Views\users\update',$this->data);
    }

    public function add_funds($ids = null)
    {
        _is_ajax();

        $item = $this->main_model->get('*','users',['ids'=>$ids],'','',true);
        if (!$item) {
            _validation('error', 'The account does not exists');
        }

        if (!empty(post('ids'))) {
            
            $rules = [
                'payment_method'=>'trim|required',
                'amount'=>'trim|required|greater_than[0]',
                'secret_key'=>'trim|required',
            ];
            if (!$this->validate($rules)) {
                _validation('error', $this->validator->listErrors());
            }
            //Check item
            
            $is_valid_secret_key = $this->admin_model->verify_access(post('secret_key'));
            if ($is_valid_secret_key) {
                $response = $this->main_model->save_funds(['item' => $item], ['task' => 'add-funds']);
                ms($response);
            } else {
                _validation('error', 'The secret key is invalid.');
            }
        } else {
            $items_payment = $this->main_model->fetch("id, type, sort, name", 'payments', '', '', '', true);
            $this->data = array(
                "controller_name" => $this->controller_name,
                "item" => $item,
                "items_payment" => $items_payment,
            );
            echo view('Admin\Views\users\add_funds', $this->data);
        }
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $ids = post('ids');
        
        $rules = [
            'email'         => 'required|valid_email',
            'phone'         => 'required|number',
            'first_name'    => 'required|min_length[2]',
            'last_name'     => 'required|min_length[2]',
        ];
        
        if ($ids) {
            $nrules = [
                'email'=>"is_unique[users.email,ids,{$ids}]",
                'phone'=>"is_unique[users.phone,ids,{$ids}]",
            ] ;
            if (post('store_type') != 'user_information') {
                $task = 'edit-item';
            } else {
                $task = 'edit-item-information';
            }
        } else {
            $task = 'add-item';
            $nrules = [
                'email'    =>"is_unique[users.email]",
                'phone'    =>"is_unique[users.phone]",
                'password' => 'required|min_length[5]',              
            ] ;
        }
        
        if (!$this->validate(array_merge($rules,$nrules))) {
            $errors = $this->validator->listErrors();
            ms(['status' => 'error', 'message' => $errors]);
        }
        $response = $this->main_model->save_item($this->params, ['task' => $task]);
        ms($response);
        
    }
    public function mail($ids = null)
    {
        _is_ajax();
        $item = $this->main_model->get("*", 'users', ['ids' => $ids], '', '', true);
        if (!$item) {
            _validation('error', 'The account does not exists');
        }

        if (!empty(post('ids'))) {
            $rules = [
                'subject'=>'trim|required|min_length[6]',
                'message'=>'trim|required|min_length[6]',
                'email_to'=>'trim|required|min_length[6]',
            ];
            if (!$this->validate($rules)) {
                _validation('error', $this->validator->listErrors());
            }
            //Check item
            $subject = get_option("site_name", "") . " - " . post('subject');
            $email_content = post('message',true);
            $check_email_issue = $this->main_model->sendMail($subject, $email_content, $item['id'], false);
            if ($check_email_issue) {
                _validation('error', $check_email_issue);
            }

            ms(['status' => 'success', 'message' => 'The email has been successfully sent']);
        } else {
            
            $this->data = array(
                "controller_name" => $this->controller_name,
                "item" => $item,
            );
            
            echo view('Admin\Views\users\send_mail',$this->data);
        }
    }

    // Set Password
    public function set_password($ids = null)
    {
        _is_ajax();

        if (post('ids')) {
            $rules = [
                'password'    => 'required',
                'secret_key'     => 'required',
            ];
            if (!$this->validate($rules)) {
                $errors = $this->validator->listErrors();
                _validation('error', $errors);
            }
            //Check item
            $item = $this->main_model->get("*", 'users', ['ids' => $ids], '', '', true);
            if (!$item) {
                _validation('error', 'The account does not exists');
            }
            $is_valid_secret_key=$this->admin_model->verify_access(post('secret_key'));
            if ($is_valid_secret_key) {
                $response = $this->main_model->save_item(null, ['task' => 'set-password']);
                ms($response);
            } else {
                _validation('error', 'The secret key is invalid.');
            }
        } else {
            $item = null;
            if ($ids !== null) {
                $this->params = ['ids' => $ids];
                $item = $this->main_model->get('*','users',['ids'=>$ids],'','',true);
            }
            $this->data = array(
                "controller_name" => $this->controller_name,
                "item" => $item,
            );
            echo view('Admin\Views\users\set_password',$this->data);
        }
    }
    public function view_user($ids = "")
    {
        try {
            $item = $this->main_model->get("*", 'users', ['ids' => $ids], '', '', true);
            if (empty($item)) {
                _validation('error', 'There was an error processing your request. Please try again later....');
            }
            set_session('uid',$item['id']);

            if (session('uid')) {
                ms([
                    'status' => 'success',
                    'message' => 'Your request is being processed',
                    'redirect_url' => user_url('dashboard'),
                    'new_page' => true,
                ]);
            } 
        } catch (\Throwable $th) {
            _validation('error', 'There was an error processing your request. Please try again later....');
        }

    }

}
