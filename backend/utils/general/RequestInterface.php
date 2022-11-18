<?php


namespace app\utils\general;

/**
 * Interface RequestInterface
 * @package common\utils\lib\http
 * @property $end_log
 */
interface RequestInterface
{
    /**
     * 获取请求头
     *
     * @return array
     */
    public function getHeaders(): array;

    /**
     * 获取请求消息体
     *
     * @return array
     *
     * @extends
     * ```
     *  提交json数据
     *  [
     *       'body' => 'json raw data'
     *   ])
     *  或者 提交form表单
     *  'form_params' => [
     *       'field_name' => 'abc',
     *       'other_field' => '123',
     *       'nested_field' => [
     *           'nested' => 'hello'
     *       ]
     *   ]
     *  上传图片或者文件
     * 'multipart' => [
     *       [
     *           'name'     => 'field_name',
     *           'contents' => 'abc'
     *       ],
     *       [
     *           'name'     => 'file_name',
     *           'contents' => fopen('/path/to/file', 'r')
     *       ],
     *       [
     *           'name'     => 'other_file',
     *           'contents' => 'hello',
     *           'filename' => 'filename.txt',
     *           'headers'  => [
     *               'X-Foo' => 'this is an extra header to include'
     *           ]
     *       ]
     *   ]
     *  GET请求参数
     *  'query' => ['foo' => 'bar']
     *
     * ```
     */
    public function getParams(): array;

    /**
     * 获取请求定地址
     *
     * @return string
     */
    public function getUri(): string;

    /**
     * 获取接口请求的方式 post 或者 get
     *
     * @return string
     */
    public function getMethod(): string;



}
