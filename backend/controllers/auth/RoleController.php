<?php

namespace app\controllers\auth;

use app\controllers\base\AuthController;
use yii\rbac\Item;

class RoleController extends ItemController
{

    /**
     * @return int
     */
    public function getType(): int
    {
        return Item::TYPE_ROLE;
    }
}
