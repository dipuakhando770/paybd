<?php

namespace Blocks\Models;

use CodeIgniter\Model;

class BlocksModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $allowedFields = ['is_read','is_admin_read','admin_status', 'message', 'created'];

    public function getUserNotification($is_admin=false)
    {
        if($is_admin){
            return $this->where(['admin_status' => 1, 'is_admin_read' => 0])
                    ->orderBy('id', 'desc')
                    ->findAll();
        }
        return $this->where(['uid' => session('uid'), 'is_read' => 0])
                    ->orderBy('id', 'desc')
                    ->findAll();
    }
    
    public function getTotalNotificationCount()
    {
        $result = [];

        if (!empty(post('type')) && post('type') == 'admin') {
            $totNot = $this->countResults('id', 'notifications', ['admin_status' => 1,'is_admin_read'=>0]);
            $totTicket = $this->countResults('id', 'tickets', ['is_admin_read' => 0]);
            $result = [
                'notification_count' => (string)$totNot,
                'tickets_count' => (string)$totTicket,
            ];
        } else {
            $totNot = $this->countResults('id', 'notifications', ['uid' => session('uid'), 'is_read' => 0]);
            $totTicket = $this->countResults('id', 'tickets', ['is_user_read' => 0, 'uid' => session('uid')]);

            $result = [
                'notification_count' => (string)$totNot,
                'tickets_count' => (string)$totTicket,
            ];
        }

        return $result;
    }
    public function trash()
    {   
       return;
    }
    public function getList()
    {
        $input = post('search_input');

        $ts_arr = [];
        $ts = $this->db->table('notifications')
            ->like('created_at', $input)
            ->orLike('message', $input)
            ->where('uid', session('uid'))
            ->get()
            ->getResultArray();

        foreach ($ts as $t) {
            $ts_data = [
                'search' => $t['transaction_id'],
                'link' => user_url('transactions?query=' . $t['transaction_id'] . '&field=transaction_id'),
                'from' => 'Transaction',
                'created' => $t['created'],
            ];
            $ts_arr[] = $ts_data;
        }
        $b=['dashboard'=>'Dashboard','transactions'=>'Transactions','bank_transactions'=>'Bank Transactions','wallet'=>'Wallet','invoice'=>'Invoice','plans'=>'Plans','user_setting'=>'Settings','tickets'=>'Tickets','profile'=>'Profile'];
		
        $ts=arrayLike($input,$b);
		foreach($ts as $k => $t){
			$ts_data = [
				'search'=>$t,
				'link'=>user_url($k),
				'from'=>'Menu',
				'created'=>'',
			];
			$ts_arr[] = $ts_data;
		}

        return $ts_arr;
    }

    public function getListAdmin()
    {
        $input = post('search_input');

        $ts_arr = [];

        // Search from transaction
        $ts = $this->db->table('users')
            ->like('name', $input)
            ->orLike('email', $input)
            ->get()
            ->getResultArray();

        foreach ($ts as $t) {
            $ts_data = [
                'search' => $t['transaction_id'],
                'link' => base_url('transactions?query=' . $t['transaction_id'] . '&field=transaction_id'),
                'from' => 'Transaction',
                'created' => $t['created'],
            ];
            $ts_arr[] = $ts_data;
        }


        return $ts_arr;
    }

}
