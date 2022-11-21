<?php

namespace app\utils\pdd;

use app\utils\general\Client;

class PddClient extends Client
{

    /**
     * @var string
     */
    public string $host = 'https://gw-api.pinduoduo.com/api/router';
    //public string $host = 'http://qa.jzcassociates.com:8085/api/router';

    /**
     * @var string
     */
    public string $port = '';
}
