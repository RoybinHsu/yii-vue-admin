<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "app_account".
 *
 * @property int $app_id                  授权的app id 对应platform_app的主键id
 * @property int $account_id              授权账户的的id 对应的account的主键id
 * @property string $access_token         access_token
 * @property string $refresh_token        refresh_token
 * @property int $access_token_expire_at  access token过期时间点
 * @property int $refresh_token_expire_at refresh token过期时间点
 * @property int $status                  状态 0暂未授权 1已授权  3授权失败 10授权已过期
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class AppAccount extends Base
{
    const STATUS_NOT = 0;// 未授权
    const STATUS_YES = 1;// 已授权
    const STATUS_ERR = 3;// 授权失败
    const STATUS_EXP = 10; // 授权过期

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'app_account';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['app_id', 'account_id', 'access_token_expire_at', 'refresh_token_expire_at', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['access_token', 'refresh_token'], 'string', 'max' => 1024],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'app_id'                  => 'App ID',
            'account_id'              => 'Account ID',
            'access_token'            => 'Access Token',
            'refresh_token'           => 'Refresh Token',
            'access_token_expire_at'  => 'Access Token Expire At',
            'refresh_token_expire_at' => 'Refresh Token Expire At',
            'status'                  => 'Status',
            'created_at'              => 'Created At',
            'updated_at'              => 'Updated At',
        ];
    }

    /**
     * 获取状态
     *
     * @param null $status
     *
     * @return string|string[]
     */
    public static function getStatus($status = null)
    {
        $map = [
            self::STATUS_NOT => '未授权',
            self::STATUS_YES => '已授权',
            self::STATUS_ERR => '授权失败',
            self::STATUS_EXP => '授权过期',
        ];
        if ($status === null) {
            return $map;
        }
        return $map[$status] ?? '-';
    }
}
