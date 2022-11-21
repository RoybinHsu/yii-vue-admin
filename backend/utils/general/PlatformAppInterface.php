<?php

namespace app\utils\general;

interface PlatformAppInterface
{

    /**
     * 按要求格式化参数
     *
     * @param array $data
     *
     * @return mixed
     */
    public function formatData(array &$data);

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
     * @return string
     */
    public function authorizeUrl(): string;


    /**
     * 通过refresh_token 获取access_token
     *
     *
     * @return string
     */
    public function getAccessToken(): string;

    /**
     * @param string|null $refresh_token
     *
     * @return mixed
     */
    public function getToken(string $refresh_token = null);

    /**
     * @param array $data
     *
     * @return bool
     */
    public function updateToken(array $data): bool;
}
