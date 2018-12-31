<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/12/30
 * Time: 20:43
 */

namespace app\services;


use app\models\UserBind;

class UserBindService
{
    public function bindUser($userId, $openId, $appId)
    {
        //已绑定
        if ($this->get($userId, $openId, $appId)) {
            return true;
        }
        //绑定
        $userBindModel = new UserBind([
            'user_id' => $userId,
            'openid' => $openId,
            'appid' => $appId,
        ]);
        return $userBindModel->save();
    }

    public function get($userId, $openId, $appId)
    {
        return UserBind::find()->where('user_id=:user_id and openid=:openid and appid=:appid', array(':user_id' => $userId, ':openid' => $openId, ':appid' => $appId))->one();
    }
}