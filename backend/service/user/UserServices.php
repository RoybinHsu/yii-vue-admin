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
            'menus'    => $this->getUserMenu($id),
        ];

    }

    /**
     * 获取用户菜单
     *
     * @param $id
     *
     * @return array
     */
    public function getUserMenu($id = null): array
    {
        return [
            [
                'path'     => '/',
                'redirect' => '/dashboard',
                'meta'     => ['title' => 'Dashboard', 'icon' => 'el-icon-s-home'],
                'children' => [
                    [
                        'path' => 'dashboard',
                        'name' => 'Home',
                        'meta' => ['title' => '首页'],
                    ],
                ],
            ],
            [
                'path'     => '/example',
                'name'     => 'Example',
                'meta'     => ['title' => 'Example', 'icon' => 'el-icon-s-help'],
                'children' => [
                    [
                        'path' => 'table',
                        'name' => 'Table',
                        'meta' => ['title' => '表格样例', 'icon' => 'el-icon-s-grid'],
                    ],
                    [
                        'path' => 'tree',
                        'name' => 'Tree',
                        'meta' => ['title' => '树形样例', 'icon' => 'el-icon-s-grid'],
                    ],
                ],
            ],
        ];
    }

}
