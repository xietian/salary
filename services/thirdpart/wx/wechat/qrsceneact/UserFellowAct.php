<?php
namespace app\services\thirdpart\wx\wechat\qrsceneact;

use app\models\user\UserFollow;
use app\services\thirdpart\wx\wechat\WechatQrScene;
use Yii;

/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2018/5/4
 * Time: 14:31
 */
class UserFellowAct
{
    public function check(WechatQrScene $wechatQrScene, $openUserId)
    {
        if (empty($openUserId)) {
            return false;
        }

//        if ($wechatQrScene->act_id == $openUserId) {
//        	return ['status' => true, 'msg' => Yii::t('app', 'Wechat_Act_Is_Not_Follow_Self')];
//        }

        $userFollow = new UserFollow();
        if ($userFollow->checkFollow($openUserId, $wechatQrScene->act_id)) {
            return ['status' => true, 'msg' => Yii::t('app', 'Wechat_Act_Is_User_Follow')];
        }

        return false;
    }
}
