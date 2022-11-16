<?php

namespace app\controllers\base;


use Yii;
use yii\rest\Controller;
use yii\web\Response;
use app\utils\jwt\Response as JR;

/**
 * Class Controller
 * @package backend\controllers\base
 * @property $uid
 * @property $role
 * @property $token
 */
class BaseController extends Controller
{

    /**
     * @param array $data
     * @param string $msg
     * @param int $code
     *
     * @return Response
     */
    public function returnOk(
        array $data = [],
        string $msg = '请求成功',
        int $code = JR::SUC_CODE
    ): Response {
        return $this->asJson([
            'code' => $code,
            'msg'  => $msg,
            'data' => $data,
        ]);
    }

    /**
     * @param string $msg
     * @param array $data
     * @param int $code
     *
     * @return Response
     */
    public function returnErr(
        string $msg = '请求失败',
        array $data = [],
        int $code = JR::ERR_CODE
    ): Response {
        return $this->asJson([
            'code' => $code,
            'msg'  => $msg,
            'data' => $data,
        ]);
    }

    /**
     * 页面响应一个Image图片
     *
     * @param string $content 读取的文件资源字符串
     * @param bool $download
     * @param null $filename
     *
     * @return void
     */
    public function returnImage(string $content, bool $download = false, $filename = null)
    {
        $response = Yii::$app->response;
        //获取文件扩展名
        $ext = substr($filename, stripos($filename, '.') + 1, strlen($filename));
        $response->headers->set('Content-Type', 'image/' . $ext . ';charset=utf-8;');
        $response->headers->set('Cache-Control', 'max-age=86400');
        if ($filename === null) {
            $filename = uniqid() . '.' . $ext;
        }
        if ($download) {
            // 下载
            $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename . '"');
            $response->headers->set('Connection', 'close');
        } else {
            $response->headers->set('Content-Disposition', 'filename="' . $filename . '"');
        }
        $response->format  = Response::FORMAT_RAW;
        $response->content = $content;
        return $response->send();
    }

}
