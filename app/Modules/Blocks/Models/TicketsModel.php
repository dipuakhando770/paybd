<?php

namespace Blocks\Models;

use CodeIgniter\Model;
use User\Models\UserModel;

class TicketsModel extends Model
{
    protected $table = 'tickets';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = ['ids', 'uid', 'subject', 'description', 'is_read', 'created_at', 'updated_at', 'status', 'message', 'author', 'support', 'ticket_id'];
    protected $useSoftDeletes = true;
    public function __construct()
    {
        parent::__construct();
        $this->field_search_accepted = app_config('config')['search']['tickets'];
    }
    public function getItem($params = null, $option = null)
    {
        $result = null;

        if ($option['task'] == 'get-item') {
            $result = $this->select('us.id,us.email,us.first_name,tickets.*')
                ->join('users us', 'us.id=tickets.uid')
                ->where(['tickets.ids' => $params['ids'], 'uid' => session('uid')])
                ->first();
        }

        if ($option['task'] == 'get-admin-item') {
            $result = $this->select('us.id,us.email,us.first_name,tickets.*')
                ->join('users us', 'us.id=tickets.uid')
                ->where(['tickets.ids' => $params['ids']])
                ->first();
        }

        if ($option['task'] == 'items-ticket-message') {

            $builder = $this->db->table('ticket_messages tm');
            $builder->select('tm.*,us.first_name,us.email');
            $builder->join('tickets ts', 'ts.id=tm.ticket_id');
            $builder->join('users us', 'us.id=ts.uid');
            $builder->where('ticket_id', $params['ticket_id']);
            $builder->orderBy('id', 'DESC');
            $result = $builder->get()->getResultArray();

            $dataItem = [
                'is_user_read' => 1,
                'updated_at' => now(),
            ];


            $this->db->table('tickets')->where('id', $params['ticket_id'])->update($dataItem);
        }

        if ($option['task'] == 'admin-items-ticket-message') {

            $builder = $this->db->table('ticket_messages tm');
            $builder->select('tm.*,us.first_name,us.email');
            $builder->join('tickets ts', 'ts.id=tm.ticket_id');
            $builder->join('users us', 'us.id=ts.uid');
            $builder->where('ticket_id', $params['ticket_id']);
            $builder->orderBy('id', 'DESC');
            $result = $builder->get()->getResultArray();

            $dataItem = [
                'is_admin_read' => 1,
                'updated_at' => now(),
            ];

            $this->db->table('tickets')->where('id', $params['ticket_id'])->update($dataItem);
        }


        return $result;
    }
    public function save_item($params = null, $option = null)
    {
        if (empty($option['task'])) {
            return ['status' => 'error', 'message' => 'Invalid task'];
        }
        $author = current_user('first_name');

        $table = '';
        switch ($option['task']) {
            case 'add-item':
                $table = 'tickets';
                $data = [
                    'ids' => ids(),
                    'uid' => session('uid'),
                    'subject' => $params['subject'],
                    'description' => $params['description'],
                    'is_user_read' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $this->table($table)->insert($data);

                if ($this->affectedRows() > 0) {
                    // Send notice to admin with a new Ticket
                    if (get_option('is_ticket_notice_email_admin', 0)) {
                        $ticketId = $this->insertID();
                        $mailParams = [
                            'template' => [
                                'subject' => "{{website_name}}" . " - New Ticket #" . $ticketId . " - [" . $params['subject'] . "]",
                                'message' => $params['description'],
                                'type' => 'default',
                            ],
                            'from_email_data' => [
                                'from_email' => current_user('email'),
                                'from_email_name' => $author,
                            ],
                        ];
                        $this->sendNoticeMail($mailParams);
                    }

                    return ['status' => 'success', 'message' => lan('ticket_created_successfully')];
                } else {
                    return ['status' => 'error', 'message' => lan('There_was_an_error_processing_your_request_Please_try_again_later')];
                }

                break;

            case 'change-status':
                $table = 'tickets';
                $data2 = [];
                $ret = false;
                $data = ['status' => $params['status']];
                if ($params['status'] === 'unread') {
                    $data2 = [
                        'status' => 'pending',
                        'is_admin_read' => 0,
                        'is_user_read' => 0,
                        'updated_at' => now(),
                    ];
                    $ret = true;
                }
                $this->db->table($table)->where('ids', $params['id'])->update(array_merge($data, $data2));
                return ["status" => $ret];
                break;
            case 'add-item-ticket-message-admin':
                $table = 'tickets';

                $item = $this->where(['ids' => $params['ids'], 'id' => $params['ticket_id']])->first([$table => ['id', 'ids', 'uid', 'subject']]);
                if (!$item) {
                    return ['status' => 'error', 'message' => 'There was something wrong with your'];
                }

                $dataItem = [
                    'status' => 'answered',
                    'is_user_read' => 0,
                    'updated_at' => now(),
                ];


                $dataItemTicketMessage = [
                    'ids' => ids(),
                    'message' => $params['message'],
                    'author' => current_admin('first_name'),
                    'support' => 1,
                    'ticket_id' => $item['id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $this->db->table($table)->where('ids', $params['ids'])->update($dataItem);

                $this->db->table('ticket_messages')->insert($dataItemTicketMessage);
                //send notification to user
                $usermodel = new UserModel();
                $usermodel->setNotification($item['uid'], 'Hi, You have got a response of your ticket of ' . $item['subject'], 'Ticket reply-#' . $item['ids'], 0);

                return ['status' => 'success', 'message' => lan('Update_successfully')];
                break;
            case 'edit-item-ticket-message-admin':
                $table = 'ticket_messages';

                $dataItem = [
                    'is_user_read' => 0,
                    'updated_at' => now(),
                ];


                $dataItemTicketMessage = [
                    'message' => $params['message'],
                    'author' => current_admin('first_name'),
                    'updated_at' => now(),
                ];

                $this->db->table('tickets')->where('id', $params['ticket_id'])->update($dataItem);

                $this->db->table($table)->where('ids', $params['ids'])->update($dataItemTicketMessage);

                return ['status' => 'success', 'message' => lan('Update_successfully')];
                break;

            case 'add-item-ticket-message':
                $table = 'tickets';

                $item = $this->where(['ids' => $params['ids'], 'id' => $params['ticket_id']])->first([$table => ['id', 'ids', 'uid', 'subject']]);
                if (!$item) {
                    return ['status' => 'error', 'message' => 'There was something wrong with your'];
                }

                $dataItem = [
                    'status' => 'pending',
                    'is_admin_read' => 0,
                    'updated_at' => now(),
                ];


                $dataItemTicketMessage = [
                    'ids' => ids(),
                    'message' => $params['message'],
                    'author' => $author,
                    'support' => 0,
                    'ticket_id' => $item['id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $this->db->table($table)->where('ids', $params['ids'])->update($dataItem);
                $this->db->table('ticket_messages')->insert($dataItemTicketMessage);


                // Send notice to admin when the client replies
                if (get_option('is_ticket_notice_email_admin', 0)) {
                    $mailParams = [
                        'template' => [
                            'subject' => "{{website_name}}" . " - Ticket Message #" . $params['ids'],
                            'message' => $params['message'],
                            'type' => 'default',
                        ],
                        'from_email_data' => [
                            'from_email' => current_user('email'),
                            'from_email_name' => $author,
                        ],
                    ];
                    $this->sendNoticeMail($mailParams);
                }

                return ['status' => 'success', 'message' => lan('Update_successfully')];

                break;

            case 'bulk-action':
                if (in_array($params['type'], ['delete', 'pending', 'answered', 'unread', 'closed'])) {
                    if (empty($params['ids'])) {
                        return ["status"  => "error", "message" => 'Please choose at least one item'];
                    } else {
                        $arr_ids = convert_str_number_list_to_array($params['ids']);
                    }
                }

                if ($params['type'] == 'delete-all') {
                    $this->purgeDeleted();
                    return ["status"  => "success", "message" => 'Deleted successfully'];
                } elseif ($params['type'] == 'delete') {
                    $this->whereIn('id', $arr_ids)->builder()
                        ->update(['deleted_at' => now(), 'updated_at' => now()]);
                    return ["status"  => "success", "message" => 'Deleted successfully'];
                } elseif (in_array($params['type'], ['pending', 'closed', 'answered'])) {
                    $this->whereIn('id', $arr_ids)->builder()
                        ->update(['status' => $params['type'], 'updated_at' => now()]);
                } elseif ($params['type'] == 'unread') {
                    $this->whereIn('id', $arr_ids)->builder()
                        ->update(['is_admin_read' => 0, 'is_user_read' => 0, 'updated_at' => now()]);
                } elseif ($params['type'] == 'restore') {
                    $items = $this->fetch('id,deleted_at', 'tickets', 'deleted_at is not null');
                    $i = 0;
                    if (!empty($items)) {
                        foreach ($items as $item) {
                            if ($this->restoreRecord($item->id)) {
                                $i++;
                            }
                        }
                    }
                    return ["status"  => "success", "message" => $i . ' tickets restored successfully'];
                    break;
                }
                foreach ($arr_ids as $id) {
                    $item = $this->where(['id' => $id])->first([$table => ['id', 'ids', 'uid', 'subject']]);
                    $usermodel = new UserModel();
                    $usermodel->setNotification($item['uid'], 'Hi, an action against on your ticket -' . $item['subject'], 'Ticket-#' . $item['ids'], 0);
                }

                return ["status"  => "success", "message" => 'Status Changed successfully'];

                break;


            default:
                return ['status' => 'error', 'message' => 'Invalid task'];
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
            } else {
                $result = [
                    'status' => 'error',
                    'message' => 'There was an error processing your request. Please try again later',
                ];
            }
        }
        if ($option['task'] == 'delete-item-ticket-message') {
            $this->db->table('ticket_messages')->where('ids', $params['ids'])->delete();

            $result = [
                'status' => 'success',
                'message' => 'Deleted successfully',
                "ids"     => $params['ids'],
            ];
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

    public function countItems($params = null, $option = null)
    {
        $result = null;

        if ($option['task'] == 'count-items-pending') {
            $builder = $this->builder();
            $builder->select('id');
            $builder->where('uid', session('uid'));
            $builder->whereIn('status', ['pending', 'answered']);

            $query = $builder->get();
            $result = $query->getNumRows();
        }

        return $result;
    }
    public function sendNoticeMail($params = [], $option = [])
    {
        $staffs = $this->fetch('id,email', 'staffs');
        foreach ($staffs as $staff) {
            $send_message = $this->sendMail($params['template']['subject'], $params['template']['message'], $staff->email, 'admin');
        }

        if ($send_message) {
            return ["status" => "error", "message" => $send_message];
        }
    }

    public function helper($type = 'admin')
    {
        $query = get('query');
        $field = get('field');

        $filter_status = get('status') ?? 'all';
        if ($type == 'user') {
            $this->builder()->where('tickets.uid', session('uid'));
        }

        if ($filter_status != 'all') {
            $this->builder()->where('tickets.status', $filter_status);
        }
        if ($field == 'all') {
            $i = 1;

            foreach ($this->field_search_accepted as $column) {
                if ($column != 'all') {
                    if ($i == 1) {
                        if ($column == 'email') {
                            $this->builder()->like('us.' . $column, $query);
                        } else {
                            $this->builder()->like('tickets.' . $column, $query);
                        }
                    } elseif ($i > 1) {
                        if ($column == 'email') {
                            $this->builder()->orLike('us.' . $column, $query);
                        } else {
                            $this->builder()->orLike('tickets.' . $column, $query);
                        }
                    }
                    $i++;
                }
            }
        } elseif (!empty($query) && !empty($field)) {
            if ($field == 'email') {
                $this->builder()->like('us.' . $field, $query);
            } else {
                $this->builder()->like('tickets.' . $field, $query);
            }
        }
        $this->builder->select('us.id, us.email, us.first_name,tickets.* ');
        $this->builder->join('users us', 'us.id = tickets.uid');
        $this->builder->orderBy('tickets.id', 'DESC');

        return $this;
    }
    public function count()
    {

        $result = $this->builder()
            ->select('COUNT(id) as count, status')
            ->groupBy('status')
            ->get()
            ->getResultArray();

        return $result;
    }
    public function trash()
    {
        $result = $this->builder()
            ->where('deleted_at IS NOT NULL')
            ->countAllResults();
        return $result;
    }
}
