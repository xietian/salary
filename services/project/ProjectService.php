<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/12/31
 * Time: 14:16
 */
namespace app\services\project;


use app\models\Project;
use app\models\ProjectUser;
use app\tools\ErrorCode;
use app\tools\OutTools;

class ProjectService
{

    public function add($name, $no, $desc, $startDate, $endDate, $isExpatriated, $startUserId)
    {
        $projectRole = ProjectRoleService::getStartProjectRole();
        if (empty($projectRole) == false) {
            $project = new Project(['name' => $name, 'no' => $no, 'desc' => $desc, 'start_date' => $startDate, 'end_date' => $endDate, 'is_expatriated' => $isExpatriated]);
            if ($project->save()) {
                $projectUserService = new ProjectUserService();
                $projectUserService->add($project->id, $startUserId, $projectRole['role_id']);
                return OutTools::success(['project_id' => $project->id], \Yii::t('app', 'Success'));
            } else {
                return OutTools::error(ErrorCode::ERROR, \Yii::t('app', 'Failure'));
            }
        } else {
            return OutTools::error(ErrorCode::ERROR, \Yii::t('app', 'Config_Exception'));
        }
    }

    /**
     * @param $id
     * @param $name
     * @param $no
     * @param $desc
     * @param $startDate
     * @param $endDate
     * @param $isExpatriated
     * @param $startUserId
     * @return array
     */
    public function update($id, $name, $no, $desc, $startDate, $endDate, $isExpatriated, $startUserId)
    {
        $project = Project::findOne($id);
        if (empty($project) == false) {
            if (ProjectUserService::hasPower($id, $startUserId, 'deleted')) {
                $project->name = $name;
                $project->no = $no;
                $project->desc = $desc;
                $project->start_date = $startDate;
                $project->end_date = $endDate;
                $project->is_expatriated = $isExpatriated;
                if ($project->save()) {
                    return OutTools::success(['project_id' => $project->id], \Yii::t('app', 'Success'));
                } else {
                    return OutTools::error(ErrorCode::ERROR, \Yii::t('app', 'Failure'));
                }
            } else {
                return OutTools::error(ErrorCode::NO_POWER, \Yii::t('app', 'No_Power'));
            }
        }
    }

    /**
     * @param $id
     * @param $startUserId
     * @return array
     */
    public function delete($id, $startUserId)
    {
        $project = Project::findOne($id);
        if (empty($project) == false) {
            if (ProjectUserService::hasPower($id, $startUserId, 'deleted')) {
                $project->is_deleted = 1;
                if ($project->save()) {
                    return OutTools::success(['project_id' => $project->id], \Yii::t('app', 'Success'));
                } else {
                    return OutTools::error(ErrorCode::ERROR, \Yii::t('app', 'Failure'));
                }
            } else {
                return OutTools::error(ErrorCode::NO_POWER, \Yii::t('app', 'No_Power'));
            }
        }

    }

    /**
     * @param $userId
     * @param int $max
     * @param int $page
     * @return array
     */
    public function getListMyUserId($userId, $max = 10, $page = 1)
    {
        $offset = ($page - 1) * $max;
        $projectList = Project::find()->select("p.*, pu.role_id")->alias('p')->innerJoin("oa_project_user as pu", "pu.project_id = p.id")->where('pu.user_id=:user_id and p.is_finished=0 and p.is_deleted=0', array(':user_id' => $userId))->offset($offset)->limit($max)->asArray()->all();
        return OutTools::success(array('list' => FormatProjectList::format($projectList)), \Yii::t('app', 'Request_Success'));
    }

    /**
     * @param $projectId
     * @param $userId
     * @return array
     */
    public function getProjectDetail($projectId, $userId)
    {
        $project = Project::find()->select("p.*, pu.role_id")->alias('p')->innerJoin("oa_project_user as pu", "pu.project_id = p.id")->where('pu.user_id=:user_id and p.id=:project_id and p.is_deleted=0', array(':user_id' => $userId, ':project_id' => $projectId))->asArray()->one();
        return OutTools::success(array('project' => FormatProject::format($project)), \Yii::t('app', 'Request_Success'));
    }
}