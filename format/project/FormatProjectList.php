<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/1/1
 * Time: 10:16
 */

namespace app\format\project;


use app\services\project\ProjectRoleService;

class FormatProjectList
{
    /**
     * 获取项目列表
     * @param $dataList
     * @return array
     */
    public static function format($dataList)
    {
        $rolesMap = ProjectRoleService::getListMap();
        $newList = [];
        if (empty($dataList) == false) {
            foreach ($dataList as $key => $value) {
                $newList[$key] = $value;
                $newList[$key]['role_name'] = $rolesMap[$value['role_id']];
            }
        }
        return $newList;
    }
}