<?php
namespace app\tools;

use Yii;


/**
 * @modified 2017/7/10
 * @modify_user:tian.xie
 * @desc:接口重构
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/10/10
 * Time: 20:25
 */
class OutTools
{
    private static $_logMap = array('info', 'warning', 'error', 'trace');

    /**
     * modified 2017/7/10
     * @desc 把数组的key转为小写，不是数组的话直接返回数值
     * @param $data
     * @return array
     */
    public static function toLower($data)
    {
        if (empty($data) == false) {
            if (is_array($data)) {
                foreach ($data as $key => $val) {
                    if (is_array($val)) {
                        $data[$key] = self::toLower($val);
                    } else {
                        if (strtolower($key) != $key) {
                            unset($data[$key]);
                            $data[strtolower($key)] = $val;
                        }
                    }
                }
            }
        }
        return $data;
    }

    /**
     * 被引用
     * @desc 添加头允许跨域请求
     * @throws Yii\base\ExitException
     */
    public static function add_common_header()
    {
        $request = Yii::$app->request;
        if (method_exists($request, 'getPathInfo')) {
            $pathInfo = Yii::$app->request->getPathInfo();
            header("Content-type:application/json;charset=utf8");
            header("Access-Control-Allow-Origin:*");
            header("Access-Control-Allow-Credentials:true");
            header("Access-Control-Allow-Methods:*");
            header("Access-Control-Expose-Headers:*");
            header("Access-Control-Allow-Headers:app_token,app_lang,app_version,app_type");
            if (Yii::$app->request->getMethod() == "OPTIONS") {
                self::success((object)[], '', '', '', false);
                die();
            }
        }
    }


    /**
     * @desc 错误输出不带数据
     * @author xietian
     * @param int $code
     * @param string $msg
     * @param string $logCatalog 日志类型 'info', 'warning', 'error', 'trace'
     * @param string $logMethod 被调用方的方法 class::method
     * @param bool $isReturn 是返回还是输出
     * @return array/
     */
    public static function error($code, $msg, $logCatalog = '', $logMethod = '', $isReturn = true)
    {
        return self::out($code, $msg, (object)[], $logCatalog, $logMethod, $isReturn);
    }

    /**
     * @desc 错误输出带数据
     * @author xietian
     * @param int $code
     * @param string $msg
     * @param array $data
     * @param string $logCatalog
     * @param string $logMethod
     * @param bool $isReturn
     * @return array/
     */
    public static function errorWithData($code, $msg, $data = array(), $logCatalog = '', $logMethod = '', $isReturn = false)
    {
        return self::out($code, $msg, $data, $logCatalog, $logMethod, $isReturn);
    }

    /**
     * @desc 成功输出
     * @param string $msg 返回message
     * @param object $data 返回数据
     * @param string $logCatalog 打印输出
     * @param string $logMethod 打印方法
     * @param bool $isReturn 是返回还是输出
     * @return array
     */
    public static function success($data, $msg = '', $logCatalog = '', $logMethod = '', $isReturn = true)
    {
        if (empty($msg)) {
            $msg = Yii::t('app', 'Success');
        }
        return self::out(ErrorCode::SUCCESS, $msg, $data, $logCatalog, $logMethod, $isReturn);
    }

    /**
     * @desc 输出
     * @param string $code 返回码
     * @param string $msg 返回message
     * @param object $data 返回数据
     * @param string $logCatalog 打印输出
     * @param string $logMethod 打印方法
     * @param bool $isReturn 是返回还是输出
     * @return array
     */
    public static function out($code, $msg, $data, $logCatalog = '', $logMethod = '', $isReturn = false)
    {
        if (!self::isNotSetData($data)) {
            $res['data'] = $data;
        } else {
            $res['data'] = (object)[];
        }
        if (!empty($msg)) {
            $res['msg'] = $msg;
        } else {
            $res['msg'] = '';
        }
        $retArray = array('code' => $code, 'msg' => $msg, 'data' => $data);
        if (empty($logCatalog) == false && empty($logMethod) == false) {
            if (in_array(strtolower($logCatalog), self::$_logMap)) {
                self::dispatchLog($logCatalog, $retArray, $logMethod);
            }
        }
        if (!$isReturn) { //直接输出
            self::outJsonP($retArray);
        } else { //返回
            return $retArray;
        }
    }

    /**
     * 输出日志
     * @param $type
     * @param $msg
     * @param $method
     */
    public static function dispatchLog($type, $msg, $method)
    {
        switch ($type) {
            case $type == 'info':
                Yii::info(json_encode($msg), $method);
                break;
            case $type == 'error':
                Yii::error(json_encode($msg), $method);
                break;
            case $type == 'warning':
                Yii::warning(json_encode($msg), $method);
                break;
            case $type == 'trace':
                Yii::trace(json_encode($msg), $method);
                break;
            default;
        }
    }


    /**
     * 兼容jsonp的请求
     * 输出callback
     * @param $data
     * @param $isEnd
     */
    public static function outJsonP($data, $isEnd = true)
    {
        if (is_array($data)) {
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        $callback = Yii::$app->request->get('callback');
        if (empty($callback)) {
            echo $data;
        } else {
            echo $callback . "(" . $data . ")";
        }
        if ($isEnd) {
            Yii::$app->end();
        }
    }

    /**
     * 是否成功
     * @param $json
     * @return bool
     */
    public static function isSuccess($json)
    {
        if (is_string($json)) {
            $json = json_decode($json, true);
        }
        return isset($json['code']) && $json['code'] == ErrorCode::SUCCESS;
    }

    /**
     * 输出中没有设置data
     * @param object $data
     * @return bool
     */
    public static function isNotSetData($data)
    {
        if (!isset($data) || $data === '') {
            return true;
        }
        return false;
    }
}