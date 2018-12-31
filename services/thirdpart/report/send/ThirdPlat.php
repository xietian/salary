<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2017/12/8
 * Time: 14:14
 */

namespace app\services\thirdpart\report\send;


interface ThirdPlat
{
    public function sendReportToDoctor($phone, $doctorId, $filePath);

    public function unbind($phone, $doctorId);

    public function uploadReport($phone, $filePath, $recordCreateTime);

    //上传报告待报告编号
    public function uploadReportWithRecordId($phone, $filePath, $recordId, $recordCreateTime);
}