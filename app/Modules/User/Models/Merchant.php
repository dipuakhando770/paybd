<?php

namespace User\Models;

use CodeIgniter\I18n\Time;
use CodeIgniter\Model;
use User\Models\UserModel as ModelsUserModel;

class Merchant extends Model
{
    protected $table = 'invoice';
    protected $primaryKey = 'id';
    protected $allowedFields = ['ids', 'uid', 'customer_name', 'customer_number', 'customer_amount', 'customer_email', 'customer_address', 'customer_description', 'status', 'pay_status', 'brand_id', 'transaction_id', 'extras', 'created_at', 'updated_at'];
    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';
    protected $field_search_accepted;
    protected $ModelsUserModel;

    public function __construct()
    {
        parent::__construct();
        $this->ModelsUserModel = new ModelsUserModel();
        $this->field_search_accepted = app_config('config')['search']['default'];
    }
    public function get_item($params = null, $option = null)
    {
        $result = [];

        if ($option['task'] == 'get-item') {
            $builder = $this->db->table('invoice');
            $builder->select('invoice.*, brands.brand_name');
            $builder->where('invoice.uid', session('uid'));
            $builder->where('invoice.ids', $params['ids']);
            $builder->join('brands', 'brands.id = invoice.brand_id', 'right');
            $query = $builder->get();
            $result = $query->getRowArray();
        }
        if ($option['task'] == 'active-list-items') {
            $result = $this->fetch("*", 'payments', ['status' => 1, 'id !=' => 1], 'sort', 'ASC', true);
        }
        if ($option['task'] == 'get-brands') {
            $result = $this->fetch("*", 'brands', ['uid' => session('uid')], 'id', 'ASC', '', '', true);
        }
        if ($option['task'] == 'get-brand-object') {
            $builder = $this->db->table('brands');
            $builder->select('*')->where('uid', session('uid'))->orderBy('id', 'desc');
            $result = $builder->get()->getResult();
        }
        return $result;
    }

    public function helper()
    {
        $query = get('query');
        $field = get('field');

        $filter_status = get('status') ?? 'all';

        if ($filter_status != 'all') {
            $this->builder()->where('status', $filter_status);
        }
        if ($field == 'all') {
            $i = 1;
            foreach ($this->field_search_accepted as $column) {
                if ($column != 'all') {
                    if ($i == 1) {
                        $this->builder()->like($column, $query);
                    } else {
                        $this->builder()->like($column, $query);
                    }
                    $i++;
                }
            }
        } elseif (!empty($query) && !empty($field)) {
            $this->builder()->like($field, $query);
        }
        $this->builder()->where('uid', session('uid'));


        return $this;
    }
    public function count()
    {
        $query = get('query');
        $field = get('field');

        $filter_status = get('status') ?? 'all';

        if ($field == 'all') {
            $i = 1;
            foreach ($this->field_search_accepted as $column) {
                if ($column != 'all') {
                    if ($i == 1) {
                        $this->builder()->like($column, $query);
                    } else {
                        $this->builder()->like($column, $query);
                    }
                    $i++;
                }
            }
        } elseif (!empty($query) && !empty($field)) {
            $this->builder()->like($field, $query);
        }

        $result = $this->builder()
            ->select('COUNT(id) as count, status')
            ->groupBy('status')
            ->where('deleted_at is null')
            ->get()
            ->getResultArray();

        return $result;
    }


    public function save_item($params = null, $option = null)
    {

        switch ($option['task']) {
            case 'reset_key':
                $data = array(
                    "brand_key"      => create_random_string_key(50),
                    "updated_at"     => now(),
                );
                $this->db->table('brands')->where('id', $params['id'])->where('uid', session('uid'))->update($data);
                return ['redirect_url' => current_url(), "status"  => "success", "message" => 'Brand Key reset successfully'];
                break;
            case 'brand_setup':
                $data2 = [
                    'mobile_number'   =>  post('mobile_number'),
                    'whatsapp_number' =>  post('whatsapp_number'),
                    'support_mail'    =>  post('support_mail'),
                ];
                $data = array(
                    "meta"           => json_encode($data2),
                    "brand_name"     => post("brand_name"),
                    "status"         => (int)post("status"),
                    "ip"             => ip(),
                    "brand_logo"     => post("brand_logo"),
                    "fees"           => post("fees"),
                    "fees_type"      => post("fees_type"),
                    "currency"       => post("currency"),
                    "updated_at"     => now(),
                );

                if (!empty(post('id'))) {
                    $this->db->table('brands')->where('id', post('id'))->update($data);
                    return ["status"  => "success", "message" => 'Updated successfully'];
                    break;
                } else {
                    $brands = $this->fetch('*', 'brands', ['uid' => session('uid')], '', '', '', '', true);

                    if (!canAddBrand($brands)) {
                        return ["status"  => "error", "message" => "Brands reaches it's limit! Upgrade your Subscription"];
                    }
                    $data3 = array(
                        "uid"            => session('uid'),
                        "domain"         => post("domain"),
                        "brand_key"      => create_random_string_key(50),
                        "created_at"     => now(),
                    );
                    $this->db->table('brands')->insert(array_merge($data, $data3));
                    return ["status"  => "success", "message" => 'Brand added successfully'];
                }
                break;

            case 'invoice-item':
                $data = [
                    "customer_name"          => post("customer_name"),
                    "customer_number"        => post("customer_number"),
                    "customer_email"         => post("customer_email"),
                    "customer_address"       => post('customer_address'),
                    "customer_description"   => post('customer_description'),
                    "uid"                    => session('uid'),
                    "customer_amount"        => post("customer_amount"),
                    "brand_id"               => post("brand_id"),
                    "status"                 => (int)post("status"),
                    "pay_status"             => (int)post("pay_status"),
                ];
                if (!empty(post('id'))) {
                    $this->update(post('id'), $data);
                    return ["status"  => "success", "message" => 'Invoice Updated successfully'];
                } else {
                    $data2 = [
                        'ids'        => ids(),
                        'created_at' => now(),

                    ];
                    $this->doInsert(array_merge($data, $data2));
                    return ["status"  => "success", "message" => 'Invoice Added successfully'];
                }

                break;

            case 'edit-item':
                $data = [
                    "customer_number"          => post("customer_number"),
                    "customer_name"          => post("customer_name"),
                    "customer_email"         => post("customer_email"),
                    "customer_address"       => post('customer_address'),
                    "customer_description"   => post('customer_description'),
                    "customer_amount"        => post("customer_amount"),
                    "domain"                => post("domain"),
                    "created"               => post("created"),
                    "status"                => (int)post("status"),
                    "pay_status"                => (int)post("pay_status"),
                ];
                $this->db->update($this->tb_main, $data, ["id" => post('id'), 'user_id' => session('uid')]);
                return ["status"  => "success", "message" => 'Update successfully'];
                break;

            case 'change-status':
                $this->where('ids', $params['id'])->where('uid', session('uid'))->builder()->update(['status' => $params['status']]);
                return ["status"  => "success", "message" => 'Status Updated successfully'];
                break;
        }
    }

    public function delete_item($params = null, $option = null)
    {

        $result = [];
        if ($option['task'] == 'delete-item') {
            $item = $this->get("ids,id", $this->table, ['ids' => $params['id']]);
            if ($item) {
                $deleted = $this->delete($item->id);
                $this->where('id', $item->id)->purgeDeleted();


                $result = [
                    'status' => 'success',
                    'message' => 'Deleted successfully',
                    "ids"     => $item->ids,
                ];
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
