<?php



namespace App\Database\Migrations;



use CodeIgniter\Database\Migration;



class UserScore extends Migration

{


    public function up()

    {

        $this->forge->addField([

            'user_score_id'          => [

                'type'           => 'INT',

                'constraint'     => 5,

                'unsigned'       => true,

                'auto_increment' => true

            ],

            'user_id'          => [

                'type'           => 'INT',

                'constraint'     => 5,

                'unsigned'       => true,

                'null'            => true,

            ],

            'total_course'          => [

                'type'           => 'INT',

                'constraint'     => 5,

                'unsigned'       => true,

                'null'            => true,

            ],

            'average_score'          => [

                'type'           => 'INT',

                'constraint'     => 5,

                'unsigned'       => true,

                'null'            => true,

            ],

            'created_at datetime default current_timestamp',

            'updated_at datetime default current_timestamp on update current_timestamp',

        ]);

        $this->forge->addKey('user_score_id', TRUE);

        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'SET NULL');

        $this->forge->createTable('user_score', TRUE);
    }



    public function down()

    {

        $this->forge->dropTable('user_score');
    }
}
