<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/1/1
 * Time: 10:18
 */

namespace app\services\project;


use app\models\ProjectRole;
use yii\helpers\ArrayHelper;

class ProjectRoleService
{
    /**
     * 获取角色列表
     * @return array
     */
    public static function getListMap()
    {
        $list = ProjectRole::find()->select("*")->asArray()->all();
        return ArrayHelper::map($list, 'role_id', 'role_name');
    }

    /**
     * 发起用户的角色
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getStartProjectRole()
    {
        return ProjectRole::find()->where('is_start = :is_start', array(':is_start' => 1))->asArray()->one();
    }
}