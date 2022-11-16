<?php


namespace app\utils\jwt;


use yii\base\Action;
use yii\helpers\StringHelper;

class JwtHttpBearerAuth extends \sizeg\jwt\JwtHttpBearerAuth
{

    /**
     * @param Action $action
     *
     * @return bool
     */
    public function isOptional($action)
    {
        $route = '/' . $action->controller->id . '/' . $action->id;
        foreach ($this->optional as $pattern) {
            if (StringHelper::matchWildcard($pattern, $route)) {
                return true;
            }
        }
        return false;
    }

}
