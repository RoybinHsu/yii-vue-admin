<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "platform_app".
 *
 * @property int $id
 * @property string $platform     账户平台: 1688 | PDD
 * @property string $app_name     应用名称
 * @property string $redirect_url 应用首页地址
 * @property string $app_key      应用的app_key或者client_id
 * @property string $app_secret   应用的secret
 * @property int $uid             创建用户
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class PlatformApp extends Base
{

    const PLATFORM_1688 = '1688';  // alibaba 1688
    const PLATFORM_PDD = 'PDD';    // pdd
    const PLATFORM_TTB = 'TAOBAO'; // 淘宝


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'platform_app';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['platform', 'app_name', 'redirect_url', 'app_key', 'app_secret'], 'string', 'max' => 255],
            [['app_key'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'platform'     => 'Platform',
            'app_name'     => 'App Name',
            'redirect_url' => 'Redirect Url',
            'app_key'      => 'App Key',
            'app_secret'   => 'App Secret',
            'uid'          => 'Uid',
            'created_at'   => 'Created At',
            'updated_at'   => 'Updated At',
        ];
    }

    /**
     * 获取平台描述
     *
     * @param null $platform
     */
    public static function getPlatform($platform = null)
    {
        $map = [
            self::PLATFORM_1688 => '阿里巴巴',
            self::PLATFORM_PDD  => '拼多多',
            self::PLATFORM_TTB  => '淘宝',
        ];
        if ($platform === null) {
            return $map;
        }
        return $map[$platform] ?? '-';
    }
}
