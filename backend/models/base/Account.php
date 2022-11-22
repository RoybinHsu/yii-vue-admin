<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "account".
 *
 * @property int $id
 * @property string $platform   账户平台: 1688 | PDD
 * @property string $owner_id   授权用户的id
 * @property string $owner_name 授权用户的名称
 * @property string $member_id  会员接口id 1688平台会有
 * @property string $ali_id     阿里巴巴集团统一的id 1688平台会有
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Account extends Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'account';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['platform', 'owner_id', 'owner_name', 'member_id', 'ali_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'platform'   => 'Platform',
            'owner_id'   => 'Owner ID',
            'owner_name' => 'Owner Name',
            'member_id'  => 'Member ID',
            'ali_id'     => 'Ali ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
