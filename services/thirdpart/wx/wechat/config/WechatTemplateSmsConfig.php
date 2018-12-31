<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2018/5/8
 * Time: 10:36
 */

namespace app\services\thirdpart\wx\wechat\config;


class WechatTemplateSmsConfig
{

    //第三方推送类型
    const SEND_RECORD_WX_MSEEAGE = 1; // 患者发送报告医生收到推送
    const ADD_FRIEND_WX_MSEEAGE = 2; // 患者申请好友医生收到推送
    const RELATIVES_RECORD_WX_MSEEAGE = 3; // 亲友健康报告推送
}