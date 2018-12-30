<?php
namespace app\models\wxApi;

use app\tools\HttpTools;
use Yii;

class Wxclient extends Wxbase
{
    public function sendImage($openid, $media_id)
    {
        $access_token = $this->get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=%s";
        $data = <<<HTML
        {
            "touser":"$openid",
            "msgtype":"image",
            "image":
            {
                 "media_id":"$media_id"
            }
        }
HTML;

        $result = json_decode(HttpTools::https_post(sprintf($url, $access_token), $data), true);
        \Yii::error($result, 'yii\*');
        if (empty($result) == false && empty($result['errorcode'])) {
            return $result;
        } else {
            return false;
        }
    }

    public function sendVoice($openid, $media_id)
    {
        $access_token = $this->get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=%s";
        $data = <<<HTML
        {
            "touser":"$openid",
            "msgtype":"voice",
            "voice":
            {
                 "media_id":"$media_id"
            }
        }
HTML;
        $result = json_decode(HttpTools::https_post(sprintf($url, $access_token), $data), true);
        \Yii::error($result, 'yii\*');
        if (empty($result) == false && empty($result['errorcode'])) {
            return $result;
        } else {
            return false;
        }
    }

    public function sendVideo($openid, $media_id, $thumb_media_id, $title, $desc)
    {
        $access_token = $this->get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=%s";
        $data = <<<HTML
        {
            "touser":"$openid",
            "msgtype":"voice",
            "video":
            {
               "media_id":"$media_id",
               "thumb_media_id":"$thumb_media_id",
               "title":"$title",
               "description":"$desc"
            }
        }
HTML;
        $result = json_decode(HttpTools::https_post(sprintf($url, $access_token), $data), true);
        \Yii::error($result, 'yii\*');
        if (empty($result) == false && empty($result['errorcode'])) {
            return $result;
        } else {
            return false;
        }
    }

    public function sendText($openid, $content)
    {
        $access_token = $this->get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=%s";
        $data = <<<HTML
        {
            "touser":"$openid",
            "msgtype":"text",
            "text":
            {
                 "content":"$content"
            }
        }
HTML;
        $result = json_decode(HttpTools::https_post(sprintf($url, $access_token), $data), true);
        \Yii::error($result, 'yii\*');
        if (empty($result) == false && empty($result['errorcode'])) {
            return $result;
        } else {
            return false;
        }
    }

    public function sendOneNews($openid, $title, $desc, $linkUrl, $imgUrl)
    {
        $access_token = $this->get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=%s";
        $data = <<<HTML
        {
            "touser":"$openid",
            "msgtype":"news",
            "news":{
                "articles": [
                 {
                     "title":"$title",
                     "description":"$desc",
                     "url":"$linkUrl",
                     "picurl":"$imgUrl"
                 }
                 ]
            }
        }
HTML;
        $result = json_decode(HttpTools::https_post(sprintf($url, $access_token), $data), true);
        if (empty($result) == false && empty($result['errorcode'])) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 转多客服
     * @param $toUserName
     * @param $fromUserName
     * @param $dkf_account
     */
    public function transfer_to_dkf($toUserName, $fromUserName, $dkf_account)
    {

        $time = time();
        $accountList = explode(",", $dkf_account);
        $xmlAcc = "";

        foreach ($accountList as $acc) {
            $xmlAcc .= "<KfAccount>{$acc}</KfAccount>";
        }

        $xml = <<<EOT
<xml>
    <ToUserName><![CDATA[$toUserName]]></ToUserName>
    <FromUserName><![CDATA[$fromUserName]]></FromUserName>
    <CreateTime>$time</CreateTime>
    <MsgType><![CDATA[transfer_customer_service]]></MsgType>
    <TransInfo>
       $xmlAcc
    </TransInfo>
</xml>
EOT;
        echo $xml;
        die();


    }

    public function createWxGroup($name)
    {
        $access_token = $this->get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/groups/create?access_token=%s";
        $data = <<<HTML
        {
            "group":
            {
                 "name":"$name"
            }
        }
HTML;

        $result = json_decode(HttpTools::https_post(sprintf($url, $access_token), $data), true);
        \Yii::error($result, 'yii\*');
        if (empty($result) == false && empty($result['errcode'])) {
            return $result;
        } else {
            Yii::error(var_export($result, true), "yii\*");
            return false;
        }
    }


    public function deleteWxGroup($gid)
    {
        $access_token = $this->get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/groups/delete?access_token=%s";
        $data = <<<HTML
        {
            "group":
            {
                 "id":"$gid"
            }
        }
HTML;

        $result = json_decode(HttpTools::https_post(sprintf($url, $access_token), $data), true);
        \Yii::error($result, 'yii\*');
        if (empty($result) == false && empty($result['errcode'])) {
            return $result;
        } else {
            Yii::error(var_export($result, true), "yii\*");
            return false;
        }
    }


    public function selectWxGroup()
    {
        $access_token = $this->get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/groups/get?access_token=%s";
        $result = json_decode(HttpTools::http_get(sprintf($url, $access_token)), true);
        \Yii::error($result, 'yii\*');
        if (empty($result) == false && empty($result['errcode'])) {
            return $result;
        } else {
            Yii::error(var_export($result, true), "yii\*");
            return false;
        }
    }

    public function selectUserGroup($openid)
    {
        $access_token = $this->get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/groups/getid?access_token=%s";
        $data['openid'] = $openid;
        $result = json_decode(HttpTools::https_post(sprintf($url, $access_token), $data), true);
        \Yii::error($result, 'yii\*');
        if (empty($result) == false && empty($result['errcode'])) {
            return $result;
        } else {
            Yii::error(var_export($result, true), "yii\*");
            return false;
        }
    }

    public function upWxGroupName($gid, $name)
    {
        $access_token = $this->get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/groups/update?access_token=%s";
        $data = <<<HTML
        {
            "group":
            {
                 "id":$gid,
                 "name":"$name"
            }
        }
HTML;

        $result = json_decode(HttpTools::https_post(sprintf($url, $access_token), $data), true);
        \Yii::error($result, 'yii\*');
        if (empty($result) == false && empty($result['errcode'])) {
            return $result;
        } else {
            Yii::error(var_export($result, true), "yii\*");
            return false;
        }
    }


    public function moveUserGroup($openid, $gid)
    {
        $access_token = $this->get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=%s";
        $data = <<<HTML
        {
                 "openid":"$openid",
                 "to_groupid":$gid  
        }
HTML;

        $result = json_decode(HttpTools::https_post(sprintf($url, $access_token), $data), true);
        \Yii::error($result, 'yii\*');
        if (empty($result) == false && empty($result['errcode'])) {
            return $result;
        } else {
            Yii::error(var_export($result, true), "yii\*");
            return false;
        }
    }


    public function moveBatchUserGroup($openid, $gid)
    {
        $openid_list = json_encode($openid, true);
        $access_token = $this->get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/groups/members/batchupdate?access_token=%s";
        $data = <<<HTML
        {
                 "openid_list":$openid_list,
                 "to_groupid":$gid
        }
HTML;

        $result = json_decode(HttpTools::https_post(sprintf($url, $access_token), $data), true);
        \Yii::error($result, 'yii\*');
        if (empty($result) == false && empty($result['errcode'])) {
            return $result;
        } else {
            Yii::error(var_export($result, true), "yii\*");
            return false;
        }
    }


    public function createWxMenu($data)
    {
        $access_token = $this->get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/addconditional?access_token=%s";
        $data = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $result = json_decode(HttpTools::https_post(sprintf($url, $access_token), $data), true);
        \Yii::error($result, 'yii\*');
        if (empty($result) == false && empty($result['errcode'])) {
            return $result;
        } else {
            Yii::error(var_export($result, true), "yii\*");
            return false;
        }
    }


    public function deleteWxMenu($menuid)
    {
        $access_token = $this->get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/delconditional?access_token=%s";

        $data = <<<HTML
        {
                 "menuid":$menuid
        }
HTML;
        $result = json_decode(HttpTools::https_post(sprintf($url, $access_token), $data), true);
        \Yii::error($result, 'yii\*');
        if (empty($result) == false && empty($result['errcode'])) {
            return $result;
        } else {
            Yii::error(var_export($result, true), "yii\*");
            return false;
        }
    }

    /**
     * 测试个性化菜单匹配结果
     * 微信返回的是菜单列表
     */
    public function testWxMenuMatch($user)
    {
        $access_token = $this->get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/trymatch?access_token=%s";
        $data = <<<HTML
        {
            "user_id":"$user"
        }
HTML;
        $result = json_decode(HttpTools::https_post(sprintf($url, $access_token), $data), true);

        \Yii::error($result, 'yii\*');
        if (empty($result) == false && empty($result['errcode'])) {
            return $result;
        } else {
            Yii::error(var_export($result, true), "yii\*");
            return false;
        }
    }


    /**
     * 查询微信菜单
     */
    public function selectMenu()
    {
        $access_token = $this->get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=%s";
        $result = json_decode(HttpTools::http_get(sprintf($url, $access_token)), true);
        \Yii::error($result, 'yii\*');
        if (empty($result) == false && empty($result['errcode'])) {
            return $result;
        } else {
            Yii::error(var_export($result, true), "yii\*");
            return false;
        }
    }

    /**
     * 创建微信菜单
     * @param $data
     * @return mixed;
     */
    public function createWxDefaultMenu($data)
    {
        $access_token = $this->get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=%s";
        $data = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $result = json_decode(HttpTools::https_post(sprintf($url, $access_token), $data), true);
        \Yii::error($result, 'yii\*');
        if (empty($result) == false && empty($result['errcode'])) {
            return $result;
        } else {
            Yii::error(var_export($result, true), "yii\*");
            return false;
        }
    }

    public function deleteWxAllMenu()
    {
        $access_token = $this->get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=%s";
        $result = json_decode(HttpTools::http_get(sprintf($url, $access_token)), true);
        \Yii::error($result, 'yii\*');
        if (empty($result) == false && empty($result['errcode'])) {
            return $result;
        } else {
            Yii::error(var_export($result, true), "yii\*");
            return false;
        }
    }


    /**
     * 创建菜单的click和view菜单的数据处理
     * @param $data
     */
    public function menuDataHandle($data)
    {
        foreach ($data as $key => $value) {
            if (empty($dataNew) &&  $value['group_id'] != 0 ) {
                $dataNew['matchrule'] = array(
                    "group_id"=>$value['group_id'],
                    "sex"=>"",
                    "country"=>"",
                    "province"=>"",
                    "city"=>"",
                    "client_platform_type"=>"",
                    "language"=>""
                );
            }
            if ($value['p_menu_id'] == 0) {
                if ($value['menu_type'] == 'click') {
                   $arr = array();
                   $arr['type'] = $value['menu_type'];
                   $arr['name'] = $value['menu_name'];
                   $arr['key'] = $value['key'];
                   $dataNew['button'][] = $arr;
                }elseif ($value['menu_type'] == 'view') {
                    $arr = array();
                    $arr['type'] = $value['menu_type'];
                    $arr['name'] = $value['menu_name'];
                    $arr['url'] = $value['url'];
                    $dataNew['button'][] = $arr;
                }elseif ($value['menu_type'] == 'sub_button') {
                    $sub_button = array();
                    $sub_button['name'] = $value['menu_name'];
                    $sub_button['sub_button'] = array();
                    foreach ($data as $ke => $val) {
                        if ($val['p_menu_id'] == $value['menu_id']) {
                            if ($val['menu_type'] == 'click') {
                               $arr = array();
                               $arr['type'] = $val['menu_type'];
                               $arr['name'] = $val['menu_name'];
                               $arr['key'] = $val['key'];
                               $sub_button['sub_button'][] = $arr;
                            }elseif ($val['menu_type'] == 'view') {
                                $arr = array();
                                $arr['type'] = $val['menu_type'];
                                $arr['name'] = $val['menu_name'];
                                $arr['url'] = $val['url'];
                                $sub_button['sub_button'][] = $arr;
                            }
                        }
                    }
                    if (empty($sub_button['sub_button'])) {
                        return false;
                    }
                    $dataNew['button'][] = $sub_button;
                }
            }
        }
        if (empty($dataNew['button'])) {
            return false;
        }
        return  $dataNew;
    }
}