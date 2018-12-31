<?php
/**
 * 用于调用第三方的接口类.
 * Author: andy
 * Date: 2017/7/5
 * Time: 20:15
 */

namespace app\services\thirdpart;


use app\tools\Constants;
use app\tools\curl\HttpTools;
use app\tools\OutTools;
use Yii;
use yii\base\Exception;

class ApiService
{

    /**
     * 获取api地址
     * @param $method
     * @return mixed
     */
    public static function getApiUrl($method)
    {
        return $method;
    }

	/**
	 * post请求
	 *
	 * @param $method
	 * @param $data
	 * @param bool $isProxy
	 * @param bool $isJson
	 *
	 * @return mixed
	 */
    public static function post($method, $data, $isProxy = true, $isJson = false)
    {
        try {
            $url = self::getApiUrl($method);
            $result = HttpTools::http_post($url, $data, $isProxy, $isJson);
            if ($result) {
                return json_decode($result, true);
            } else {
                \Yii::error("request:" . print_r($method, true) . ";\n data:" . print_r($data, true), 'yii\error\apiService\post');
                return OutTools::error(Constants::ERROR, Yii::t('app', 'ErrorWithCode', array(Constants::CURL_EXCEPTION)));
            }
        } catch (Exception $e) {
            \Yii::error("request:" . print_r($method, true) . ";\n data:" . print_r($data, true) . "\n exception:" . $e->getTraceAsString(), 'yii\error\apiService\post');
            return OutTools::error(Constants::ERROR, $e->getMessage());
        }

    }

    /**
     * get请求
     * @param $method
     * @param $data
     * @param $isProxy
     * @return mixed
     */
    public static function get($method, $data, $isProxy = true)
    {
        try {
            $url = self::getApiUrl($method);
            if (empty($data) == false) {
                $url .= "?";
                foreach ($data as $key => $val) {
                    $url .= "$key=$val&";
                }
            }
            $result = HttpTools::http_get($url, $isProxy);
            if ($result) {
                return json_decode($result, true);
            } else {
                \Yii::error("request:" . print_r($url, true) . ";\n yii.error.apiService.get");
                return OutTools::error(Constants::ERROR, Yii::t('app', 'ErrorWithCode', array(Constants::CURL_EXCEPTION)));
            }
        } catch (Exception $e) {
            \Yii::error("request:" . print_r($method, true) . ";\n data:" . print_r($data, true) . "\n exception:" . $e->getTraceAsString(), "yii.error.apiService.get");
            return OutTools::error(Constants::ERROR, Yii::t('app', 'ErrorWithCode', array(Constants::CURL_EXCEPTION)));
        }
    }
}