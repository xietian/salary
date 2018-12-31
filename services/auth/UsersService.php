<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/12
 * Time: 23:16
 */

namespace app\services\auth;


use app\format\FormatUser;
use app\models\UserBind;
use app\services\LoginService;
use app\services\thirdpart\wx\xcx\OauthService;
use app\services\UserBindService;
use app\tools\ErrorCode;
use app\tools\OutTools;
use mdm\admin\models\User;

class UsersService
{
    /**
     * 登录
     * @param $userName
     * @param $password
     * @param $openId
     * @param $appId
     * @return array
     */
    public function login($userName, $password, $openId, $appId)
    {
        $info = User::findByUsername($userName);
        if (empty($info) == false) {
            if ($info->password_hash == $password) {
                $userBindService = new UserBindService();
                $userBindService->bindUser($info->id, $openId, $appId);
                $token = LoginService::setToken($info->getId());
                $array = array('user' => FormatUser::format($info->toArray()), 'app_token' => $token);
                return OutTools::success($array);
            }
        }
        return OutTools::error(ErrorCode::ERROR, \Yii::t('app', 'Account_Or_Password_Error'));
    }


    /**
     * 登录
     * @param $openId
     * @param $appId
     * @return array
     */
    public function autoLogin($openId, $appId)
    {
        $userInfo = UserBind::find()->where('appid=:appid and openid=:openid', [':appid' => $appId, ':openid' => $openId])->one();
        if (empty($userInfo) == false) {
            $info = User::findIdentity($userInfo->user_id);
            if (empty($info) == false) {
                $token = LoginService::setToken($userInfo->user_id);
                $array = array('is_registered' => 1, 'user' => FormatUser::format($info->toArray()), 'app_token' => $token);
                return OutTools::success($array);
            } else {
                return OutTools::error(ErrorCode::ERROR, \Yii::t('app', 'Account_Or_Password_Error'));
            }
        } else {
            return OutTools::success(array('is_registered' => 0, 'user' => [], 'app_token' => ''));
        }

    }

    /**
     * 获取用户信息
     * @param $id
     * @return array
     */
    public function get($id)
    {
        $info = User::findIdentity($id);
        if (empty($info) == false) {
            $array = array('is_registered' => 1, 'user' => FormatUser::format($info->toArray()), 'user_token' => LoginService::getAppToken());
            return OutTools::success($array);
        }
        return OutTools::error(ErrorCode::ERROR, \Yii::t('app', 'Not_Found', [\Yii::t('app', 'User')]));
    }

    /**
     * 获取openInfo,后续可扩展
     * @param $code
     * @return array
     */
    public function getOpenInfo($code)
    {
        return OauthService::jscode2session("xcx", $code);
    }
}