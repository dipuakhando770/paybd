<?php

namespace Admin\Controllers;

use Admin\Models\Faqs;

class FaqsController extends AdminController
{

    public function __construct()
    {
        parent::__construct();

        $this->controller_name = 'faqs';
        $this->path_views = 'faqs/';
        $this->main_model = new Faqs();
        $this->tb_main = 'faqs';
        $this->columns     =  array(
            "faq"              => ['name' => 'Detail',      'class' => 'text-center'],
            "sort"             => ['name' => 'Sort',      'class' => 'text-center'],
            "status"           => ['name' => 'Status',   'class' => 'text-center'],
            "changed"          => ['name' => 'Changed',  'class' => 'text-center'],
        );
    }

    public function store()
    {

        $ids = post('id');
        
        $rules = [
            'question'       => 'required',
            'answer'           => 'required',
            'sort'     => 'required',
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

    
    public function change_sort($id = ""){
		_is_ajax();
        $params = [
            'id'        => $id,
            'sort'      => (int)post('sort'),
        ];
		$response = $this->main_model->save_item($params, ['task' => 'change-sort']);
		ms($response);
	}
    public function sortfaqs()
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
