<?php

namespace app\controllers\base;

use app\models\User;
use app\utils\jwt\JwtHttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\web\Response;

class AuthController extends BaseController
{
    /**
     * 默认
     *
     * @return array|array[]
     */
    public function behaviors(): array
    {
        $behaviors                      = parent::behaviors();
        $behaviors['contentNegotiator'] = [
            'class'   => ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];
        $behaviors['authenticator']     = [
            'class'    => JwtHttpBearerAuth::class,
            'optional' => [
                '/user/login',
                '/user/get-token',
            ],
        ];
        return $behaviors;
    }

}
