<?php

namespace Admin\Models;

use CodeIgniter\I18n\Time;
use CodeIgniter\Model;

class Transactions extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['*'];
    public $field_search_accepted;
    public function __construct()
    {
        parent::__construct();
        $this->field_search_accepted = app_config('config')['search']['transactions'];
    }

    public function helper($type = '')
    {
        $query = get('query');
        $field = get('field');

        $filter_status = get('status') ?? 'all';

        $this->builder = $this->db->table('transactions');

        if ($filter_status != 'all') {
            $this->builder->where('transactions.status', $filter_status);
        }
        if ($field == 'all') {
            $i = 1;
            foreach ($this->field_search_accepted as $column) {
                if ($column != 'all') {
                    if ($i == 1) {
                        $this->builder->like('transactions.' . $column, $query);
                        $this->builder->orLike('transactions.message', $query);
                    } elseif ($i > 1) {
                        $this->builder->orLike('transactions.' . $column, $query);
                    }
                    $i++;
                }
            }
        } elseif (!empty($query) && !empty($field)) {
            $this->builder->like('transactions.' . $field, $query);
        }

        $this->builder->select('tm.*,transactions.* ');
        if ($type == 'bank') {
            $this->builder->join('bank_transaction_logs bl', 'bl.ids = transactions.ids', 'right');
        }
        $this->builder->join('temp_transactions tm', 'tm.ids = transactions.ids', 'left');
        $this->builder->orderBy('transactions.id', 'DESC');

        return $this;
    }
    public function count($type = '')
    {
        $this->builder = $this->db->table('transactions');

        if ($type == 'bank') {
            $this->builder->join('bank_transaction_logs bl', 'bl.ids = transactions.ids', 'right');
        }

        $result = $this->builder
            ->select('COUNT(transactions.id) as count, transactions.status')
            ->groupBy('transactions.status')
            ->get()
            ->getResultArray();

        return $result;
    }


    public function get_info_by_temp_ids($ids = '')
    {

        $result = '';

        $tmp = $this->get('*', 'temp_transactions', ['ids' => $ids]);

        if ($tmp) {
            $b_info = $this->get('*', 'brands', ['id' => $tmp->brand_id, 'uid' => $tmp->uid]);
            $fees = 0;
            if (!empty($b_info->fees_amount)) {
                if ($b_info->fees_type == 0) {
                    $fees = $b_info->fees_amount;
                } else {
                    $fees = $b_info->fees_amount * $tmp->amount / 100;
                }
            }
            $rate = 1;

            $all_info = [
                'brand_id' => $b_info->id,
                'brand_logo' => $b_info->brand_logo,
                'brand_name' => $b_info->brand_name,
                'support_mail' => get_value($b_info->meta, 'support_mail'),
                'mobile_number' => get_value($b_info->meta, 'mobile_number'),
                'whatsapp_number' => get_value($b_info->meta, 'whatsapp_number'),
                'fees_type' => $b_info->fees_type,
                'fees_amount' => $rate * $fees,
                'b_fees_amount' => $fees,
                'amount' => $rate * $tmp->amount,
                'b_amount' => $tmp->amount,
                'total_amount' => ceil($rate * ($tmp->amount + $fees)),
                'b_total_amount' => ceil($tmp->amount + $fees),
                'cus_name' => get_value($tmp->params, 'cus_name'),
                'cus_email' => get_value($tmp->params, 'cus_email'),
                'cus_phone' => get_value($tmp->params, 'cus_phone'),
                'success_url' => get_value($tmp->params, 'success_url'),
                'cancel_url' => get_value($tmp->params, 'cancel_url'),
                'webhook_url' => get_value($tmp->params, 'webhook_url'),
                'transaction_id' => $tmp->transaction_id,
                'tmp_ids' => $tmp->ids,
                'uid' => $tmp->uid,
                'currency' => get_option('currency_code'),
            ];

            $mobile_s = $this->fetch('*', 'user_payment_settings', ['uid' => $tmp->uid, 'brand_id' => $tmp->brand_id, 'status' => 1, 't_type' => 'mobile']);
            $bank_s = $this->fetch('*', 'user_payment_settings', ['uid' => $tmp->uid, 'brand_id' => $tmp->brand_id, 'status' => 1, 't_type' => 'bank']);
            $int_b_s = $this->fetch('*', 'user_payment_settings', ['uid' => $tmp->uid, 'brand_id' => $tmp->brand_id, 'status' => 1, 't_type' => 'int_b']);

            $newArray_mobile = [];
            $newArray_int_bank = [];

            foreach ($mobile_s as $item) {
                $params = json_decode($item->params, true);
                $active_payments = isset($params['active_payments']) ? $params['active_payments'] : [];

                foreach ($active_payments as $payment_type => $value) {
                    if ($value == 1) {
                        $newItem = clone $item;
                        $newItem->active_payment = $payment_type;
                        $newArray_mobile[] = $newItem;
                    }
                }
            }
            foreach ($int_b_s as $item) {
                $params = json_decode($item->params, true);
                $active_payments = isset($params['active_payments']) ? $params['active_payments'] : [];

                foreach ($active_payments as $payment_type => $value) {
                    if ($value == 1) {
                        $newItem = clone $item;
                        $newItem->active_payment = $payment_type;
                        $newArray_int_bank[] = $newItem;
                    }
                }
            }

            $data = array(
                'all_info' => $all_info,
                'mobile_s' => $newArray_mobile,
                'bank_s'   => $bank_s,
                'int_b_s'  => $newArray_int_bank,
                'rate'     => $rate
            );
            $result = $data;
        }

        return $result;
    }

    public function trash()
    {
        return [];
    }
}
