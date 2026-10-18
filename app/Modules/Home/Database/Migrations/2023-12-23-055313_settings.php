<?php

namespace Modules\Home\Database\Migrations;

use CodeIgniter\Database\Migration;

class Settings extends Migration
{
    public function up()
    {
        $this->forge->addField('id');
        $this->forge->addField([
            'name'          => ['type' => 'TEXT', 'null' => true],
            'value'         => ['type' => 'TEXT', 'null' => true],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('options', true);
    }

    public function down()
    {
        $this->forge->dropTable('options', true);
    }
}
