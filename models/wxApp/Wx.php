<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/4/28
 * Time: 10:04
 */
namespace app\models\wxApp;

use app\tools\Constants;

class Wx
{
    public $signature;
    public $timestamp = 0;
    public $nonce;
    public $app_id = 0;
    public $app_secret = 0;
    public $token = array();
    public $echostr = "";
    public $encodingAesKey = "";

    public function __construct($signature, $nonce, $timestamp, $echostr)
    {
        $this->signature = $signature;
        $this->nonce = $nonce;
        $this->app_id = self::getAppId();
        $this->app_secret = self::getAppSecret();
        $this->timestamp = $timestamp;
        $this->echostr = $echostr;
        $this->token = self::getToken();
        $this->encodingAesKey = self::getEncodingAesKey();
    }

    public function request()
    {
        $service = new Service();
        $is_post = $_SERVER['REQUEST_METHOD'] == 'POST' ? true : false;
        if ($is_post) {
            $service->do_post_xml($this);
        } else {
            $service->do_verification($this);
        }
    }

    public static function getAppId()
    {
        return \Yii::$app->params['wx']['appId'];
    }

    public static function getAppSecret()
    {
        return \Yii::$app->params['wx']['appSecret'];
    }

    public static function getToken()
    {
        return \Yii::$app->params['wx']['token'];
    }

    public static function getEncodingAesKey()
    {
        return \Yii::$app->params['wx']['encodingAESKey'];
    }

    public static function getAccessToken()
    {
        return \Yii::$app->cache->get('access_token');
    }

    public static function setAccessToken($accessToken)
    {
        return \Yii::$app->cache->set('access_token', $accessToken, 1800);
    }

    public static function setRefreshToken($refreshToken)
    {
        return \Yii::$app->cache->set('refresh_token', $refreshToken, 1800);
    }


    public static function getOauth2AccessToken()
    {
        return \Yii::$app->cache->get('oauth2_access_token');
    }

    public static function setOauth2AccessToken($accessToken)
    {
        return \Yii::$app->cache->set('oauth2_access_token', $accessToken, 1800);
    }

    public static function setOauth2RefreshToken($refreshToken)
    {
        return \Yii::$app->cache->set('oauth2_refresh_token', $refreshToken, 1800);
    }

    public static function getAuthUrl()
    {
        return \Yii::$app->params['wxOAuthServer'];
    }


    public static function deleteCache($key)
    {
        return \Yii::$app->cache->delete($key);
    }

    /**
     * @return string
     */
    public static function getCurrentOpenId()
    {
        if (defined('YII_ENV_DEV') && YII_ENV_DEV) {
            $appId = \Yii::$app->session->get('openid');
            if (empty($appId)) {
                return "oIQiEwj_bS7Ar9kj8lWNw7l_kPrQ";
            }
            return $appId;
        } else {
            return \Yii::$app->session->get('openid');
        }

    }

    public static function setCurrentOpenId($openId)
    {
        return \Yii::$app->session->set('openid', $openId);
    }

    /**
     * 设置用户的状态
     */
    public static function setOpenIdState($state)
    {
        return \Yii::$app->session->set('openid_state', $state);
    }

    public static function getOpenIdState()
    {
        return \Yii::$app->session->get('openid_state');
    }

    public static function isOpenIdUnBind()
    {
        return Constants::STATE_UN_BIND == self::getOpenIdState() || Constants::STATE_BIND_UN_PASS == self::getOpenIdState();
    }

    public static function isOpenIdBindRequest()
    {
        return Constants::STATE_BIND_REQ == self::getOpenIdState();
    }

    public static function isOpenIdBindPass()
    {
        return Constants::STATE_BIND_PASS == self::getOpenIdState();
    }


    public static function getDkfAccounts()
    {
        return \Yii::$app->params['wx']['dkf_account'];
    }

    public static function getDkfOpenIds()
    {
        return \Yii::$app->params['wx']['dkf_openid'];
    }

    public static function isManageOpenId($openId)
    {
        $openIds = self::getDkfOpenIds();
        $openIdList = explode(",", $openIds);
        return empty($openId) == false && empty($openIdList) == false && in_array($openId, $openIdList);
    }

    /**
     * 是否可以审核账号的权限
     * @param $openId
     * @param $inviteOpenId
     * @return bool
     */
    public static function isAuditOpenId($openId, $inviteOpenId)
    {
        return self::isManageOpenId($openId) || $openId == $inviteOpenId;
    }
}