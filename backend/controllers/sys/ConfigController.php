<?php

namespace app\controllers\sys;

use app\controllers\base\AuthController;
use app\models\base\PlatformApp;
use yii\web\Request;
use yii\web\Response;

class ConfigController extends AuthController
{

    /**
     * 获取系统配置数据
     *
     * @param Request $request
     *
     * @return Response
     */
    public function actionIndex(Request $request): Response
    {
        $types  = $request->get('type', '');
        $types  = explode('|', $types);
        $config = [];
        foreach ($types as $type) {
            if ($type === 'platform') {
                // 获取platform相关配置数据
                $config[$type] = PlatformApp::getPlatform();
            }
            if ($type === 'app') {
                $config[$type] = [
                    'app_name' => 'Cuckoo'
                ];
            }
        }
        return $this->returnOk($config);
    }
}
