<?php

namespace app\controllers\auth;

use app\controllers\base\AuthController;
use mdm\admin\components\Helper;
use mdm\admin\models\AuthItem;
use mdm\admin\models\searchs\AuthItem as AuthItemSearch;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\rbac\Item;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\Response;
use Yii;


/**
 * @property $type
 *
 */
class ItemController extends AuthController
{
    /**
     * 权限页面
     *
     * @param Request $request
     *
     * @return Response
     */
    public function actionIndex(Request $request): Response
    {
        $searchModel  = new AuthItemSearch(['type' => $this->type]);
        $dataProvider = $searchModel->search($request->get());
        return $this->returnOk(array_values($dataProvider->allModels));
    }


    /**
     * 创建全新啊
     *
     * @param Request $request
     *
     * @return Response
     */
    public function actionCreate(Request $request): Response
    {
        $model       = new AuthItem(null);
        $model->type = $this->type;
        if ($model->load($request->post(), '') && $model->save()) {
            return $this->returnOk();
        } else {
            return $this->returnErr('保存失败: ' . (array_values($model->errors)[0][0] ?? ''));
        }
    }


    /**
     * 删除权限组
     *
     * @param Request $request
     *
     * @return Response
     * @throws NotFoundHttpException|ForbiddenHttpException
     * @throws Exception
     */
    public function actionDelete(Request $request): Response
    {
        $arr = [
            '超级管理员', 'guest'
        ];
        $id    = $request->get('name', 0);
        if (in_array($id, $arr)) {
            throw new Exception($id . '不允许被删除');
        }
        $model = $this->findModel($id);
        $ret   = Yii::$app->authManager->remove($model->item);
        Helper::invalidate();
        if ($ret) {
            return $this->returnOk();
        }
        return $this->returnErr('删除失败');
    }

    /**
     * 分配权限或者角色
     *
     * @param Request $request
     *
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionView(Request $request): Response
    {
        $id        = $request->get('id', '');
        $model     = $this->findModel($id);
        $items     = $model->getItems();
        $available = $items['available'] ?? [];
        $assigned  = $items['assigned'] ?? [];
        $available = array_filter($available, function ($k) {
            if ($this->type === Item::TYPE_ROLE) {
                return $k !== 'route';
            } else {

            }
            //return $this->type === Item::TYPE_ROLE ? $k === 'permission' : $k === 'route';
            return true;
        });
        asort($available);
        return $this->returnOk(['available' => array_keys($available), 'assigned' => array_keys($assigned)]);
    }

    /**
     * 分配权限或者角色
     *
     * @param Request $request
     *
     * @return Response
     * @throws InvalidConfigException|NotFoundHttpException
     */
    public function actionAssign(Request $request): Response
    {
        $data    = $request->getBodyParams();
        $id      = $data['id'] ?? 0;
        $items   = $data['items'];
        $model   = $this->findModel($id);
        $success = $model->addChildren($items);
        if ($success) {
            return $this->returnOk();
        }
        return $this->returnErr('分配失败');
    }

    /**
     * 移除路由或者角色
     *
     * @param Request $request
     *
     * @return Response
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    public function actionRemove(Request $request): Response
    {
        $data    = $request->getBodyParams();
        $id      = $data['id'] ?? 0;
        $items   = $data['items'];
        $model   = $this->findModel($id);
        $success = $model->removeChildren($items);
        if ($success) {
            return $this->returnOk();
        }
        return $this->returnErr('移除失败');
    }

    /**
     * @return int
     */
    public function getType(): int
    {
    }

    /**
     * Finds the AuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param mixed $id
     *
     * @return AuthItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): AuthItem
    {
        $auth = Yii::$app->authManager;
        $item = $this->type === Item::TYPE_ROLE ? $auth->getRole($id) : $auth->getPermission($id);
        if ($item) {
            return new AuthItem($item);
        } else {
            throw new NotFoundHttpException('页面不存在');
        }
    }

}
