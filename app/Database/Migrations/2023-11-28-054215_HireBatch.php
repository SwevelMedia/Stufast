<?php



namespace App\Database\Migrations;



use CodeIgniter\Database\Migration;



class HireBatch extends Migration

{


    public function up()

    {

        $this->forge->addField([

            'hire_batch_id'          => [

                'type'           => 'INT',

                'constraint'     => 5,

                'unsigned'       => true,

                'auto_increment' => true

            ],

            'user_id'          => [

                'type'           => 'INT',

                'constraint'     => 5

            ],

            'company_id'          => [

                'type'           => 'INT',

                'constraint'     => 5

            ],

            'created_at datetime default current_timestamp',

            'updated_at datetime default current_timestamp on update current_timestamp',

        ]);



        $this->forge->addKey('hire_batch_id', TRUE);

        $this->forge->createTable('hire_batch', TRUE);
    }



    public function down()

    {

        $this->forge->dropTable('hire_batch');
    }
}
