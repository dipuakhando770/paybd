<?php

namespace Admin\Libraries;

use CodeIgniter\HTTP\Response;
use Admin\Models\AdminModel;

class AdminLibrary
{
    public $response;

    public function __construct()
    {
        $config = config(App::class);
        $this->response = new Response($config);
    }
}
