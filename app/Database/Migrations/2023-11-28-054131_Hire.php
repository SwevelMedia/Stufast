<?php



namespace App\Database\Migrations;



use CodeIgniter\Database\Migration;



class Hire extends Migration

{


    public function up()

    {

        $this->forge->addField([

            'hire_id'          => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true
            ],

            'company_id'          => [
                'type'           => 'INT',
                'constraint'     => 5
            ],

            'position'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255'
            ],

            'status'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255'
            ],

            'method'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255'
            ],

            'min_date'       => [
                'type'           => 'DATE',
            ],

            'max_date'       => [
                'type'           => 'DATE',
            ],

            'min_salary'       => [
                'type'           => 'INT',
                'constraint'     => '10'
            ],

            'max_salary'       => [
                'type'           => 'INT',
                'constraint'     => '10'
            ],

            'information'       => [
                'type' => 'TEXT',
                'null' => true
            ],

            'created_at datetime default current_timestamp',

            'updated_at datetime default current_timestamp on update current_timestamp',

        ]);



        $this->forge->addKey('hire_id', TRUE);

        $this->forge->createTable('hire', TRUE);
    }



    public function down()

    {

        $this->forge->dropTable('hire');
    }
}
