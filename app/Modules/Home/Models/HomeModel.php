<?php

namespace Home\Models;

use CodeIgniter\Model;
use Home\Entities\Home;

class HomeModel extends Model
{
    protected $allowedFields    = [];
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;

    protected $validationRules     = [];

    protected $validationMessages  = [];
    public function get_total_notification_count()
    {
        $result = [];

        if (!empty($_POST['type']) && $_POST['type'] == 'admin') {
            $totNot = $this->countResults(['a_status' => 0]);
            $totTicket = $this->count_results('id', TICKETS, ['admin_read' => 1]); // Assuming TICKETS is a constant
            $totKyc = $this->count_results('id', 'kyc', ['status' => 0]);

            $result = [
                'notification_count' => (string) $totNot,
                'tickets_count' => (string) $totTicket,
                "kyc_count" => (string) $totKyc,
            ];
        } else {
            $totNot = $this->countResults(['uid' => session('uid'), 'status' => 0]);
            $totTicket = $this->count_results('id', TICKETS, ['user_read' => 1, 'uid' => session('uid')]);

            $result = [
                'tickets_count' => (string) $totTicket,
                'notification_count' => (string) $totNot,
            ];
        }

        return $result;
    }
    public function list_items($params = null, $option = null, $id = "")
    {
        $result = null;

        if ($option['task'] == 'list-items-blogs') {
            $this->db->select('*');
            $this->db->from('general_blogs');
            $this->db->where("created < NOW()");
            $this->db->where('status', 1);
            $this->db->order_by('created', 'DESC');
            $query = $this->db->get();
            $result = $query->result_array();
        }
        if ($option['task'] == 'list-items-payments') {
            $result = $this->fetch('params', 'payments', ['status' => 1, 'id !=' => 1], 'id', 'ASC', '', '', true);
        }
        if ($option['task'] == 'list-items-blog') {
            $this->db->select('*');
            $this->db->from('general_blogs');
            $this->db->where("created < NOW()");
            $this->db->where('status', 1);
            $this->db->where('uri', $id);
            $this->db->order_by('created', 'DESC');
            $query = $this->db->get();
            $result = $query->result_array();
        }

        if ($option['task'] == 'list-items-faq') {
            $result = $this->fetch("*", 'faqs', ['status' => 1], 'sort', 'ASC', '', '', true);
        }
        if ($option['task'] == 'list-items-plans') {
            $result = $this->fetch("*", 'plans', ['status' => 1], 'sort', 'ASC', '', '', true);
        }

        return $result;
    }
    public function getItem($params = null, $option = null)
    {
        $result = null;

        if ($option['task'] == 'get-item') {
            $builder = $this->db->table('invoice i');
            $builder->select('i.ids AS invoice_ids, i.*, u.id AS user_id, u.*');
            $builder->join('users u', 'u.id = i.uid', 'left');
            $builder->where('i.ids', $params['ids']);
            $builder->where('i.status', 1);

            $query = $builder->get();
            $result = $query->getRowArray();
        }

        return $result;
    }
}
