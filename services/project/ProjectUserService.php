<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/12/31
 * Time: 14:28
 */
namespace app\services\project;


use app\format\user\FormatUserList;
use app\models\ProjectRole;
use app\models\ProjectUser;
use app\models\User;
use app\tools\OutTools;
use yii\helpers\ArrayHelper;

class ProjectUserService
{
    /**
     * 发起人
     * @param $projectId
     * @param $userId
     * @param $roleId
     * @return array
     */
    public function add($projectId, $userId, $roleId)
    {
        $projectUser = new ProjectUser([
            'project_id' => $projectId,
            'user_id' => $userId,
            'role_id' => $roleId
        ]);
        return $projectUser->save();
    }

    /**
     * @param $projectId
     * @param $userId
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getProjectUser($projectId, $userId)
    {
        return ProjectUser::find()->where('project_id=:project_id and user_id=:user_id', array(':project_id' => $projectId, ':user_id' => $userId))->asArray()->one();
    }

    /**
     * @param $projectId
     * @param $userId
     * @param $op == 'delete' // 'update'
     * @return array
     */
    public static function hasPower($projectId, $userId, $op = 'delete')
    {
        $projectUser = self::getProjectUser($projectId, $userId);
        $projectRole = ProjectRoleService::getStartProjectRole();
        return empty($projectRole) == false && empty($projectUser) == false && $projectUser['role_id'] == $projectRole['role_id'];
    }

    /**
     * 获取用户列表
     * @param $projectId
     * @return array
     */
    public function getProjectUserList($projectId)
    {
        $list = ProjectUser::find()->where("project_id=:project_id", array(':project_id' => $projectId))->asArray()->all();
        $retList = [];
        if (empty($list) == false) {
            $userIdList = ArrayHelper::getColumn($list, 'user_id');
            $userIds = join(",", $userIdList);
            $userList = User::find()->where("id in($userIds)")->asArray()->all();
            $retList = FormatUserList::format($userList, $list);
        }
        return OutTools::success(array('list' => $retList), \Yii::t('app', 'Request_Success'));
    }

}