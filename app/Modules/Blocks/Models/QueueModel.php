<?php

namespace Blocks\Models;

use CodeIgniter\Model;

class QueueModel extends Model
{
    protected $table = 'queue';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['task_type', 'task_data', 'status'];

    public function addTask($taskType, $taskData)
    {
        $data = [
            'task_type' => $taskType,
            'task_data' => $taskData,
            'status' => 'pending',
        ];

        $this->insert($data);

        return $this->affectedRows() > 0;
    }

    public function processPendingTasks()
    {
        $pendingTasks = $this->getPendingTasks();

        if (!empty($pendingTasks)) {
            foreach ($pendingTasks as $pendingTask) {
                $taskId = $pendingTask['id'];

                try {
                    $taskType = $pendingTask['task_type'];
                    $taskData = json_decode($pendingTask['task_data'], true);

                    if ($taskType === 'send_email') {
                        $to = $taskData['to'];
                        $subject = $taskData['subject'];
                        $message = $taskData['message'];

                        $this->sendMail($subject, $message, $to);
                        $this->markTaskAsCompleted($taskId);
                    } else {
                        $this->markTaskAsCompleted($taskId);
                    }
                } catch (\Exception $e) {
                    $this->markTaskAsFailed($taskId);
                    log_message('error', 'Error processing task: ' . $e->getMessage());
                }
            }
        } else {
            sleep(5);
        }
    }


    public function getPendingTasks()
    {
        if (get_option('is_clear_ticket')) {
            $days = get_option('default_clear_ticket_days');
            $cutoffDate = date('Y-m-d H:i:s', strtotime("-$days days"));

            $this->db->table('tickets')
                ->where('updated_at <', $cutoffDate)
                ->delete();
                
            $this->db->table('module_data')
                ->where('created_at <', $cutoffDate)
                ->delete();
        }

        return $this->where('status', 'pending')->findAll();
    }

    public function markTaskAsCompleted($id)
    {
        $this->update($id, ['status' => 'completed']);
    }

    public function markTaskAsFailed($id)
    {
        $this->update($id, ['status' => 'failed']);
    }
    public function trash()
    {
        return;
    }
}
