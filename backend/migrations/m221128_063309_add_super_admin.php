<?php

use app\models\User;
use app\service\user\UserServices;
use mdm\admin\models\Assignment;
use mdm\admin\models\AuthItem;
use yii\db\Migration;
use yii\rbac\Item;

/**
 * Class m221128_063309_add_super_admin
 */
class m221128_063309_add_super_admin extends Migration
{
    /**
     * {@inheritdoc}
     * @throws \yii\base\Exception
     */
    public function safeUp()
    {
        // 插入超级用户
        $model           = new User();
        $model->username = 'admin';
        $model->phone    = '1380000000000';
        $model->email    = 'admin@163.com';
        $model->generateAuthKey();
        $model->generatePasswordResetToken();
        $model->password = '123456';
        $model->status   = User::STATUS_ACTIVE;
        $model->save();
        $uid = $model->id;
        echo "用户创建成功: username: admin  password: 123456\n";
        $model       = new AuthItem(null);
        $model->type = Item::TYPE_ROLE;
        $form        = [
            'name'        => '超级管理员',
            'description' => '拥有系统超级权限的管理员',
        ];
        if ($model->load($form, '') && $model->save()) {
            echo "角色: 超级管理员 创建成功\n";
        } else {
            echo "角色: 超级管理员 创建失败\n";
        }
        // 将超级管理员分配给admin用户
        $assign = ['超级管理员'];
        $model  = new Assignment($uid);
        $model->assign($assign);
        echo "账号:admin 角色: 超级管理员 创建成功 \n";
        $ret = UserServices::getInstance()->isRole(true, $uid, '超级管理员');
        echo '角色数据缓存结果: ' . $ret . "\n";
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221128_063309_add_super_admin cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221128_063309_add_super_admin cannot be reverted.\n";

        return false;
    }
    */
}
