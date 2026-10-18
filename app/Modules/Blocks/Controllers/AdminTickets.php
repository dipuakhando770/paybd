<?php

namespace Blocks\Controllers;

use Admin\Controllers\AdminController;
use Blocks\Models\TicketsModel;

class AdminTickets extends AdminController
{
    public $data = [];
    public $model;
    public $main_model;

    public function __construct()
    {
        parent::__construct();


        $this->main_model = new TicketsModel();

        $this->controller_name = 'tickets';
        $this->controller_title = 'tickets';
        $this->path_views = "admin_tickets/";
        $this->params = [];

        $this->columns = array(
            "id" => ['name' => 'ID', 'class' => 'text-center'],
            "user" => ['name' => 'User', 'class' => 'text-center'],
            "subject" => ['name' => 'Subject', 'class' => 'text-center'],
            "status" => ['name' => 'Status', 'class' => 'text-center'],
            "created" => ['name' => 'Created', 'class' => 'text-center'],
        );
    }



    public function store()
    {
        _is_ajax();


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
                $subject = "Gateway Setup";
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

        $response = $this->main_model->save_item($params, ['task' => 'add-item']);
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

        if (post('type') == 'edit') {
            $task = 'edit-item-ticket-message-admin';
        } else {
            $task = 'add-item-ticket-message-admin';
        }
        $response = $this->main_model->save_item(['ticket_id' => post('id'), 'ids' => $ids, 'message' => post('message', true)], ['task' => $task]);
        return ms($response);
    }


    public function view($ids)
    {
        $item = $this->main_model->getItem(['ids' => $ids], ['task' => 'get-admin-item']);
        if (!$item) return redirect()->to(admin_url());
        $items_ticket_message = $this->main_model->getItem(['ticket_id' => $item['id']], ['task' => 'admin-items-ticket-message']);
        $this->data = array(
            "item"                  => $item,
            "items_ticket_message"  => $items_ticket_message,
            "controller_name" => $this->controller_name,
        );
        $this->template->view('admin_tickets/view', $this->data)->render();
    }

    public function edit_item_ticket_message($ids = null)
    {
        _is_ajax();
        if ($ids !== null) {
            $item = $this->main_model->get("*", 'ticket_messages', ['ids' => $ids], '', '', true);
        }

        $this->data = array(
            "item"                  => $item,
            "controller_name"       => $this->controller_name,
        );
        echo view('Blocks\Views\admin_tickets\edit_ticket_message', $this->data);
    }
    public function changeStatus($status = "", $id = "")
    {

        if (!in_array($status, ['closed', 'pending', 'unread', 'answered']) || !$id) {
            return redirect()->to(admin_url("dashboard"));
        }
        $params = [
            'id' => $id,
            'status' => $status,
        ];
        $response = $this->main_model->save_item($params, ['task' => 'change-status']);

        if ($response['status'] && $status == 'unread') {
            return redirect()->to(admin_url("tickets"));
        } else {
            return redirect()->to(admin_url("tickets/view/" . $id));
        }
    }
    public function delete_item_ticket_message($ids = "")
    {
        _is_ajax();
        $response = $this->main_model->delete_item(['ids' => $ids], ['task' => 'delete-item-ticket-message']);
        return ms($response);
    }
}
