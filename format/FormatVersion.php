<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2018/11/13
 * Time: 11:38
 */

namespace app\format;


use app\tools\OutFormat;

class FormatVersion
{
    /**
     * @param $data
     * @return array
     */
    public static function format($data)
    {
        $newData = [];
        if (empty($data) == false) {
            $newData = [
                'version_id' => $data['id'],
                'version_code' => $data['version_code'],
                'version_content' => $data['version_content'],
                'version_url' => OutFormat::formatImage($data['download_url']),
                'file_size' => $data['file_size'],
                'is_force' => $data['is_force'],
            ];
        }
        return $newData;
    }

}