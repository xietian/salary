<?php
namespace app\services\thirdpart\wx\wechat\qrsceneact;

use app\models\doctor\DoctorUser;
use app\models\user\OpenUser;
use app\models\user\User;
use app\services\thirdpart\wx\wechat\WechatQrScene;
use Yii;

/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2018/5/4
 * Time: 14:31
 */
class DoctorUserAct
{
    /**
     * 判断是否登录
     * @param WechatQrScene $wechatQrScene
     * @param $openUserId
     * @return array|bool
     */
    public function check(WechatQrScene $wechatQrScene, $openUserId)
    {
	
			   Yii::error(var_export($openUserId, true),'yii\error\subscribe');
        if (empty($openUserId)) {
            return false;
        }
        $openUserModel = new OpenUser();
        $openUserInfo = $openUserModel->getLoginInfoById($openUserId);
        if (empty($openUserInfo)) {
            return false;
        }

			   Yii::error(var_export($openUserInfo, true),'yii\error\subscribe');
        $userModel = new User();
        $userInfo = $userModel->getUserInfoByUserName($openUserInfo['user_name']);
        if (empty($userInfo)) {
            return false;
        }
			   Yii::error(var_export($userInfo, true),'yii\error\subscribe');
        $doctorUser = new DoctorUser();
        $info = $doctorUser->isFriends($userInfo['user_id'], $wechatQrScene->act_id);
        if (empty($info) == false) {
            return ['status' => true, 'msg' => Yii::t('app', 'Wechat_Act_Is_Doctor_Friends')];
        } else {
            return false;
        }
    }
}
