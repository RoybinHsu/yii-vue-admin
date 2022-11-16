<?php
// 断点打印方法
use yii\db\ActiveQuery;
use yii\helpers\VarDumper;
use Yii;

if (!function_exists('dd')) {
    function dd(...$args)
    {
        if (php_sapi_name() != 'cli') {
            http_response_code(500);
        }
        foreach ($args as $x) {
            if (php_sapi_name() == 'cli') {
                VarDumper::dump($x);
                echo "\n";
            } else {
                VarDumper::dump($x, 10, true);
            }
        }
        Yii::$app->end();
    }
}
// 获取yii配置方法
if (!function_exists('getParam')) {
    function getParam($key)
    {
        $params = Yii::$app->params;
        if (!$key) {
            return $params;
        }
        $arr = explode('.', $key);
        foreach ($arr as $v) {
            if (isset($params[$v])) {
                $params = $params[$v];
            } else {
                $params = null;
                break;
            }
        }
        return $params;
    }
}
// 转换人读单位
if (!function_exists('formatBytes')) {
    function formatBytes($size): string
    {
        $units = [' B', ' KB', ' MB', ' GB', ' TB'];
        for ($i = 0; $size >= 1024 && $i < 4; $i++) {
            $size /= 1024;
        }
        return round($size, 2) . $units[$i];
    }
}

// 获取管理员的角色
if (!function_exists('currentRole')) {
    function currentRole()
    {
        $roles = Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId());
        unset($roles['guest']);
        return key($roles);
    }
}
if (!function_exists('formatAmount')) {
    function formatAmount($amount): string
    {
        return sprintf('%.2f', $amount);
    }
}
if (!function_exists('can')) {
    /**
     * 检查登录用户是否有权限
     *
     * @param $permission
     *
     * @return bool
     */
    function can($permission)
    {
        return Yii::$app->authManager->checkAccess(Yii::$app->user->getId(), $permission);
    }
}
if (!function_exists('dumpSql')) {
    /**
     * @param $model
     */
    function dumpSql($model)
    {
        /**
         * @var ActiveQuery
         */
        if ($model instanceof ActiveQuery) {
            dd($model->createCommand()->getRawSql());
        }
        dd('参数类型不是ActiveRecord哦');
    }
}

if (!function_exists('shortMd5')) {
    function shortMd5($str)
    {
        return substr(md5(json_encode($str)), 8, 16);
    }
}
if (!function_exists('randomString')) {
    /**
     * @param int $len
     * @param int $type 1 数字和字目 2数字 3字母 4含特殊字符
     *
     * @return string
     */
    function randomString(int $len = 32, int $type = 1): string
    {
        $number = '1234567890';
        $letter = 'zxcvbnmlkjhgfdsaqwertyuiopZXCVBNMLKJHGFDSAPOIUYTREWQ';
        $symbol = '~!@#$%^&*()-_=+<>,.{}[]';
        switch ($type) {
            case 1:
                $str = $number . $letter;
                break;
            case 2:
                $str = $number;
                break;
            case 3:
                $str = $letter;
                break;
            case 4:
                $str = $letter . $number . $symbol;
                break;
            default:
                return uniqid();
        }
        return substr(str_shuffle($str), 0, $len);
    }
}

/**
 * 加密文件
 */
if (!function_exists('sha256File')) {
    function sha256File($file, $raw = false)
    {
        if (!file_exists($file) || !is_readable($file)) {
            return false;
        }
        return hash_file('sha256', $file, $raw);
    }
}

/**
 * js escape php 实现
 *
 * @param $string the sting want to be escaped
 * @param string $in_encoding
 * @param string $out_encoding
 *
 * @return string
 */
if (!function_exists('escape')) {
    /**
     * @param string $string
     * @param string $in_encoding
     * @param string $out_encoding
     *
     * @return string
     */
    function escape(string $string, string $in_encoding = 'UTF-8', string $out_encoding = 'UTF-8'): string
    {
        $return = '';
        if (function_exists('mb_get_info')) {
            for ($x = 0; $x < mb_strlen($string, $in_encoding); $x++) {
                $str = mb_substr($string, $x, 1, $in_encoding);
                if (strlen($str) > 1) { // 多字节字符
                    $return .= '%u' . strtoupper(bin2hex(mb_convert_encoding($str, $out_encoding, $in_encoding)));
                } else {
                    $return .= '%' . strtoupper(bin2hex($str));
                }
            }
        }
        return $return;
    }
}


if (!function_exists('unescape')) {
    /**
     * @param string $str
     *
     * @return string
     */
    function unescape(string $str): string
    {
        $ret = '';
        $len = strlen($str);
        for ($i = 0; $i < $len; $i++) {
            if ($str[$i] == '%' && $str[$i + 1] == 'u') {
                $val = hexdec(substr($str, $i + 2, 4));
                if ($val < 0x7f) {
                    $ret .= chr($val);
                } else {
                    if ($val < 0x800) {
                        $ret .= chr(0xc0 | ($val >> 6)) .
                            chr(0x80 | ($val & 0x3f));
                    } else {
                        $ret .= chr(0xe0 | ($val >> 12)) .
                            chr(0x80 | (($val >> 6) & 0x3f)) .
                            chr(0x80 | ($val & 0x3f));
                    }
                }
                $i += 5;
            } else {
                if ($str[$i] == '%') {
                    $ret .= urldecode(substr($str, $i, 3));
                    $i   += 2;
                } else {
                    $ret .= $str[$i];
                }
            }
        }
        return $ret;
    }
}
if (!function_exists('unicode_decode')) {
    /**
     * 将unicode编码的字符转换成非unicode编码的字符
     *
     * @param string $unicode_str
     *
     * @return mixed|string
     */
    function unicode_decode(string $unicode_str)
    {
        $unicode_str = addcslashes($unicode_str, '"');
        $json        = '{"json": "' . $unicode_str . '"}';
        $jsonArr     = json_decode($json, true);
        if ($jsonArr) {
            return $jsonArr['json'];
        }
        return '';
    }
}

