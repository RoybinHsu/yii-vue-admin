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
                $response->data = [
                    'code' => $response->data['code'] ?? self::ERR_CODE,
                    'msg'  => $response->data['message'] ?? '请求失败',
                    'data' => [],
                ];
            }
            $response->statusCode = 200;
        }
    }

}
