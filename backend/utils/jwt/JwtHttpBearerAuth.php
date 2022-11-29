<?php


namespace app\utils\jwt;


use app\common\exception\ParamErrorException;
use app\services\company\CompanyService;
use Lcobucci\JWT\Token;
use yii\base\Action;
use yii\base\InvalidArgumentException;
use yii\helpers\StringHelper;
use yii\web\IdentityInterface;
use yii\web\Request;
use yii\web\User;
use Yii;

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

    /**
     * @param User $user
     * @param Request $request
     * @param \yii\web\Response $response
     *
     * @return IdentityInterface|null
     */
    public function authenticate($user, $request, $response): ?IdentityInterface
    {
        $authHeader = $request->getHeaders()->get('Authorization');
        if (!$this->isOptional(Yii::$app->requestedAction) && empty($authHeader)) {
            throw new InvalidArgumentException('缺少权限参数[Authorization]');
        }
        if (preg_match('/^' . $this->schema . '\s+(.*?)$/', $authHeader, $matches)) {
            /**
             * @var Token $token
             */
            $token = $this->loadToken($matches[1]);
            if ($token === null) {
                return null;
            }
            return Yii::$app->user->loginByAccessToken($token, get_class($this));
        }
        return null;
    }

}
