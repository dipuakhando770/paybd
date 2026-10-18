<?php

namespace Admin\Models;

use CodeIgniter\I18n\Time;
use CodeIgniter\Model;

class Brands extends Model
{
    protected $table = 'brands';
    protected $primaryKey = 'id';
    protected $allowedFields = ['uid', 'domain', 'ip', 'brand_name', 'brand_key', 'brand_logo', 'meta', 'fees', 'fees_type', 'currency', 'status', 'created_at', 'deleted_at'];
    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';
    protected $field_search_accepted;

    public function __construct()
    {
        parent::__construct();
        $this->field_search_accepted = app_config('config')['search']['devices'];
    }

    public function helper()
    {
        $query = get('query');
        $field = get('field');

        if ($field == 'all') {
            $i = 1;
            foreach ($this->field_search_accepted as $column) {
                if ($column != 'all') {
                    if ($i == 1) {
                        $this->builder()->like($column, $query);
                    } else {
                        $this->builder()->orLike($column, $query);
                    }
                    $i++;
                }
            }
        } elseif (!empty($query) && !empty($field)) {
            $this->builder()->like($field, $query);
        }
        $this->builder()->orderBy('id');
        return $this;
    }
    public function count()
    {
        return '';
    }


    public function save_item($params = null, $option = null)
    {
        switch ($option['task']) {

            case 'edit-item':
                $data = [
                    "domain"          => post("domain"),
                    "ip"              => post("ip"),
                    "status"          => (int)post("status"),
                ];
                $this->doUpdate(["id" => post('id')], $data);

                return ["status"  => "success", "message" => 'Brand Updated successfully'];
                break;
            case 'change-status':
                $this->where('id', $params['id'])->builder()->update(['status' => $params['status']]);
                return ["status"  => "success", "message" => 'Status Updated successfully'];
                break;
            case 'bulk-action':
                if (in_array($params['type'], ['delete', 'deactive', 'active'])) {
                    if (empty($params['ids'])) {
                        return ["status"  => "error", "message" => 'Please choose at least one item'];
                    } else {
                        $arr_ids = convert_str_number_list_to_array($params['ids']);
                    }
                }

                switch ($params['type']) {
                    case 'delete-all':
                        $this->purgeDeleted();
                        return ["status"  => "success", "message" => 'Deleted successfully'];

                        break;
                    case 'delete':
                        $this->whereIn('id', $arr_ids)->builder()
                            ->update(['deleted_at' => now()]);

                        return ["status"  => "success", "message" => 'Deleted successfully'];
                        break;
                    case 'deactive':
                        $this->whereIn('id', $arr_ids)->builder()
                            ->update(['status' => 0]);
                        return ["status"  => "success", "message" => 'Deactivated successfully'];
                        break;
                    case 'active':
                        $this->whereIn('id', $arr_ids)->builder()
                            ->update(['status' => 1]);
                        return ["status"  => "success", "message" => 'Activated successfully'];
                        break;
                    case 'restore':
                        $items = $this->fetch('id,deleted_at', 'brands', 'deleted_at is not null');
                        $i = 0;
                        if (!empty($items)) {
                            foreach ($items as $item) {
                                if ($this->restoreRecord($item->id)) {
                                    $i++;
                                }
                            }
                        }
                        return ["status"  => "success", "message" => $i . ' Brands restored successfully'];
                        break;
                }
                break;
        }
    }


    public function delete_item($params = null, $option = null)
    {

        $result = [];
        if ($option['task'] == 'delete-item') {
            $item = $this->get("id", $this->table, ['id' => $params['id']]);
            if ($item) {
                if (!empty($option['delete-all'])) {
                    $deleted = $this->purgeDeleted($item->id);
                } else {
                    $deleted = $this->delete($item->id);
                }
                $result = [
                    'status' => 'success',
                    'message' => 'Deleted successfully',
                    "ids"     => $item->id,
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
    public function restoreRecord($userId)
    {
        return $this
            ->withDeleted()
            ->where('id', $userId)
            ->builder()
            ->update(['deleted_at' => null]);
    }
    public function trash()
    {
        $result = $this->builder()
            ->where('deleted_at IS NOT NULL')
            ->countAllResults();

        return $result;
    }
}
