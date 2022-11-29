<?php

namespace app\controllers\auth;

use app\controllers\base\AuthController;
use mdm\admin\components\Configs;
use mdm\admin\models\Route;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\StringHelper;
use yii\web\Request;
use yii\web\Response;

class RouteController extends AuthController
{

    /**
     * @param $name
     *
     * @return array|false|string|string[]|null
     */

    /**
     * 可获取后台所有路由和已添加的路由
     *
     * @param Request $request
     *
     * @return Response
     */
    public function actionIndex(Request $request): Response
    {
        $model               = new Route();
        $routes              = $model->getRoutes();
        $admin               = $model->getAppRoutes(Yii::$app->getModule('admin'));
        $available           = $routes['available'];
        $available           = array_filter($available, function ($v) use ($admin) {
            return !(in_array($v, $admin) || strpos($v, '*') !== false || strpos($v, 'gii') !== false || strpos($v,
                    'debug') !== false);
        });
        $routes['available'] = array_values($available);
        $routes['assigned']  = array_values($routes['assigned']);
        return $this->returnOk($routes);

    }

    /**
     * 刷新路由缓存
     *
     * @param Request $request
     *
     * @return Response
     */
    public function actionFreshCache(Request $request): Response
    {
        $key   = ['mdm\admin\models\Route::getAppRoutes', Yii::$app->id, Yii::$app->getUniqueId()];
        $cache = Yii::$app->cache;
        $cache->delete($key);
        return $this->returnOk();
    }

    /**
     * 创建添加路由
     *
     * @param Request $request
     *
     * @return Response
     * @throws InvalidConfigException
     */
    public function actionCreate(Request $request): Response
    {
        $routes = $request->getBodyParams();
        $model  = new Route();
        $model->addNew($routes);
        return $this->returnOk();
    }


    /**
     * 移除路由
     *
     * @param Request $request
     *
     * @return Response
     * @throws InvalidConfigException
     */
    public function actionRemove(Request $request): Response
    {
        $routes = $request->getBodyParams();
        $model  = new Route();
        $model->remove($routes);
        return $this->returnOk();
    }

}
