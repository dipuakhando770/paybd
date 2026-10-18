<?php

namespace Admin\Models;

use CodeIgniter\I18n\Time;
use CodeIgniter\Model;
use User\Models\UserModel as ModelsUserModel;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['ids', 'email', 'first_name', 'last_name', 'more_information', 'avatar', 'activation_key', 'reset_key', 'password', 'status', 'ref_key', 'phone'];
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $deletedField = 'deleted_at';
    protected $field_search_accepted;
    protected $ModelsUserModel;

    public function __construct()
    {
        parent::__construct();
        $this->ModelsUserModel = new ModelsUserModel();
        $this->field_search_accepted = app_config('config')['search']['users'];
    }

    public function get_item($params = null, $option = null)
    {
        $result = null;
        if ($option['task'] == 'get-all-users') {
            $result = $this->fetch('first_name,last_name,ids,id,avatar,email', 'users');
        }
        if ($option['task'] == 'get-users-activity') {
            $result = $this->fetchByDate('*', 'activity_logs', ['uid' => $params['id']], 'created_at', $params['start_date'], $params['end_date'], 'id');
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
                "first_name"        => post("first_name"),
                "last_name"         => post("last_name"),
                "phone"             => post("phone"),
                "email"             => post("email"),
                "status"            => (int)post("status"),
                "reset_key"         => create_random_string_key(32),
                "activation_key"    => create_random_string_key(25),

            );
        }
        switch ($option['task']) {

            case 'add-item':
                $data['ids']         = ids();
                $data['ref_key']     = ids();
                $data['password']    =  password_hash(post('password'), PASSWORD_BCRYPT);

                $uid = $this->insert($data);
                $this->ModelsUserModel->setNotification($uid, 'Hi,' . post('first_name') . ', your account is created Successfully', get_option('email_welcome_email_subject'), 0);
                $this->setLogs("A user was created-" . post('email'));
                return ["status"  => "success", "message" => 'Added successfully'];
                break;

            case 'edit-item':
                $this->where('ids', post('ids'))->builder()->update($data);

                $user = $this->get('*', 'users', ['ids' => post('ids')], '', '', true);
                $this->ModelsUserModel->setNotification($user['id'], 'Hi,' . $user['first_name'] . ', your account is Updated by an Admin', get_option('email_welcome_email_subject'), 0);
                $this->setLogs("The was created-" . post('email'));
                return ["status"  => "success", "message" => 'Updated successfully'];
                break;


            case 'change-status':
                $this->where('ids', $params['id'])->builder()->update(['status' => $params['status']]);
                $this->setLogs("User Status changed");
                return ["status"  => "success", "message" => 'Status Updated successfully'];
                break;

            case 'set-password':
                $data['password']    =  password_hash(post('password'), PASSWORD_BCRYPT);
                $this->where('ids', post('ids'))->builder()->update($data);
                $user = $this->get('*', 'users', ['ids' => post('ids')], '', '', true);
                $this->ModelsUserModel->setNotification($user['id'], 'Hi,' . $user['first_name'] . ', your Pasword is Updated by an Admin', get_option('email_welcome_email_subject'), 0);
                $this->setLogs("User Password changed");
                return ["status"  => "success", "message" => 'Updated successfully'];
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
                        $this->whereIn('ids', $arr_ids)->builder()
                            ->update(['deleted_at' => now()]);

                        $this->setLogs("Bulk user delete");

                        return ["status"  => "success", "message" => 'Deleted successfully'];
                        break;
                    case 'deactive':
                        $this->whereIn('ids', $arr_ids)->builder()
                            ->update(['status' => 0]);
                        $this->setLogs("Bulk user deactive");

                        return ["status"  => "success", "message" => 'Deactivated successfully'];
                        break;
                    case 'active':
                        $this->whereIn('ids', $arr_ids)->builder()
                            ->update(['status' => 1]);
                        $this->setLogs("Bulk user active");
                        return ["status"  => "success", "message" => 'Activated successfully'];
                        break;
                    case 'restore':
                        $items = $this->fetch('id,deleted_at', 'users', 'deleted_at is not null');
                        $i = 0;
                        if (!empty($items)) {
                            foreach ($items as $item) {
                                if ($this->restoreRecord($item->id)) {
                                    $i++;
                                }
                            }
                        }
                        $this->setLogs("Bulk user restore");
                        return ["status"  => "success", "message" => $i . ' users restored successfully'];
                        break;
                }
                break;
        }
    }
    public function save_funds($params = null, $option = null)
    {
        if ($option['task'] == 'add-funds') {
            // Update balance to user
            $user = $this->get('*', 'users', ['id' => $params['item']['id']], '', '', true);

            if (post('type') == 'add') {
                $data_item = [
                    'balance' => $params['item']['balance'] + (float)post('amount'),
                ];
                $message = 'Balance added by Admin and transaction_id:' . post('transaction_id');
            } else {
                $data_item = [
                    'balance' => $params['item']['balance'] - (float)post('amount'),
                ];
                $message = 'Balance deducted by Admin and transaction_id:' . post('transaction_id');
            }
            $updated = $this->where('ids', post('ids'))->builder()->update($data_item);
            if ($updated) {
                $data_tnx_log = array(
                    "ids"               => ids(),
                    "uid"               => session("uid"),
                    "type"              => 'add_fund',
                    "transaction_id"    => trxId(),
                    "amount"            => (float)post('amount'),
                    "information"       => json_encode(['message' => $message]),
                    "status"            => 2,
                    "currency"          => "BDT",
                    "created_at"        => now(),
                    "updated_at"        => now(),

                );
                $this->db->table('user_transactions')->insert($data_tnx_log);

                $this->ModelsUserModel->setNotification($user['id'], 'Hi,' . $user['first_name'] . ',' . $message, 'Balance Update', 0);

                $this->setLogs($user['email'] . "-user's fund changed by-" . post('amount'));

                return ["status"  => "success", "message" => 'Balance Updated successfully'];
            };
        }
    }

    public function delete_item($params = null, $option = null)
    {

        $result = [];
        if ($option['task'] == 'delete-item') {
            $item = $this->get("id, ids", $this->table, ['ids' => $params['id']]);
            if ($item) {
                if (!empty($option['delete-all'])) {
                    $deleted = $this->purgeDeleted($item->id);
                } else {
                    $deleted = $this->delete($item->id);
                }
                $result = [
                    'status' => 'success',
                    'message' => 'Deleted successfully',
                    "ids"     => $item->ids,
                ];
                $this->setLogs("User deleted");
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
