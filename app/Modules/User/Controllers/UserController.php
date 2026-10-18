<?php

namespace User\Controllers;

use CodeIgniter\I18n\Time;
use User\Controllers\BaseController;
use App\Libraries\Phpspreadsheet_lib;
use App\Libraries\QR_lib;

class UserController extends BaseController
{
    public    $data = [];
    protected $main_model;
    protected $tb_main;
    protected $controller_title  = '';
    protected $controller_name   = '';
    protected $path_views        = '';
    protected $params = [];
    protected $columns = [];
    protected $limit_per_page = 50;
    protected $sidebarItems;
    public $db;

    public function __construct()
    {
        $this->limit_per_page = get_option("default_limit_per_page", 10);
        $this->db = db_connect();
    } 

    public function index()
    {
        $page = (int) $this->request->getGet('page');
        $page = ($page > 0) ? ($page - 1) : 0;
        $query = $this->request->getGet('query');
        $field = $this->request->getGet('field');

        $filter_status = $this->request->getGet('status')??'all';
        

        $this->params = [
            'filter' => ['status' => $filter_status],
            'search' => ['query' => $query, 'field' =>$field ],
        ];

        $itemsStatusCount = $this->main_model->count();

        $this->data = [
            "controller_name"    => $this->controller_name,
            "params"             => $this->params,
            "columns"            => $this->columns, 
            "from"               => $page * $this->limit_per_page,
            "items"              => $this->main_model->helper()->paginate($this->limit_per_page),
            "pagination"         => $this->main_model->pager ,
            "items_status_count" => $itemsStatusCount,
        ];

        $this->template->view($this->path_views.'index',$this->data)->render(); 
    }

    public function update($id = null)
    {
        // _is_ajax();

        $item = null;

        if ($id !== null) {
            $this->params = [
                'id' => $id,
                'ids' => $id,
            ];
            $uid = session('uid');
            $item = $this->main_model->get('*',$this->tb_main,"uid = $uid AND (id = $id OR ids = $id) ",'','',true);
        }

        $this->data = [
            "controller_name" => $this->controller_name,
            "item" => $item,
        ];

        return view('User\Views\\'.$this->path_views . 'update', $this->data);
    }

    public function changeStatus($id = "")
    {
        // _is_ajax();

        $params = [
            'id' => $id,
            'status' => (int) post('status'),
        ];
        $response = $this->main_model->save_item($params, ['task' => 'change-status']);

        ms($response);
    }
    // Bulk action
    public function bulk_action($type = "")
    {
        _is_ajax();

        $params = [
            'ids'       => post('ids'),
            'type'      => $type,
        ];
        $response = $this->main_model->save_item($params, ['task' => 'bulk-action']);
        ms($response);
    }

    // Delete Item
    public function delete($id = "")
    {
        _is_ajax();
        $params['id'] = $id;
        $response = $this->main_model->delete_item($params, ['task' => 'delete-item']);
        ms($response);
    }
    
    public function export($type = "")
    {
        $items = $this->main_model->fetch('*','users');
        $phpexel = new Phpspreadsheet_lib();
        
        $columns = array_keys((array)$items[0]);
        $filename = $this->controller_name . '-' . date("d-m-Y", strtotime(now()));
        switch ($type) {
            case 'excel':
                if (!empty($items)) {
                    $filename .= ".xlsx";
                    $phpexel->export_excel($columns, $items, $filename);
                }
                break;
            case 'csv':
                if (!empty($items)) {
                    $filename .= ".csv";
                    $phpexel->export_csv($columns, $items, $filename);
                }
                break;

        }
        return redirect()->to(user_url());
    }
    public function qrCode(){
        $content = $_GET['content']??"Empty";
        $qr = new QR_lib();
        echo $qr->ccreate($content);
    }

    public function qrCode2($type=''){
        switch ($type) {
            case 'affiliate':
                $content = base_url('affiliates/'.current_user('ref_key'));
                break;
            case 'signin':
                $data['email']      = current_user('email');
                $data['device_key'] = "LKJ";
                $content = base_url('device-connect&'.http_build_query($data));
                break;
            
            default:
                $content = base_url('affiliates/'.current_user('ref_key'));
                break;
        }
        $qr = new QR_lib();
        echo $qr->ccreate($content);
    }


}
