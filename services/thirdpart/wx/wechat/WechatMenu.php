<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2018/4/24
 * Time: 17:05
 */

namespace app\services\thirdpart\wx\wechat;


use app\tools\Constants;
use app\tools\curl\HttpTools;
use app\tools\OutTools;
use Yii;

class WechatMenu
{
    /**
     * 创建菜单
     * @param $product
     * @param $menuStr
     * @param string $accessToken
     * @return string
     */
    public static function createMenu($product, $menuStr, $accessToken = "")
    {
        try {
            $accessToken = WechatConfig::getAccessToken($product, $accessToken);
            $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=" . $accessToken;
            $str = HttpTools::http_post($url, $menuStr);
            $res = json_decode($str, true);
            if (isset($res['errcode']) && $res['errcode']) {
                return OutTools::error(Constants::ERROR, $res['errmsg']);
            } else {
                return OutTools::success($res, '');
            }
        } catch (\Exception $e) {
            Yii::error($e->getMessage(), 'yii\wx\createMenu');
            return OutTools::error(Constants::ERROR, Yii::t('app', 'ErrorWithCode', [Constants::CODE_EXCEPTION]));
        }

    }

    /**
     * 获取菜单
     * @param $product
     * @param string $accessToken
     * @return string
     */
    public static function getMenu($product, $accessToken = "")
    {
        try {
            $accessToken = WechatConfig::getAccessToken($product, $accessToken);
            $url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=" . $accessToken;
            $str = HttpTools::http_post($url, '');
            $res = json_decode($str, true);
            if (isset($res['errcode']) && $res['errcode']) {
                return OutTools::error(Constants::ERROR, $res['errmsg']);
            } else {
                return OutTools::success($res, '');
            }
        } catch (\Exception $e) {
            Yii::error($e->getMessage(), 'yii\wx\createMenu');
            return OutTools::error(Constants::ERROR, Yii::t('app', 'ErrorWithCode', [Constants::CODE_EXCEPTION]));
        }
    }


}