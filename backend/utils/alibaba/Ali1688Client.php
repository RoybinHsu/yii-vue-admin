<?php

namespace app\utils\alibaba;

use app\utils\general\Client;

class Ali1688Client extends Client
{
    /**
     * 域名
     *
     * @var string
     */
    public string $host = 'https://gw.open.1688.com';

    /**
     * 端口
     *
     * @var string
     */
    public string $port = '';

}
