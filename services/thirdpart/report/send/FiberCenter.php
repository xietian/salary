<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/11 0011
 * Time: 17:19
 */

namespace app\services\thirdpart\report\send;


use app\services\thirdpart\ApiService;
use app\tools\Constants;
use app\tools\OutTools;
use Yii;

class FiberCenter extends ThirdPlatBase
{

    private $apiUrl = 'http://192.168.4.8:8080/mtms/heartInterface/getUserPic';

    /**
     * 上传报告
     *
     * @param int $phone 手机号
     * @param string $filePath 报告地址
     * @param bigint $recordId 报告编号
     * @param string $recordCreateTime 报告创时间
     *
     * @return bool
     */
    public function uploadReportWithRecordId($phone, $filePath, $recordId, $recordCreateTime)
    {
        $param = [
            'phone' => $phone,
            'file' => "@".$filePath,
            'recordId' => $recordId,
            'time' => $recordCreateTime
        ];
//        return OutTools::success(array(), '');
        $res = ApiService::post($this->apiUrl, $param);
        if (!OutTools::isSuccess($res)) {
            Yii::error('接口调用失败：' . var_export($res, true), 'yii\error\uploadReportWithRecordId');
            if ($res) {
                return $res;
            } else {
                return OutTools::error(Constants::ERROR, Yii::t('app', 'ErrorWithCode', [Constants::CODE_EXCEPTION]));
            }
        } else {
            return $res;
        }
    }

    public function unbind($phone, $doctorId)
    {
    }

}
