<?php

namespace app\commands;


use app\models\User;
use yii\base\Exception;

class UserController extends \yii\console\Controller
{
    /**
     * 添加后台用户
     *
     * @param $username
     * @param $phone
     * @param $pass
     * @param $email
     *
     * @return bool|int|string
     * @throws Exception
     */
    public function actionAdd($username, $phone, $pass, $email)
    {
        if (empty($username)) {
            return $this->stderr('请输入用户名') . "\n";
        }
        if (empty($phone)) {
            return $this->stderr('请输入电话号码' . "\n");
        }
        if (empty($pass)) {
            return $this->stderr('请输入密码' . "\n");
        }
        if (empty($email)) {
            return $this->stderr('请输入邮箱地址' . "\n");
        }
        $model = User::findUser($username, $phone, $email);
        if (empty($model)) {
            $model = new User();
        } else {
            $repeat = '';
            foreach (['username', 'phone', 'email'] as $r) {
                if ($$r === $model->$r) {
                    $repeat = '[' . $r . ': ' . $$r . ' 重复]';
                    break;
                }
            }
            return $this->stderr($repeat . "\n");
        }
        $model->username = $username;
        $model->phone    = $phone;
        $model->status   = User::STATUS_ACTIVE;
        $model->email    = $email;
        $model->generateAuthKey();
        $model->generatePasswordResetToken();
        $model->password = $pass;
        $model->save();
        return $this->stdout("用户[$username]创建成功\n");
    }

}
