<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property int $id
 * @property int $pid          菜单父级
 * @property string $name      名称
 * @property string $title     中文名称
 * @property string $icon      icon
 * @property string $path      前端页面路径
 * @property string $redirect  重定向页面路径
 * @property int $hidden       菜单栏中隐藏
 * @property string|null $meta meta信息
 * @property string $api       后台接口api路由
 * @property int $order        排序
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Menu extends Base
{
    const SHOW = 0;
    const HIDE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'menu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['pid', 'hidden', 'order'], 'integer'],
            [['meta', 'created_at', 'updated_at'], 'safe'],
            [['name', 'title', 'icon', 'path', 'redirect', 'api'], 'string', 'max' => 255],
            [['path'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'         => 'ID',
            'pid'        => 'Pid',
            'name'       => 'Name',
            'title'      => 'Title',
            'icon'       => 'Icon',
            'path'       => 'Path',
            'redirect'   => 'Redirect',
            'hidden'     => 'Hidden',
            'meta'       => 'Meta',
            'api'        => 'Api',
            'order'      => 'Order',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
