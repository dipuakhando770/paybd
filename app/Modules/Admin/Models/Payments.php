<?php

namespace Admin\Models;

use CodeIgniter\I18n\Time;
use CodeIgniter\Model;

class Payments extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['*'];
    protected $field_search_accepted;
    protected $ModelsUserModel;

    public function __construct()
    {
        parent::__construct();
        $this->field_search_accepted = app_config('config')['search']['default'];
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
                        $this->builder()->orLike($column, $query);
                    }
                    $i++;
                }
            }
        } elseif (!empty($query) && !empty($field)) {
            $this->builder()->like($field, $query);
        }
        $this->builder()->orderBy('sort');

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
                        $this->builder()->orLike($column, $query);
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
            ->get()
            ->getResultArray();

        return $result;
    }


    public function save_item($params = null, $option = null)
    {
        if (in_array($option['task'], ['add-item', 'edit-item'])) {
            $data = [
                "type"      => $params['type'],
                "name"      => $params['name'],
                "sort"      => post('sort'),
                "status"    => (int)$params['status'],
                "params"    => json_encode($params),
            ];
        }
        switch ($option['task']) {

            case 'add-item':
                $this->doInsert($data);
                return ["status"  => "success", "message" => 'FAQ added successfully'];
                break;

            case 'edit-item':
                $this->where('id', post('id'))->builder()->update($data);
                return ["status"  => "success", "message" => 'Updated successfully'];
                break;
            case 'change-sort':
                $items = $this->get('*', 'payments', ['sort' => $params['sort'], 'id !=' => $params['id']]);

                if (!empty($items)) {
                    return ["status" => "error", "message" => 'Sort Key already Exists'];
                }

                $this->where('id', $params['id'])->builder()->update(['sort' => $params['sort']]);

                return ["status"  => "success", "message" => 'Update successfully'];
                break;
            case 'change-sort-all':

                $this->where('id', $params['id'])->builder()->update(['sort' => $params['sort']]);

                return ["status"  => "success", "message" => 'Update successfully'];
                break;

            case 'change-status':
                $this->where('id', $params['id'])->builder()->update(['status' => $params['status']]);
                return ["status"  => "success", "message" => 'Status Updated successfully'];
                break;
            case 'bulk-action':
                if (in_array($params['type'], ['deactive', 'active'])) {
                    if (empty($params['ids'])) {
                        return ["status"  => "error", "message" => 'Please choose at least one item'];
                    } else {
                        $arr_ids = convert_str_number_list_to_array($params['ids']);
                    }
                }

                switch ($params['type']) {

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
                $deleted = $this->delete($item->id);

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

    public function trash()
    {
        return;
    }
}
