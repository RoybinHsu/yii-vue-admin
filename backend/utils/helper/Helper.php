<?php

namespace app\utils\helper;

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

}
