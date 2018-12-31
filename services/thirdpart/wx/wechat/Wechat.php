<?php
namespace app\services\thirdpart\wx\wechat;

use app\models\app\config\RedisConfig;
use app\tools\Constants;
use Yii;

/**
 * Created by PhpStorm.
 * User: health1
 * Date: 2017/5/11
 * Time: 14:46
 */
class Wechat
{

    private $appid;
    private $appKey;
    private $product = 'doctor';

    static $redisAppKeys = [];

    function __construct($appId, $appKey, $product = 'doctor')
    {
        $this->appid = $appId;
        $this->appKey = $appKey;
        $this->product = $product;
    }

    public static function flushAllKeys()
    {

        $redis = Yii::app()->cache;
        $redis->connect(RedisConfig::REDIS_DB_CACHED_ZERO);
        foreach (self::$redisAppKeys as $k => $v) {
            $redis->del($v);
        }
        self::$redisAppKeys = [];
    }

    /**
     * 获取token
     *
     * */
    public function getAccessToken()
    {

        $redisKey = $this->product . ":accessToken:" . $this->appid;
        self::$redisAppKeys[] = $redisKey;
        $redis = Yii::$app->redis;
        $redis->select(RedisConfig::REDIS_DB_CACHED_ZERO);
        $accessToken = $redis->get($redisKey);
        if (!$accessToken) {
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $this->appid . '&secret=' . $this->appKey;
            $res = $this->_getMsg([], $url);
            //WechatQrCode::$displayErrors["accessToken" . $this->appid] = $res;
            $res = json_decode($res, true);
            $accessToken = $res['access_token'];
            $expireTime = $res['expires_in'];
            $redis->set($redisKey, $accessToken);
            $redis->expire($redisKey, 900);
        }
        return $accessToken;
    }

    /**
     * 获取用户昵称
     *
     * */
    public function getNickName($openId)
    {

        $redisKey = $this->product . ".nickName:" . $this->appid;
        self::$redisAppKeys[] = $redisKey;
        $redis = Yii::app()->cache;
        $redis->connect(RedisConfig::REDIS_DB_CACHED_ZERO);
        $nickName = $redis->get($redisKey);
        if (!$nickName) {
            $token = $this->getAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . $token . "&openid=" . $openId;
            $res = $this->_getMsg([], $url);
            $res = json_decode($res, true);
            $nickName = isset($res['nickname']) ? $res['nickname'] : "unknow";
            $redis->set($redisKey, $nickName);
            $redis->expire($redisKey, 86400);
        }
        return $nickName;
    }

    public function getOpenId($code)
    {
        $apiUrl = "https://api.weixin.qq.com/sns/oauth2/access_token";
        $option ['appid'] = Yii::$app->params['config']['public'][$this->product]['appId'];
        $option ['secret'] = Yii::$app->params['config']['public'][$this->product]['secret'];
        $option ['code'] = $code;
        $option ['grant_type'] = "authorization_code";

        $self = new self;
        $backJson = $self->_getMsg($option, $apiUrl);
        $backArr = json_decode($backJson, true);
        $openid = $backArr['openid'];
        return $openid;
    }

    public function getJsApiTicket()
    {

        $redisKey = $this->product . ":jsapiticket:" . $this->appid;
        self::$redisAppKeys[] = $redisKey;
        $redis = Yii::$app->redis;
        $redis->select(RedisConfig::REDIS_DB_CACHED_ZERO);
        $jsTicket = $redis->get($redisKey);

        if (!$jsTicket) {
            $accessToken = $this->getAccessToken();
            if ($accessToken) {
                $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=' . $accessToken . '&type=jsapi';
                $res = $this->_getMsg([], $url);
                $res = json_decode($res, true);
                $jsTicket = $res['ticket'];
                $redis->set($redisKey, $jsTicket);
                $redis->expire($redisKey, $res['expires_in'] - 10);
            }
        }
        return $jsTicket;
    }

    /**
     * 生成签名参数
     *
     * */
    public function getSignature($url)
    {

        $params = [
            "jsapi_ticket" => self::getJsApiTicket(),
            "noncestr" => self::getNonceStr(),
            "timestamp" => time(),
            "url" => $url,
        ];
        $params['signature'] = self::refreshSign($params);
        $params['appId'] = $this->appid;
        return $params;
    }

    /**
     * 签名
     * */
    public static function ToUrlParams($data)
    {
        $buff = "";
        foreach ($data as $k => $v) {
            if ($k != "sign" && $v != "" && !is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }

    public static function getNonceStr($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    public static function getParams()
    {

        $params = array();
        foreach ($_GET as $k => $v) {
            $params[] = $k . "=" . urlencode($v);
        }
        return $params ? "?" . implode("&", $params) : "";
    }

    /**
     * 重新生成签名
     *
     * */
    public static function refreshSign($data)
    {

        ksort($data);
        $string = self::ToUrlParams($data);
        $string = sha1($string);
        return $string;
    }

    /**
     * @param 参数 $sendParam
     * @param url $apiUrl
     * @return
     */
    public function _getMsg($sendParam, $apiUrl)
    {
        $proxy = Yii::$app->params['config']['proxy']['host'];
        $proxyport = Yii::$app->params['config']['proxy']['port'];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $apiUrl);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_POST, 1);
        if (is_array($sendParam)) {
            $body = http_build_query($sendParam);
        } else {
            $body = $sendParam;
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        if (Yii::$app->params['config']['proxy']['status']) {
            curl_setopt($curl, CURLOPT_PROXY, $proxy);
            curl_setopt($curl, CURLOPT_PROXYPORT, $proxyport);
        }
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $httpInfo = curl_getinfo($curl);
        curl_close($curl);
        return $response;
    }


    public function getCodeUrl($url)
    {
        //对url进行编码
        $url = urlencode($url);
        $codeUrl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $this->appid . "&redirect_uri=" . $url . "?action=viewtest&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
        return $codeUrl;

    }


    public function getOauthAccessOpenId($code)
    {
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $this->appid . "&secret=" . $this->appKey . "&code=" . $code . "&grant_type=authorization_code";
        $res = $this->_getMsg([], $url);
        Yii::error('微信信息：' . $res, "yii\resetPwd");
        return $res;

    }
}
