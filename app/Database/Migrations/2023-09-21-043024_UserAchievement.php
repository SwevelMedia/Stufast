<?php



namespace App\Database\Migrations;



use CodeIgniter\Database\Migration;



class UserAchievement extends Migration

{


    public function up()

    {

        $this->forge->addField([

            'user_achievement_id'          => [

                'type'           => 'INT',

                'constraint'     => 5,

                'unsigned'       => true,

                'auto_increment' => true

            ],

            'user_id'          => [

                'type'           => 'INT',

                'constraint'     => 5,

                'unsigned'       => true

            ],

            'event_name'       => [

                'type'           => 'VARCHAR',

                'constraint'     => '255'

            ],

            'position'       => [

                'type'           => 'VARCHAR',

                'constraint'     => '255'

            ],

            'year'       => [

                'type'           => 'int',

                'constraint'     => '5'

            ],

            'created_at datetime default current_timestamp',

            'updated_at datetime default current_timestamp on update current_timestamp',

        ]);



        $this->forge->addKey('user_achievement_id', TRUE);

        $this->forge->addForeignKey('user_id', 'users', 'id');

        $this->forge->createTable('user_achievement', TRUE);
    }



    public function down()

    {

        $this->forge->dropTable('user_achievement');
    }
}
