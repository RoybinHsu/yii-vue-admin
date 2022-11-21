<?php

namespace app\utils\alibaba;

use GuzzleHttp\RequestOptions;
use yii\helpers\ArrayHelper;

class AccessTokenRequest extends BaseRequest
{
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
     * @inheritDoc
     */
    public function getParams(): array
    {
        return [
            RequestOptions::QUERY =>
                [
                    'client_id'          => $this->app->client_id,
                    'client_secret'      => $this->app->app_secret,
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
        return '/http/1/system.oauth2/getToken/' . $this->app->client_id;
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): string
    {
        return 'GET';
    }
}
