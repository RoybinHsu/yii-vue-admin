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

class UserServices extends Base
{

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
            'id'       => $model->id,
            'username' => $model->username,
            'phone'    => $model->phone,
            'email'    => $model->email,
            'status'   => $model->status,
            'avatar'   => 'a' . (($model->id % 8) + 1) . '.jpg',
            'menus'    => $this->getUserMenu($id),
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
            //$json = '[{"path":"/login","redirect":"","hidden":true,"name":"Login","meta":{"title":"登录"}},{"path":"/404","redirect":"","hidden":true,"name":"","meta":{"title":"未找到页面"}},{"path":"/","redirect":"/home","hidden":false,"name":"Home","meta":"","children":[{"path":"home","redirect":"","hidden":false,"name":"HomeIndex","meta":{"title":"首页","icon":"dashboard","noCache":false,"affix":true}}]},{"path":"/site","redirect":"/site/menu","hidden":false,"name":"Site","meta":{"title":"网站管理","icon":"el-icon-s-management"},"children":[{"path":"menu","redirect":"","hidden":false,"name":"SiteMenu","meta":{"title":"菜单管理","icon":"el-icon-s-operation"}}]},{"path":"/example","redirect":"/site/menu","hidden":false,"name":"Example","meta":{"title":"Example","icon":"el-icon-s-help"},"children":[{"path":"table","redirect":"","hidden":false,"name":"ExampleTable","meta":{"title":"表格","icon":"table","noCache":false}},{"path":"tree","redirect":"","hidden":false,"name":"ExampleTree","meta":{"title":"树形","icon":"tree","noCache":false}}]},{"path":"/form","redirect":"/form/index","hidden":false,"name":"Form","meta":"","children":[{"path":"index","redirect":"","hidden":false,"name":"FormForm","meta":{"title":"Form","icon":"form"}}]},{"path":"*","redirect":"/404","hidden":true,"name":"Any","meta":""},{"path":"/:catchAll(.*)","redirect":"/404","hidden":true,"name":"CatchAll","meta":""}]';
            //return json_decode($json, true);
            $menus = $model->where(['hidden' => Menu::SHOW])->asArray()->all();
            return $this->tree($menus);
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
        $json = '[{"path":"/login","redirect":"","hidden":true,"name":"Login","meta":{"title":"登录"}},{"path":"/404","redirect":"","hidden":true,"name":"","meta":{"title":"未找到页面"}},{"path":"/","redirect":"/home","hidden":false,"name":"Home","meta":"","children":[{"path":"home","redirect":"","hidden":false,"name":"HomeIndex","meta":{"title":"首页","icon":"dashboard","noCache":false,"affix":true}}]},{"path":"/site","redirect":"/site/menu","hidden":false,"name":"Site","meta":{"title":"网站管理","icon":"el-icon-s-management"},"children":[{"path":"menu","redirect":"","hidden":false,"name":"SiteMenu","meta":{"title":"菜单管理","icon":"el-icon-s-operation"}}]},{"path":"/example","redirect":"/site/menu","hidden":false,"name":"Example","meta":{"title":"Example","icon":"el-icon-s-help"},"children":[{"path":"table","redirect":"","hidden":false,"name":"ExampleTable","meta":{"title":"表格","icon":"table","noCache":false}},{"path":"tree","redirect":"","hidden":false,"name":"ExampleTree","meta":{"title":"树形","icon":"tree","noCache":false}}]},{"path":"/form","redirect":"/form/index","hidden":false,"name":"Form","meta":"","children":[{"path":"index","redirect":"","hidden":false,"name":"FormForm","meta":{"title":"Form","icon":"form"}}]},{"path":"*","redirect":"/404","hidden":true,"name":"Any","meta":""},{"path":"/:catchAll(.*)","redirect":"/404","hidden":true,"name":"CatchAll","meta":""}]';
        return json_decode($json, true);
    }

    /**
     * @throws Exception
     */
    public function checkRepeat($raw)
    {
        $key = 'FLY:USER:MENU:' . shortMd5($raw);
        if (Yii::$app->redis->setnx($key, 1)) {
            Yii::$app->redis->expire($key, 86400);
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
                Yii::error('保存数据错误:' . Json::encode($model->errors));
                throw new Exception('保存数据错误');
            }
        }
    }


    /**
     * @param $data
     * @param int $pid
     *
     * @return array
     */
    public function tree($data, int $pid = 0): array
    {
        $menus = [];
        foreach ($data as $v) {
            if (+$v['pid'] === $pid) {
                $child         = $this->tree($data, $v['id']);
                $c             = [];
                $c['path']     = $v['path'];
                $c['redirect'] = $v['redirect'];
                $c['hidden']   = boolval($v['hidden']);
                $c['name']     = $v['name'];
                $c['meta']     = json_decode($v['meta'], true);
                if ($child) {
                    $c['children'] = $child;
                }
                $menus[] = $c;
            }
        }
        return $menus;
    }

}
