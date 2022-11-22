<?php

namespace app\service\platform;

use app\models\base\Account;
use app\models\base\AppAccount;
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

    /**
     * 获取授权商家列表
     *
     * @param array $filter
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function getAccountList(array $filter = [], int $offset = 0, int $limit = 20): array
    {
        $model = AppAccount::find()->alias('a')
            ->leftJoin(PlatformApp::tableName() . ' AS b', 'a.app_id=b.id')
            ->leftJoin(Account::tableName() . ' AS c', 'a.account_id=c.id')
            ->select([
                'b.platform',
                'b.app_name',
                'c.owner_id',
                'c.owner_name',
                'a.access_token_expire_at',
                'a.refresh_token_expire_at',
                'a.status',
                'a.created_at',
            ])->asArray();
        foreach ($filter as $k => $v) {
            if ($v === '') {
                continue;
            }
            switch ($k) {
                case 'status':
                    $model->andWhere(['a.status' => $v]);
                    break;
                case 'platform':
                    $model->andWhere(['b.platform' => $v]);
                    break;
                case 'app_name':
                    $model->andWhere(['b.app_name' => $v]);
                    break;
            }
        }
        $total = $model->count();
        $data  = $model->offset($offset)->limit($limit)->all();
        foreach ($data as &$v) {
            $v['status_desc']             = AppAccount::getStatus($v['status']);
            $v['access_token_type']       = $this->getTokenTagType($v['access_token_expire_at']);
            $v['access_token_expire_at']  = date('Y-m-d', $v['access_token_expire_at']);
            $v['refresh_token_type']      = $this->getTokenTagType($v['refresh_token_expire_at']);
            $v['refresh_token_expire_at'] = date('Y-m-d', $v['refresh_token_expire_at']);
            $v['platform_name']           = PlatformApp::getPlatform($v['platform']);
        }
        return [$total, $data];
    }

    /**
     * token过期时间显示颜色
     *
     * @param $expire
     *
     * @return string
     */
    public function getTokenTagType($expire): string
    {
        $now  = time();
        $diff = $expire - $now;
        $type = 'info';
        if ($diff > 60 * 86400) {
            $type = 'success';
        } elseif ($diff > 30 * 86400) {
            $type = '';
        } elseif ($diff > 20 * 86400) {
            $type = 'warning';
        } elseif ($diff > 5 * 86400) {
            $type = 'danger';
        }
        return $type;
    }
}
