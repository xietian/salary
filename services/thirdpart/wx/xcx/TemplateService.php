<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2018/12/3
 * Time: 13:49
 */

namespace app\services\thirdpart\wx\xcx;


use app\services\thirdpart\wx\wechat\Wechat;
use app\tools\curl\HttpTools;
use Yii;

class TemplateService
{
    /**
     * 发送模板消息
     * @param $openId
     * @param $formId
     * @param $reportContent
     * @param $reportDate
     * @param $userFullName
     * @param $recordId
     * @return mixed
     */
    public function sendReplyNotify($openId, $formId, $reportContent, $reportDate, $userFullName, $recordId)
    {
        $templateId = "r-6XvnS1EWZkzP4YRmgKOOA7rBpuNX3LNSPts6Z31mk";
        $data['keyword1']['value'] = mb_strlen($reportContent, 'utf-8') <= 45 ? $reportContent : mb_substr($reportContent, 0, 45, 'utf-8') . "...";
        $data['keyword2']['value'] = $reportDate;
        $data['keyword3']['value'] = "掌上心电，随时随地守护您的心~";
        return $this->sendTemplate($templateId, $openId, $formId, $data, "pages/reportDetail/reportDetail?login=login&recordId=$recordId");
    }

    /**
     * 发送模板消息
     * @param $templateId
     * @param $openId
     * @param $formId
     * @param $data
     * @param $page
     * @return mixed
     */
    public function sendTemplate($templateId, $openId, $formId, $data, $page)
    {
        $wechat = new Wechat(Yii::$app->params['config']['public']['mhealth_measure_lc']['appId'], Yii::$app->params['config']['public']['mhealth_measure_lc']['secret'], "xcx");
        $accessToken = $wechat->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=$accessToken";
        $data['touser'] = $openId;
        $data['template_id'] = $templateId;
        $data['page'] = $page;
        $data['form_id'] = $formId;
        $data['data'] = $data;
        return HttpTools::http_post($url, $data, true, true);
    }
}