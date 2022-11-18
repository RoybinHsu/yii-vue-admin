<?php

namespace app\utils\base;

use yii\base\BaseObject;
use yii\helpers\StringHelper;

class Base extends BaseObject
{

    /**
     * @var array $this
     */
    protected static array $_instance = [];

    /**
     * 获取实例
     *
     * @param array $config
     * @param bool $fresh
     *
     * @return mixed|null|$this
     */
    public static function getInstance(array $config = [], bool $fresh = false)
    {
        ksort($config);
        $className = get_called_class();
        $key       = $className . shortMd5(json_encode($config));
        if (empty(self::$_instance[$key]) || $fresh) {
            self::$_instance[$key] = new $className($config);
        } else {
        }
        return self::$_instance[$key];
    }
}
