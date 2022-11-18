<?php

namespace app\service\user;

use app\models\User;
use app\utils\base\Base;
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
        ];

    }

}
