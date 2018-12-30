<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2018/11/13
 * Time: 11:38
 */

namespace app\format;


class FormatDevice
{
    public static function format($data)
    {
        $newData = [];
        if (empty($data) == false) {
            $newData['device_id'] = $data['id'];
            $newData['device_name'] = $data['name'];
            $newData['device_desc'] = $data['desc'];
        }
        return $newData;
    }

}