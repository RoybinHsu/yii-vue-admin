<?php

namespace app\utils\alibaba;

use app\utils\base\Base;
use app\utils\general\PlatformAppInterface;
use yii\base\Component;

/**
 * @property $client_id
 * @property $app_secret
 * @property $site
 * @property $authorize_url
 *
 */
abstract class BaseApp extends Component implements PlatformAppInterface
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
    public function sign(array $data, $url_info = ''): string
    {
        if ($data) {
            foreach ($data as &$param) {
                $param = is_string($param) ? $param : json_encode($param, JSON_UNESCAPED_UNICODE);
            }
        }
        foreach ($data as $key => $val) {
            $ali_params[] = $key . $val;
        }
        sort($ali_params);
        $sign_str = join('', $ali_params);
        $sign_str = $url_info . $sign_str;
        return strtoupper(bin2hex(hash_hmac("sha1", $sign_str, $this->app_secret, true)));
    }


    /**
     * 生成获取code的url
     *
     * @param string $state
     *
     * @return string
     */
    public function authorizeUrl(string $state = ''): string
    {
        $query = [
            'client_id'    => $this->client_id,
            'site'         => $this->site,
            'redirect_uri' => $this->redirect_url,
            'state'        => $state,
        ];
        return $this->authorize_url . '?' . http_build_query($query, '', '&');
    }

    /**
     * 通过refresh_token 获取access_token
     *
     * @param string|null $code
     * @param string|null $refresh_token
     *
     * @return string
     */
    public function getToken(string $code = null, string $refresh_token = null): string
    {
        if ($refresh_token === null) {
            // 获取token
            $data = [
                'app'          => new Cuckoo(),
                'redirect_uri' => $this->redirect_url,
                'code'         => $code,
                'grant_type'   => 'authorization_code',
            ];
        } else {
            // 通过refresh_token 获取token
            $data = [
                'app'          => new Cuckoo(),
                'redirect_uri' => $this->redirect_url,
                'grant_type'   => 'refresh_token',
            ];
        }
        $request = new AccessTokenRequest($data);
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
        return '';
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
