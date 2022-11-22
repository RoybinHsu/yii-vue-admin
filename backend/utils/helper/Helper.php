<?php

namespace app\utils\helper;

use app\utils\general\PlatformAppInterface;
use yii\base\Component;

class Helper extends Component
{
    /**
     * @param $code
     * @param $msg
     * @param $data
     *
     * @return array
     */
    public function responseArray($code, $msg, $data): array
    {
        return [
            'code'    => $code,
            'message' => $msg,
            'data'    => $data,
        ];
    }

    /**
     * 获取格式化的组建名称
     *
     * @param $app_name
     * @param $platform
     *
     * @return string
     */
    public function formatAppComponent($app_name, $platform): string
    {
        return sprintf('%s_%s', strtolower($app_name), strtolower($platform));
    }

}
