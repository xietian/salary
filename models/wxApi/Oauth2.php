<?php
namespace app\models\wxApi;

use app\models\wxApp\Wx;
use app\tools\HttpTools;

class Oauth2 extends Wxbase
{

    public function get_token($appid, $appsecret, $code)
    {
        $url = sprintf('https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code', $appid, $appsecret, $code);
        $response = json_decode(HttpTools::http_get($url), true);
        return $response;
    }

    public function refresh_token($refresh_token)
    {
        $url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=%s&grant_type=refresh_token&refresh_token=%s";
        $appid = Wx::getAppId();
        $url = sprintf($url, $appid, $refresh_token);
        $response = json_decode(HttpTools::http_get($url), true);
        if (is_array($response) == false || isset($response["errcode"])) {
            return false;
        } else {
            $access_token = $response["access_token"];
        }
        return $access_token;
    }

    public function check_auth($openid)
    {

        $access_token = $this->get_storage_access_token($openid);
        $url = "https://api.weixin.qq.com/sns/auth?access_token=%s&openid=%s";
        $url = sprintf($url, $access_token, $openid);
        $result = json_decode(HttpTools::http_get($url), true);
        if (is_array($result) && $result["errcode"] == 0) {
            return true;
        }
        return false;
    }

    /**
     * 获取用户信息
     * @param $openid
     * @param null $access_token
     * @return bool|mixed
     */
    public function get_user_info($openid, $access_token = null)
    {
        if (empty($access_token)) {
            $access_token = $this->get_access_token();
        }
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s&lang=zh_CN";
        $url = sprintf($url, $access_token, $openid);
        \Yii::error("url".var_export($url, true), "yii\*");
        $response = HttpTools::http_get($url);
        \Yii::error(var_export($response, true), 'yii\*');
        $result = json_decode($response, true);
        if (is_array($result) == false || isset($result["errcode"])) {
            $result = false;
        } else {
            if (isset($result["unionid"]) == false) {
                $result["unionid"] = "";
            }
        }
        return $result;
    }

    /**
     * 编写请求地址
     * @param $appid
     * @param $authurl
     * @param $state
     * @param string $scope
     * @return string
     */
    public static function build_url($appid, $authurl, $state, $scope = "base")
    {

        if (strcasecmp($scope, "base") == 0) {
            $scope = "snsapi_base";
        } else {
            $scope = "snsapi_userinfo";
        }
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=%s&state=%s#wechat_redirect";
        $url = sprintf($url, $appid, $authurl, $scope, $state);
        return $url;
    }
}