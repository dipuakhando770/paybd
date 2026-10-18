<?php

namespace Home\Controllers;

use App\Controllers\BaseController;


class DocController extends BaseController
{
    public $data = [];
    public $model;
    public function __construct()
    {
    }

    public function index()
    {
        $this->template->set_layout('docs');
        return  $this->template->view('developers/index')->render();
    }
    public function docs()
    {
        $this->template->set_layout('docs');
        return  $this->template->view('developers/docs')->render();
    }
}
