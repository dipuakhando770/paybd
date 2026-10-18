<?php

namespace Admin\Controllers;

use Admin\Models\AdminModel;
use Admin\Models\UserModel as ModelsUserModel;
use App\Libraries\QR_lib;

class Dashboard extends AdminController
{
    public $data = [];
    private $model;
    protected $main_model;

    /**
     * Constructor.
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new ModelsUserModel();
        $this->main_model = new AdminModel();
    }

    public function index()
    {

        $this->template->view('index', $this->data)->render();
    }
    public function dashboardData()
    {
        $data = $this->main_model->breadboard_values();
        echo json_encode($data);
    }
    public function search()
    {
        $users = $this->model->get_item('', ['task' => 'get-all-users']);
        $members = [];
        foreach ($users as $user) {
            $member = [
                'name'      => $user->first_name . ' ' . $user->last_name,
                'subtitle'  => $user->email,
                'src'       => get_avatar('user', $user->id),
                'url'       => admin_url('users-timeline/' . $user->ids)
            ];
            $members[] = $member;
        }
        $data = [
            "pages" => [
                [
                    "name" => "Dashboard Analytics",
                    "icon" => "bx-home-circle",
                    "url" => "index.html"
                ],
            ],
            "members" => $members

        ];

        return $this->response->setJSON($data);
    }
}
