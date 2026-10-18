<?php

namespace Home\Controllers;

use App\Controllers\BaseController;
use App\Libraries\GatewayApi;
use Blocks\Models\QueueModel;
use CodeIgniter\Debug\Exceptions;
use Home\Models\HomeModel;

class HomeController extends BaseController
{
    public $data = [];
    public $model, $db, $params, $apikey, $payment_lib;
    public function __construct()
    {
        $this->model = new HomeModel;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            "payments"     => $this->model->list_items(null, ['task' => 'list-items-payments']),
            "items"        => $this->model->list_items(null, ['task' => 'list-items-faq']),
            "plans"        => $this->model->list_items(null, ['task' => 'list-items-plans']),
        ];
        return  $this->template->view('index', $data)->render();
        // return  $this->template->view('index', $data)->render( ['cache' => 60, 'cache_name' => 'home']);
    }
    public function terms()
    {
        return  $this->template->view('terms')->render();
    }
    public function privacy()
    {
        return  $this->template->view('privacy')->render();
    }

    public function blogs()
    {
        $item = $this->model->fetch('*', 'blogs', ['status' => 1, 'created_at <=' => now()], 'id');
        return  $this->template->view('blogs', ['items' => $item])->render();
    }

    public function blogSingle($uri)
    {
        $item = $this->model->fetch('*', 'blogs', ['status' => 1, 'created_at <=' => now()], 'id');
        $blog = $this->model->get('*', 'blogs', ['uri' => $uri, 'status' => 1], 'id');

        if (!empty($blog)) {
            $data = [
                "og_title"        => $blog->title,
                "og_description"  => $blog->title,
                "og_image"        => base_url($blog->thumbnail),
                "description"     => $blog->description,
                "og_url"          => current_url(),
                "items"           => $item,
                "blog"           => $blog,
            ];
            return  $this->template->view('blog', $data)->render();
        }
        load_404();
    }

    public function invoice($ids = '')
    {

        $this->params = ['ids' => $ids];
        $item = $this->model->getItem($this->params, ['task' => 'get-item']);


        if (!empty($item)) {
            $data['items'] = $item;
            $rate = 1;
            $this->apikey = get_brand_data($item['brand_id'], $item['uid'])->brand_key;
            $this->payment_lib = new GatewayApi();


            if (isset($_GET['start_payment'])) {


                $success_url = base_url('invoice/' . $ids . "?complete=" . $ids);
                $cancel_url = base_url('invoice/' . $ids);

                $data   = array(
                    "cus_name"          => $item['customer_name'],
                    "cus_email"         => $item['customer_email'],
                    "amount"            => $item['customer_amount'],
                    "webhook_url"       => $success_url,
                    "success_url"       => $cancel_url,
                    "cancel_url"        => $cancel_url,
                );

                $header   = array(
                    "api"               => $this->apikey,
                    "url"               => getenv('PAYMENT_URL') . 'api/payment/create',
                );

                $response = $this->payment_lib->payment($data, $header);
                if (!empty($response)) {
                    $res = json_decode($response);
                    if ($res->status == 1) {
                        return redirect()->to($res->payment_url);
                    }
                }
                return redirect()->to(previous_url());
            } elseif (isset($_GET['complete'])) {
                $trxId = $_REQUEST['transactionId'];
                $amount   = $_REQUEST['paymentAmount'];

                $data   = array(
                    "transaction_id"        => $trxId,
                );

                $header   = array(
                    "api"               => $this->apikey,
                    "url"               => getenv('PAYMENT_URL') . 'api/payment/verify',
                );


                $response = $this->payment_lib->payment($data, $header);
                $data = json_decode($response);

                if (!empty($data)) {
                    $sta = $data->status == 'COMPLETED' ? '2' : ($data->status == 'PENDING' ? '1' : '0');
                    $this->db = db_connect();
                    $this->db->table('invoice')->where('ids', $ids)->update(['pay_status' => $sta, 'transaction_id' => $trxId]);
                    $this->db->close();
                }
                return redirect()->to(base_url('invoice/' . $ids));
            } else {
                return view('Home\Views\invoice', $data);
            }
        } else {
            load_404();
        }
    }
}
