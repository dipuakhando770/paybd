<?php

namespace Admin\Controllers;

use Admin\Models\Transactions as ModelsTransactions;

class Transactions extends UserController
{
    public $data = [];


    public function __construct()
    {
        parent::__construct();

        $this->controller_name = 'transactions';
        $this->path_views = 'transactions/';
        $this->main_model = new ModelsTransactions();

        $this->columns     =  array(
            "id"          => ['name' => '#',  'class' => 'text-center'],
            "us_email"   => ['name' => 'User Email',    'class' => 'text-center'],
            "cus_email"   => ['name' => 'Cus Email',    'class' => 'text-center'],
            "type"        => ['name' => lang('Payment_method'), 'class' => 'text-center'],
            "trxId"       => ['name' => lang('Transaction_ID'),   'class' => 'text-center'],
            "amount"      => ['name' => 'amount',    'class' => 'text-center'],
            "status"      => ['name' => 'Status',    'class' => 'text-center'],
            "created"     => ['name' => lang("Created"), 'class' => 'text-center'],
        );
    }

    public function bankTrx()
    {
        $page = (int) $this->request->getGet('page');
        $page = ($page > 0) ? ($page - 1) : 0;
        $query = $this->request->getGet('query');
        $field = $this->request->getGet('field');

        $filter_status = $this->request->getGet('status') ?? 'all';


        $this->params = [
            'filter' => ['status' => $filter_status],
            'search' => ['query' => $query, 'field' => $field],
        ];

        $itemsStatusCount = $this->main_model->count('bank');

        $this->data = [
            "controller_name"    => 'bank_transactions',
            "params"             => $this->params,
            "columns"            => $this->columns,
            "from"               => $page * $this->limit_per_page,
            "items"              => $this->main_model->helper('bank')->paginate($this->limit_per_page),
            "pagination"         => $this->main_model->pager,
            "items_status_count" => $itemsStatusCount,
        ];

        $this->template->view($this->path_views . 'index', $this->data)->render();
    }
    public function trxView($type = '', $ids = '')
    {
        _is_ajax();

        $builder = $this->db->table('transactions m');
        $builder->select('t.*,m.*');
        $builder->join('temp_transactions t', 't.ids = m.ids', 'left');
        if ($type == 'bank_transactions') {
            $builder->select('t.*,m.*,bt.*');

            $builder->join('bank_transaction_logs bt', 'bt.ids = m.ids', 'left');
        }
        $builder->where('m.ids', $ids);
        $query = $builder->get();

        $item = $query->getRow();

        if (!empty(post('type'))) {

            $this->db->table('transactions')->update(['status' => (int)post('k_status')], ['ids' => $ids]);
            $this->db->table('temp_transactions')->update(['status' => (int)post('k_status')], ['ids' => $ids]);

            if (post('type') == 'bank') {
                $this->db->table('bank_transaction_logs')->update(['status' => (int)post('k_status')], ['ids' => $ids]);
            }
            $tmp = $this->main_model->get_info_by_temp_ids($ids);

            if (!empty($tmp['all_info']['webhook_url'])) {
                $post_data = [
                    'paymentMethod'  => $item->type,
                    'transactionId'  => $tmp['all_info']['transaction_id'],
                    'paymentAmount'  => $tmp['all_info']['amount'],
                    'paymentFee'     => $tmp['all_info']['fees_amount'],
                    'status'         => post('k_status')
                ];
                simple_post($tmp['all_info']['webhook_url'], $post_data);
            }
            ms(['status' => 'success', 'message' => 'Status updated successfully', 'redirect' => current_url()]);
        }

        return view('User\Views\transactions\view', ['item' => $item]);
    }
}
