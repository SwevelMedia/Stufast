<?php



namespace App\Database\Migrations;



use CodeIgniter\Database\Migration;



class UserExperience extends Migration

{


    public function up()

    {

        $this->forge->addField([

            'user_experience_id'          => [

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

            'type'       => [

                'type'           => 'VARCHAR',

                'constraint'     => '255'

            ],

            'instance_name'       => [

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



        $this->forge->addKey('user_experience_id', TRUE);

        $this->forge->addForeignKey('user_id', 'users', 'id');

        $this->forge->createTable('user_experience', TRUE);
    }



    public function down()

    {

        $this->forge->dropTable('user_experience');
    }
}
