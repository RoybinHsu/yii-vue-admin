<?php

use yii\db\Migration;

/**
 * Class m221117_055506_AT_user
 */
class m221117_055506_AT_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';

        $this->createTable('{{%user}}', [
            'id'                   => $this->primaryKey(),
            'username'             => $this->string(255)->notNull()->defaultValue('')->unique()->comment('用户名称'),
            'phone'                => $this->string(255)->notNull()->defaultValue('')->unique()->comment('手机号码'),
            'email'                => $this->string(255)->notNull()->defaultValue('')->unique()->comment('email'),
            'auth_key'             => $this->string(255)->notNull()->defaultValue(''),
            'password_hash'        => $this->string(255)->notNull()->defaultValue(''),
            'password_reset_token' => $this->string(255)->unique()->defaultValue(''),
            'status'               => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at'           => $this->dateTime(),
            'updated_at'           => $this->dateTime(),
        ], $tableOptions);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221117_055506_AT_user cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221117_055506_AT_user cannot be reverted.\n";

        return false;
    }
    */
}
