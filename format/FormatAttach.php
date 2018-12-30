<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2018/11/13
 * Time: 11:38
 */

namespace app\format;


use app\tools\CommonFunc;
use app\tools\OutFormat;

class FormatAttach
{
    public static function format($dataList)
    {
        $newDataList = [];
        if (empty($dataList) == false) {
            foreach ($dataList as $data) {
                $newData = [];
                $newData['attach_id'] = $data['id'];
                $newData['attach_name'] = $data['name'];
                $newData['attach_desc'] = $data['desc'];
                $newData['attach_suffix'] = $data['suffix'];
                $newData['attach_url'] = OutFormat::formatImage($data['url']);
                $newData['attach_date'] = date('Y.m.d H:i:s', $data['created_at']);
                $newDataList[] = $newData;
            }
        }
        return $newDataList;
    }

}