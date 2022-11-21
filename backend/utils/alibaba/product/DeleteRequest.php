<?php

namespace app\utils\alibaba\product;

use app\utils\alibaba\BaseRequest;
use GuzzleHttp\RequestOptions;
use yii\base\InvalidArgumentException;

class DeleteRequest extends BaseRequest
{
    /**
     * 接口参数产品id
     *
     * @var int
     */
    public int $product_id = 0;

    /**
     * 接口名称
     *
     * @var string
     */
    public string $api = 'com.alibaba.product:alibaba.product.delete-1';

    /**
     * 构造函数
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        if (empty($this->product_id)) {
            throw new InvalidArgumentException('参数[product_id]错误');
        }
    }

    /**
     * @return array[]
     */
    public function getParams(): array
    {
        return [
            RequestOptions::FORM_PARAMS => [
                '_aop_timestamp' => $this->timestamp,
                'access_token'   => $this->app->getAccessToken(),
                'productID'      => $this->product_id,
            ],
        ];
    }

    /**
     *
     * @return string
     */
    public function getMethod(): string
    {
        return 'POST';
    }

}
