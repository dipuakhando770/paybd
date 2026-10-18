<?php

namespace User\Controllers;

use User\Models\UserModel;

class Dashboard extends UserController
{
    public $data = [];
    private $model;

    /**
     * Constructor.
     *
     */
    public function __construct()
    {
        $this->model = new UserModel();
    }

    public function index()
    {
        $this->template->view('index', $this->data)->render();
    }

    public function dashboardData()
    {
        $data = $this->model->breadboard_values();
        echo json_encode($data);
    }
}
