<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2018/5/3
 * Time: 17:07
 */

namespace app\services\thirdpart\wx\wechat;


use app\tools\Constants;
use app\tools\curl\HttpTools;
use app\tools\OutTools;

class WechatMedia
{
    /**
     * 上传内容
     * @param $product
     * @param $content
     * @param string $accessToken
     * @return bool|mixed
     */
    public static function uploadContent($product, $content, $accessToken = '')
    {
        $accessToken = WechatConfig::getAccessToken($product, $accessToken);
        $postImageUrl = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=" . $accessToken . "&type=image";
        $res = HttpTools::postImageContent($postImageUrl, array(), array('media' => $content), 'json');
        \Yii::error($res, 'yii\wx\con');
        if (isset($res['errcode']) && $res['errcode'] != 0) {
            return OutTools::errorWithData(Constants::ERROR, $res['errmsg'], $res);
        } else {
            return OutTools::success($res, '');
        }
    }

    /**
     * 上传
     * @param $product
     * @param $url
     * @param string $accessToken
     * @return bool|mixed
     */
    public static function upload($product, $url, $accessToken = '')
    {
        $accessToken = WechatConfig::getAccessToken($product, $accessToken);
        $postImageUrl = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=" . $accessToken . "&type=image";
        $res = HttpTools::postImageContent($postImageUrl, array(), array('media' => WechatQrContent::getQrCodeContent($url)), 'json');
        if (isset($res['errcode']) && $res['errcode'] == 0) {
            OutTools::success($res, '');
        } else {
            OutTools::errorWithData(Constants::ERROR, $res['errmsg'], $res);
        }
    }

    /**
     * 上传本地文件
     * @param $product
     * @param $filePath
     * @param string $accessToken
     * @return mixed
     */
    public static function uploadLocal($product, $filePath, $accessToken = '')
    {
        $accessToken = WechatConfig::getAccessToken($product, $accessToken);
        $postImageUrl = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=" . $accessToken . "&type=image";
        $res = HttpTools::https_post($postImageUrl, array('media' => '@' . $filePath));
        $res = json_decode($res, true);
        if (isset($res['errcode']) && $res['errcode'] == 0) {
            OutTools::success($res, '');
        } else {
            OutTools::errorWithData(Constants::ERROR, $res['errmsg'], $res);
        }
    }
}
