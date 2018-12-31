<?php
namespace app\services\thirdpart\wx\wechat;
use app\tools\Constants;
use app\tools\curl\HttpTools;
use Yii;
/**
 * Created by PhpStorm.
 * User: health1
 * Date: 2017/5/11
 * Time: 15:02
 */
class WechatQrCode {

    private $token = "";
    private $imageGeneralUrl = "";
    static $displayErrors = [];

    function __construct($token, $imageGeneralUrl)
    {
        $this->token = $token;
        $this->imageGeneralUrl = $imageGeneralUrl;
    }

    public function generalPatientId($url) {

        $postImageUrl = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=".$this->token."&type=image";
        $res = HttpTools::postImageContent($postImageUrl, array(), array('media'=>WechatQrContent::getQrCodeContent($url)), 'json');
        if (!is_array($res)) {
            return false;
        }
        if (isset($res['media_id'])) {
            return $res['media_id'];
        }
        return false;
    }

    public function generalMediaContent($sceId, $token = "") {

        $url = $this->generalUrl($sceId);
        return WechatQrContent::getQrCodeContent($url);
    }

    public function generalMediaId($sceId, $token = "") {

        $url = $this->generalUrl($sceId);
        $nowToken = $this->token;
        if ($token) {
            $nowToken = $token;
        }
        $postImageUrl = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=".$nowToken."&type=image";
        $res = HttpTools::postImageContent($postImageUrl, array(), array('media'=>WechatQrContent::getQrCodeContent($url)), 'json');
        if (!is_array($res)) {
            return false;
        }
        if (isset($res['media_id'])) {
            return $res['media_id'];
        }
        return false;
    }

	/**
	 * 生成永久或者临时的带参数二维码
	 *
	 * @param string $sceId 字符串形式的场景值ID
	 * @param string $action 二维码类型，QR_SCENE为临时的整型参数值，QR_STR_SCENE为临时的字符串参数值，QR_LIMIT_SCENE为永久的整型参数值，QR_LIMIT_STR_SCENE为永久的字符串参数值
	 * @param int $expire 临时二维码的过期时间，生成临时二维码需要传递，单位秒
	 *
	 * @return bool|mixed
	 */
	public function generalUrl($sceId = '', $action = 'QR_LIMIT_STR_SCENE', $expire = 0) {

        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$this->token;
        $postData = '{"action_name": "' . $action . '", ';
        if ($expire) {
        	$postData .= '"expire_seconds": ' . $expire . ',';
        }
        $postData .= '"action_info": {"scene": {"scene_str": "'.$sceId.'"}}}';
        $res = HttpTools::post($url, $postData, 'json');
        self::$displayErrors["getqrCode"] = $res;
        if (!is_array($res)) {
            return false;
        }
        if (isset($res['url'])) {
            return $res['url'];
        }
        return false;
    }

}
