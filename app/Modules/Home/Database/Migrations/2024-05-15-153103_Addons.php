<?php

namespace Modules\Home\Database\Migrations;

use CodeIgniter\Database\Migration;

class Addons extends Migration
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
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'unique_identifier' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'version' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,3',
                'default' => '0.000',   
            ],
            'status' => [
                'type' => 'int',
                'constraint' => '2',
                'default' => 1 
            ],
            'image' => [
                'type' => 'text'
            ],           
            
        ]);
        $tableAttributes = ['ENGINE' => 'InnoDB', 'DEFAULT CHARSET' => 'utf8mb4', 'COLLATE' => 'utf8mb4_unicode_ci'];
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('addons', true,$tableAttributes);
    }

    public function down()
    {
        $this->forge->dropTable('addons', true);
    }
}
