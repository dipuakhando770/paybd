<?php

namespace Admin\Controllers;

use Admin\Models\Plans;

class PlanController extends AdminController
{
    public $admin_model;
    public function __construct()
    {
        parent::__construct();

        $this->controller_name = 'plans';
        $this->path_views = 'plans/';
        $this->main_model = new Plans();
        $this->tb_main = 'plans';
        $this->columns     =  array(
            "name"          => ['name' => 'Name',    'class' => ''],
            "brand"         => ['name' => 'Brands',    'class' => ''],
            "Device"        => ['name' => 'Device',    'class' => ''],
            "Transaction"   => ['name' => 'Transaction',    'class' => ''],
            "price"         => ['name' => 'Price', 'class' => 'text-center'],
            "sort"          => ['name' => 'Sort', 'class' => 'text-center'],
            "status"        => ['name' => 'Status',  'class' => 'text-center'],
        );
    }
    public function userPlan()
    {
        $builder = $this->db->table('user_plans u');
        $builder->select('us.email, p.name, u.*');
        $builder->join('plans p', 'p.id = u.plan_id', 'left');
        $builder->join('users us', 'us.id = u.uid', 'left');
        $builder->orderBy('u.id', 'desc');
        $query = $builder->get();
        $this->data['items'] = $query->getResultArray();
        $this->template->view($this->path_views . 'user_plan', $this->data)->render();
    }
    public function edit_user_plan($id = null)
    {
        _is_ajax();
        if (!empty(post('type')) && post('type') == 'plan_edit') {
            $rules = [
                'brand'           => 'required|numeric',
                'device'          => 'required|numeric',
                'duration'        => 'required|numeric',
            ];
            if (!$this->validate($rules)) {
                $errors = $this->validator->listErrors();
                ms(['status' => 'error', 'message' => $errors]);
            }
            if (post('duration') > 0) {
                $expire =  calculateExpirationDate(post('duration'), post('expire'));
            } else {
                $expire =  post('expire');
            }
            $data_plan = array(
                "brand"           => post('brand'),
                "device"           => post('device'),
                "expire"            => $expire,
            );
            $this->db->table('user_plans')->update($data_plan, ["id" => post('id')]);
            if ($this->db->affectedRows() > 0) {
                ms(["status"  => "success", "message" => 'Update successfully']);
            } else {
                ms(["status"  => "error", "message" => 'Failed to update successfully']);
            }
        }

        $item = null;
        if ($id !== null) {
            $item = $this->main_model->get("*", 'user_plans', ['id' => $id], '', '', true);;
        }
        $data = array(
            "controller_name"   => $this->controller_name,
            "item"              => $item,
        );
        echo view('Admin\Views\plans\edit_user_plan', $data);
    }
    public function store()
    {
        $ids = post('ids');

        $rules = [
            'name'            => 'required',
            'description'     => 'required',
            'duration_type'   => 'required',
            'duration'        => 'required|numeric|greater_than[0]',
            'price'           => 'required|numeric',
            'brand'           => 'required|numeric',
            'device'          => 'required|numeric',
            'transaction'     => 'required|numeric',
            'final_price'     => 'required|numeric',
            'sort'            => 'required|numeric',
        ];

        $labels = [
            'description' => 'About',
        ];

        if ($ids) {
            $rules2 = [
                'sort'                   => "is_unique[plans.sort,ids,{$ids}]"
            ];
            $task = 'edit-item';
        } else {
            $rules2 = [
                'sort'                   => "is_unique[plans.sort]"
            ];
            $task = 'add-item';
        }
        $task = !empty($ids) ? 'edit-item' : 'add-item';

        if (!$this->validate(array_merge($rules, $rules2))) {
            $errors = $this->validator->listErrors();
            ms(['status' => 'error', 'message' => $errors]);
        }
        $response = $this->main_model->save_item($this->params, ['task' => $task]);
        ms($response);
    }
    public function change_sort($id = "")
    {
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
                'sort'      => (int)($key + 1),
            ];
            $response = $this->main_model->save_item($params, ['task' => 'change-sort-all']);
        }
        ms(["status"  => "success", "message" => 'Update successfully']);
    }
}
