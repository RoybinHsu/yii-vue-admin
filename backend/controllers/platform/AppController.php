<?php

namespace app\controllers\platform;

use app\controllers\base\AuthController;
use app\models\base\PlatformApp;
use app\service\platform\AppService;
use app\utils\general\PlatformAppInterface;
use yii\base\Exception;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\Response;
use Yii;

class AppController extends AuthController
{

    /**
     * 添加平台应用
     *
     * @param Request $request
     *
     * @return Response
     */
    public function actionAdd(Request $request): Response
    {
        try {
            $appInfo = $request->getBodyParams();
            AppService::getInstance()->saveAppInfo($appInfo);
            return $this->returnOk();
        } catch (InvalidConfigException $e) {
            return $this->returnErr($e->getMessage());
        }

    }

    /**
     * 获取列表
     *
     * @param Request $request
     *
     * @return Response
     */
    public function actionList(Request $request): Response
    {
        $filter['app_name'] = $request->get('app_name', '');
        $filter['platform'] = $request->get('platform', '');
        $page               = $request->get('page', 1);
        $limit              = $request->get('limit', 20);
        $offset             = ($page - 1) * $limit;
        [$count, $data] = AppService::getInstance()->getList($filter, $offset, $limit);
        return $this->returnOk(['total' => $count, 'data' => $data]);
    }

    /**
     * 获取授权页面地址
     *
     * @param Request $request
     *
     * @return Response
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function actionAuthorizeUrl(Request $request): Response
    {
        $id = intval($request->get('id', 0));
        if (empty($id)) {
            throw new NotFoundHttpException('参数错误');
        }
        $model = PlatformApp::findOne($id);
        if (empty($model)) {
            throw new NotFoundHttpException('参数错误');
        }
        $component = Yii::$app->helper->formatAppComponent($model->app_name, $model->platform);
        if (!Yii::$app->has($component)) {
            throw new Exception('系统配置错误');
        }
        $state = $component . '_' . uniqid();
        /** @var PlatformAppInterface $component */
        $component = Yii::$app->$component;
        $url       = $component->authorizeUrl($state);
        return $this->returnOk(['url' => $url]);
    }

    /**
     * 获取授权商家列表
     *
     * @param Request $request
     *
     * @return Response
     */
    public function actionAccount(Request $request): Response
    {
        $platform = trim($request->get('platform', ''));
        $app_name = trim($request->get('app_name', ''));
        $status   = '' . trim($request->get('status', ''));
        $page     = intval($request->get('page', ''));
        $limit    = intval($request->get('limit', ''));
        $filter   = [];
        if ($platform) {
            $filter['platform'] = $platform;
        }
        if ($app_name) {
            $filter['app_name'] = $app_name;
        }
        if ($status !== '') {
            $filter['status'] = $status;
        }
        $offset = ($page - 1) * $limit;
        [$total, $data] = AppService::getInstance()->getAccountList($filter, $offset, $limit);
        return $this->returnOk(['data' => $data, 'total' => $total]);
    }

}
