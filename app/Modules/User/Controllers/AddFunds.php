<?php

namespace User\Controllers;

use App\Libraries\GatewayApi;
use User\Models\UserModel;
use User\Controllers\BaseController;

class AddFunds extends UserController
{
    public $data = [];
    public $model, $apikey, $convert_rate, $payment_lib;

    public function __construct()
    {
        parent::__construct();
        $this->model = new UserModel();
        $this->payment_lib = new GatewayApi();
        $this->convert_rate = !empty(get_option('new_currecry_rate')) ? (float)get_option('new_currecry_rate') : 1;
        $this->apikey = $this->model->get('brand_key,uid', 'brands', ['uid' => 1])->brand_key;
    }

    public function index()
    {
        _is_ajax();
        $payments = $this->model->get('type, name, id, params', 'payments', ['id' => 1], 'sort', 'ASC');
        $data = array(
            "module"          => get_class($this),
            "payments"        => $payments,
            "currency_code"   => get_option("currency_code", 'BDT'),
            "currency_symbol" => get_option("currency_symbol", '৳'),
        );
        return view('User\Views\add_funds\index', $data);
    }

    public function process()
    {
        _is_ajax();
        $payment_id     = (int)post("payment_id");

        $amount         = (float)post("amount");
        $agree = post("agree");
        if ($amount == "") {
            ms(array(
                "status" => "error",
                "message" => lang("amount_is_required"),
            ));
        }

        if ($amount < 0) {
            ms(array(
                "status" => "error",
                "message" => lang("amount_must_be_greater_than_zero"),
            ));
        }

        /*----------  Check payment method  ----------*/
        $payment = $this->model->get('id, type, name, params', 'payments', ['id' => $payment_id]);
        if (!$payment) {
            _validation('error', lang('There_was_an_error_processing_your_request_Please_try_again_later'));
        }

        $min_payment = get_value($payment->params, 'min');
        $max_payment = get_value($payment->params, 'max');

        if ($amount < $min_payment) {
            _validation('error', lang("minimum_amount_is") . " " . $min_payment);
        }

        if ($max_payment > 0 && $amount > $max_payment) {
            _validation('error', 'Maximal amount is' . " " . $max_payment);
        }

        if (!$agree) {
            _validation('error', lang("you_must_confirm_to_the_conditions_before_paying"));
        }

        $response = $this->create_payment($amount);
        if (!empty($response)) {
            $res = json_decode($response);
            log_message('alert',$response);
            if ($res->status == 1) {
                ms(['status' => 'success', 'message' => 'You are being redirected to payment page', 'redirect_url' => $res->payment_url]);
            }
        }

        _validation('error', lang('There_was_an_error_processing_your_request_Please_try_again_later'));
    }
    public function create_payment($amount)
    {

        _is_ajax();
        if (!$amount) {
            _validation('error', lang('There_was_an_error_processing_your_request_Please_try_again_later'));
        }


        if (!$this->apikey) {
            _validation('error', lang('this_payment_is_not_active_please_choose_another_payment_or_contact_us_for_more_detail'));
        }


        $users  = current_user();

        $full_name = $users->first_name .  $users->last_name;


        $amount = $amount * $this->convert_rate;

        $cus_name = (isset($full_name)) ? $full_name : 'John Doe';
        $cus_email = $users->email;
        $success_url = user_url("add_funds/complete/success");
        $cancel_url = user_url("add_funds/complete/unsuccess");
        $webhook_url = user_url("add_funds/complete/validate");

        $data   = array(
            "cus_name"          => $cus_name,
            "cus_email"         => $cus_email,
            "amount"            => $amount,
            "metadata"          => ['phone' => $users->phone, 'uid' => $users->id],
            "success_url"       => $success_url,
            "webhook_url"       => $webhook_url,
            "cancel_url"        => $cancel_url,
        );

        $header   = array(
            "api"               => $this->apikey,
            "url"               => getenv('PAYMENT_URL') . 'api/payment/create',
        );
        $response = $this->payment_lib->payment($data, $header);
        return $response;
    }

    /**
     *
     * Call Execute payment after creating payment
     *
     */
    public function complete($type = '')
    {
        if ($type == 'validate') {
            $trxId = $_REQUEST['transactionId'];

            $data   = array(
                "transaction_id"         => $trxId,
            );
            $header   = array(
                "api"               => $this->apikey,
                "url"               => getenv('PAYMENT_URL') . 'api/payment/verify',

            );

            $response = $this->payment_lib->payment($data, $header);
            $data = json_decode($response);

            if (!empty($data)) {
                set_session('uid', get_value($data->metadata, 'uid'));

                $se = $this->model->get('*', 'user_transactions', ['transaction_id' => $trxId]);

                if (empty($se)) {
                    if ($data->status == 'COMPLETED') {
                        $this->db = db_connect();
                        $message = "Add fund amount of" . currency_format($data->amount) . " is " . strtolower($data->status);
                        $data_item_tnx = [
                            "ids"               => ids(),
                            "uid"               => session("uid"),
                            "type"              => 'add_balance',
                            "transaction_id"    => $trxId,
                            "amount"            => $data->amount,
                            "information"       => json_encode(['message' => $message]),
                            "status"            => 2,
                            "currency"          => "BDT",
                            "created_at"        => now(),
                            "updated_at"        => now(),
                        ];
                        $this->db->table('user_transactions')->insert($data_item_tnx);
                        $insertId = $this->db->insertID();
                        $this->db->close();
                        $this->model->add_funds_bonus_email($insertId);
                    }
                } else {
                    $sta = $data->status == 'COMPLETED' ? 2 : ($data->status == 'PENDING' ? 1 : 3);
                    if ($se->status == 2 && $sta != 2) {
                        $this->db->table('user_transactions')->update(['status' => $sta], ['transaction_id' => $trxId]);
                        $this->model->add_funds_bonus_email($se->id, 'deduct');
                    } elseif ($se->status != 2 && $sta == 2) {
                        $this->model->add_funds_bonus_email($se->id, 'add');
                    }
                }
            }
        } elseif (!empty($_GET['transactionId']) && $type == 'success') {
            $transaction_id = $_GET['transactionId'];
            $transaction = $this->model->get("*", 'transactions', ['transaction_id' => $transaction_id]);
            if (!empty($transaction)) {
                if ($transaction->status == 1) {
                    set_flashdata('message', ['message' => 'Your request is sent for Review', 'status' => 'warning']);
                } else {
                    set_flashdata('message', ['message' => 'Your Balance added Successfully', 'status' => 'success']);
                }

                $data = array(
                    "module" => get_class($this),
                    "transaction" => $transaction,
                );
                return $this->template->view('add_funds/payment_successfully', $data)->render();
            } else {
                set_flashdata('message', ['message' => 'Sorry! Failed to add Balance! Please Contact with Administrator', 'status' => 'error']);

                redirect()->to(user_url());
            }
        }
        set_flashdata('message', ['message' => 'Sorry! Failed to add Balance! Please Contact with Administrator', 'status' => 'error']);
        return $this->template->view('add_funds/payment_unsuccessfully', $this->data)->render();
    }
}
