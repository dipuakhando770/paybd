<?php

namespace Admin\Models;

use Admin\Entities\Admin;
use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table            = 'staffs';
    protected $allowedFields = ['ids', 'email', 'first_name', 'last_name', 'more_information', 'avatar', 'activation_key', 'reset_key', 'password', 'status'];
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;

    // Validation
    protected $validationRules     = [];

    protected $validationMessages  = [];

    public function breadboard_values()
    {
        $info = array();
        $period = post('period');

        switch ($period) {
            case 'today':
                $start = date('Y-m-d 00:00:00');
                $end = date('Y-m-d 23:59:59');
                $text = 'Today';
                break;
            case 'week':
                $start = date('Y-m-d 00:00:00', strtotime('monday this week'));
                $end = date('Y-m-d 23:59:59', strtotime('sunday this week'));
                $text = 'This Week';
                break;
            case 'month':
                $start = date('Y-m-01 00:00:00');
                $end = date('Y-m-t 23:59:59');
                $text = 'This Month';
                break;
            case 'year':
                $start = date('Y-01-01 00:00:00');
                $end = date('Y-12-31 23:59:59');
                $text = 'This Year';
                break;
        }

        $con = ['created_at >=' => $start, 'created_at <=' => $end];

        // /users
        $info['total_users'] = $this->countResults('id', 'users', []);
        $info['total_active_users'] = $this->countResults('id', 'users', ['status' => 1]);
        $info['total_tickets'] = $this->countResults('id', 'tickets', []);
        $info['total_pending_tickets'] = $this->countResults('id', 'tickets', ['status' => 'pending']);

        $info['total_invoices'] = $this->countResults('id', 'invoice', []);
        $info['total_pending_invoices'] = $this->countResults('id', 'invoice', ['status' => 0]);

        // trx        
        $info['total_success_trx'] = $this->countResults('id', 'transactions', ['status' => 2]);
        $info['total_pending_trx'] = $this->countResults('id', 'transactions', ['status' => 1]);
        $info['success_trx'] = $text . ' ' . $this->countResults('id', 'transactions', array_merge($con, ['status' => 2]));
        $info['pending_trx'] = $text . ' ' . $this->countResults('id', 'transactions', array_merge($con, ['status' => 1]));
        // earning
        $info['total_earning'] = $this->sumColumn('price', 'user_plans', []);
        $info['earning'] = $text . ' earning ' . currency_format($this->sumColumn('price', 'user_plans', $con));

        $info['type'] = $this->getSumByType('transactions', 'type', 'amount', array_merge($con, ['status' => 2]));
        $info['type_all'] = $this->getCountByType('transactions', 'type', array_merge($con, ['status' => 2]));
        $info['type_0'] = $this->getSumByType('transactions', 'type', 'amount', array_merge($con, ['status' => 1]));
        $info['type_2'] = $this->getSumByType('transactions', 'type', 'amount', array_merge($con, []));

        $info['type_plans_all'] = $this->getCountByType('user_plans', 'plan_id', $con);

        $info['type_plans'] = $this->getSumByType('user_plans', 'plan_id', 'price', array_merge($con, []));
        // Generate the HTML for the list items
        $info['listItems'] = $this->generateList($info);
        $info['PlanlistItems'] = $this->generatePlanList($info);
        return $info;
    }

    public function generateList($data = [])
    {
        $listItems = '';
        foreach ($data['type'] as $item) {
            $typeName = $item['type'];
            $typeAmount = $item['total_amount'];
            $typeCount = '';
            foreach ($data['type_all'] as $type) {
                if ($type['type'] == $typeName) {
                    $typeCount = $type['total_count'];
                    break;
                }
            }
            $type0Amount = '';
            foreach ($data['type_0'] as $type) {
                if ($type['type'] == $typeName) {
                    $type0Amount = $type['total_amount'];
                    break;
                }
            }
            $type2Amount = '';

            foreach ($data['type_2'] as $type) {
                if ($type['type'] == $typeName) {
                    $type2Amount = $type['total_amount'];
                    break;
                }
            }
            $s_p = (is_numeric($type2Amount) && $type2Amount != 0) ? (((int)$typeAmount / (int)$type2Amount) * 100) . '%' : '100%';
            $f_p = (is_numeric($type2Amount) && $type2Amount != 0) ? (((int)$type0Amount / (int)$type2Amount) * 100) . '%' : '100%';

            $listItems .= '
                <li class="product-list">
                    <img class="msr-3 rounded" width="55" src="' . base_url(payment_option($typeName)) . '" alt="product">
                    <div class="set-flex">
                        <div class="float-end">
                            <div class="font-weight-600 text-muted text-small">Total ' . strtoupper($typeName) . '</div>
                            <div class="text-small">' . $typeCount . '</div>
                        </div>
                        <div class="fw-bold font-15">' . strtoupper($typeName) . '</div>
                        <div class="mt-1">
                            <div class="budget-price">
                                <div class="budget-price-square bg-primary" style="width: ' . $s_p . '"></div>
                                <div class="budget-price-label">Success-' . ': ' . currency_format((int)$typeAmount) . '</div>
                            </div>
                            <div class="budget-price">
                                <div class="budget-price-square bg-danger" style="width: ' . $f_p . '"></div>
                                <div class="budget-price-label">Pending-' . ': ' . currency_format((int)$type0Amount) . '</div>
                            </div>
                        </div>
                    </div>
                </li>';
        }

        return $listItems;
    }
    public function generatePlanList($data = [])
    {
        $listItems = '';
        foreach ($data['type_plans'] as $item) {
            $typeName = $item['plan_id'];
            $typeAmount = $item['total_amount'];
            $typeCount = '';
            foreach ($data['type_plans_all'] as $type) {
                if ($type['plan_id'] == $typeName) {
                    $typeCount = $type['total_count'];
                    break;
                }
            }

            $s_p = '50%';
            $listItems .= '
                <li class="product-list">
                    <div class="set-flex">
                        <div class="float-end">
                            <div class="font-weight-600 text-muted text-small">Total Plan: ' . current_plan('name', $item['plan_id']) . '</div>
                            <div class="text-small">' . $typeCount . '</div>
                        </div>
                        <div class="fw-bold font-15">' . current_plan('name', $item['plan_id']) . '</div>
                        <div class="mt-1">
                            <div class="budget-price">
                                <div class="budget-price-square bg-primary" style="width: ' . $s_p . '"></div>
                                <div class="budget-price-label">Success-' . ': ' . currency_format((int)$typeAmount) . '</div>
                            </div>
                            
                        </div>
                    </div>
                </li>';
        }

        return $listItems;
    }

    public function login()
    {
        $admin = $this->where('email', post('email'))->first();
        if (!empty($admin)) {
            if (password_verify(post('password'), $admin->password)) {
                return $admin;
            }
            ms(['status' => 'error', 'message' => 'Password doesn\'t match ']);
        }
        ms(['status' => 'error', 'message' => 'Please Provide a Correct Email...']);
    }
    public function verify_access($password, $sid = null)
    {
        if ($sid === null) {
            $sid = session('sid');
        }

        if ($sid) {
            $admin = $this->where('id', $sid)->first();
            if ($admin) {
                if (password_verify($password, $admin->password)) {
                    return true;
                }
            }
        }
        return false;
    }
    public function trash()
    {
        return;
    }
    public function save_item($params = null, $option = null)
    {

        switch ($option['task']) {
            case 'bulk-action':
                if (in_array($params['type'], ['delete', 'deactive', 'active'])) {
                    if (empty($params['ids'])) {
                        return ["status"  => "error", "message" => 'Please choose at least one item'];
                    } else {
                        $arr_ids = convert_str_number_list_to_array($params['ids']);
                    }
                }
                switch ($params['type']) {
                    case 'delete':

                        $this->db->table('addons')->whereIn('id', $arr_ids)->delete();

                        return ["status"  => "success", "message" => 'Deleted successfully'];
                        break;
                    case 'deactive':
                        $this->db->table('addons')->whereIn('id', $arr_ids)->update(['status' => 0]);
                        return ["status"  => "success", "message" => 'Deactivated successfully'];
                        break;
                    case 'active':
                        $this->db->table('addons')->whereIn('id', $arr_ids)->update(['status' => 1]);

                        return ["status"  => "success", "message" => 'Activated successfully'];
                        break;
                }
                break;
        }
    }

    public function delete_item($params = null, $option = null)
    {

        $result = [];
        if ($option['task'] == 'delete-item') {
            $item = $this->get("id", 'addons', ['id' => $params['id']]);
            if ($item) {
                $this->db->table('addons')->where('id', $item->id)->delete();

                $result = [
                    'status' => 'success',
                    'message' => 'Deleted successfully',
                    "ids"     => $item->id,
                ];
                $this->setLogs("Addon deleted");
            } else {
                $result = [
                    'status' => 'error',
                    'message' => 'There was an error processing your request. Please try again later',
                ];
            }
        }
        return $result;
    }
}
