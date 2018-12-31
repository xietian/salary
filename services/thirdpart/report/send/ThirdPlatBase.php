<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2017/12/8
 * Time: 14:14
 */

namespace app\services\thirdpart\report\send;


use app\tools\Constants;
use ErrorException;
use Yii;

class ThirdPlatBase implements ThirdPlat
{
    public function sendReportToDoctor($phone, $doctorId, $filePath)
    {
        throw new ErrorException(Yii::t('app', 'Not_Support_Method', [get_called_class(), 'sendReportToDoctor']), Constants::ERROR);
    }

    public function unbind($phone, $doctorId)
    {
        throw new ErrorException(Yii::t('app', 'Not_Support_Method', [get_called_class(), 'unbind']), Constants::ERROR);
    }

    public function uploadReport($phone, $filePath, $recordCreateTime)
    {
        throw new ErrorException(Yii::t('app', 'Not_Support_Method', [get_called_class(), 'uploadReport']), Constants::ERROR);
    }

    public function uploadReportWithRecordId($phone, $filePath, $recordId, $recordCreateTime)
    {
        throw new ErrorException(Yii::t('app', 'Not_Support_Method', [get_called_class(), 'uploadReportWithRecordId']), Constants::ERROR);
    }
}