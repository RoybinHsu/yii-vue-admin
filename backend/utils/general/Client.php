<?php


namespace app\utils\general;


use app\utils\base\Base;
use GuzzleHttp\Client as GzClient;
use GuzzleHttp\Exception\GuzzleException;
use Yii;
use yii\base\InvalidArgumentException;
use yii\helpers\ArrayHelper;

/**
 * http 接口请求基类
 *
 * Class Client
 * @package app\common\http
 * @property $host
 * @property $port
 */
class Client extends Base implements ClientInterface
{
    /**
     * 请求接口的host
     *
     * @var string
     */
    public string $host = '';

    /**
     * 接口请求的端口号
     *
     * @var string
     */
    public string $port = '';


    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    /**
     * 基类中的发送qpi请求
     *
     * @param RequestInterface $request
     *
     * @return null|array
     */
    public function send(RequestInterface $request): ?array
    {
        $header = $request->getHeaders();
        $body   = $request->getParams();
        $method = $request->getMethod();
        $url    = rtrim($this->host, '/');
        if ($this->port) {
            $url .= ':' . $this->port;
        }
        $options = ArrayHelper::merge($header, $body);
        try {
            $client   = new GzClient(['base_uri' => $url]);
            $res      = $client->request($method, $request->getUri(), $options);
            $response = $res->getBody()->getContents();
            return json_decode($response, true);
        } catch (GuzzleException | InvalidArgumentException $e) {
            Yii::error('发送外部请求出错 url:' . $url . $request->getUri() . ' error:' . $e->getMessage() . ' file:' . $e->getFile() . ' line:' . $e->getLine());
            return [];
        }
    }
}
