<?php

use yii\db\Migration;

class m181001_192000_create_table_user extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull(),
            'auth_key' => $this->string()->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string(),
            'email' => $this->string()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue('10'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('email', '{{%user}}', 'email', true);
        $this->createIndex('username', '{{%user}}', 'username', true);
        $this->createIndex('password_reset_token', '{{%user}}', 'password_reset_token', true);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
