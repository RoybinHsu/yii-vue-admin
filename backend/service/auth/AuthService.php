<?php

namespace app\service\auth;

use app\utils\base\Base;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use yii\base\InvalidConfigException;
use yii\web\Controller;
use Yii;

class AuthService extends Base
{
    /**
     * 获取路由php doc 注释
     *
     * @param string $route
     *
     * @return string
     * @throws InvalidConfigException
     * @throws ReflectionException
     */
    public function routeDocComment(string $route): string
    {
        /** @var Controller $controller */
        $result = Yii::$app->createController($route);
        [$controller, $actionID] = $result;
        $reflect       = new ReflectionClass($controller);
        if (empty($action)) {
            return '';
        }
        $action        = $controller->createAction($actionID);
        $public_method = $reflect->getMethod($action->actionMethod);
        return $this->parseDocCommentDetail($public_method);
    }

    /**
     * 解析php doc注释
     *
     * @param ReflectionMethod $reflection
     *
     * @return string
     */
    protected function parseDocCommentDetail(ReflectionMethod $reflection): string
    {
        $comment = strtr(trim(preg_replace('/^\s*\**( |\t)?/m', '', trim($reflection->getDocComment(), '/'))), "\r",
            '');
        if (preg_match('/^\s*@\w+/m', $comment, $matches, PREG_OFFSET_CAPTURE)) {
            $comment = trim(substr($comment, 0, $matches[0][1]));
        }
        if ($comment !== '') {
            return trim($comment);
        }
        return '';
    }
}
