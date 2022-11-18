<?php

namespace app\utils\menu;

use app\utils\base\Base;

/**
 * @property $path
 * @property $name
 * @property $component
 * @property $hidden
 * @property $meta
 * @property $redirect
 * @property $children
 */
class Menu extends Base
{

    /**
     * 页面路径
     *
     * @var string
     */
    public string $path = '';

    /**
     * 页面名称
     *
     * @var string
     */
    public string $name = '';

    /**
     * 页面使用的组件路径
     *
     * @var string
     */
    public string $component = '';

    /**
     * 菜单栏是否隐藏目录
     *
     * @var bool
     */
    public bool $hidden = true;

    /**
     * meta
     *
     * @var Meta
     */
    public Meta $meta;

    /**
     * 重定向页面地址
     *
     * @var string
     */
    public string $redirect = '';

    /**
     * @var array $children
     */
    public array $children = [];
}
