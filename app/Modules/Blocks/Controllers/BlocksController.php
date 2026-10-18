<?php

namespace Blocks\Controllers;

use Blocks\Models\BlocksModel;
use Blocks\Models\QueueModel;
use CodeIgniter\Controller;

class BlocksController extends Controller
{
    public $data = [];
    public $model;

    public function __construct()
    {
        $this->model = new BlocksModel;
    }
    public function search(){
        if(post('type')=='user'){
            $this->data['items']=$this->model->getList();
        }else{
            $this->data['items']=$this->model->getListAdmin();
        }
        echo view('Blocks\Views\search', $this->data);

	}
    public function get_user_notifications()
    {
        $updateRows = ['is_read' => 1];

        if (!empty($type = post('type'))) {
            if ($type == 'all') {
                $this->model
                ->where('uid', session('uid'))
                ->set($updateRows)
                ->update();
            } elseif ($type == 'single' && !empty($id = post('id'))) {
                $this->model
                ->where('id', $id)
                ->where('uid',session('uid'))
                ->set($updateRows)
                ->update();
            }
        }

        $this->data['notItems'] = $this->model->getUserNotification();
        echo view('Blocks\Views\notifications', $this->data);
    }
    public function get_admin_notifications()
    {
        $updateRows = ['is_admin_read' => 1];

        if (!empty($type = post('type'))) {
            if ($type == 'all') {
                $this->model
                ->where('admin_status',1)
                ->set($updateRows)
                ->update();
            } elseif ($type == 'single' && !empty($id = post('id'))) {
                $this->model
                ->where('id', $id)
                ->where('admin_status',1)
                ->set($updateRows)
                ->update();
            }
        }

        $this->data['notItems'] = $this->model->getUserNotification(true);
        echo view('Blocks\Views\notifications', $this->data);
    }

    
    public function get_total_notifiaction_count() {
        $result = $this->model->getTotalNotificationCount();	
		ms($result);
    }

    public function sendEmailsToAllUsers() {
        _is_ajax();
        $rules = [
            'mail_subject'    => 'required',
            'mail_body'     => 'required|min_length[5]',
        ];

        if (!$this->validate($rules)) {
            $errors = $this->validator->listErrors();
            ms(['status' => 'error', 'message' => $errors]);
        }

        $allUserEmails = $this->model->fetch('*','users');

        $success =0;
        $failed  =0;

        foreach ($allUserEmails as $email) {
            $Queue = new QueueModel();
            
            $mail_subject = post('mail_subject');
          
            $email_content = post('mail_body');
            
            $sent = $Queue->addTask('send_email', json_encode(['to' => $email->email, 'subject' => $mail_subject, 'message' => $email_content]));
            if ($sent) {
                $success++;
            }else{
                $failed++;
            }
        }

        ms(['status'=>'success','message'=>$success.' mail sent successfully and '. $failed.' mail failed']);
    }
}
