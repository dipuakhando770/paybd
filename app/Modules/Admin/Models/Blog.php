<?php

namespace Admin\Models;

use CodeIgniter\I18n\Time;
use CodeIgniter\Model;
use User\Models\UserModel as ModelsUserModel;

class Blog extends Model
{
    protected $table = 'blogs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['*'];
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
                        $this->builder()->like($column, $query);
                    }
                    $i++;
                }
            }
        } elseif (!empty($query) && !empty($field)) {
            $this->builder()->like($field, $query);
        }

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
            ->groupBy('status')->where('deleted_at is null')
            ->get()
            ->getResultArray();

        return $result;
    }


    public function save_item($params = null, $option = null)
    {
        if (in_array($option['task'], ['add-item', 'edit-item'])) {
            $data = array(
                "title"           => post('title'),
                "created_at"      => date("Y-m-d H:i:s", strtotime(str_replace('/', '-', post('start')))),
                "status"          => (int)post("status"),
                "description"     => post('description', FALSE),
                "updated_at"      => now(),
                "thumbnail"       => post('thumbnail'),
                "uri"             => makeUrlFriendly(post('title'))
            );
        }
        switch ($option['task']) {

            case 'add-item':
                $data['ids'] = ids();
                $this->doInsert($data);
                return ["status"  => "success", "message" => 'Blog added successfully'];
                break;

            case 'edit-item':
                $this->where('id', post('id'))->builder()->update($data);
                return ["status"  => "success", "message" => 'Updated successfully'];
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
                        $items = $this->fetch('id,deleted_at', 'blogs', 'deleted_at is not null');
                        $i = 0;
                        if (!empty($items)) {
                            foreach ($items as $item) {
                                if ($this->restoreRecord($item->id)) {
                                    $i++;
                                }
                            }
                        }
                        return ["status"  => "success", "message" => $i . ' blogs restored successfully'];
                        break;
                }
                break;
        }
    }

    public function trash()
    {
        $result = $this->builder()
            ->where('deleted_at IS NOT NULL')
            ->countAllResults();

        return $result;
    }


    public function delete_item($params = null, $option = null)
    {

        $result = [];
        if ($option['task'] == 'delete-item') {
            $item = $this->get("id, ids", $this->table, ['id' => $params['id']]);
            if ($item) {
                $deleted = $this->delete($item->id);

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
    public function restoreRecord($userId)
    {
        return $this
            ->withDeleted()
            ->where('id', $userId)
            ->builder()
            ->update(['deleted_at' => null]);
    }
}
