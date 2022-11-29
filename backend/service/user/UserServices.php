<?php

namespace app\service\user;

use app\models\base\Menu;
use app\models\User;
use app\utils\base\Base;
use app\utils\exception\DataRepeatException;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\rbac\Permission;

class UserServices extends Base
{

    const USER_ROLE_CACHE = 'FLY:USER_ROLE_CACHE:%s';

    /**
     * 通过id获取用户信息
     *
     * @param $id
     *
     * @return array
     * @throws Exception
     */
    public function getLoginUserInfo($id): array
    {
        $model = User::findIdentity($id);
        if (empty($model)) {
            throw new Exception('用户不存在');
        }
        return [
            'id'          => $model->id,
            'username'    => $model->username,
            'phone'       => $model->phone,
            'email'       => $model->email,
            'status'      => $model->status,
            'avatar'      => 'a' . (($model->id % 8) + 1) . '.jpg',
            'menus'       => $this->getUserMenu($id),
            'permissions' => $this->getPermissions($id),
        ];

    }

    /**
     * 获取用户菜单
     *
     * @param null $id
     * @param bool $tree
     * @param array $filter
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function getUserMenu(
        $id = null,
        bool $tree = true,
        array $filter = [],
        int $offset = 0,
        int $limit = 20
    ): array {
        $model = Menu::find()->orderBy(['hidden' => SORT_ASC, 'order' => SORT_ASC]);
        if ($tree) {
            $menus = $model->where(['hidden' => Menu::SHOW])->asArray()->all();
            return $this->tree($menus, 0, $id);
        } else {
            foreach ($filter as $k => $v) {
                if (!$v) {
                    continue;
                }
                $model->andWhere([$k => $v]);
            }
            $count      = intval($model->count());
            $menus      = $model->offset($offset)->limit($limit)->asArray()->all();
            $menusIndex = ArrayHelper::index($menus, 'id');
            foreach ($menus as &$v) {
                $v['pid_title'] = '';
                if ($v['pid'] > 0) {
                    $v['pid_title'] = $menusIndex[$v['pid']]['title'] ?? '-';
                }
            }
            return [$menus, $count];
        }
    }

    /**
     * @throws Exception
     */
    public function checkRepeat($raw)
    {
        $key = 'FLY:USER:MENU1:' . shortMd5($raw);
        if (Yii::$app->redis->setnx($key, 1)) {
            Yii::$app->redis->expire($key, 60);
        } else {
            throw new Exception('请勿重复提交');
        }

    }

    /**
     * 删除全部的菜单重新同步
     *
     * @throws \yii\db\Exception
     */
    public function delMenus()
    {
        Menu::deleteAll();
        $sql = 'ALTER TABLE `' . Menu::tableName() . '` AUTO_INCREMENT=0';
        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * 保存菜单
     *
     * @param $menus
     * @param int $pid
     *
     * @throws Exception
     */
    public function saveMenus($menus, int $pid = 0)
    {
        foreach ($menus as $v) {
            $model           = new Menu();
            $model->pid      = $pid;
            $model->path     = $v['path'];
            $model->name     = $v['name'];
            $model->redirect = $v['redirect'] ?? '';
            $model->hidden   = intval($v['hidden']);
            $model->meta     = $v['meta'] ?? '';
            $model->path     = $v['path'];
            $model->title    = $v['meta']['title'] ?? '';
            $model->icon     = $v['meta']['icon'] ?? '';
            $model->api      = $v['api'] ?? '';
            if ($model->save() && !empty($v['children'])) {
                $this->saveMenus($v['children'], $model->id);
            } elseif (!empty($model->errors)) {
                $msg = '保存数据错误:' . Json::encode($model->errors);
                Yii::error($msg);
                throw new Exception('保存数据错误' . $msg);
            }
        }
    }


    /**
     * @param $data
     * @param int $pid
     * @param null $uid
     *
     * @return array
     */
    public function tree($data, int $pid = 0, $uid = null): array
    {
        if ($uid === null) {
            $uid = Yii::$app->user->getId();
        }
        $menus = [];
        foreach ($data as $v) {
            if (+$v['pid'] === $pid) {
                $child         = $this->tree($data, $v['id'], $uid);
                $c             = [];
                $c['path']     = $v['path'];
                $c['redirect'] = $v['redirect'];
                $c['hidden']   = boolval($v['hidden']);
                $c['name']     = $v['name'];
                $c['meta']     = json_decode($v['meta'], true);
                if ($child) {
                    $c['children'] = $child;
                    $menus[]       = $c;
                } else {
                    // 标识最底层菜单 检查api权限
                    if ($this->isRole()) {
                        $menus[] = $c;
                    }
                    if (Yii::$app->authManager->checkAccess(Yii::$app->user->getId(), $v['api'])) {
                        $menus[] = $c;
                    }
                }
            }
        }
        return $menus;
    }

    /**
     * @throws Exception
     */
    public function add($data): bool
    {
        $id = $data['id'] ?? 0;
        $id = intval($id);
        if ($id !== 0) {
            // 编辑
            $model = User::findOne(['id' => $id]);
        } else {
            // 创建
            $model = User::findUser($data['username'], $data['phone'], $data['email']);
            if ($model) {
                foreach (['username', 'phone', 'email'] as $r) {
                    if ($data[$r] === $model->$r) {
                        throw new Exception('' . $r . ': [' . $data[$r] . '] 已经被使用过了');
                    }
                }
            } else {
                $model = new User();
            }
        }
        $model->username = $data['username'];
        $model->phone    = $data['phone'];
        $model->status   = User::STATUS_ACTIVE;
        $model->email    = $data['email'];
        $model->generateAuthKey();
        $model->generatePasswordResetToken();
        $model->password = $data['password'];
        if ($model->save()) {
            return true;
        } else {
            Yii::error('保存用户数据错误:' . Json::encode($model->errors));
            throw new Exception('保存用户数据错误');
        }
    }

    /**
     * 获取用户列表
     *
     * @param array $filter
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function getList(array $filter = [], int $offset = 0, int $limit = 20): array
    {
        $model = User::find()->select(['id', 'username', 'phone', 'email', 'status', 'created_at', 'updated_at']);
        foreach ($filter as $k => $v) {
            if (!$v) {
                continue;
            }
            $model->andWhere([$k => $v]);
        }
        $count = $model->count();
        $data  = $model
            ->offset($offset)
            ->limit($limit)
            ->asArray()
            ->orderBy(['id' => SORT_DESC])
            ->all();
        foreach ($data as &$v) {
            $v['status_desc'] = User::getStatus($v['status']);
        }
        return [$count, $data];
    }

    /**
     * 判断用户是否是超级管理员
     *
     * @param bool $flush
     * @param null $uid
     * @param string $role
     *
     * @return bool
     */
    public function isRole(bool $flush = false, $uid = null, string $role = '超级管理员'): bool
    {
        if ($uid === null) {
            $uid = Yii::$app->user->getId();
        }
        $key = sprintf(self::USER_ROLE_CACHE, $uid);
        if (!$flush && Yii::$app->redis->sismember($key, $role)) {
            // 是该角色
            return true;
        } else {
            // 没有该角色
            // 查询数据库 再存缓存
            Yii::$app->authManager->cache->flush();
            $roles = Yii::$app->authManager->getRolesByUser($uid);
            $roles = array_keys($roles);
            Yii::$app->redis->del($key);
            Yii::$app->redis->sadd($key, ...$roles);
            Yii::$app->redis->expire($key, 86400);
            if (in_array($role, $roles)) {
                return true;
            }
        }
        return false;
    }

    /**
     * 获取用户的全部权限
     *
     * @param $id
     *
     * @return array|Permission[]
     */
    public function getPermissions($id): array
    {
        return array_keys(Yii::$app->authManager->getPermissionsByUser($id));
    }

}
