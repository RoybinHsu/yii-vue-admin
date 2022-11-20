<?php

use yii\db\Migration;

/**
 * Class m221119_102847_AT_menu
 */
class m221119_102847_AT_menu extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';
        $this->createTable('{{%menu}}', [
            'id'         => $this->primaryKey(),
            'pid'        => $this->integer()->notNull()->defaultValue(0)->comment('菜单父级'),
            'name'       => $this->string(255)->notNull()->defaultValue('')->comment('名称'),
            'title'      => $this->string(255)->notNull()->defaultValue('')->comment('中文名称'),
            'icon'       => $this->string(255)->notNull()->defaultValue('')->comment('icon'),
            'path'       => $this->string(255)->notNull()->defaultValue('')->unique()->comment('前端页面路径'),
            'redirect'   => $this->string(255)->notNull()->defaultValue('')->comment('重定向页面路径'),
            'hidden'     => $this->tinyInteger(1)->notNull()->defaultValue(1)->comment('菜单栏中隐藏'),
            'meta'       => $this->json()->defaultValue(null)->comment('meta信息'),
            'api'        => $this->string(255)->notNull()->defaultValue('')->comment('后台接口api路由'),
            'order'      => $this->integer()->notNull()->unsigned()->defaultValue(0)->comment('排序'),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221119_102847_AT_menu cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221119_102847_AT_menu cannot be reverted.\n";

        return false;
    }
    */
}
