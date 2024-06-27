<?php



namespace App\Database\Migrations;



use CodeIgniter\Database\Migration;



class UserCv extends Migration

{


    public function up()

    {

        $this->forge->addField([
            'user_cv_id'          => [
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

            'method'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255'
            ],

            'about'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '500'
            ],

            'instagram'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255'
            ],

            'facebook'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255'
            ],

            'linkedin'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255'
            ],

            'portofolio'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255'
            ],

            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',

        ]);



        $this->forge->addKey('user_cv_id', TRUE);

        // $this->forge->addForeignKey('user_id', 'users', 'id');

        $this->forge->createTable('user_cv', TRUE);
    }



    public function down()
    {
        $this->forge->dropTable('user_cv');
    }
}
