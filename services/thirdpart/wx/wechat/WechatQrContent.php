<?php
namespace app\services\thirdpart\wx\wechat;

use app\models\QRcode;
use Yii;

/**
 * Created by PhpStorm.
 * User: health1
 * Date: 2017/5/11
 * Time: 15:41
 */
class WechatQrContent
{

    public static function getQrCodeContent($preContent, $returnContent = true) {
        if($returnContent){
            ob_start();
            QRcode::png($preContent, false, QR_ECLEVEL_H,16);
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }else{
            QRcode::png($preContent, false, QR_ECLEVEL_H, 16, 1);
        }
    }
}
