<?php

namespace app\utils\pdd;

use app\utils\base\Base;
use app\utils\general\PlatformAppInterface;
use yii\base\InvalidArgumentException;

class BaseApp extends Base implements PlatformAppInterface
{
    /**
     * 应用的 App Key
     *
     * @see https://open.1688.com/doc/guide.htm
     * @var string
     */
    public string $client_id = '';

    /**
     * 应用秘钥 App Secret
     *
     * @see https://open.1688.com/doc/guide.htm
     * @var string
     */
    public string $app_secret = '';


    /**
     * 授权登录后重定向url
     *
     * @var string
     */
    public string $redirect_url = '';

    /**
     * 自定义参数
     *
     * @var string
     */
    public string $state = '';

    /**
     * 授权获取code
     *
     * @var string
     */
    public string $authorize_url = 'https://fuwu.pinduoduo.com/service-market/auth';


    /**
     * @inheritDoc
     */
    public function sign($data): string
    {
        ksort($data);
        $params =[$this->app_secret];
        foreach ($data as $k => $v) {
            $params[] = $k . $v;
        }
        $params[] = $this->app_secret;
        $str = join('', $params);
        return strtoupper(md5($str));
    }

    /**
     * @inheritDoc
     */
    public function authorizeUrl(): string
    {
        $query = [
            'client_id'     => $this->client_id,
            'response_type' => 'code',
            'redirect_uri'  => urlencode($this->redirect_url),
            'state'         => $this->state,
            'web'           => 'web',
        ];
        return $this->authorize_url . '?' . http_build_query($query, '', '&');
    }


    /**
     * 通过授权获取 access_token
     *
     * @param null $refresh_token
     *
     * @return string
     */
    public function getToken($refresh_token = null): string
    {
        if ($refresh_token === null) {
            // 通过授权码 code 获取 access_token
            return '1';
        } else {
            // 通过 refresh token 获取access_token
            return '2';
        }
    }

    /**
     * @inheritDoc
     */
    public function getAccessToken(string $refresh_token = null): string
    {
        // 可从缓存中获取access_token用于api的请求
        return '';
    }

    /**
     * @inheritDoc
     */
    public function updateToken(array $data): bool
    {
        // TODO: Implement updateToken() method.
    }

    /**
     * @param array $data
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function formatData(array &$data)
    {
        if (empty($data['type'])) {
            throw new InvalidArgumentException('参数错误');
        }
        $data['client_id']    = $this->client_id;
        $data['timestamp']    = time();
        $data['data_type']    = 'JSON';
        $data['access_token'] = $this->getAccessToken();
        $data['sign']         = $this->sign($data);
    }

}
