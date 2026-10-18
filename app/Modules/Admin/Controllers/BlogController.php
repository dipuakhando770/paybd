<?php

namespace Admin\Controllers;

use Admin\Models\Blog;

class BlogController extends AdminController
{

    public function __construct()
    {
        parent::__construct();

        $this->controller_name = 'blogs';
        $this->path_views = 'blogs/';
        $this->main_model = new Blog();
        $this->tb_main = 'blogs';
        $this->columns     =  array(
            "description" => ['name' => 'description', 'class' => 'text-center'],
            "title"       => ['name' => 'title',        'class' => 'text-center'],
            "start"       => ['name' => 'Start',       'class' => 'text-center'],
            "status"      => ['name' => 'status',      'class' => 'text-center'],
        );
    }

    public function store()
    {

        $ids = post('id');
        
        $rules = [
            'thumbnail'       => 'required',
            'title'           => 'required',
            'description'     => 'required',
            'start'           => 'required',
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
