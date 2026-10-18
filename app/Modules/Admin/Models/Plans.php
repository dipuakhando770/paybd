<?php

namespace Admin\Models;

use CodeIgniter\I18n\Time;
use CodeIgniter\Model;
use User\Models\UserModel as ModelsUserModel;

class Plans extends Model
{
    protected $table = 'plans';
    protected $primaryKey = 'id';
    protected $allowedFields = ['ids', 'name', 'description', 'price', 'website', 'device', 'transaction', 'final_price', 'duration', 'duration_type', 'brand', 'sort', 'status', 'created_at', 'deleted_at'];
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
            ->groupBy('status')->where('deleted_at is null')
            ->get()
            ->getResultArray();

        return $result;
    }


    public function save_item($params = null, $option = null)
    {
        if (in_array($option['task'], ['add-item', 'edit-item'])) {
            $data = array(
                "name"           => post("name"),
                "status"         => (int)post("status"),
                "sort"           => (int)post("sort"),
                "description"    => post("description"),
                "duration"       => (int)post("duration"),
                "duration_type"  => (int)post("duration_type"),
                "price"          => post("price"),
                "final_price"    => post("final_price"),
                "device"         => post("device"),
                "brand"          => post("brand"),
                "transaction"    => post("transaction"),

            );
        }
        switch ($option['task']) {

            case 'add-item':
                $data['ids'] = ids();
                $data['created_at'] = now();
                $this->insert($data);
                return ["status"  => "success", "message" => 'Plan added successfully'];
                break;

            case 'edit-item':
                $this->where('ids', post('ids'))->builder()->update($data);
                return ["status"  => "success", "message" => 'Updated successfully'];
                break;
            case 'change-sort':
                $items = $this->get('*', 'plans', ['sort' => $params['sort'], 'id !=' => $params['id']]);

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
                        $items = $this->fetch('id,deleted_at', 'plans', 'deleted_at is not null');
                        $i = 0;
                        if (!empty($items)) {
                            foreach ($items as $item) {
                                if ($this->restoreRecord($item->id)) {
                                    $i++;
                                }
                            }
                        }
                        return ["status"  => "success", "message" => $i . ' Plans restored successfully'];
                        break;
                }
                break;
        }
    }


    public function delete_item($params = null, $option = null)
    {

        $result = [];
        if ($option['task'] == 'delete-item') {
            $item = $this->get("id, ids", $this->table, ['id' => $params['id']]);
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
