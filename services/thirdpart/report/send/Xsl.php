<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2017/11/29
 * Time: 15:18
 */

namespace app\services\thirdpart\report\send;


use app\services\thirdpart\ApiService;
use app\tools\Constants;
use app\tools\OutTools;
use Yii;
use yii\base\ErrorException;

class Xsl extends ThirdPlatBase
{
    private $serverUrl = 'http://medchart.xingshulin.com/new-cloudfollowup-service/mhealth/upload/';
    private $unbindUrl = 'http://medchart.xingshulin.com/new-cloudfollowup-service/MhealthXSLController/mHealthCallUnbindPatientAndDoctor';


    /**
     * 发送报告给
     * @param $doctorId
     * @param $phone
     * @param $filePath
     * @return mixed
     */
    public function sendReportToDoctor($phone, $doctorId, $filePath)
    {
        try {
            $param = [
                'doctorAccount' => $doctorId,
                'patientPhone' => $phone,
                'filedata' => '@' . $filePath
            ];
            $res = ApiService::post($this->serverUrl, $param);
            if ($res['success']) {
                return OutTools::success(array(), Yii::t('app', 'Send_Success'));
            } else {
                Yii::error('param:' . var_export($param, true), 'yii.error.sendReportParam');
                Yii::error('res:' . var_export($res, true), 'yii.error.sendReportRes');
                return OutTools::error(Constants::ERROR, isset($res['message']) ? $res['message'] : '发送失败');
            }
        } catch (ErrorException $e) {
            Yii::error($e->getMessage(), 'yii.error.sendReportToDoctor.Message');
            Yii::error($e->getTraceAsString(), 'yii.error.sendReportToDoctor.Trace');
            return OutTools::error(Constants::ERROR, Yii::t('app', 'ErrorWithCode', [Constants::CURL_EXCEPTION]));
        }

    }

    /**
     * 解绑杏树林医生
     *
     * @param string $phone 号码
     * @param string $doctorId 杏树林医生ID
     *
     * @return array
     */
    public function unbind($phone, $doctorId)
    {

        try {
            $param = [
                'doctorAccount' => $doctorId,
                'patientPhone' => $phone
            ];
            $res = ApiService::get($this->unbindUrl, $param);
            if (isset($res['result']) && $res['result']) {
                return OutTools::success(array(), Yii::t('app', 'Send_Success'));
            } else {
                Yii::error('调用杏树林接口解绑医生失败，返回结果为：' . var_export($res, true), 'yii\error\unbind');
                return OutTools::error(Constants::ERROR, $res['message']);
            }
        } catch (ErrorException $e) {
            return OutTools::error(Constants::ERROR, Yii::t('app', 'ErrorWithCode', [Constants::CURL_EXCEPTION]));
        }


    }
}
