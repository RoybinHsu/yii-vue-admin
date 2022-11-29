<?php

namespace app\controllers\auth;

use yii\rbac\Item;

/**
 * @property $type
 */
class PermissionController extends ItemController
{

    /**
     * @return int
     */
    public function getType(): int
    {
        return Item::TYPE_PERMISSION;
    }

}
