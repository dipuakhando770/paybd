<?php

namespace Modules\Home\Database\Migrations;

use CodeIgniter\Database\Migration;

class FileManager extends Migration
{
    public function up()
    {
        $this->forge->addField('id');
        $this->forge->addField([
            'uid'           => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'file_name'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'file_url'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'file_type'     => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'file_size'     => ['type' => 'INT', 'constraint' => 11, 'null' => false],
            'created_at'    => ['type' => 'DATETIME', 'null' => false],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('file_managers', true);
    }

    public function down()
    {
        $this->forge->dropTable('file_managers', true);
    }
}
