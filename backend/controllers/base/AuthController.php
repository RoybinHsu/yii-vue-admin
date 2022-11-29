<?php

namespace app\controllers\base;

use app\models\User;
use app\service\user\UserServices;
use app\utils\jwt\JwtHttpBearerAuth;
use Yii;
use yii\base\Action;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;

class AuthController extends BaseController
{

    /**
     * 不验证权限接口,新加控制器重写属性进行修改
     *
     * @var array
     */
    public array $optional = [];

    /**
     * 不验证权限接口
     *
     * @var array
     */
    private array $_optional = [
        '/auth/user/login',
        '/auth/user/get-token',
        '/auth/user/captcha',
    ];

    /**
     * 初始化执行
     */
    public function init()
    {
        parent::init();
        $this->_optional = ArrayHelper::merge($this->_optional, $this->optional);
    }

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
            'optional' => $this->_optional,
        ];
        return $behaviors;
    }

    /**
     * @param Action $action
     *
     * @return bool
     * @throws BadRequestHttpException
     * @throws ForbiddenHttpException
     */
    public function beforeAction($action): bool
    {
        $actions = parent::beforeAction($action);
        $route   = '/' . ltrim($action->controller->getRoute(), '/');
        if ($actions) {
            if (in_array($route, $this->_optional) || UserServices::getInstance()->isRole()) {
                return true;
            } else {
                if (Yii::$app->user->can($route)) {
                    return true;
                } else {
                    Yii::error('uid:' . Yii::$app->user->identity->id . ' username:' . Yii::$app->user->identity->username . ' ip:' . Yii::$app->request->remoteIP);
                    throw new ForbiddenHttpException('对不起, 暂无权限访问', 403);
                }
            }
        }
        return false;
    }

}
