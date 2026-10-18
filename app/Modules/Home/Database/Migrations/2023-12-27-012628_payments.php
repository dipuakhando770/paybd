<?php

namespace Modules\Home\Database\Migrations;

use CodeIgniter\Database\Migration;

class Payments extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'type' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 225,
            ],
            'sort' => [
                'type' => 'INT',
                'constraint' => 3,
                'null' => true,
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'default' => 0,
                'comment' => '1=on, 0=off'
            ],
            'params' => [
                'type' => 'TEXT',
            ]
        ]);

        $tableAttributes = ['ENGINE' => 'InnoDB', 'DEFAULT CHARSET' => 'utf8mb4', 'COLLATE' => 'utf8mb4_unicode_ci'];
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('payments', true, $tableAttributes);
    }

    public function down()
    {
        $this->forge->dropTable('payments', true);
    }
}
