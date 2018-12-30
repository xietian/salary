<?php
namespace app\models\wxApi;


use app\models\wxApp\Wx;
use app\tools\HttpTools;

class Wxbase
{
    /**
     * 获取access_token
     * @return bool|mixed
     */
    public function get_access_token()
    {
//        Wx::deleteCache("access_token");
        $access_token = Wx::getAccessToken();
        \Yii::error("access_token".var_export($access_token, true), "yii\*");
        if (empty($access_token)) {
            $appId = Wx::getAppId();
            $appSecret = Wx::getAppSecret();
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appId . "&secret=" . $appSecret;
            $response = json_decode(HttpTools::https_request($url), true);
            \Yii::error("get_access_token".var_export($response, true), "yii\*");
            if (is_array($response) && isset($response['access_token'])) {
                $access_token = $response["access_token"];
            } else {
                $access_token = false;
            }
            Wx::setAccessToken($access_token);
        }
        return $access_token;
    }

}	