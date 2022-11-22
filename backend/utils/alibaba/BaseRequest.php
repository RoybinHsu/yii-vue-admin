<?php

namespace app\utils\alibaba;

use app\utils\general\PlatformAppInterface;
use app\utils\general\RequestInterface;
use GuzzleHttp\RequestOptions;
use yii\base\BaseObject;
use yii\base\Exception;
use yii\base\InvalidArgumentException;
use yii\helpers\ArrayHelper;

class BaseRequest extends BaseObject implements RequestInterface
{

    /**
     * @var mixed|Cuckoo
     */
    public $app = null;


    /**
     * 签名
     *
     * @var string
     */
    public string $sign = '';

    /**
     * 接口请求时间戳
     *
     * @var int
     */
    public int $timestamp = 0;

    /**
     * 要请求的接口名称
     *
     * @var string
     */
    public string $api = '';


    /**
     * 构造函数
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        if (empty($this->app)) {
            $this->app = new Cuckoo();
        }
        if (empty($this->api)) {
            throw new InvalidArgumentException('请设置调用api');
        }
        if (empty($this->timestamp)) {
            $this->timestamp = time();
        }
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): array
    {
        $sign = $this->app->sign($this->getParams(), ltrim($this->formatUri(), '/'));
        return [
            RequestOptions::QUERY => [
                '_aop_signature' => $sign,
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function getParams(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getUri(): string
    {
        return $this->formatUri();
    }

    /**
     * @return string
     */
    private function formatUri(): string
    {
        $arr     = explode(':', $this->api);
        $space   = $arr[0];
        $arr     = explode('-', $arr[1]);
        $version = $arr[1];
        $name    = $arr[0];
        return '/param2/' . $version . '/' . $space . '/' . $name . '/' . $this->app->client_id;
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): string
    {
        return 'GET';
    }

}
