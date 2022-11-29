<?php

namespace app\controllers;

use app\controllers\base\AuthController;
use Yii;
use yii\web\Request;
use yii\web\Response;

class TestController extends AuthController
{

    public function actionIndex(): Response
    {
        return $this->returnOk(['username' => Yii::$app->user->identity->username]);
    }

    /**
     * 测试列表
     *
     * @return Response
     */
    public function actionList(): Response
    {
        return $this->returnOk(['username' => Yii::$app->user->identity->username]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function actionTest2(Request $request): Response
    {
        return $this->returnOk(['username' => Yii::$app->user->identity->username]);
    }
}
