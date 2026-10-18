<?php

namespace Blocks\Controllers;

use Blocks\Models\TicketsModel;
use User\Controllers\BaseController;

class Tickets extends BaseController
{
    public $data = [];
    public $ticket_model;
    public $controller_name;
    public function __construct()
    {
        $this->ticket_model = new TicketsModel();
    }

    public function index()
    {
        $this->data['payments'] = $this->ticket_model->fetch('type, name, id, params', 'payments', ['status' => 1], 'sort', 'ASC');
        $this->data['items']    = $this->ticket_model->helper('user')->paginate(get_option('limit_per_page', 10));
        $this->data['pagination'] = $this->ticket_model->pager;
        $this->template->view('tickets/index', $this->data)->render();
    }

    public function add()
    {
        _is_ajax();
        $this->data['payments'] = $this->ticket_model->fetch('type, name, id, params', 'payments', ['status' => 1], 'sort', 'ASC');
        $this->data['items']    = $this->ticket_model->helper('user')->paginate(get_option('limit_per_page', 10));
        $this->data['pagination'] = $this->ticket_model->pager;
        echo view('Blocks\Views\tickets\add', $this->data);
    }
    public function store()
    {
        _is_ajax();

        $itemsPendingNumber = $this->ticket_model->countItems(['status' => 'pending'], ['task' => 'count-items-pending']);
        $defaultPendingTicketPerUser = get_option('default_pending_ticket_per_user', 2);

        if ($itemsPendingNumber >= $defaultPendingTicketPerUser && $defaultPendingTicketPerUser != 0) {
            ms(['status' => 'error', 'message' => 'The number of pending tickets has been limited']);
        }

        $subject = post('subject');
        $description = post('description');

        $validation = \Config\Services::validation();
        $validation->setRules([
            'subject' => 'trim|required',
            'description' => 'trim|required',
        ]);
        if (!$validation->withRequest($this->request)->run()) {
            $message = '';
            foreach ($validation->getErrors() as $va) {
                $message .= $va . '<br>';
            }
            ms(['status' => 'error', 'message' => $message]);
        }


        switch ($subject) {
            case 'subject_payment':
                $payment = post('payment');
                $transactionId = post('transaction_id');

                $validation->setRules([
                    'payment' => 'trim|required',
                    'transaction_id' => 'trim|required',
                ]);

                $subject = "Payment - {$payment} - {$transactionId}";
                break;
            case 'gateway_setup':
                $validation->setRules([
                    'site_link' => 'trim|required',
                ]);
                $subject = "Gateway Setup";
                $description = $description . "\nLink:" . post("site_link");
                break;
            case 'subject_kyc':
                $validation->setRules([
                    'kyc' => 'trim|required',
                ]);
                $subject = "Kyc";
                $description = $description . "\nKYC Id:" . post("kyc");
                break;

            default:
                $subject = lan('Other');
                break;
        }

        if (!$validation->withRequest($this->request)->run()) {
            $message = '';
            foreach ($validation->getErrors() as $va) {
                $message .= $va . '<br>';
            }
            ms(['status' => 'error', 'message' => $message]);
        }

        $params = [
            'subject' => $subject,
            'description' => $description,
        ];

        $response = $this->ticket_model->save_item($params, ['task' => 'add-item']);
        return ms($response);
    }

    public function storeMessage($ids)
    {
        _is_ajax();

        $validation = \Config\Services::validation();
        $validation->setRules([
            'message' => 'required',
            'id' => 'trim|required',
        ]);
        if (!$validation->withRequest($this->request)->run()) {
            $message = '';
            foreach ($validation->getErrors() as $va) {
                $message .= $va . '<br>';
            }
            ms(['status' => 'error', 'message' => $message]);
        }
        $task = 'add-item-ticket-message';
        $response = $this->ticket_model->save_item(['ticket_id' => post('id'), 'ids' => $ids, 'message' => post('message', true)], ['task' => $task]);
        return ms($response);
    }
    public function view($ids)
    {
        $item = $this->ticket_model->getItem(['ids' => $ids], ['task' => 'get-item']);
        if (!$item) return redirect()->to(user_url());
        $items_ticket_message = $this->ticket_model->getItem(['ticket_id' => $item['id']], ['task' => 'items-ticket-message']);

        $this->data = array(
            "item"                  => $item,
            "controller_name"       => $this->controller_name,
            "items_ticket_message"  => $items_ticket_message,
        );
        $this->template->view('tickets/view', $this->data)->render();
    }
}
