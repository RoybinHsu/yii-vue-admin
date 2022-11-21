<?php

namespace app\utils\pdd;

class Cuckoo extends BaseApp
{
    /**
     * 应用详情中查看应用详情中client_id字段值得到
     *
     * @see https://open.pinduoduo.com/application/document/browse?idStr=BD3A776A4D41D5F5
     * @var string
     */
    public string $client_id = '1111';

    /**
     * 应用秘钥 App Secret
     *
     * @see https://open.1688.com/doc/guide.htm
     * @var string
     */
    public string $app_secret = 'Abshfsdhjf123123';

    /**
     * @var string 参数标识当前授权的站点，直接填写1688
     */
    public string $site = '1688';

    /**
     * 授权登录后重定向url
     *
     * @var string
     */
    public string $redirect_url = 'http://fly.com/auth';

    /**
     * 自定义参数
     *
     * @var string
     */
    public string $state = 'code123';

}
