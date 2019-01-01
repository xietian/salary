<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2018/11/13
 * Time: 11:38
 */
namespace app\format\user;

use app\services\project\ProjectRoleService;
use yii\helpers\ArrayHelper;

class FormatUserList
{
    public static function format($dataList, $projectUserList)
    {
        $projectUserMap = ArrayHelper::map($projectUserList, 'user_id', 'role_id');
        $rolesMap = ProjectRoleService::getListMap();
        $newDataList = [];
        if (empty($dataList) == false) {
            foreach ($dataList as $key => $item) {
                $newDataList[$key] = FormatUser::format($item);
                $newDataList[$key]['role_id'] = $projectUserMap[$item['id']];
                $newDataList[$key]['role_name'] = $rolesMap[$newDataList[$key]['role_id']];
            }
        }
        return $newDataList;
    }

}