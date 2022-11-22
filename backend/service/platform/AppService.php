<?php

namespace app\service\platform;

use app\models\base\PlatformApp;
use app\models\User;
use app\utils\base\Base;
use Exception;
use Yii;
use yii\helpers\ArrayHelper;

class AppService extends Base
{

    /**
     * @param $data
     *
     * @return bool
     */
    public function saveAppInfo($data): bool
    {
        $model = new PlatformApp();
        $data  = array_merge($data, ['uid' => Yii::$app->user->getId()]);
        $model->setAttributes($data);
        if ($model->save()) {
            return true;
        }
        throw new Exceptioin('数据保存失败');
    }

    /**
     * 获取应用列表
     *
     * @param array $filter
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function getList(array $filter = [], int $offset = 0, int $limit = 20): array
    {
        $model = PlatformApp::find();
        foreach ($filter as $k => $v) {
            if (!$v) {
                continue;
            }
            $model->andWhere([$k => $v]);
        }
        $count = $model->count();
        $data  = $model->offset($offset)->limit($limit)->orderBy(['id' => SORT_DESC])->asArray()->all();
        $uid   = [];
        foreach ($data as $v) {
            $uid[$v['uid']] = $v['uid'];
        }
        $userInfo = User::find()->where(['id' => array_values($uid)])->select(['id', 'username'])->asArray()->all();
        $userInfo = ArrayHelper::index($userInfo, 'id');
        foreach ($data as &$v) {
            $v['username']      = $userInfo[$v['uid']]['username'] ?? '';
            $component          = Yii::$app->helper->formatAppComponent($v['app_name'], $v['platform']);
            $v['can_authorize'] = Yii::$app->has($component);
            $v['platform_name'] = PlatformApp::getPlatform($v['platform']);
        }
        return [intval($count), $data];
    }
}
