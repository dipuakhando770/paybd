<?php

namespace Modules\Blocks\Database\Migrations;

use CodeIgniter\Database\Migration;

class Queue extends Migration
{
    public function up()
    {
        $this->forge->addField('id');
        $this->forge->addField([
            'task_type'          => ['type' => 'TEXT', 'null' => true],
            'task_data'          => ['type' => 'TEXT', 'null' => true],
            'status'        => [
                'type' => 'varchar',
                'constraint' => 16, 
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('queue', true);
    }

    public function down()
    {
        $this->forge->dropTable('queue', true);
    }
}
