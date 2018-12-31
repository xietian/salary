<?php
/**
 * 模板消息
 * User: andy
 * Date: 2018/5/3
 * Time: 13:26
 */

namespace app\services\thirdpart\wx\wechat;


use app\tools\Constants;
use app\tools\curl\HttpTools;
use app\tools\OutTools;
use Yii;

class WechatTemplateSms
{
    /**
     * @param $product doctor/patient
     * @param $toUser
     * @param $templateId
     * @param $url
     * @param $first
     * @param $param [[value:,color:]]
     * @param $remark
     * @param string $accessToken
     * @return mixed
     */
    public static function sendTemplateInfo($product, $data, $accessToken = '')
    {
        try {

            $accessToken = WechatConfig::getAccessToken($product, $accessToken);
            // $data = self::formatTemplateInfo($toUser, $templateId, $url, $first, $param, $remark);
            $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $accessToken;
            $str = HttpTools::http_post($url, $data);
            $res = json_decode($str, true);
            if (isset($res['errcode']) && $res['errcode']) {
                return OutTools::error(Constants::ERROR, $res['errmsg']);
            } else {
                return OutTools::success($res, '');
            }
        } catch (\Exception $e) {
            Yii::error($e->getMessage(), 'yii\wx\sendTemplateInfo');
            return OutTools::error(Constants::ERROR, Yii::t('app', 'ErrorWithCode', [Constants::CODE_EXCEPTION]));
        }

    }

    /**
     * @param $toUser
     * @param $templateId
     * @param $url
     * @param $first
     * @param $param [[value:,color:]]
     * @param $remark
     * @return array
     */
    public static function formatTemplateInfo($toUser, $templateId, $url, $first, $param, $remark)
    {

        $data = [];
        $data['touser'] = $toUser;
        $data['template_id'] = $templateId;
        $data['url'] = $url;
        $data['data']['first'] = array('value' => $first,
            'color' => "");
        foreach ($param as $index => $item) {
            $color = isset($item['color']) ? $item['color'] : "#173177";
            $val = isset($item['value']) ? $item['value'] : "";
            $key = "keyword" . ($index + 1);
            $data['data'][$key] = array('value' => $val,
                'color' => $color);
        }
        $data['data']['remark'] = array('value' => $remark,
            'color' => "");
        return json_encode($data);
    }

	/**
	 * 批量发送模版消息
	 *
	 * @param $product
	 * @param $toUsers
	 * @param $templateId
	 * @param $url
	 * @param $first
	 * @param $param
	 * @param $remark
	 * @param string $accessToken
	 *
	 * @return array
	 */
	public static function batchSendTemplateMsg($product, $toUsers, $templateId, $url, $first, $param, $remark, $accessToken = '')
	{
		try {
			$accessToken = WechatConfig::getAccessToken($product, $accessToken);
			$result = array();
			foreach ($toUsers as $toUser) {
				$data = self::formatTemplateInfo($toUser, $templateId, $url, $first, $param, $remark);
				$fp = fsockopen('api.weixin.qq.com', 80, $error, $errstr, 1);
				$http = "POST /cgi-bin/message/template/send?access_token={$accessToken} HTTP/1.1\r\nHost: api.weixin.qq.com\r\nContent-type: application/x-www-form-urlencoded\r\nContent-Length: " . strlen($data) . "\r\nConnection:close\r\n\r\n$data\r\n\r\n";
				fwrite($fp, $http);
				$res = '';
				while (!feof($fp)) {
					$res .= fgets($fp, 128);
				}
				fclose($fp);

				preg_match('/(\{.*?\})/is', $res, $match);
				if (!empty($match)) {
					$resultJson = json_decode($match[0]);
					if ($resultJson->errcode !== 0) {
						$result[] = array(
							'to_user_openid' => $toUser,
							'result' => $resultJson
						);
					}
				}
			}

			if (!empty($result)) {
				return OutTools::errorWithData(Constants::ERROR, '失败返回', $result);
			}

			return OutTools::success(null, '');
		} catch (\Exception $e) {
			Yii::error($e->getMessage(), 'yii\wx\batchSendTemplateMsg');
			return OutTools::error(Constants::ERROR, Yii::t('app', 'ErrorWithCode', [Constants::CODE_EXCEPTION]));
		}
	}

}