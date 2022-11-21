<?php

namespace app\utils\general;

interface PlatformAppInterface
{

    /**
     * 生成签名算法
     *
     *
     * @param array $data
     *
     * @return string
     */
    public function sign(array $data): string;

    /**
     * 生成获取code的url
     *
     * @param string $state
     *
     * @return string
     */
    public function authorizeUrl(string $state): string;


    /**
     * 通过refresh_token 获取access_token
     *
     *
     * @return string
     */
    public function getAccessToken(): string;

    /**
     * @param string|null $refresh_token
     * @param string|null $code
     *
     * @return mixed
     */
    public function getToken(string $code = null, string $refresh_token = null);

    /**
     * @param array $data
     *
     * @return bool
     */
    public function updateToken(array $data): bool;
}
