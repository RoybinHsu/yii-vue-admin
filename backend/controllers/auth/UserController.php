<?php

namespace app\controllers\auth;

use app\controllers\base\AuthController;
use app\models\base\Menu;
use app\models\User;
use app\service\user\UserServices;
use app\utils\exception\DataRepeatException;
use app\utils\jwt\Jwt;
use mdm\admin\models\Assignment;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\captcha\CaptchaAction;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\Response;

class UserController extends AuthController
{
    /**
     * 获取登录token
     *
     * @param Request $request
     *
     * @return Response
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function actionGetToken(Request $request): Response
    {
        $data = $request->getBodyParams();
        if (empty($data['username']) || empty($data['password']) || empty($data['captcha'])) {
            throw new Exception('缺少登录必须参数');
        }
        $config = [
            'class' => CaptchaAction::class,
        ];
        /** @var CaptchaAction $c */
        $c = Yii::createObject($config, ['__captcha', $this]);
        if (!$c->validate($data['captcha'], false)) {
            throw new Exception('验证码输入不正确');
        }
        $user = User::findUser($data['username'], $data['username'], $data['username']);
        if ($user) {
            if ($user->validatePassword($data['password'])) {
                $jwt   = Jwt::getInstance([
                    'claims' => ['id' => $user->id],
                ]);
                $token = $jwt->generateToken();
                if ($token) {
                    return $this->returnOk(['token' => (string)$token]);
                }
            } else {
                throw new Exception('密码不正确, 请重新输入');
            }
        }
        return $this->returnErr('手机号或者密码错误');

    }

    /**
     * 获取登录用户信息
     *
     * @param Request $request
     *
     * @return Response
     * @throws Exception
     */
    public function actionInfo(Request $request): Response
    {
        $data = UserServices::getInstance()->getLoginUserInfo(Yii::$app->user->getId());
        return $this->returnOk($data);
    }

    /**
     * 退出登录
     *
     * @param Request $request
     *
     * @return Response
     */
    public function actionLogout(Request $request): Response
    {
        Yii::$app->user->logout();
        return $this->returnOk();

    }

    /**
     * 菜单获取和上传
     *
     * @param Request $request
     *
     * @return Response
     */
    public function actionMenus(Request $request): Response
    {
        if ($request->isPost) {
            // 上传菜单数据
            $trans = Yii::$app->db->beginTransaction();
            try {
                $data = $request->getBodyParams();
                UserServices::getInstance()->checkRepeat($request->getRawBody());
                UserServices::getInstance()->delMenus();
                UserServices::getInstance()->saveMenus($data);
                $trans->commit();
                return $this->returnOk();
            } catch (InvalidConfigException | Exception $e) {
                Yii::error('upload menus错误: ' . $e->getMessage() . ' file:' . $e->getFile() . ' line:' . $e->getLine());
                $trans->rollBack();
                return $this->returnErr($e->getMessage());
            }
            return $this->returnErr();
        }
        $page      = $request->get('page', 1);
        $limit     = $request->get('limit', 20);
        $path      = trim($request->get('path', ''));
        $title     = trim($request->get('title', ''));
        $pid_title = trim($request->get('pid_title', ''));
        $api       = trim($request->get('api', ''));
        $offset    = ($page - 1) * $limit;
        $filter    = [];
        if ($path) {
            $filter['path'] = $path;
        }
        if ($title) {
            $filter['title'] = $title;
        }
        if ($pid_title) {
            $parent = Menu::find()->select('id')->where(['title' => $pid_title])->asArray()->all();
            $pidS   = ArrayHelper::getColumn($parent, 'id');
            if (!$pidS) {
                $pidS = [-1];
            }
            $filter['pid'] = $pidS;
        }
        if ($api) {
            $filter['api'] = $api;
        }
        [$menus, $count] = UserServices::getInstance()->getUserMenu(Yii::$app->user->getId(), false, $filter, $offset,
            $limit);
        return $this->returnOk(['menus' => $menus, 'total' => $count]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws InvalidConfigException
     */
    public function actionCaptcha(Request $request): Response
    {
        $config = [
            'class'     => CaptchaAction::class,
            'width'     => 221,
            'height'    => 45,
            'padding'   => 4,
            'minLength' => 4,
            'maxLength' => 4,
            'offset'    => 30,
        ];
        $c      = Yii::createObject($config, ['__captcha', $this]);
        $c->getVerifyCode(true);
        $bin = $c->run();
        return $this->returnOk(['captcha' => 'data:image/png;base64,' . base64_encode($bin)]);
    }

    /**
     * 获取用户列表
     *
     * @param Request $request
     *
     * @return Response
     */
    public function actionList(Request $request): Response
    {

        $page     = $request->get('page', 1);
        $limit    = $request->get('limit', 20);
        $username = $request->get('username', '');
        $phone    = $request->get('phone', '');
        $email    = $request->get('email', '');
        $filter   = [];
        if ($username) {
            $filter['username'] = $username;
        }
        if ($phone) {
            $filter['phone'] = $phone;
        }
        if ($email) {
            $filter['email'] = $email;
        }
        $offset = ($page - 1) * $limit;
        [$count, $data] = UserServices::getInstance()->getList($filter, $offset, $limit);
        return $this->returnOk(['total' => intval($count), 'data' => $data]);

    }

    /**
     * 创建用户
     *
     * @param Request $request
     *
     * @return Response
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function actionAdd(Request $request): Response
    {
        $data = $request->getBodyParams();
        $ret  = UserServices::getInstance()->add($data);
        if ($ret) {
            return $this->returnOk();
        }
        return $this->returnErr();
    }


    /**
     * 用户分配角色
     *
     * @param Request $request
     *
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionView(Request $request): Response
    {
        $id        = $request->get('id', 0);
        $model     = $this->findModel($id);
        $items     = $model->getItems();
        $available = $items['available'] ?? [];
        $assigned  = $items['assigned'] ?? [];
        // 用户只能绑定角色
        $available = array_filter($available, function ($k) {
            return $k === 'role';
        });
        return $this->returnOk(['available' => array_keys($available), 'assigned' => array_keys($assigned)]);
    }

    /**
     * 分配角色
     *
     * @param Request $request
     *
     * @return Response
     * @throws InvalidConfigException
     */
    public function actionAssign(Request $request): Response
    {
        $data    = $request->getBodyParams();
        $id      = $data['id'] ?? 0;
        $items   = $data['items'];
        $model   = new Assignment($id);
        $success = $model->assign($items);;
        $success = 1;

        UserServices::getInstance()->isRole(true, $id);
        if ($success) {
            return $this->returnOk();
        }
        return $this->returnErr();
    }

    /**
     * 移除路由或者角色
     *
     * @param Request $request
     *
     * @return Response
     * @throws InvalidConfigException
     */
    public function actionRemove(Request $request): Response
    {
        dd(123);
        $data    = $request->getBodyParams();
        $id      = $data['id'] ?? 0;
        $items   = $data['items'];
        $model   = new Assignment($id);
        $success = $model->revoke($items);
        UserServices::getInstance()->isRole(true, $id);
        if ($success) {
            return $this->returnOk();
        }
        return $this->returnErr('移除失败');
    }


    /**
     * @param $id
     *
     * @return Assignment
     * @throws NotFoundHttpException
     */
    public function findModel($id): Assignment
    {
        /** @var User $class */
        $class = Yii::$app->user->identityClass;
        if (($user = $class::findIdentity($id)) !== null) {
            return new Assignment($id, $user);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}
