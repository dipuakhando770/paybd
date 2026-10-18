<?php

namespace User\Models;

use CodeIgniter\I18n\Time;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'User\Entities\User';
    protected $allowedFields = ['ids', 'email', 'first_name', 'last_name', 'phone', 'api_credentials', 'timezone', 'ref_id', 'ref_key', 'addons', 'more_information', 'avatar', 'activation_key', 'reset_key', 'password', 'status'];
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $deletedField = 'deleted_at';
    protected $beforeFind = ['processFind'];



    protected $validationRules = [
        'email' => 'required|valid_email|is_unique[users.email]',
        'first_name' => 'required',
        'last_name' => 'required',
        // 'phone'    => 'required|is_unique[users.phone]',
        'status' => 'required|in_list[0,1]',
    ];

    protected $validationMessages = [
        'email' => [
            'required' => 'Email is required.',
            'valid_email' => 'Please enter a valid email address.',
            'is_unique' => 'Email address is already taken.',
        ],
        'phone' => [
            'required' => 'Phone is required.',
            'is_unique' => 'Phone number Already Exists.',
        ],
        'first_name' => [
            'required' => 'First Name is required.',
        ],
        'last_name' => [
            'required' => 'Last Name is required.',
        ],
        'status' => [
            'required' => 'Active status is required.',
            'in_list' => 'Invalid active status.',
        ],
    ];

    protected function processFind(array $params)
    {
        $this->where('deleted_at', NULL);
    }

    public function login()
    {
        $user = $this->where('email', post('email'))->first();
        if ($user) {
            if (password_verify(post('password'), $user->password)) {
                return $user;
            }
            ms(['status' => 'error', 'message' => 'Password doesn\'t match ']);
        }
        ms(['status' => 'error', 'message' => 'Please Provide a Correct Email...']);
    }

    public function verify_access($password, $uid = null)
    {
        $uid = $uid ?? session('uid');
        $user = $this->where('id', $uid)->first();
        if ($user) {
            if (password_verify($password, $user->password)) {
                return true;
            }
        }
        return false;
    }
    public function setNotification($uid, $message, $subject = '', $admin_status = 1)
    {
        if (!get_option('enable_notification', '1')) {
            return;
        }
        $uid = $uid ?? session('uid');

        $data = [
            'uid' => $uid,
            'message' => $message,
            'created_at' => Time::now(),
            'admin_status' => $admin_status
        ];
        $this->db->table('notifications')->insert($data);

        $subject = $subject ?? 'Notification from ' . get_option('site_name');
        $this->sendMail($subject, $message, $uid);
    }

    public function add_funds_bonus_email($data_tnx, $type = 'add')
    {
        if (!$data_tnx) {
            return false;
        }
        $data_tnx = $this->get('*', 'user_transactions', ['id' => $data_tnx]);

        if (!isset($data_tnx->transaction_id)) {
            return false;
        }

        // Update Balance  and total spent
        $user = $this->get('*', 'users', ["id" => $data_tnx->uid]);

        if (empty($user)) {
            return false;
        }

        $new_funds = $data_tnx->amount;

        if ($type == 'deduct') {
            $new_balance = $user->balance - $new_funds;
        } else {
            $new_balance = $user->balance + $new_funds;
        }

        $this->doUpdate(['id' => $data_tnx->uid], ["balance" => $new_balance]);
        if (get_option('is_addfund_bonus') == 1) {
            if (get_option('min_affiliate_amount') <= $data_tnx->amount) {
                $this->add_affiliate_bonus($data_tnx->uid, $data_tnx->amount, $type);
            }
        }
        $user->pay_amount = $data_tnx->amount;

        /*----------  Send payment notification email  ----------*/
        if ($type == 'add' && get_option("is_payment_notice_email", '')) {

            $subject = get_option('email_payment_notice_subject', '');
            $subject = str_replace("{{pay_amount}}", $user->pay_amount, $subject);

            $email_content = get_option('email_payment_notice_content', '');
            $email_content = str_replace("{{pay_amount}}", $user->pay_amount, $email_content);
            $this->sendMail($subject, $email_content, $user->id);
        }
        return true;
    }

    public function add_affiliate_bonus($uid, $amount, $type = 'add')
    {
        $user = $this->get('*', 'users', ["id" => $uid]);

        if ($user && !empty($user->ref_id)) {
            $ref_id = $user->ref_id;

            $row_count = $this->countResults('id', 'affiliates', ['uid' => $ref_id]);

            if ($type == 'deduct' || ($type == 'add' && get_option('max_affiliate_time') >= $row_count)) {
                $affiliate_bonus_type = get_option('affiliate_bonus_type');
                $affiliate_bonus = get_option('affiliate_bonus');

                $bonus = ($affiliate_bonus_type == 0) ? $affiliate_bonus : ($amount * $affiliate_bonus / 100);

                $data = [
                    'uid' => $ref_id,
                    'ref_id' => $uid,
                    'amount' => $bonus,
                    'created_at' => now()
                ];
                $this->db->table('affiliates')->insert($data);

                if ($type == 'deduct') {
                    $message = "Your account is Debited  " . $bonus . " from the referral program bonus cancelled";
                    $b_t = 'bonus_cancel';
                    $this->db->table('users')
                        ->where('id', $ref_id)
                        ->set('balance', 'balance - ' . $bonus, false)
                        ->update();
                } else {
                    $message = "You have received Tk " . $bonus . " from the referral program bonus";
                    $b_t = 'bonus';

                    $this->db->table('users')
                        ->where('id', $ref_id)
                        ->set('balance', 'balance + ' . $bonus, false)
                        ->update();
                }


                if ($this->db->affectedRows() > 0) {
                    $data_item_tnx = array(
                        "ids"               => ids(),
                        "uid"               => $ref_id,
                        "type"              => $b_t,
                        "transaction_id"    => trxId(),
                        "amount"            => $bonus,
                        "information"       => json_encode(['message' => $message]),
                        "status"            => 2,
                        "currency"          => "BDT",
                        "created_at"        => now(),
                        "updated_at"        => now(),

                    );

                    $this->db->table('user_transactions')->insert($data_item_tnx);

                    return ["status" => "success", "message" => 'Update successfully'];
                }
            }
        }
        return ["status" => "error", "message" => 'Update failed'];
    }
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

        // trx        
        $info['total_success_trx'] = $this->countResults('id', 'transactions', ['status' => 2, 'uid' => session('uid')]);
        $info['total_pending_trx'] = $this->countResults('id', 'transactions', ['status' => 1, 'uid' => session('uid')]);
        $info['success_trx'] = $text . ' ' . $this->countResults('id', 'transactions', array_merge($con, ['status' => 2, 'uid' => session('uid')]));
        $info['pending_trx'] = $text . ' ' . $this->countResults('id', 'transactions', array_merge($con, ['status' => 1, 'uid' => session('uid')]));
        // earning
        $info['total_earning'] = $this->sumColumn('amount', 'transactions', ['status' => 2, 'uid' => session('uid')]);
        $info['earning'] = $text . ' earning ' . currency_format($this->sumColumn('amount', 'transactions', array_merge($con, ['status' => 2, 'uid' => session('uid')])));

        $info['type'] = $this->getSumByType('transactions', 'type', 'amount', array_merge($con, ['status' => 2, 'uid' => session('uid')]));
        $info['type_all'] = $this->getCountByType('transactions', 'type', array_merge($con, ['status' => 2, 'uid' => session('uid')]), 'type');
        $info['type_0'] = $this->getSumByType('transactions', 'type', 'amount', array_merge($con, ['status' => 1, 'uid' => session('uid')]));
        $info['type_2'] = $this->getSumByType('transactions', 'type', 'amount', array_merge($con, ['uid' => session('uid')]));

        // Generate the HTML for the list items
        $info['listItems'] = $this->generateList($info);
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
}
