<?php

namespace app\utils\pdd;

use app\utils\general\RequestInterface;
use Exception;
use GuzzleHttp\RequestOptions;
use yii\base\BaseObject;
use yii\base\InvalidArgumentException;
use yii\helpers\ArrayHelper;

class BaseRequest extends BaseObject implements RequestInterface
{
    /**
     * @var mixed | Cuckoo
     */
    public $app = null;

    /**
     * 接口名称
     *
     * @var string
     */
    public string $api = '';

    /**
     * @var int
     */
    public int $timestamp = 0;


    /**
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        if (empty($this->api)) {
            throw new InvalidArgumentException('接口参数错误');
        }
        if (empty($this->timestamp)) {
            $this->timestamp = time();
        }
        if (empty($this->app)) {
            $this->app = Cuckoo::getInstance();
        }
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function getHeaders(): array
    {
        $headers = [
            RequestOptions::HEADERS => [
                'Content-Type' => 'application/json;',
            ],
        ];
        $params  = $this->getParams();
        $key     = array_keys($params)[0];
        $params  = ArrayHelper::getValue($params, $key);
        $params  = ArrayHelper::merge($params, $this->_commonParams());
        $sign    = $this->app->sign($params);
        $params  = ArrayHelper::merge(['sign' => $sign], $params);
        return ArrayHelper::merge($headers, [$key => $params]);
    }

    /**
     * 通用参数
     *
     * @return array
     */
    protected function _commonParams(): array
    {
        return [
            'type'         => $this->api,
            'client_id'    => $this->app->client_id,
            'timestamp'    => $this->timestamp,
            'access_token' => $this->app->getAccessToken(),
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
     * 通过api 辨别接口
     *
     * @inheritDoc
     */
    public function getUri(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): string
    {
        return 'POST';
    }
}
