<?php

use yii\db\Migration;

class m230520_184138_create_init_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%incrementer}}', [
            'year' => $this->string(8),
            'value' => $this->integer(),
            'type' => $this->string(8),
            'status' => $this->integer(2)->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createTable('{{%users}}', [
            'user_id' => $this->primaryKey(),
            'username' => $this->string(16)->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string(64)->notNull(),
            'api_key' => $this->string(32)->notNull(),
            'verification_token' => $this->string(),
            'type' => $this->string(8)->notNull(),
            'status' => $this->integer(2)->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createTable('{{%patient_profile}}', [
            'patient_id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'first_name' => $this->string(16),
            'middle_name' => $this->string(16),
            'last_name' => $this->string(16),
            'mobile_number' => $this->string(13),
            'email_address' => $this->string(256),
            'status' => $this->integer(2)->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('FK_patient_profile_user','{{%patient_profile}}','user_id','{{%users}}','user_id');

        $this->createTable('{{%admin_profile}}', [
            'admin_id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'first_name' => $this->string(32),
            'last_name' => $this->string(32),
            'mobile_number' => $this->string(13),
            'email_address' => $this->string(256),
            'status' => $this->integer(2)->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('FK_admin_profile_user','{{%admin_profile}}','user_id','{{%users}}','user_id');

        $this->createTable('{{%doctor_profile}}', [
            'doctor_id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'first_name' => $this->string(32),
            'last_name' => $this->string(32),
            'mobile_number' => $this->string(13),
            'email_address' => $this->string(256),
            'status' => $this->integer(2)->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('FK_doctor_profile_user','{{%doctor_profile}}','user_id','{{%users}}','user_id');
        
        $this->createTable('{{%vendor_profile}}', [
            'vendor_id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'first_name' => $this->string(32),
            'last_name' => $this->string(32),
            'mobile_number' => $this->string(13),
            'email_address' => $this->string(256),
            'status' => $this->integer(2)->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('FK_vendor_profile_user','{{%vendor_profile}}','user_id','{{%users}}','user_id');
        
        $this->createTable('{{%token}}', [
            'token_id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'token' => $this->integer(8),
            'type' => $this->string(16),
            'status' => $this->integer(2)->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('FK_token_user','{{%token}}','user_id','{{%users}}','user_id');

        $this->createTable('{{%verification}}', [
            'verification_id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'mobile_number' => $this->integer(2)->notNull(),
            'email_address' => $this->integer(2)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('FK_verification_user','{{%verification}}','user_id','{{%users}}','user_id');

        $this->createTable('{{%password_history}}', [
            'password_history_id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'password' => $this->string(32),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('FK_password_history_user','{{%password_history}}','user_id','{{%users}}','user_id');

        $this->createTable('{{%mode}}', [
            'mode_id' => $this->primaryKey(),
            'name' => $this->string(8)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->insert('{{%users}}',
        [
            'user_id' => 1,
            'username' => 'admin',
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password_hash' =>Yii::$app->security->generatePasswordHash('admin'),
            'api_key' => Yii::$app->security->generateRandomString(),
            'type' => 'admin',
            'status' =>10 ,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert('{{%admin_profile}}',
        [
            'admin_id' => 1,
            'user_id' => 1,
            'first_name' => 'system',
            'last_name' => 'admin',
            'mobile_number' => '0713116240',
            'email_address' => 'admin@gmail.com',
            'status' => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%mode}}');
        $this->dropTable('{{%password_history}}');
        $this->dropForeignKey('FK_password_history_user','{{%password_history}}');
        $this->dropTable('{{%verification}}');
        $this->dropForeignKey('FK_verification_user','{{%verification}}');
        $this->dropTable('{{%token}}');
        $this->dropForeignKey('FK_token_user','{{%token}}');
        $this->dropTable('{{%vendor_profile}}');
        $this->dropForeignKey('FK_vendor_profile_user','{{%vendor_profile}}');
        $this->dropTable('{{%doctor_profile}}');
        $this->dropForeignKey('FK_doctor_profile_user','{{%doctor_profile}}');
        $this->dropTable('{{%admin_profile}}');
        $this->dropForeignKey('FK_admin_profile_user','{{%admin_profile}}');
        $this->dropTable('{{%patient_profile}}');
        $this->dropForeignKey('FK_patient_profile_user','{{%patient_profile}}');
        $this->dropTable('{{%users}}');
        $this->dropTable('{{%incrementer}}');
    }
}
