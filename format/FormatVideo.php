<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2018/11/13
 * Time: 11:38
 */

namespace app\format;



class FormatVideo
{
    public static function format($data)
    {
        $newData = [];
        if (empty($data) == false) {
            $newData['video_suffix'] = $data['suffix'];
            $newData['video_thumb'] = $data['thumb'];
            $newData['video_url'] = $data['url'];
            $newData['video_date'] = date('Y.m.d H:i:s', $data['created_at']);
        }
        return $newData;
    }

}