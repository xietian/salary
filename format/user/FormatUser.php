<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2018/11/13
 * Time: 11:38
 */
namespace app\format\user;
use app\models\Dept;

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
            'dept_name' => '',
        ];
        if (empty($data['dept_id']) == false) {
            $dept = Dept::findOne($data['dept_id']);
            if (empty($dept) == false) {
                $newData['dept_name'] = $dept->dept_name;
                $newData['dept_id'] = $dept->dept_id;
            }
        }
        return $newData;
    }

}