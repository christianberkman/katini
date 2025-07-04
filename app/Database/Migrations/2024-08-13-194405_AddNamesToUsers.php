<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Forge;
use CodeIgniter\Database\Migration;
use Config\Auth;

class AddNamesToUsers extends Migration
{
    /**
     * @var list<string>
     */
    private array $tables;

    public function __construct(?Forge $forge = null)
    {
        parent::__construct($forge);

        /** @var Auth $authConfig */
        $authConfig   = config('Auth');
        $this->tables = $authConfig->tables;
    }

    public function up()
    {
        $fields = [
            'first_name' => ['type' => 'VARCHAR', 'constraint' => '45', 'null' => true],
            'last_name'  => ['type' => 'VARCHAR', 'constraint' => '45', 'null' => true],
        ];
        $this->forge->addColumn($this->tables['users'], $fields);
    }

    public function down()
    {
        $fields = [
            'first_name',
            'last_name',
        ];
        $this->forge->dropColumn($this->tables['users'], $fields);
    }
}
