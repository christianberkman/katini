<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Katini extends Migration
{
    public function up()
    {
        $this->addSupportersTable();
        $this->addDonationsTable();
        $this->addCirclesTable();
        $this->addSupportersCirclesTable();
    }

    public function down()
    {
        $this->forge->dropTable('supporters_circles');
        $this->forge->dropTable('circles');
        $this->forge->dropTable('donations');
        $this->forge->dropTable('supporters');
    }

    private function addSupportersTable()
    {
        // Table: supporter
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
                'null'           => false,
            ],
            'first_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '45',
                'null'       => true,
            ],
            'infix' => [
                'type'       => 'VARCHAR',
                'constraint' => 16,
                'null'       => true,
            ],
            'last_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '45',
                'null'       => true,
            ],
            'org_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '90',
                'null'       => true,
            ],
            'display_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 16,
                'null'       => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '90',
                'null'       => true,
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '15',
                'null'       => true,
            ],
            'address_street' => [
                'type'       => 'VARCHAR',
                'constraint' => '45',
                'null'       => true,
            ],
            'address_number' => [
                'type'       => 'INT',
                'constraint' => '4',
                'null'       => true,
            ],
            'address_addition' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'null'       => true,
            ],
            'address_postcode' => [
                'type'       => 'CHAR',
                'constraint' => '7',
                'null'       => true,
            ],
            'address_city' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'null'       => true,
            ],
            'date_birth' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'iban' => [
                'type'       => 'STRING',
                'constraint' => '34',
                'null'       => true,
            ],
            'note' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id');
        $this->forge->createTable('supporters');
    }

    private function addDonationsTable()
    {
        // Table: donations
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
                'null'           => false,
            ],
            'supporter_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'null'       => true,
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'null' => false,
            ],
            'method' => [
                'type'       => 'STRING',
                'constraint' => '45',
                'null'       => true,
            ],
            'note' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'is_recurring' => [
                'type'    => 'BOOL',
                'null'    => false,
                'default' => false,
            ],
            'interval' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
                'null'       => true,
            ],
            'next_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'has_recurred' => [
                'type'    => 'BOOL',
                'null'    => false,
                'default' => 0,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id');
        $this->forge->addForeignKey('supporter_id', 'supporters', 'supporter_id');
        $this->forge->createTable('donations');
    }

    private function addCirclesTable()
    {
        // Table: circles
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => '5',
                'usigned'        => true,
                'auto_increment' => true,
                'null'           => false,
            ],
            'circle_name' => [
                'type'       => 'STRING',
                'constraint' => 32,
                'null'       => false,
            ],
            'note' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id');
        $this->forge->createTable('circles');
    }

    private function addSupportersCirclesTable()
    {
        // Table: supporters_circles
        $this->forge->addField([
            'rid' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
                'null'           => false,
            ],
            'supporter_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'null'       => true,
            ],
            'circle_id' => [
                'type'       => 'STRING',
                'constraint' => 64,
                'null'       => false,
            ],
        ]);

        $this->forge->addKey('rid');
        $this->forge->addKey(['supporter_id', 'circle_id'], false, true);
        $this->forge->addForeignKey('supporter_id', 'supporters', 'supporter_id');
        $this->forge->createTable('supporters_circles');
    }
}
