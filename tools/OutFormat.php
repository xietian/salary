<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/10/22
 * Time: 18:50
 */

namespace app\tools;


use app\models\Dept;
use app\models\Dictcontent;
use app\models\Factory;
use app\models\Sh;
use Yii;

class OutFormat
{
    /**
     * 显示性别
     * @param $gender
     * @return mixed
     */
    public static function formatSex($gender)
    {
        if (isset($gender) && $gender !== '') {
            $sexList = CommonFunc::getSexList();
            return $sexList[$gender];
        } else {
            return $gender;
        }
    }

    public static function formatImage($images)
    {
        if ($images == '') {
            $images = 'default.jpg';
        }
        $basePath = Yii::$app->getUrlManager()->getHostInfo() . Yii::$app->getUrlManager()->getBaseUrl() . Yii::$app->params['url_path'] . $images;
        return $basePath;
    }

    public static function formatDept($id)
    {
        $info = Dept::findOne($id);
        if (empty($info) == false) {
            return $info->dept_name;
        }
        return '';
    }




    public static function formatDate($dateStamp)
    {
        return date('Y-m-d H:i:s', $dateStamp);
    }

    public static function formatTimeLong($time)
    {
        return $time . 's';
    }

    public static function formatSize($b, $times = 0)
    {
        if ($b > 1024) {
            $temp = $b / 1024;
            return self::formatSize($temp, $times + 1);
        } else {
            $unit = 'B';
            switch ($times) {
                case '0':
                    $unit = 'B';
                    break;
                case '1':
                    $unit = 'KB';
                    break;
                case '2':
                    $unit = 'MB';
                    break;
                case '3':
                    $unit = 'GB';
                    break;
                case '4':
                    $unit = 'TB';
                    break;
                case '5':
                    $unit = 'PB';
                    break;
                case '6':
                    $unit = 'EB';
                    break;
                case '7':
                    $unit = 'ZB';
                    break;
                default:
                    $unit = '单位未知';
            }
            return sprintf('%.2f', $b) . $unit;
        }

    }

    /**
     *
     * @param $yesNo
     * @return mixed
     */
    public static function formatYesNo($yesNo)
    {
        if (isset($yesNo) && $yesNo !== '') {
            $list = CommonFunc::getYesNoList();
            return $list[$yesNo];
        } else {
            return $yesNo;
        }
    }

    /**
     * 展示字典数据
     * @param $code
     * @param $dictId
     * @return mixed
     */
    public static function formatDict($code, $dictId)
    {
        if (isset($code) && $code !== '' && empty($dictId) == false) {
            $info = Dictcontent::getDictContent($dictId, $code);
            if (empty($info) == false) {
                return $info['name'];
            }
        }
        return $code;
    }

}