<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmployeTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nom' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'prenom' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'unique'     => true,
                'null'       => false,
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['Admin', 'Employe', 'RH'],
                'default'    => 'Employe',
            ],
            'departement_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'date_embauche' => [
                'type'   => 'DATE',
                'null'   => true,
            ],
            'actif' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', false, true);
        $this->forge->addKey('email', false, false);
        $this->forge->addForeignKey('departement_id', 'departement', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable('employe');
    }

    public function down()
    {
        $this->forge->dropTable('employe');
    }
}
