<?php



namespace App\Database\Migrations;



use CodeIgniter\Database\Migration;



class Banner extends Migration

{


    public function up()

    {

        $this->forge->addField([

            'banner_id'          => [

                'type'           => 'INT',

                'constraint'     => 5,

                'unsigned'       => true,

                'auto_increment' => true

            ],

            'text'       => [

                'type'           => 'VARCHAR',

                'constraint'     => '255'

            ],

            'thumbnail'       => [

                'type'           => 'VARCHAR',

                'constraint'     => '255'

            ],

            'link'       => [

                'type'           => 'VARCHAR',

                'constraint'     => '255'

            ],

            'created_at datetime default current_timestamp',

            'updated_at datetime default current_timestamp on update current_timestamp',

        ]);



        $this->forge->addKey('banner_id', TRUE);

        $this->forge->createTable('banner', TRUE);
    }



    public function down()

    {

        $this->forge->dropTable('banner');
    }
}
