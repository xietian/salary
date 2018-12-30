<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2018/11/13
 * Time: 11:38
 */

namespace app\format;


class FormatDeviceList
{
    public static function format($dataArray)
    {
        $newDataArray = [];
        if (empty($dataArray) == false) {
            foreach ($dataArray as $rec) {
                $newData['device'] = FormatDevice::format($rec);
                $newData['video'] = FormatVideo::format($rec);
                $newDataArray[] = $newData;
            }
        }
        return $newDataArray;
    }

}