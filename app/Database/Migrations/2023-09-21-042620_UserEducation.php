<?php



namespace App\Database\Migrations;



use CodeIgniter\Database\Migration;



class UserEducation extends Migration

{


    public function up()

    {

        $this->forge->addField([

            'user_education_id'          => [

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

            'status'       => [

                'type'           => 'VARCHAR',

                'constraint'     => '255'

            ],


            'education_name'       => [

                'type'           => 'VARCHAR',

                'constraint'     => '255'

            ],

            'major'       => [

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



        $this->forge->addKey('user_education_id', TRUE);

        $this->forge->addForeignKey('user_id', 'users', 'id');

        $this->forge->createTable('user_education', TRUE);
    }



    public function down()

    {

        $this->forge->dropTable('user_education');
    }
}
