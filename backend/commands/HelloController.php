<?php
/**
 * @link      http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license   http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\base\Menu;
use app\service\user\UserServices;
use app\utils\alibaba\AccessTokenRequest;
use app\utils\alibaba\Ali1688Client;
use app\utils\alibaba\Cuckoo;
use app\utils\alibaba\product\DeleteRequest;
use app\utils\base\Base;
use app\utils\pdd\PddClient;
use app\utils\pdd\product\AddRequest;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since  2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     *
     * @param string $message the message to be echoed.
     *
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world')
    {

        $request = new AddRequest(['buy_limit' => 20, 'bad_fruit_claim' => 30]);

        $data = PddClient::getInstance()->send($request);
        dd($data);

        //$arr = [
        //    'client_id'    => 1,
        //    'data_type'    => 'XML',
        //    'type'         => 'pdd.order.number.list.get',
        //    'timestamp'    => '1480411125',
        //    'order_status' => '1',
        //    'page'         => '1',
        //    'page_size'    => '10',
        //    'access_token' => 'asd78172s8ds9a921j9qqwda12312w1w21211',
        //
        //];
        //\app\utils\pdd\Cuckoo::getInstance()->sign($arr);
        //
        //$request = new DeleteRequest(['product_id' => 1]);
        //$res = Ali1688Client::getInstance()->send($request);
        //dd($res);


    }

}
