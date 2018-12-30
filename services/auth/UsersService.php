<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/12
 * Time: 23:16
 */

namespace app\services\auth;


use app\format\FormatUser;
use app\services\LoginService;
use app\tools\ErrorCode;
use app\tools\OutTools;
use mdm\admin\models\User;

class UsersService
{
    /**
     * 登录
     * @param $userName
     * @param $password
     * @return array
     */
    public function login($userName, $password)
    {
        $info = User::findByUsername($userName);
        if (empty($info) == false) {
            if ($info->password_hash == $password) {
                $token = LoginService::setToken($info->getId());
                $array = array('user' => FormatUser::format($info->toArray()), 'app_token' => $token);
                return OutTools::success($array);
            }
        }
        return OutTools::error(ErrorCode::ERROR, \Yii::t('app', 'Account_Or_Password_Error'));
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
            $array = array('user' => FormatUser::format($info->toArray()), 'user_token' => LoginService::getAppToken());
            return OutTools::success($array);
        }
        return OutTools::error(ErrorCode::ERROR, \Yii::t('app', 'Not_Found', [\Yii::t('app', 'User')]));

    }
}