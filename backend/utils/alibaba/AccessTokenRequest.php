<?php

namespace app\utils\alibaba;

use app\utils\general\RequestInterface;
use GuzzleHttp\RequestOptions;
use yii\base\BaseObject;

class AccessTokenRequest extends BaseObject implements RequestInterface
{

    /**
     * app key
     *
     * @var string
     */
    public string $client_id;

    /**
     * @var string
     */
    public string $app_secret;

    /**
     * 为授权类型
     *
     * @var string
     */
    public string $grant_type = 'authorization_code';

    /**
     * 是否需要返回refresh_token
     *
     * @var bool
     */
    public bool $need_refresh_token = true;

    /**
     * 重定向地址
     *
     * @var string
     */
    public string $redirect_uri = '';

    /**
     * 授权码
     *
     * @var string
     */
    public string $code = '';

    /**
     *
     *
     * @var string
     */
    //public string $_aop_signature;


    /**
     * @inheritDoc
     */
    public function getHeaders(): array
    {
        return [
            RequestOptions::HEADERS => [],
        ];
    }

    /**
     * @inheritDoc
     */
    public function getParams(): array
    {
        return [
            RequestOptions::QUERY =>
                [
                    'client_id'          => $this->client_id,
                    'client_secret'      => $this->app_secret,
                    'grant_type'         => $this->grant_type,
                    'need_refresh_token' => $this->need_refresh_token,
                    'redirect_uri'       => $this->redirect_uri,
                    'code'               => $this->code,
                ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function getUri(): string
    {
        return '/openapi/http/1/system.oauth2/getToken/' . $this->client_id;
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): string
    {
        return 'GET';
    }
}
