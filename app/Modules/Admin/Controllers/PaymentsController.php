<?php

namespace Admin\Controllers;

use Admin\Models\Payments;

class PaymentsController extends AdminController
{

    public function __construct()
    {
        parent::__construct();

        $this->controller_name = 'payments';
        $this->path_views = 'payments/';
        $this->main_model = new Payments();
        $this->tb_main = 'payments';
        $this->columns     =  array(
            "method"           => ['name' => 'Payment Method',    'class' => ''],
            "name"             => ['name' => 'Name',    'class' => ''],
            "sort"             => ['name' => 'Sorting', 'class' => 'text-center'],
            "status"           => ['name' => 'Status',  'class' => 'text-center'],
        );
    }

    public function store()
    {
        _is_ajax();

        $ids = post('id');
        
        $rules = [
            'payment_params.name'       => 'required',
            'payment_params.status'     => 'required',
            'payment_params.type'       => 'required|regex_match[/^\w+$/]'
        ];
        
        if (!empty($ids)) {
            $rules2 = [
                'sort'                   => "is_unique[payments.sort,id,{$ids}]"
            ];
            $task = 'edit-item';
        } else {
            $rules2 = [
                'sort'                   => "is_unique[payments.sort]"
            ];
            $task = 'add-item';
        }
        
        
        if (!$this->validate(array_merge($rules,$rules2))) {
            $errors = $this->validator->listErrors();
            ms(['status' => 'error', 'message' => $errors]);
        }
        $response = $this->main_model->save_item(post('payment_params'), ['task' => $task]);
        ms($response);
        
    }

    public function change_sort($id = ""){
		_is_ajax();
       
        $params = [
            'id'        => $id,
            'sort'      => (int)post('sort'),
        ];
		$response = $this->main_model->save_item($params, ['task' => 'change-sort']);
		ms($response);
	}
    public function sortpayments()
    {
        _is_ajax();
        foreach (post('sort') as $key => $value) {
            $params = [
                'id'        => $value,
                'sort'      => (int)($key+1),
            ];
            $response = $this->main_model->save_item($params, ['task' => 'change-sort-all']);
        }
        ms(["status"  => "success", "message" => 'Update successfully']);
    }

}
