<?php

namespace User\Controllers;

use User\Models\UserModel;

class PlanController extends UserController
{
    public $data = [];
    public $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new UserModel();
    }

    public function index()
    {
        $this->data['items'] = $this->model->fetch('*', 'plans', ['status' => 1, 'deleted_at' => null], 'sort', 'ASC', '', '', true);
        $this->template->view('plan/index', $this->data)->render();
    }
    public function list()
    {
        $builder = $this->db->table('user_plans u');
        $builder->select('p.name, u.*');
        $builder->join('plans p', 'p.id = u.plan_id', 'left');
        $builder->where('u.uid', session('uid'));
        $builder->orderBy('u.id', 'desc');

        $this->data['items'] = $builder->get()->getResultArray();

        $this->template->view('plan/list', $this->data)->render();
    }



    public function buyPlan($ids = '')
    {
        _is_ajax();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $ids == 'apply_coupon') {
            return $this->checkCoupon(post('coupon'), post('id'));
        } else {
            $item = $this->model->get('*', 'plans', ['status' => 1, 'ids' => $ids]);
            $data['item'] = $item;
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $free_plan = $this->model->get('*', 'user_plans', ['uid' => session('uid'), 'plan_id' => 1]);
                if (post('id') == 1 && !empty($free_plan)) {
                    _validation('error', 'You cannot buy a Starter plan for multiple time!');
                }
                $discount = 0;
                if (!empty(post('coupon'))) {
                    $check_coupon = $this->checkCoupon(post('coupon'), post('id'));
                    $check_coupon = json_decode($check_coupon);
                    if ($check_coupon->status !== "success") {
                        _validation('error', $check_coupon->message);
                    }
                    $discount  = $check_coupon->discount;
                    $coupon_id = $check_coupon->coupon_id;
                }
                $users = $this->model->get('id,balance', 'users', ['id' => session('uid')]);
                $final_price = abs($item->final_price - $discount);
                if ($final_price > $users->balance) {
                    _validation('error', 'Your balance is low!!! Please add funds');
                }

                switch ($item->duration_type) {
                    case '1':
                        $duration = $item->duration;
                        break;
                    case '2':
                        $duration = $item->duration * 30;
                        break;
                    case '3':
                        $duration = $item->duration * 365;
                        break;
                    default:
                        $duration = $item->duration;
                        break;
                }

                $expire = calculateExpirationDate($duration);

                if (get_active_plan()) {
                    $user_plan = get_active_plan();
                    $get_plan = $this->model->get('*', 'plans', ['id' => $user_plan->plan_id]);
                    if ($get_plan->sort < $item->sort) {
                        $rem = $user_plan->price - calculateExpenditure($user_plan->expire, $user_plan->created_at, $user_plan->price);
                        $final_price = ($final_price - $rem) > 0 ? floor($final_price - $rem) : $final_price;
                    }
                    if ($user_plan->plan_id == post('id')) {
                        $expire = calculateExpirationDate($duration, $user_plan->expire);
                    }
                }
                $data_plan = array(
                    "uid"               => session("uid"),
                    "plan_id"           => post('id'),
                    "price"             => $final_price,
                    "brand"             => $item->brand,
                    "device"            => $item->device,
                    "transaction"       => $item->transaction,
                    "expire"            => $expire,
                    "created_at"        => now(),
                    "updated_at"        => now(),
                );
                $this->db->table('user_plans')->insert($data_plan);

                $this->db->table('users')
                    ->where('id', session('uid'))
                    ->set('balance', 'balance - ' . $final_price, false)
                    ->update();
                if ($discount > 0) {
                    $data = array(
                        "uid"               => session("uid"),
                        "coupon_id"         => $coupon_id,
                        "plan_id"           => post('id'),
                        "price"             => $item->final_price,
                        "discount"          => $discount,
                        "created_at"        => now(),
                        "updated_at"        => now(),
                    );
                    $this->db->table('user_coupons')->insert($data);

                    $this->db->table('coupons')
                        ->where('id', $coupon_id)
                        ->set('used', 'used+1', false)
                        ->update();
                }
                $message = "Your purchase of plan " . $item->name . ' for' . currency_format($item->final_price) . ' taka with a discount of ' . currency_format($discount) . ' is successful';
                $data_tnx_log = array(
                    "ids"               => ids(),
                    "uid"               => session("uid"),
                    "type"              => 'plan',
                    "transaction_id"    => trxId(),
                    "amount"            => $final_price,
                    "information"       => json_encode(['message' => $message]),
                    "status"            => 2,
                    "currency"          => "BDT",
                    "created_at"        => now(),
                    "updated_at"        => now(),

                );
                $this->db->table('user_transactions')->insert($data_tnx_log);


                ms(['status' => 'success', 'message' => 'Plan Added Successfully']);
            }
            if (!empty($data['item'])) {

                return view('User\Views\plan\buy', $data);
            }
            return view('User\Views\error');
        }
    }
    private function checkCoupon($coupon_code, $plan_id)
    {
        if ($coupon_code && $plan_id) {
            $query = $this->db->table('coupons')
                ->select('*')
                ->where('code', $coupon_code)
                ->where('status', 1)
                ->where('times > used')
                ->where('start_date <= CURDATE()')
                ->where('end_date >= CURDATE()')
                ->get();
            $result = $query->getRow();
            if (!empty($result)) {
                $user = true;
                $plan = true;

                $coupon_user = get_value($result->param, 'coupon_user');
                $coupon_plan = get_value($result->param, 'coupon_plan');
                $item = $this->model->get('*', 'plans', ['id' => $plan_id]);

                if (!empty($coupon_user) && !in_array(session('uid'), $coupon_user)) {
                    $user = false;
                }

                if (!empty($coupon_plan) && !in_array($plan_id, $coupon_plan)) {
                    $plan = false;
                }

                if ($plan && $user) {
                    if ($result->type == 0) {
                        $discount = $result->price;
                    } else {
                        $discount = $item->final_price * $result->price / 100;
                    }
                    if ($item->final_price - $discount <= 0) {
                        return json_encode(['status' => 'error', 'message' => 'Plan price is less than coupon price']);
                    }
                    return json_encode(['status' => 'success', 'plan_id' => $plan_id, 'coupon_id' => $result->id, 'discount' => $discount, 'message' => "You Got a Discount of :" . currency_format($discount)]);
                }
                return json_encode(['status' => 'error', 'message' => "This Coupon is not For You or This Plan"]);
            }
        }
        return json_encode(['status' => 'error', 'message' => 'Invalid Coupon Code']);
    }
}
