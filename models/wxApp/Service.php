<?php
namespace app\models\wxApp;


use app\models\wxApi\Wxclient;
use app\models\wxApi\Wxuser;
use app\models\wxLib\WXBizMsgCrypt;
use app\modules\wx\services\PositionApiService;
use app\modules\wx\services\UsersApiService;
use app\tools\Constants;

class Service
{
    public function do_verification(Wx $wx)
    {
        $timestamp = time();
        if (empty($wx->signature) == false && $timestamp - 3600 < $wx->timestamp) {
            $tmpArr = array(
                $wx->token,
                $wx->timestamp,
                $wx->nonce);
            sort($tmpArr, SORT_STRING);
            $tmpStr = implode($tmpArr);
            $tmpStr = sha1($tmpStr);
            if ($tmpStr == $wx->signature) {
                echo $wx->echostr;
            } else {
                header("Content-type:text/html; charset=utf-8");
                echo "Validation Error!";
            }
            die();
        } else {
            header("Content-type:text/html; charset=utf-8");
            echo "Invalid Request!";
            die();
        }
    }

    public function do_post_xml(Wx $wx)
    {
        $content = file_get_contents('php://input');
        if (empty($content)) {
            header("Content-type:text/html; charset=utf-8");
            echo "Illegal Data!";
            die();
        }

        $encrypt_type = isset($_GET['encrypt_type']) ? $_GET['encrypt_type'] : "";
        $encoding = $encrypt_type == "aes" ? true : false;
        $data = $this->parse_xml_array($wx, $content, $encoding);

        \Yii::error("内容：" . var_export($data, true), "yii\*");
        //消息类型
        /******************************************************************************************/
        $MsgType = $data['MsgType'];
        if (in_array($MsgType, array(
            "text",
            "image",
            "voice",
            "video",
            "link",
            "location"))) {
            if ($MsgType == "location") {

            } else {
                if ($MsgType == 'text') {
                    $service = new UsersApiService();
                    $wxUserRes = $service->getWxUser($data["FromUserName"]);
                    if (Constants::isSuccess($wxUserRes)) {
                        $fromName = "来自（" . $wxUserRes['data']['nickname'] . ")";
                        if (empty($wxUserRes['data']['name']) == false) {
                            $fromName = "来自（" . $wxUserRes['data']['name'] . "," . $wxUserRes['data']['mobile'] . ") ";
                        }
                        $this->sendMsgToDkf($fromName . "反馈：" . $data['Content']);
                    }
                } else if ($MsgType == 'image') {
                    $service = new UsersApiService();
                    $wxUserRes = $service->getWxUser($data["FromUserName"]);
                    if (Constants::isSuccess($wxUserRes)) {
                        $fromName = "来自（" . $wxUserRes['data']['nickname'] . ")";
                        if (empty($wxUserRes['data']['name']) == false) {
                            $fromName = "来自（" . $wxUserRes['data']['name'] . ", " . $wxUserRes['data']['mobile'] . ") ";
                        }
                        $this->sendOneNewsToDkf($fromName, '', $data['PicUrl'], $data['PicUrl']);
                    }
                } else if ($MsgType == 'voice') {
                    $service = new UsersApiService();
                    $wxUserRes = $service->getWxUser($data["FromUserName"]);
                    if (Constants::isSuccess($wxUserRes)) {
                        $fromName = "来自（" . $wxUserRes['data']['nickname'] . ")";
                        if (empty($wxUserRes['data']['name']) == false) {
                            $fromName = "来自（" . $wxUserRes['data']['name'] . "," . $wxUserRes['data']['mobile'] . ") ";
                        }
                        $this->sendMsgToDkf($fromName . "反馈了一个音频消息");
                    }
                } else if ($MsgType == 'video' || $MsgType == 'shortvideo') {
                    $service = new UsersApiService();
                    $wxUserRes = $service->getWxUser($data["FromUserName"]);
                    if (Constants::isSuccess($wxUserRes)) {
                        $fromName = "来自（" . $wxUserRes['data']['nickname'] . ")";
                        if (empty($wxUserRes['data']['name']) == false) {
                            $fromName = "来自（" . $wxUserRes['data']['name'] . "," . $wxUserRes['data']['mobile'] . ") ";
                        }
                        $this->sendMsgToDkf($fromName . "反馈了一个视频消息");
                    }
                }
                $dkf_accounts = Wx::getDkfAccounts();
                if (empty($dkf_accounts) == false) {
                    //接受用户留言
                    $openId = $data["FromUserName"];
                    $wxId = $data['ToUserName'];
                    $wxClient = new Wxclient();
                    $wxClient->transfer_to_dkf($openId, $wxId, $dkf_accounts);
                }

            }
        } else {
            if ($MsgType == "event") {
                //Click
                if ($data["Event"] == "CLICK") {
                    $fromUserName = $data['FromUserName'];
                    $eventKey = $data['EventKey'];
                    switch ($eventKey) {

                    }
                } else if ($data["Event"] == 'VIEW') {

                } else if ($data["Event"] == 'LOCATION') {
                    //上报位置
                    $positionApiService = new PositionApiService();
                    $positionApiService->addPos($data['FromUserName'], $data['Latitude'], $data['Longitude'], $data['Precision'], $data['CreateTime']);
                } else if ($data["Event"] == "SCAN") {

                } else if ($data["Event"] == 'subscribe') {
                    //关注
                    \Yii::error("关注" . var_export($data, true), "yii\*");
                    $openId = $data["FromUserName"];
                    $wxUser = new Wxuser();
                    $userInfo = $wxUser->get_user_info($openId);

                    if (empty($userInfo) == false) {
                        $userApiService = new UsersApiService();
                        $addUserRes = $userApiService->addWxUser($userInfo['sex'], $userInfo['nickname'], $userInfo['headimgurl'], $userInfo['city'], $userInfo['province'], $userInfo['country'], $openId, Wx::getAppId(), $userInfo['subscribe_time']);
                        \Yii::error("用户信息" . var_export($userInfo, true), "yii\*");
                        \Yii::error("添加结果" . var_export($addUserRes, true), "yii\*");
                    } else {
                        \Yii::error("用户信息" . var_export($userInfo, true), "yii\*");
                    }
                } else if ($data["Event"] == 'unsubscribe') {
                    //取消关注
                    \Yii::error("取消关注" . var_export($data, true), "yii\*");
                    $openId = $data["FromUserName"];
                    $userApiService = new UsersApiService();
                    $userApiService->unSubscribe($openId);
                } else {

                }
            }
        }
        die();
    }

    /**
     * 发送消息给客服的微信账号
     * @param $msg
     */
    public function sendMsgToDkf($msg)
    {
        $wxClient = new Wxclient();
        $openIds = Wx::getDkfOpenIds();
        if (empty($openIds) == false) {
            $openIdArray = explode(",", $openIds);
            foreach ($openIdArray as $openId) {
                $wxClient->sendText($openId, $msg);
            }
        }
    }

    /**
     * 发送给制定openid
     * @param $openId
     * @param $msg
     */
    public function sendMsgOpenId($openId, $msg)
    {
        $wxClient = new Wxclient();
        $wxClient->sendText($openId, $msg);
    }

    public function sendImageToDkf($mageId)
    {
        $wxClient = new Wxclient();
        $openIds = Wx::getDkfOpenIds();
        if (empty($openIds) == false) {
            $openIdArray = explode(",", $openIds);
            foreach ($openIdArray as $openId) {
                $wxClient->sendImage($openId, $mageId);
            }
        }
    }

    public function sendVoiceToDkf($mediaId)
    {
        $wxClient = new Wxclient();
        $openIds = Wx::getDkfOpenIds();
        if (empty($openIds) == false) {
            $openIdArray = explode(",", $openIds);
            foreach ($openIdArray as $openId) {
                $wxClient->sendVoice($openId, $mediaId);
            }
        }
    }

    public function sendVideoToDkf($mediaId, $thumbMediaId, $title, $desc)
    {
        $wxClient = new Wxclient();
        $openIds = Wx::getDkfOpenIds();
        if (empty($openIds) == false) {
            $openIdArray = explode(",", $openIds);
            foreach ($openIdArray as $openId) {
                $wxClient->sendVideo($openId, $mediaId, $thumbMediaId, $title, $desc);
            }
        }
    }

    public function sendOneNewsToDkf($title, $desc, $url, $imgUrl)
    {
        $wxClient = new Wxclient();
        $openIds = Wx::getDkfOpenIds();
        if (empty($openIds) == false) {
            $openIdArray = explode(",", $openIds);
            foreach ($openIdArray as $openId) {
                $wxClient->sendOneNews($openId, $title, $desc, $url, $imgUrl);
            }
        }
    }

    /**
     * @param $wx
     * @param $content
     * @param bool $encoding
     * @return mixed
     */
    private function parse_xml_array(Wx $wx, $content, $encoding = false)
    {

        if ($encoding == true) {
            $timestamp = $_REQUEST['timestamp'];
            $nonce = $_REQUEST['nonce'];
            $msg_signature = $_REQUEST['msg_signature'];
            $newContent = "";
            //查询微信账号
            $xml = new DOMDocument();
            $xml->loadXML($content);
            $wxid = $xml->getElementsByTagName('ToUserName')->item(0)->nodeValue;
            //查询APPID
            $appid = $wx->app_id;
            $token = $wx->token;
            $encoding_aes_key = $wx->encodingAesKey;
            $obj = new WXBizMsgCrypt($token, $encoding_aes_key, $appid);
            $errCode = $obj->decryptMsg($msg_signature, $timestamp, $nonce, $content,
                $newContent);
            if ($errCode < 0) {
                header("Content-type:text/html; charset=utf-8");
                echo "Decrypt Error!";
                die();
            }
            $content = $newContent;
        }

        $data = $this->xmlToArray($content);
        if (empty($data)) {
            header("Content-type:text/html; charset=utf-8");
            echo "XML Parse Error!";
            die();
        }
        return $data;
    }

    function xmlToArray($xml)
    {
        $array_data = json_decode(json_encode(simplexml_load_string($xml,
            'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }


    /* 发送回复消息到微信平台 */
    protected function replyTextData($data, $contentStr)
    {
        $fromUsername = $data['FromUserName'];
        $toUsername = $data['ToUserName'];
        $time = time();
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[text]]></MsgType>
					<Content>%s</Content>
					</xml>";
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $contentStr);
        echo $resultStr;
    }

    protected function replyNewData($data, $articles)   //单图文回复
    {
        $fromUsername = $data['FromUserName'];
        $toUsername = $data['ToUserName'];
        $time = time();
        $title = $articles['title'];
        $article = $articles['articles'];
        $picurl = $articles['picurl'];
        $url = $articles['url'];
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[news]]></MsgType>
                    <ArticleCount>1</ArticleCount>
                    <Articles>
                    <item>
                    <Title><![CDATA[%s]]></Title>
                    <Description><![CDATA[%s]]></Description>
                    <PicUrl><![CDATA[%s]]></PicUrl>
                    <Url><![CDATA[%s]]></Url>
                    </item>
                    </Articles>
                    </xml> ";
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $title, $article, $picurl, $url);
        echo $resultStr;
    }

    protected function replyNewsData($data, $articles) //多图文回复
    {
        $fromUsername = $data['FromUserName'];
        $toUsername = $data['ToUserName'];
        $time = time();
        $title = $articles['title'];
        $article = $articles['articles'];
        $picurl = $articles['picurl'];
        $url = $articles['url'];
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[news]]></MsgType>
                    <ArticleCount>2</ArticleCount>
                    <Articles>
                    <item>
                    <Title><![CDATA[%s]]></Title>
                    <Description><![CDATA[%s]]></Description>
                    <PicUrl><![CDATA[%s]]></PicUrl>
                    <Url><![CDATA[%s]]></Url>
                    </item>
                    <item>
                    <Title><![CDATA[%s]]></Title>
                    <Description><![CDATA[%s]]></Description>
                    <PicUrl><![CDATA[%s]]></PicUrl>
                    <Url><![CDATA[%s]]></Url>
                    </item>
                    </Articles>
                    </xml> ";
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $articles[0]['title'], $articles[0]['articles'], $articles[0]['picurl'], $articles[0]['url'], $articles[1]['title'], $articles[1]['articles'], $articles[1]['picurl'], $articles[1]['url']);
        echo $resultStr;
    }
}