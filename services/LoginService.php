<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2018/11/12
 * Time: 17:13
 */

namespace app\services;


use Yii;

class LoginService
{
    
    /**
     *
     * 获取token里的所有信息
     * @param $token
     * @return mixed
     */
    static function getAllTokenInfo($token)
    {
        $redis = Yii::$app->redis;
        $redis->select(2);
        $id = $redis->get($token);
        return $id;
    }

    /**
     * 获取token值
     * @return array|string
     */
    public static function getAppToken()
    {
        return Yii::$app->request->getHeaders()->get('app_token');
    }

    /**
     * 获取token信息
     * @param $id
     * @return array|string
     */
    static function setToken($id)
    {
        $newToken = Yii::$app->security->generateRandomString(32);
        $oldToken = self::getAppToken();
        if (empty($oldToken) == false) {
            $id = self::getAllTokenInfo($oldToken);
            if (empty($id) == false) {
                $newToken = $oldToken;
            }
        }
        $redis = Yii::$app->redis;
        $redis->select(2);
        $redis->set($newToken, $id);
        return $newToken;
    }

    static function hasPower($request, $identity)
    {
        return true;
    }
}