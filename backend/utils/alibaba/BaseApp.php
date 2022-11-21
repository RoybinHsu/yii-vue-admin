<?php

namespace app\utils\alibaba;

use app\utils\base\Base;
use app\utils\general\PlatformAppInterface;
use app\utils\general\RequestInterface;

/**
 * @property $client_id
 * @property $app_secret
 * @property $site
 * @property $redirect_url
 * @property $authorize_url
 * @property $get_token_url
 * @property $state
 *
 */
abstract class BaseApp extends Base implements PlatformAppInterface
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
     * @var string 参数标识当前授权的站点，直接填写1688
     */
    public string $site = '1688';

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
     * 授权码
     *
     * @var string
     */
    public string $code = '';

    /**
     * 授权获取code
     *
     * @var string
     */
    public string $authorize_url = 'https://auth.1688.com/oauth/authorize';


    /**
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    /**
     * @inheritDoc
     */
    public function sign(array $data): string
    {
        $code_arr   = [
            'client_id'    => $this->client_id,
            'redirect_uri' => $this->redirect_url,
            'site'         => $this->site,
            'state'        => $this->state,
        ];
        $ali_params = [];
        foreach ($code_arr as $key => $val) {
            $ali_params[] = $key . $val;
        }
        sort($ali_params);
        $sign_str = join('', $ali_params);
        return strtoupper(bin2hex(hash_hmac("sha1", $sign_str, $this->app_secret, true)));
    }

    /**
     * @param $data
     *
     * @return void
     */
    public function formatData(&$data)
    {
        $data['_aop_timestamp'] = time();
        $data['access_token']   = $this->getAccessToken();
        $data['_aop_signature'] = $this->sign($data);
    }

    /**
     * 生成获取code的url
     *
     * @return string
     */
    public function authorizeUrl(): string
    {
        $query = [
            'client_id'    => $this->client_id,
            'site'         => $this->site,
            'redirect_uri' => $this->redirect_url,
            'state'        => $this->state,
        ];
        return $this->authorize_url . '?' . http_build_query($query, '', '&');
    }

    /**
     * 通过refresh_token 获取access_token
     *
     * @param string|null $refresh_token
     *
     * @return string
     */
    public function getToken(string $refresh_token = null): string
    {
        if ($refresh_token === null) {
            // 获取token
            $data = [
                'redirect_uri' => $this->redirect_url,
                'code'         => $this->code,
                'grant_type'   => 'authorization_code',
            ];

        } else {
            // 通过refresh_token 获取token
            $data = [
                'redirect_uri' => $this->redirect_url,
                'code'         => $this->code,
                'grant_type'   => 'refresh_token',
            ];
        }
        $request = Cuckoo::getInstance()->createHttpRequest(AccessTokenRequest::class, $data);
        $client  = Ali1688Client::getInstance();
        $res     = $client->send($request);
        if ($res) {
            // 获取成功
            $this->updateToken($res);
            $access_token = $res['access_token'];
        } else {
            $access_token = '';
        }
        return $access_token;
    }

    /**
     * 获取请求api的access_token
     *
     * @return string
     */
    public function getAccessToken(): string
    {

    }

    /**
     * 更新access_token 和 refreshToken 并保存到缓存
     *
     * @inheritDoc
     */
    public function updateToken(array $data): bool
    {
        // TODO: Implement updateToken() method.
    }
}
