<?php

namespace app\controllers;

use app\controllers\base\AuthController;
use app\models\User;
use app\service\user\UserServices;
use app\utils\jwt\Jwt;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\data\DataProviderInterface;
use yii\helpers\StringHelper;
use yii\validators\Validator;
use yii\web\Request;
use yii\web\Response;

class UserController extends AuthController
{
    /**
     * 获取登录token
     *
     * @param Request $request
     *
     * @return Response
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function actionGetToken(Request $request): Response
    {
        $data = $request->getBodyParams();
        if (empty($data['username']) || empty($data['password'])) {
            throw new Exception('缺少登录必须参数');
        }
        $user = User::findUser($data['username'], $data['username'], $data['username']);
        if ($user) {
            if ($user->validatePassword($data['password'])) {
                $jwt   = Jwt::getInstance([
                    'claims' => ['id' => $user->id]
                ]);
                $token = $jwt->generateToken();
                if ($token) {
                    return $this->returnOk(['token' => (string)$token]);
                }
            } else {
                throw new Exception('密码不正确, 请重新输入');
            }
        }
        return $this->returnErr('手机号或者密码错误');

    }

    /**
     * 获取登录用户信息
     *
     * @param Request $request
     *
     * @return Response
     * @throws Exception
     */
    public function actionInfo(Request $request): Response
    {
        $data = UserServices::getInstance() ->getLoginUserInfo(Yii::$app->user->getId());
        return $this->returnOk($data);
    }


}
