<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2018/11/13
 * Time: 11:38
 */

namespace app\format;


use app\models\Factory;

class FormatUser
{
    public static function format($data)
    {
        $newData = [
            'id' => $data['id'],
            'name' => $data['name'],
            'username' => $data['username'],
            'sex' => $data['sex'],
            'dept_id' => 0,
            'factory_name' => '',
        ];
        if (empty($data['dept_id'])) {
            $factory = Factory::findOne($data['dept_id']);
            if (empty($factory) == false) {
                $newData['factory_name'] = $factory->name;
                $newData['dept_id'] = $factory->id;
            }
        }
        return $newData;
    }

}