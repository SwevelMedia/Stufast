<?php



namespace App\Database\Migrations;



use CodeIgniter\Database\Migration;



class NotificationCategory extends Migration

{


    public function up()

    {

        $this->forge->addField([

            'notification_category_id'          => [

                'type'           => 'INT',

                'constraint'     => 5,

                'unsigned'       => true,

                'auto_increment' => true

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



        $this->forge->addKey('notification_category_id', TRUE);

        $this->forge->createTable('notification_category', TRUE);
    }



    public function down()

    {

        $this->forge->dropTable('notification_category');
    }
}
