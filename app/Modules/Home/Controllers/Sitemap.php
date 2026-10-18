<?php

namespace Home\Controllers;

use App\Controllers\BaseController;

class Sitemap extends BaseController
{
    public $data;

    public function  index(){
        
        
        $items = cache('sitemap_data');

        $this->data['items'] = $items;
        $this->response->setHeader('Content-type', 'text/xml')->setHeader('charset', 'utf-8');

        echo view('Home\Views\sitemap\xml', $this->data);
    }

}
