<?php

namespace app\utils\log;

use Throwable;
use Yii;
use yii\helpers\VarDumper;
use yii\log\FileTarget;
use yii\log\Logger;


/**
 * 自定义日志文件类
 *
 * @author xushunbin
 * @time 2022年11月16日11:28:05
 */
class AppLogTarget extends FileTarget
{

    /**
     * 显示微秒时间戳
     *
     * @var bool
     */
    public bool $microtime = true;

    /**
     * 文件权限
     *
     * @var int
     */
    public int $fileMode = 0777;

    /**
     * 使用rename操作日志文件切换
     *
     * @var bool
     */
    public bool $rotateByCopy = false;

    /**
     * 格式化日志格式
     *
     * @param array $message
     *
     * @return string
     */
    public function formatMessage($message): string
    {
        [$text, $level, $category, $timestamp] = $message;
        $level = Logger::getLevelName($level);
        if (!is_string($text)) {
            if ($text instanceof Throwable) {
                $text = (string)$text;
            } else {
                $text = VarDumper::export($text);
            }
        }
        $text = preg_replace('/\s+/', ' ', $text);
        $text = stripcslashes($text);
        if (mb_strlen($text) > 1024) {
            $text = mb_substr($text, 0, 1024) . '...';
        }
        $basePathArray = explode(DIRECTORY_SEPARATOR, Yii::$app->basePath);
        $end           = array_pop($basePathArray);
        $extra         = [
            'file' => 'UNKNOWN',
            'line' => 0,
        ];
        if (isset($message[4]) && (is_array($message[4]) || is_object($message[4]))) {
            foreach ($message[4] as $trace) {
                $extra = [
                    'file' => $end . str_replace(Yii::$app->basePath, '', $trace['file']),
                    'line' => $trace['line'] ?? 0,
                ];
            }
        }
        return sprintf("%s %s %s:%s %s %s", $this->getTime($timestamp), strtoupper($level), basename($extra['file']), $extra['line'], TRACE_ID, $text);
    }

    /**
     * @param float $timestamp
     *
     * @return string
     */
    public function getTime($timestamp): string
    {
        $parts = explode('.', sprintf('%F', $timestamp));

        return date('H:i:s', $parts[0]) . ($this->microtime ? ('.' . $parts[1]) : '');
    }
}
