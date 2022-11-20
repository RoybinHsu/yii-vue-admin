<?php

namespace app\utils\jwt;

use yii\base\BaseObject;
use yii\base\Event;
use Yii;

class Response extends BaseObject
{
    /**
     * 接口请求成功返回code
     *
     */
    const SUC_CODE = 200;

    /**
     * 接口请求失败返回
     *
     */
    const ERR_CODE = 0;

    const IGNORE_ERR = [
        'function',
        'class',
        'app',
        'db',
        'redis',
        'controllers',
        'jobs',
        'models',
        'requests',
        'services',
        'mysql',
        'connection',
        'public',
        'index',
    ];

    /**
     * 响应数据之前
     *
     * @param $event
     */
    public function beforeSend($event)
    {
        /** @var Event $event */
        /** @var \yii\web\Response $response */
        $response = $event->sender;
        $response->headers->set('Trace-Id', TRACE_ID);
        if (is_array($response->data)) {
            if (!$response->isSuccessful) {
                $trace = implode(" ", ($response->data['stack-trace'] ?? []));
                $msg   = 'name:' . ($response->data['name'] ?? '');
                if (!empty($response->data['message'])) {
                    $msg .= ' message:' . ($response->data['message'] ?? '');
                }
                if (!empty($response->data['file'])) {
                    $msg .= ' file:' . ($response->data['file'] ?? '');
                }
                if (!empty($response->data['line'])) {
                    $msg .= ' line:' . ($response->data['line'] ?? '');
                }
                if (!empty($trace)) {
                    $msg .= ' trace:' . $trace;
                }
                Yii::error($msg);
                $msg        = strtolower($msg);
                $return_msg = $response->data['message'] ?? '请求失败';
                foreach (self::IGNORE_ERR as $s) {
                    if (strpos($msg, $s) !== false) {
                        $return_msg = '系统内部错误';
                        break;
                    }
                }
                $code = self::ERR_CODE;
                if (!empty($response->data['status'])) {
                    $code = $response->data['status'];
                }
                $response->data = Yii::$app->helper->responseArray(
                    $code,
                    $return_msg,
                    []
                );
            }
            $response->statusCode = 200;
        }
    }

}
