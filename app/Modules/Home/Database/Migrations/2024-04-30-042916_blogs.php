<?php

namespace Modules\Home\Database\Migrations;

use CodeIgniter\Database\Migration;

class Blogs extends Migration
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
            'ids' => [
                'type' => 'TEXT',
                'null' => true,
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'thumbnail' => [
                'type' => 'TEXT',
                'null' => true,
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'uri' => [
                'type' => 'TEXT',
                'null' => true,
                'collation' => 'utf8mb4_unicode_ci',
            ],
            
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'status' => [
                'type' => 'INT',
                'constraint' => 1,
                'default' => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $tableAttributes = ['ENGINE' => 'InnoDB', 'DEFAULT CHARSET' => 'utf8mb4', 'COLLATE' => 'utf8mb4_unicode_ci'];
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('blogs', true,$tableAttributes);
    }

    public function down()
    {
        $this->forge->dropTable('blogs', true);
    }
}
