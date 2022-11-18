<?php


namespace app\utils\general;


interface ClientInterface
{

    /**
     * 发送一个api请求
     *
     * @param RequestInterface $request
     * @return mixed
     */
    public function send(RequestInterface $request): ?array;

}
