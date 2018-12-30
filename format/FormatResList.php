<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2018/11/13
 * Time: 11:38
 */

namespace app\format;


class FormatResList
{
    public static function format($dataArray)
    {
        $newDataArray = [];
        if (empty($dataArray) == false) {
            foreach ($dataArray as $rec) {
                $newData['res_id'] = $rec['id'];
                $newData['res_name'] = $rec['name'];
                $newData['res_desc'] = $rec['desc'];
                $newData['res_suffix'] = $rec['suffix'];
                $newData['res_thumb'] = $rec['thumb'];
                $newData['res_url'] = $rec['url'];
                $newData['res_date'] = date('Y.m.d H:i:s', $rec['created_at']);
                $newDataArray[] = $newData;
            }
        }
        return $newDataArray;
    }

}