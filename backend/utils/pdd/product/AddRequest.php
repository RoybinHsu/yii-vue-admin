<?php

namespace app\utils\pdd\product;

use app\utils\pdd\BaseRequest;
use GuzzleHttp\RequestOptions;


class AddRequest extends BaseRequest
{

    public string $api = 'pdd.goods.add';

    /**
     * @var int
     */
    public int $buy_limit = 10;

    /**
     * @var int
     */
    public int $bad_fruit_claim = 10;

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return 'POST';
    }

    /**
     * 获取参数
     *
     * @return array[]
     */
    public function getParams(): array
    {
        return [
            RequestOptions::JSON => [
                'buy_limit'       => $this->buy_limit,
                'bad_fruit_claim' => $this->bad_fruit_claim,
            ],
        ];
    }


}
