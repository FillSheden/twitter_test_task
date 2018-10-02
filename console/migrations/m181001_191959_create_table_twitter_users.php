<?php

use yii\db\Migration;

class m181001_191959_create_table_twitter_users extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%twitter_users}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'is_active' => $this->integer()->defaultValue('1'),
            'created' => $this->date()->notNull(),
        ], $tableOptions);

        $this->createIndex('twitter_users_id_uindex', '{{%twitter_users}}', 'id', true);
    }

    public function down()
    {
        $this->dropTable('{{%twitter_users}}');
    }
}
