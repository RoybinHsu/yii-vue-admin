<?php

use mdm\admin\models\AuthItem;
use yii\db\Migration;
use yii\rbac\Item;

/**
 * Class m221129_023249_CT_guest_role
 */
class m221129_023249_CT_guest_role extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $route       = '/auth/user/info';
        $permission  = '获取登录用户信息';
        $auth        = Yii::$app->authManager;
        $role        = 'guest';
        // 1. 创建一个/auth/user/info接口权限 2. 创建guest角色  3. 并分配guest角色的权限
        $model       = new AuthItem(null);
        $model->type = Item::TYPE_PERMISSION;
        $form        = [
            'name'        => $route,
            'description' => '',
        ];
        $model->load($form, '');
        $model->save();

        $model       = new AuthItem(null);
        $model->type = Item::TYPE_PERMISSION;
        $form        = [
            'name'        => $permission,
            'description' => $permission,
        ];
        $model->load($form, '');
        $model->save();

        $items = [$route];
        $item  = $auth->getPermission($permission);
        $model = new AuthItem($item);
        $model->addChildren($items);

        $model       = new AuthItem(null);
        $model->type = Item::TYPE_ROLE;
        $form        = [
            'name'        => $role,
            'description' => '默认角色',
        ];
        $model->load($form, '');
        $model->save();
        $items = ['获取登录用户信息'];
        $item  = $auth->getRole($role);
        $model = new AuthItem($item);
        $model->addChildren($items);
        $auth->cache->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221129_023249_CT_guest_role cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221129_023249_CT_guest_role cannot be reverted.\n";

        return false;
    }
    */
}
