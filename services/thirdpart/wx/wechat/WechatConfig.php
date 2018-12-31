<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2018/5/3
 * Time: 13:34
 */

namespace app\services\thirdpart\wx\wechat;

use app\models\app\AppClient;
use app\tools\Constants;
use Yii;

class WechatConfig
{
    const PRODUCT_DOCTOR = 'doctor';
    const PRODUCT_PATIENT = 'patient';

    public static function isDoctorProduct($product)
    {
        return self::PRODUCT_DOCTOR == $product;
    }

    public static function isPatientProduct($product)
    {
        return self::PRODUCT_PATIENT == $product;
    }

    public static function getDoctorProduct()
    {
        return self::PRODUCT_DOCTOR;
    }

    public static function getPatientProduct()
    {
        return self::PRODUCT_PATIENT;
    }

    /**
     * 获取accessToken
     * @param $product
     * @param string $accessToken
     * @return mixed|string
     */
    public static function getAccessToken($product, $accessToken = '')
    {
        if (empty($accessToken)) {
            $appId = Yii::$app->params['config']['public'][$product]['appId'];
            $appSec = Yii::$app->params['config']['public'][$product]['secret'];
            $wechat = new Wechat($appId, $appSec, $product);
            $accessToken = $wechat->getAccessToken();
        }
        return $accessToken;
    }

    /**
     * 默认取医生的appId
     * @return mixed
     */
    public static function getDefaultAppId()
    {
        return Yii::$app->params['config']['public'][self::PRODUCT_DOCTOR]['appId'];
    }

    /**
     * 获取appId
     * @param $clientToken
     * @return mixed
     */
    public static function getAppId($clientToken)
    {
        if ($clientToken == AppClient::CLIENT_ECG_USER_WX) {
            return isset(Yii::$app->params['config']['public'][self::PRODUCT_PATIENT]['appId']) ? Yii::$app->params['config']['public'][self::PRODUCT_PATIENT]['appId'] : "";
        } else if ($clientToken == AppClient::CLIENT_ECG_DOCTOR_WX) {
            return isset(Yii::$app->params['config']['public'][self::PRODUCT_DOCTOR]['appId']) ? Yii::$app->params['config']['public'][self::PRODUCT_DOCTOR]['appId'] : "";
        } else {
            return "";
        }
    }


    /**
     * 获取appId
     * @param $clientToken
     * @return mixed
     */
    public static function getAppIdByProduct($product)
    {
        if ($product == self::PRODUCT_PATIENT) {
            return isset(Yii::$app->params['config']['public'][self::PRODUCT_PATIENT]['appId']) ? Yii::$app->params['config']['public'][self::PRODUCT_PATIENT]['appId'] : "";
        } else if ($product == self::PRODUCT_DOCTOR) {
            return isset(Yii::$app->params['config']['public'][self::PRODUCT_DOCTOR]['appId']) ? Yii::$app->params['config']['public'][self::PRODUCT_DOCTOR]['appId'] : "";
        } else {
            return "";
        }
    }



}