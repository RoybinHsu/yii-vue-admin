<?php

use yii\db\Migration;

/**
 * Class m221121_084426_AT_account
 */
class m221121_084426_AT_account extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';
        $this->createTable('{{%platform_app}}', [
            'id'           => $this->primaryKey(),
            'platform'     => $this->string(255)->notNull()->defaultValue('')->comment('账户平台: 1688 | PDD'),
            'app_name'     => $this->string(255)->notNull()->defaultValue('')->comment('应用名称'),
            'redirect_url' => $this->string(255)->notNull()->defaultValue('')->comment('应用首页地址'),
            'app_key'      => $this->string(255)->notNull()->defaultValue('')->unique()->comment('应用的app_key或者client_id'),
            'app_secret'   => $this->string(255)->notNull()->defaultValue('')->comment('应用的secret'),
            'uid'          => $this->integer()->notNull()->unsigned()->defaultValue(0)->comment('创建用户'),
            'created_at'   => $this->dateTime(),
            'updated_at'   => $this->dateTime(),
        ], $tableOptions);
        $this->createIndex('platform_name_unq', '{{%platform_app}}', ['platform', 'app_name'], true);

        // 授权用户信息表
        $this->createTable('{{%account}}', [
            'id'         => $this->primaryKey(),
            'platform'   => $this->string(255)->notNull()->defaultValue('')->comment('账户平台: 1688 | PDD'),
            'owner_id'   => $this->string(255)->notNull()->defaultValue('')->comment('授权用户的id'),
            'owner_name' => $this->string(255)->notNull()->defaultValue('')->comment('授权用户的名称'),
            'member_id'  => $this->string(255)->notNull()->defaultValue('')->comment('会员接口id 1688平台会有'),
            'ali_id'     => $this->string(255)->notNull()->defaultValue('')->comment('阿里巴巴集团统一的id 1688平台会有'),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        // 用户授权关系表
        $this->createTable('{{%app_account}}', [
            'app_id'                  => $this->integer()->notNull()->notNull()->defaultValue(0)->comment('授权的app id 对应platform_app的主键id'),
            'account_id'              => $this->integer()->notNull()->notNull()->defaultValue(0)->comment('授权账户的的id 对应的account的主键id'),
            'access_token'            => $this->string(1024)->notNull()->defaultValue('')->comment('access_token'),
            'refresh_token'           => $this->string(1024)->notNull()->defaultValue('')->comment('refresh_token'),
            'access_token_expire_at'  => $this->integer()->notNull()->defaultValue(0)->comment('access token过期时间点'),
            'refresh_token_expire_at' => $this->integer()->notNull()->defaultValue(0)->comment('refresh token过期时间点'),
            'status'                  => $this->tinyInteger()->notNull()->unsigned()->defaultValue(0)->comment('状态 0暂未授权 1已授权  3授权失败 10授权已过期'),
            'created_at'              => $this->dateTime(),
            'updated_at'              => $this->dateTime(),
        ], $tableOptions);
        $this->createIndex('app_id_account_id_unq', '{{%app_account}}', ['app_id', 'account_id'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%platform_app}}');
        $this->dropTable('{{%account}}');
        $this->dropTable('{{%app_account}}');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221121_084426_AT_account cannot be reverted.\n";

        return false;
    }
    */
}
