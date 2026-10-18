<?php

namespace Admin\Controllers;

use Admin\Models\Coupon as ModelsCoupon;

class Coupon extends AdminController
{
    public $admin_model;
    public function __construct()
    {
        parent::__construct();

        $this->controller_name = 'coupon';
        $this->path_views = 'coupon/';
        $this->main_model = new ModelsCoupon();
        $this->tb_main = 'coupons';
        
        $this->columns     =  array(
            "code"             => ['name' => 'code',    'class' => ''],
            "type"             => ['name' => 'Type',    'class' => ''],
            "price"            => ['name' => 'Price',    'class' => ''],
            "limit"            => ['name' => 'Limit',    'class' => ''],
            "used"             => ['name' => 'Used',    'class' => ''],
            "From"             => ['name' => 'From', 'class' => 'text-center'],
            "to"               => ['name' => 'To', 'class' => 'text-center'],
            "status"           => ['name' => 'Status',  'class' => 'text-center'],
        );
    }

    public function store()
    {

        $ids = post('id');
        
        $rules = [
            'code'            => 'required',
            'times'           => 'required',
            'price'           => 'required',
            'description'     => 'required',
            'start_date'      => 'required',
            'end_date'        => 'required',
            'type'            => 'required|numeric',
            'status'          => 'required|numeric',
        ];
        
        
        if ($ids) {
            $task = 'edit-item';
        } else {
            $task = 'add-item';
        }
        $task = !empty($ids)?'edit-item':'add-item';
        
        if (!$this->validate($rules)) {
            $errors = $this->validator->listErrors();
            ms(['status' => 'error', 'message' => $errors]);
        }
        $response = $this->main_model->save_item($this->params, ['task' => $task]);
        ms($response);
        
    }

}
