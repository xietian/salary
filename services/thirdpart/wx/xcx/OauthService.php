<?php
/**
 * 微信认证Oauth类
 * @user: andy
 * @date: 2017/7/7
 * @time: 15:18
 */

namespace app\services\thirdpart\wx\xcx;


use app\services\thirdpart\ApiService;
use app\tools\ErrorCode;
use app\tools\OutTools;
use Yii;

class OauthService
{

    public static function getAppId($appId){
        if(empty($appId)){
            $appId = Yii::$app->params['config']['public']['xcx']['appId'];
        }
        return $appId;
    }
    /**
     * @param $wxConfigName
     * @param $code
     * @return array
     */
    public static function jscode2session($wxConfigName, $code)
    {
        $config = isset(Yii::$app->params['config']['public'][$wxConfigName]) ? Yii::$app->params['config']['public'][$wxConfigName] : array();
        if (empty($config['appId']) == false && empty($config['secret']) == false) {
            $appId = $config['appId'];
            $secret = $config['secret'];
            $requestData = array("appid" => $appId, "secret" => $secret, "js_code" => $code);
            $apiUrl = "https://api.weixin.qq.com/sns/jscode2session";
            $res = ApiService::get($apiUrl, $requestData);
            if (isset($res['errcode'])) {
                \Yii::error("request url:" . print_r($apiUrl, true) . ";\n yii.error.OauthService.jscode2session");
                \Yii::error("request data:" . print_r($requestData, true) . ";\n yii.error.OauthService.jscode2session");
                \Yii::error("response data:" . print_r($res, true) . ";\n yii.error.OauthService.jscode2session");
                return OutTools::error(ErrorCode::ERROR, Yii::t('app', 'ErrorWithCode', array(ErrorCode::WX_EXCEPTION)));
            } else {
                return OutTools::success($res, Yii::t('app', 'Request_Success'));
            }
        } else {
            return OutTools::error(ErrorCode::ERROR, Yii::t('app', 'ErrorWithCode', array(ErrorCode::CONFIG_EXCEPTION)));
        }
    }
}