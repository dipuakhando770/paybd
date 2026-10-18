<?php

namespace Admin\Controllers;

use Admin\Models\AdminModel;
use Admin\Models\StaffModel;

class StaffController extends AdminController
{
    public $admin_model,$db;
    public function __construct()
    {
        parent::__construct();

        $this->controller_name = 'staffs';
        $this->path_views = 'staffs/';
        $this->main_model = new StaffModel();
        $this->admin_model = new AdminModel();
        $this->db = db_connect();

        $this->columns = array(
            "staffs" => ['name' => 'User Profile', 'class' => ''],
            "funds" => ['name' => 'Balance', 'class' => 'text-center'],
            "created" => ['name' => 'Created', 'class' => 'text-center'],
            "status" => ['name' => 'Status', 'class' => 'text-center'],
        );

    }
    public function roles()
{
    $data['controller_name'] = $this->controller_name;
    $data['items'] = $this->main_model->fetch('*', 'user_roles'); 
    if (array_key_exists('setting_access_role', PERMISSIONS)) {
        $this->template->build($this->path_views . '/roles', $data);
    } else {
        $this->template->build("no_permission");
    }
}


    public function role_permission($action, $id='')
{
    $params = [
        'id' => $id,
    ];
    if (!array_key_exists('setting_access_role', PERMISSIONS)) {
        return $this->load->view("no_permission");
    }
    $items_role = $this->main_model->get('*', 'user_roles');

    if ($action == 'update') {
        $data['item'] = $this->main_model->get('*', 'user_roles', ['id' => $id], '', '', true);

        $this->load->view($this->path_views . '/role-update', $data);
    }

    if ($action == 'add') {
        $this->load->view($this->path_views . '/role-update');
    }

    if ($action == 'delete') {
        $options = [
            'task' => 'delete-role',
        ];
        $response = $this->main_model->delete_item($params, $options);
        ms($response);
    }

    if ($action == 'store') {
        if (!$this->input->is_ajax_request()) {
            redirect(admin_url($this->controller_name));
        }
        $this->form_validation->set_rules('name', 'Role name', 'trim|required|xss_clean');
        if (!$this->form_validation->run()) {
            _validation('error', validation_errors());
        }
        if (!empty(post('id'))) {
            $data['permissions'] = json_encode($_POST);
            $data['name'] = post('name');
            $this->db->update('user_roles', $data, ['id' => post('id')]); 

            ms(['status' => 'success', 'message' => 'Updated Successfully']);
        }
        $data['permissions'] = json_encode($_POST);
        $data['name'] = post('name');
        $this->db->insert('user_roles', $data);
        ms(['status' => 'success', 'message' => 'Updated Successfully']);
    }
}


    public function timeline($ids=''){
        $this->data['item'] = $this->main_model->get_item(['ids'=>$ids],['task'=>'get-staff-by-id']);
        if(!empty($this->data['item'])){
            $params = [
                'id'         => $this->data['item']->id,
                'start_date' => post('start_date')??'',
                'end_date'   => post('end_date')??'',
            ];


            $this->data['items'] = $this->main_model->get_item($params,['task'=>'get-staff-activity']);
            
        }else{
            $this->show404();
        }
        $this->template->view('settings/timeline',$this->data)->render();
    }


    public function create($ids='')
    {
        _is_ajax();
        $item = null;
        $item_infor =null;
        
        if (!empty($ids)) {
            $this->params = ['ids' => $ids];
            $item = $this->main_model->get("*", 'staffs', ['ids' => $ids], '', '', true);
            $item_infor = $item['more_information'];
        }
        $roles = $this->main_model->fetch('*','user_roles');
        $this->data = [
            "controller_name" => $this->controller_name,
            "item" => $item,
            "item_infor" => $item_infor,
            "roles" => $roles,
        ];
        echo view('Admin\Views\staffs\update',$this->data);
    }

    public function add_funds($ids = null)
    {
        _is_ajax();

        $item = $this->main_model->get('*','staffs',['ids'=>$ids],'','',true);
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
            echo view('Admin\Views\staffs\add_funds', $this->data);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $ids = post('ids');
        
        $rules = [
            'email'    => 'required|valid_email',
            'first_name'     => 'required|min_length[2]',
            'last_name'     => 'required|min_length[2]',
           
        ];
        
        if ($ids) {
            $rules2 = [
                'email'=>"is_unique[staffs.email,ids,{$ids}]",
            ] ;
            if (post('store_type') != 'user_information') {
                $task = 'edit-item';
            } else {
                $task = 'edit-item-information';
            }
        } else {
            $task = 'add-item';
            $rules2 = [
                'email'=>"is_unique[staffs.email]",
                'password' => 'required|min_length[5]',              
            ] ;
        }
        
        if (!$this->validate(array_merge($rules,$rules2))) {
            $errors = $this->validator->listErrors();
            ms(['status' => 'error', 'message' => $errors]);
        }
        $response = $this->main_model->save_item($this->params, ['task' => $task]);
        ms($response);
        
    }
    public function mail($ids = null)
    {
        _is_ajax();
        $item = $this->main_model->get("*", 'staffs', ['ids' => $ids], '', '', true);
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
            $check_email_issue = $this->main_model->sendMail($subject, $email_content, $item['id'], 'admin');
            if ($check_email_issue) {
                _validation('error', $check_email_issue);
            }

            ms(['status' => 'success', 'message' => 'The email has been successfully sent']);
        } else {
            
            $this->data = array(
                "controller_name" => $this->controller_name,
                "item" => $item,
            );
            
            echo view('Admin\Views\staffs\send_mail',$this->data);
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
            $item = $this->main_model->get("*", 'staffs', ['ids' => $ids], '', '', true);
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
                $item = $this->main_model->get('*','staffs',['ids'=>$ids],'','',true);
            }
            $this->data = array(
                "controller_name" => $this->controller_name,
                "item" => $item,
            );
            echo view('Admin\Views\staffs\set_password',$this->data);
        }
    }
 
 public function activity()
    {
        $page        = (int)get("p");
        $page        = ($page > 0) ? ($page - 1) : 0;

               
        $this->params = [
            'pagination' => [
                'limit'  => $this->limit_per_page,
                'start'  => $page * $this->limit_per_page,
            ],
        ];
        $items = $this->main_model->list_items($this->params, ['task' => 'get-staff-activity']);

        $data = array(
            "controller_name"     => $this->controller_name,
            "params"              => $this->params,
            "items"               => $items,
            "from"                => $page * $this->limit_per_page,
            "pagination"          => create_pagination([
                'base_url'         => admin_url('staffs/activity'),
                'per_page'         => $this->limit_per_page,
                'query_string'     => $_GET, //$_GET 
                'total_rows'       => $this->main_model->count_items($this->params, ['task' => 'count-items-for-log-pagination']),
            ]),
        );
        if (array_key_exists('setting_acess_activity', PERMISSIONS)) {
            $this->template->build($this->path_views . '/activity',$data);
        }else{
            $this->template->build("no_permission");
        }
    }
   

}