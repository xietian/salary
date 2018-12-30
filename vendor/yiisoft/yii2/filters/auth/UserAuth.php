<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\filters\auth;

use app\services\LoginService;
use app\tools\ErrorCode;
use app\tools\OutTools;

/**
 * HttpBearerAuth is an action filter that supports the authentication method based on HTTP Bearer token.
 *
 * You may use HttpBearerAuth by attaching it as a behavior to a controller or module, like the following:
 *
 * ```php
 * public function behaviors()
 * {
 *     return [
 *         'bearerAuth' => [
 *             'class' => \yii\filters\auth\HttpBearerAuth::className(),
 *         ],
 *     ];
 * }
 * ```
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class UserAuth extends AuthMethod
{
    /**
     * @var string the HTTP authentication realm
     */
    public $realm = 'api';


    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {
        $authHeader = $request->getHeaders()->get('app_token');
        if (!empty($authHeader)) {
            $identity = $user->loginByAccessToken($authHeader, get_class($this));
            if ($identity === null) {
                $res = OutTools::error(ErrorCode::TOKEN_EXPIRE, \Yii::t('app', 'Token_Error'));
                OutTools::outJsonP($res);
//                $this->handleFailure($response);
            }
            //获取到登陆信息
            if (!LoginService::hasPower($request, $identity)) {
                $res =  OutTools::error(ErrorCode::NO_POWER, \Yii::t('app', 'No_Power'));
                OutTools::outJsonP($res);
            }
            return $identity;
        } else {

            $res =  OutTools::error(ErrorCode::TOKEN_EXPIRE, \Yii::t('app', 'Token_Empty'));
            OutTools::outJsonP($res);
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function challenge($response)
    {
        $response->getHeaders()->set('WWW-Authenticate', "app_token realm=\"{$this->realm}\"");
    }
}
