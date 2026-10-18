<?php

namespace User\Controllers;

use User\Models\UserModel;
use User\Controllers\BaseController;

class AddonController extends UserController
{
    public $data = [];
    public $model,$tb_main;

    public function __construct()
    {
        parent::__construct();
        $this->tb_main = "addons";
        $this->main_model = new UserModel();
    }

    public function index()
    {
        $items = $this->main_model->fetch('*',$this->tb_main);
        $data = [
            "items" =>$items
        ];
        $this->template->view('settings/addons',$data)->render();
    }

        

    public function store()
    {
        _is_ajax();
        $rules = [
            'id' => 'trim|required|xss_clean'
        ];
        if (!$this->validate($rules)) {
            $errors = $this->validator->listErrors();
            ms(['status' => 'error', 'message' => $errors]);
        }

        $item = $this->main_model->get('*','addons',['unique_identifier'=>post('id')],'','',true);

        if (empty($item)) {
            _validation('error', 'Something went wrong.!');
        }

        
        $user_balance = current_user()->balance;


        $final_price = $item['price'];

        if ($final_price > current_user()->balance ) {
           _validation('error', 'Your balance is low!!! Please add funds');
        }
        
        $message = "Your purchase of a addon ".$item['name'].' for '.currency_format($item['price']).' taka is successful';

        $data_tnx_log = array(
            "ids"               => ids(),
            "uid"               => session("uid"),
            "type"              => 'Account',
            "transaction_id"    => trxId(),
            "amount"            => $final_price,
            "message"           => $message,
            "status"            => 1,
            "created"           => now(),
        );
        $this->db->table('user_transactions')->insert($data_tnx_log);

        $this->db->table('users')
            ->where('id', session('uid'))
            ->set('balance', 'balance - ' . $final_price, false)
            ->update();
        if ($this->db->affectedRows() <= 0) {
            ms([
                'status' => 'error',
                'message' => 'Something went wrong! <br>Try again please...',
            ]);
        }
        $payment_link = get_value(current_user()->addons,'payment_link');
        $sms = get_value(current_user()->addons,'sms');

        if ($item['unique_identifier'] == 'payment_link') {
            $payment_link =1;
        }elseif ($item['unique_identifier'] == 'sms') {
            $sms =1;
        }

        $data = [
            'addons'=>json_encode([
                'payment_link' => $payment_link,
                'sms'          => $sms 
            ])
        ];
        $this->db->table('users')
            ->where('id', session('uid'))
            ->update($data);

        
        if ($this->db->affectedRows() <= 0) {
            $this->db->table('users')
            ->where('id', session('uid'))
            ->set('balance', 'balance + ' . $final_price, false)
            ->update();
            ms([
                'status' => 'error',
                'message' => 'Something went wrong! <br>Try again please...',
            ]);
        }
        $message = "New addon (".$item['name'].') for'.currency_format($item['price']).' taka is successfully added';
        $this->params = ['message' => $message, 'amount'=>$final_price];
        
        if (get_option('is_user_addon_sms')==1) {
            
            $this->load->library('Smssender');
        
            $templateKey = 'user_addon_sms'; 

            $params = [
                'website_name' => get_option("website_name"),
                'first_name'   =>current_user()->first_name,
                'last_name'    =>current_user()->last_name,
                'pay_amount'   => $item['price'],
                'date'         => now(),
                'addon'        => $item['name'],
            ];
            $number = current_user()->phone; 
            $uid = session('uid');
            
            $response = $this->smssender->send_sms($templateKey, $params, null, $number);            

        }

        $this->main_model->save_item($this->params,['task'=>'send-mail']);

        ms([
            'status' => 'success',
            'message' => 'Your request is being processed',
            'redirect' => user_url('addons'),
        ]);
        

    }
}
