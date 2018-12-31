<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2017/9/25
 * Time: 10:20
 */

namespace app\services\thirdpart\wx;


use app\models\thirdpart\cached\SubscribeQrImgCached;
use app\models\thirdpart\cached\SubscribeQrMediaCached;
use app\services\fibruser\UserService;
use app\services\thirdpart\wx\wechat\Wechat;
use app\services\thirdpart\wx\wechat\WechatConfig;
use app\services\thirdpart\wx\wechat\WechatMedia;
use app\services\thirdpart\wx\wechat\WechatQrCode;
use app\services\thirdpart\wx\wechat\WechatQrContent;
use app\services\thirdpart\wx\wechat\WechatQrScene;
use app\tools\CommonFun;
use app\tools\Constants;
use app\tools\OutTools;
use Yii;

class WechatService
{

    /**
     * 生成jssdk信息
     * @param $url
     * @param $product
     * @return array
     */
    public function generalJssdk($url, $product = 'patient')
    {
        $redirect_uri = $url;
        $model = new Wechat(Yii::$app->params['config']['public'][$product]['appId'], Yii::$app->params['config']['public'][$product]['secret'], $product);
        $jssdkParams = $model->getSignature($redirect_uri);
        return OutTools::success($jssdkParams, Yii::t('app', 'Request_Success'));
    }

    /**
     * 获取微信关注二维码图片，不存在即创建
     * @param $product //生成二维码所在的产品
     * @param $data ['id':111, 'type':'doctor', 'name'=>'']
     * @param $returnMediaId
     * @param $mediaProduct //上传图片的产品
     * @return mixed
     */
    public function getWxSubscribeQrImg($product, $data, $returnMediaId = false, $mediaProduct = '')
    {
        $token = WechatConfig::getAccessToken($product);
        if (empty($token)) {
            Yii::error('获取token失败', 'yii\error\getWxSubscribeQrImg');
            return OutTools::error(Constants::ERROR, Yii::t('app', 'Invalidate Token'));
        }
        $wechatQrScene = new WechatQrScene($data['id'], $data['name'], $data['type'], $data['act_type'], $data['act_id'], $product);
        $hash = $wechatQrScene->encode();
        $key = md5($hash);
        $imgUrl = SubscribeQrImgCached::get($key);
        $content = null;
        if (empty($imgUrl)) {
            $wechatQrCode = new WechatQrCode($token, '');
            if (!$wechatImgUrl = $wechatQrCode->generalUrl($hash, 'QR_STR_SCENE', SubscribeQrImgCached::CACHE_LEN)) {
                Yii::error('调用微信接口生成二维码失败'.var_export($wechatImgUrl,true), 'yii\error\getWxSubscribeQrImg');
                return OutTools::error(Constants::ERROR, Yii::t('app', 'Generate_Qrcode_Error'));
            }
            if (!$content = WechatQrContent::getQrCodeContent($wechatImgUrl)) {
                Yii::error('获取二维码图片内容失败', 'yii\error\getWxSubscribeQrImg');
                return OutTools::error(Constants::ERROR, Yii::t('app', 'Generate_Qrcode_Error'));
            }
            if (!$imgUrl = CommonFun::uploadFileByContent($content)) {
                Yii::error('保存或上传图片内容失败', 'yii\error\generateShareImg');
                return OutTools::error(Constants::ERROR, Yii::t('app', 'Generate_Qrcode_Error'));
            }
	        SubscribeQrImgCached::set($key, $imgUrl);
        }
        $mediaId = 0;
        if ($returnMediaId) {
            $mediaId = SubscribeQrMediaCached::get($key);
            if (empty($mediaId)) {
                if (empty($content)) {
                    if (!$content = file_get_contents($imgUrl)) {
                        Yii::error('获取二维码图片内容失败', 'yii\error\getWxSubscribeQrImg');
                        return OutTools::error(Constants::ERROR, Yii::t('app', 'Generate_Qrcode_Error'));
                    }
                }
                $mediaRes = WechatMedia::uploadContent($mediaProduct, $content);
                Yii::error(var_export($mediaRes, true), 'yii\error\getWxSubscribeQrImg');
                if (!OutTools::isSuccess($mediaRes)) {
                    return $mediaRes;
                } else {
                    SubscribeQrMediaCached::set($key, $mediaRes['data']['media_id']);
                    $mediaId = $mediaRes['data']['media_id'];
                }
            }
        }
        return OutTools::success(array('url' => $imgUrl, 'media_id' => $mediaId), '');
    }
}
