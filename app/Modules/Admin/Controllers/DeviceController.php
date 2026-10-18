<?php

namespace Admin\Controllers;

use Admin\Models\Devices;

class DeviceController extends AdminController
{
    public $admin_model;
    public function __construct()
    {
        parent::__construct();

        $this->controller_name = 'devices';
        $this->path_views = 'devices/';
        $this->main_model = new Devices();
        $this->tb_main = 'devices';
        $this->columns     =  array(
            "email"            => ['name' => 'Email', 'class' => 'text-center'],
            "device"           => ['name' => 'Device Name', 'class' => 'text-center'],
            "device_key"           => ['name' => 'Device Key', 'class' => 'text-center'],
            "device_ip"               => ['name' => 'Device ip', 'class' => 'text-center'],
        );
    }
}
