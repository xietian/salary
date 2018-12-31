<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2017/12/8
 * Time: 14:18
 */

namespace app\services\thirdpart\report\send;


use app\models\organization\config\PlatConfig;
use app\tools\Constants;
use Yii;
use yii\base\ErrorException;

class ThirdPlatProxy
{
    /**
     * 获取处理类
     * @param $platId
     * @return mixed
     * @throws ErrorException
     */
    public static function getInstance($platId)
    {
        $platClass = PlatConfig::getPlatClass($platId);
        if (empty($platClass)) {
            throw new ErrorException(Yii::t('app', 'Not_Support_Plat', [$platId]), Constants::ERROR);
        }
        $classPath = dirname(__FILE__) . '/' . $platClass . '.php';
        if (file_exists($classPath)) {
            $platClass2 = '\app\services\thirdpart\report\send\\' . $platClass;
            return new $platClass2();
        } else {
            throw new ErrorException(Yii::t('app', 'Plat_Not_Defined', [$platClass]), Constants::ERROR);
        }
    }

    /**
     * @param $platId
     * @param $phone
     * @param $doctorId
     * @param $filePath
     * @return mixed array('code'=>200, 'msg'=>)
     * @throws ErrorException
     */
    public function sendReportToDoctor($platId, $phone, $doctorId, $filePath)
    {
        $method = 'sendReportToDoctor';
        $platClass = self::getInstance($platId);
        if (method_exists($platClass, $method)) {
            return call_user_func_array(array($platClass, $method), array($phone, $doctorId, $filePath));
        }
    }

    /**
     * //TODO:便于将来拓展
     * @param $platId
     * @param $phone
     * @param $doctorId
     * @param $filePath
     * @param $recordCreateTime //记录创建时间
     * @param $recordId //记录编号
     * @return mixed array('code'=>200, 'msg'=>)
     * @throws ErrorException
     */
    public function sendReportToDoctorWithRecordId($platId, $phone, $doctorId, $filePath, $recordCreateTime, $recordId)
    {
        $method = 'sendReportToDoctorWithRecordId';
        $platClass = self::getInstance($platId);
        if (method_exists($platClass, $method)) {
            return call_user_func_array(array($platClass, $method), array($phone, $doctorId, $filePath, $recordCreateTime, $recordId));
        }
    }

    /**
     * 同步上传报告关联用户
     *
     * @param $platId
     * @param $phone
     * @param $filePath
     * @param $recordCreateTime
     *
     * @return mixed
     */
    public function uploadReport($platId, $phone, $filePath, $recordCreateTime)
    {
        $method = 'uploadReport';
        $platClass = self::getInstance($platId);
        if (method_exists($platClass, $method)) {
            return call_user_func_array(array($platClass, $method), array($phone, $filePath, $recordCreateTime));
        }
    }

    /**
     * 同步上传报告关联用户
     *
     * @param $platId
     * @param $phone
     * @param $filePath
     * @param $recordCreateTime
     * @param $recordId
     *
     * @return mixed
     */
    public function uploadReportWithRecordId($platId, $phone, $filePath, $recordCreateTime, $recordId)
    {
        $method = 'uploadReportWithRecordId';
        $platClass = self::getInstance($platId);
        if (method_exists($platClass, $method)) {
            return call_user_func_array(array($platClass, $method), array($phone, $filePath, $recordId, $recordCreateTime));
        }
    }

    /**
     * 解绑患者
     * @param $platId
     * @param $phone
     * @param $doctorId
     * @return mixed
     * @throws ErrorException
     */
    public function unbind($platId, $phone, $doctorId)
    {
        $method = 'unbind';
        $platClass = self::getInstance($platId);
        if (method_exists($platClass, $method)) {
            return call_user_func_array(array($platClass, $method), array($phone, $doctorId));
        }
    }
}