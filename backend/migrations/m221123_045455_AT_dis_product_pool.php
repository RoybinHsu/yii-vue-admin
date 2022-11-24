<?php

use yii\db\Migration;

/**
 * Class m221123_045455_AT_dis_product_pool
 */
class m221123_045455_AT_dis_product_pool extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';
        $this->createTable('{{%dis_product_pool}}', [
            'id'            => $this->primaryKey(),
            'product_id'    => $this->bigInteger()->notNull()->defaultValue('')->unsigned()->unique()->comment('分销产品的id'),
            'product_title' => $this->string(255)->notNull()->defaultValue('')->comment('分销产品的标题'),
            'pic_url'       => $this->string(255)->notNull()->defaultValue('')->comment('分销产品图片'),
            'cat_id'        => $this->string(255)->notNull()->defaultValue('')->comment('分销产品图片'),
            'created_at'    => $this->dateTime(),
            'updated_at'    => $this->dateTime(),
        ], $tableOptions . ' COMMENT "已申请分销的产品表"');
        $this->createIndex('platform_name_unq', '{{%platform_app}}', ['platform', 'app_name'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221123_045455_AT_dis_product_pool cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221123_045455_AT_dis_product_pool cannot be reverted.\n";

        return false;
    }
    */
}
