<?php
namespace app\models\wxApi;

use app\tools\HttpTools;

class Wxuser extends Wxbase
{
    /**
     * 获取用户列表
     * @param $appid
     * @return mixed
     */
    public function ulist()
    {

        $access_token = $this->get_access_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token=%s';
        $result = json_decode(HttpTools::http_get(sprintf($url, $access_token)), true);
        return $result;
    }

    /**
     * 获取用户信息
     * @param $openid
     * @return bool|mixed
     */
    public function get_user_info($openid)
    {
        $access_token = $this->get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=%s&openid=%s&lang=zh_CN";
        $response = HttpTools::http_get(sprintf($url, $access_token, $openid));
        $result = json_decode($response, true);
        if (is_array($result) == false || isset($result["errcode"])) {
            return false;
        } else {
            if (isset($result["unionid"]) == false) {
                $result["unionid"] = "";
            }
        }
        return $result;
    }
}