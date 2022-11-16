<?php

namespace app\controllers;

use app\controllers\base\AuthController;

class TestController extends AuthController
{

    public function actionIndex()
    {
        return $this->returnOk(['username' => 'Test']);
    }
}
