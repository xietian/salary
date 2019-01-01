<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/1/1
 * Time: 10:16
 */

namespace app\format\project;


use app\services\project\ProjectRoleService;

class FormatProject
{
    /**
     * 获取项目详情
     * @param $data
     * @return array
     */
    public static function format($data)
    {
        if (empty($data) == false) {
            $rolesMap = ProjectRoleService::getListMap();
            $data['role_name'] = $rolesMap[$data['role_id']];
            $data['start_date_show'] = empty($data['start_date']) ? '' : date('Y-m-d', $data['start_date']);
            $data['end_date_show'] = empty($data['end_date']) ? '' : date('Y-m-d', $data['end_date']);
            $data['is_expatriated_show'] = empty($data['is_expatriated']) ? '否' : '是';
        }
        return $data;
    }
}