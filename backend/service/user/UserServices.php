<?php

namespace app\service\user;

use app\models\User;
use app\utils\base\Base;
use app\utils\menu\Menu;
use app\utils\menu\Meta;
use yii\base\Exception;

class UserServices extends Base
{

    /**
     * 通过id获取用户信息
     *
     * @param $id
     *
     * @return array
     * @throws Exception
     */
    public function getLoginUserInfo($id): array
    {
        $model = User::findIdentity($id);
        if (empty($model)) {
            throw new Exception('用户不存在');
        }
        return [
            'id'       => $model->id,
            'username' => $model->username,
            'phone'    => $model->phone,
            'email'    => $model->email,
            'status'   => $model->status,
            'avatar'   => 'a' . (($model->id % 8) + 1) . '.jpg',
            'menu'     => $this->getUserMenu($id),
        ];

    }

    /**
     * 获取用户菜单
     *
     * @param $id
     *
     * @return array
     */
    public function getUserMenu($id): array
    {
        $menus[] = new Menu([
            'path'      => '/login',
            'name'      => 'Login',
            'redirect'  => '',
            'component' => '@/views/login/index',
            'hidden'    => true,
            'meta'      => new Meta(['title' => '登录']),
            'children'  => [],
        ]);
        $menus[] = new Menu([
            'path'      => '/404',
            'name'      => '404',
            'redirect'  => '',
            'component' => '@/views/404',
            'hidden'    => true,
            'meta'      => new Meta(['title' => '页面为找']),
            'children'  => [],
        ]);
        $menus[]   = new Menu([
            'path'      => '/',
            'redirect'  => '/dashboard',
            'component' => '@/layout',
            'hidden'    => false,
            'meta'      => new Meta(['title' => '']),
            'children'  => [
                new Menu([
                    'path'      => 'dashboard',
                    'name'      => 'Dashboard',
                    'component' => '@/views/dashboard/index',
                    'hidden'    => false,
                    'meta'      => new Meta(['title' => '首页']),
                ])
            ],
        ]);
        $menus[] = new Menu([
            'path'      => '/example',
            'name'      => 'Example',
            'redirect'  => '/example/table',
            'component' => '@/layout',
            'hidden'    => false,
            'meta'      => new Meta(['title' => 'Example', 'icon' => 'el-icon-s-help']),
            'children'  => [],
        ]);
        return $menus;
    }

}
