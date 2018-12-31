<?php
namespace app\services\thirdpart\wx\wechat;

use Yii;

/**
 *
 * */
class WechatReceive
{

    /**
     * 消息类型
     * */
    const MSG_RESPONSE_SUB = 1;//关注
    const MSG_RESPONSE_TEXT = 2;//回复指定文本消息
    const MSG_RESPONSE_OTHER = 3;//回复其它文本消息
    const MSG_SCAN_SUB = 4;//已关注

    const DEFAULT_SUB_TITLE = '欢迎关注我们,尽请期待！';
    const DEFAULT_CONTACT_TITLE = '您好，请拨打400 887 1788';

    public static function xmlToArray($xml)
    {
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

    public static function responseMsg($data)
    {

        $data = json_decode(json_encode(simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        if (!is_array($data)) {
            return false;
        }
        $RX_TYPE = trim($data['MsgType']);
        $event = isset($data['Event']) ? trim($data['Event']) : "";
        if ($RX_TYPE == "event" && $event == "subscribe") {
            return array('FromUserName' => $data['FromUserName'], 'ToUserName' => $data['ToUserName'], 'msgType' => self::MSG_RESPONSE_SUB, 'data' => $data['EventKey']);
        } elseif ($RX_TYPE == "event" && $event = "SCAN") {
            return array('FromUserName' => $data['FromUserName'], 'ToUserName' => $data['ToUserName'], 'msgType' => self::MSG_SCAN_SUB, 'data' => $data['EventKey']);
        } elseif ($RX_TYPE == "event" && $event == "CLICK") {
            return array('FromUserName' => $data['FromUserName'], 'ToUserName' => $data['ToUserName'], 'msgType' => self::MSG_RESPONSE_SUB, 'data' => $data['EventKey']);
        } elseif ($RX_TYPE == "text" && strtolower($data['Content']) == "qr") {
            return array('FromUserName' => $data['FromUserName'], 'ToUserName' => $data['ToUserName'], 'msgType' => self::MSG_RESPONSE_TEXT, 'data' => $data['Content']);
        } elseif ($RX_TYPE == "text") {
            return array('FromUserName' => $data['FromUserName'], 'ToUserName' => $data['ToUserName'], 'msgType' => self::MSG_RESPONSE_OTHER, 'data' => $data['Content']);
        }
        return false;
    }

    //回复文本消息
    public static function transmitText($fromToInfo, $content)
    {
        if (!isset($content) || empty($content)) {
            return "";
        }
        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[text]]></MsgType>
    <Content><![CDATA[%s]]></Content>
</xml>";
        $result = sprintf($xmlTpl, $fromToInfo['FromUserName'], $fromToInfo['ToUserName'], time(), $content);

        return $result;
    }

    //回复图文消息
    public static function transmitNews($fromToInfo, $newsArray)
    {
        if (!is_array($newsArray)) {
            return "";
        }
        $itemTpl = "        <item>
            <Title><![CDATA[%s]]></Title>
            <Description><![CDATA[%s]]></Description>
            <PicUrl><![CDATA[%s]]></PicUrl>
            <Url><![CDATA[%s]]></Url>
        </item>
";
        $item_str = "";
        foreach ($newsArray as $item) {
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        }
        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[news]]></MsgType>
    <ArticleCount>%s</ArticleCount>
    <Articles>
$item_str    </Articles>
</xml>";

        $result = sprintf($xmlTpl, $fromToInfo['FromUserName'], $fromToInfo['ToUserName'], time(), count($newsArray));
        return $result;
    }

    //回复音乐消息
    public static function transmitMusic($fromToInfo, $musicArray)
    {
        if (!is_array($musicArray)) {
            return "";
        }
        $itemTpl = "<Music>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <MusicUrl><![CDATA[%s]]></MusicUrl>
        <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
    </Music>";

        $item_str = sprintf($itemTpl, $musicArray['Title'], $musicArray['Description'], $musicArray['MusicUrl'], $musicArray['HQMusicUrl']);

        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[music]]></MsgType>
    $item_str
</xml>";

        $result = sprintf($xmlTpl, $fromToInfo['FromUserName'], $fromToInfo['ToUserName'], time());
        return $result;
    }

    //回复图片消息
    public static function transmitImage($fromToInfo, $imageArray)
    {
        $itemTpl = "<Image>
        <MediaId><![CDATA[%s]]></MediaId>
    </Image>";

        $item_str = sprintf($itemTpl, $imageArray['MediaId']);

        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[image]]></MsgType>
    $item_str
</xml>";

        $result = sprintf($xmlTpl, $fromToInfo['FromUserName'], $fromToInfo['ToUserName'], time());
        return $result;
    }

    //回复语音消息
    public static function transmitVoice($fromToInfo, $voiceArray)
    {
        $itemTpl = "<Voice>
        <MediaId><![CDATA[%s]]></MediaId>
    </Voice>";

        $item_str = sprintf($itemTpl, $voiceArray['MediaId']);
        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[voice]]></MsgType>
    $item_str
</xml>";

        $result = sprintf($xmlTpl, $fromToInfo['FromUserName'], $fromToInfo['ToUserName'], time());
        return $result;
    }

    //回复视频消息
    public static function transmitVideo($fromToInfo, $videoArray)
    {
        $itemTpl = "<Video>
        <MediaId><![CDATA[%s]]></MediaId>
        <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
    </Video>";

        $item_str = sprintf($itemTpl, $videoArray['MediaId'], $videoArray['ThumbMediaId'], $videoArray['Title'], $videoArray['Description']);

        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[video]]></MsgType>
    $item_str
</xml>";
        $result = sprintf($xmlTpl, $fromToInfo['FromUserName'], $fromToInfo['ToUserName'], time());
        return $result;
    }
}
