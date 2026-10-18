<?php

namespace Admin\Controllers;

use Admin\Models\Brands;

class BrandController extends AdminController
{
    public $admin_model;
    public function __construct()
    {
        parent::__construct();

        $this->controller_name = 'brands';
        $this->path_views = 'brand/';
        $this->main_model = new Brands();
        $this->tb_main = 'brands';
        $this->columns     =  array(
            "name"             => ['name' => 'Merchant Email',    'class' => 'text-center'],
            "email"            => ['name' => 'Business Email', 'class' => 'text-center'],
            "domain"           => ['name' => 'Domain', 'class' => 'text-center'],
            "ip"               => ['name' => 'ip', 'class' => 'text-center'],
            "status"           => ['name' => 'Status',  'class' => 'text-center'],
        );
    }
}
