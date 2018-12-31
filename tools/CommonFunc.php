<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/10/11
 * Time: 14:51
 */

namespace app\tools;


use app\models\Factory;
use app\models\Project;
use app\models\ProjectRole;
use app\models\User;
use moonland\phpexcel\Excel;
use Yii;
use yii\helpers\ArrayHelper;
use yii\rbac\Role;

class CommonFunc
{
    public static function getSexList()
    {
        return [0 => '女', 1 => '男'];
    }


    public static function getYesNoList()
    {
        return [0 => '否', 1 => '是'];
    }

    public static function getUser($userId){
       return  empty($userId)?"":User::findOne($userId)->name;
    }

    /**
     * 获取项目列表
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getProjectList()
    {
        return Project::find()->where('is_finished=0')->asArray()->all();
    }


    /**
     * 获取项目map
     * @return array
     */
    public static function getProjectListMap()
    {
        $list = self::getProjectList();
        return ArrayHelper::map($list, 'id', 'name');
    }


    /**
     * 获取项目列表
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getProjectRoleList()
    {
        return ProjectRole::find()->asArray()->all();
    }


    /**
     * 获取项目map
     * @return array
     */
    public static function getProjectRoleListMap()
    {
        $list = self::getProjectRoleList();
        return ArrayHelper::map($list, 'role_id', 'role_name');
    }

    /**
     * 特殊处理
     * @param $cls
     * @param $exceptLabels
     * @return array
     */
    public static function resolveColumns($cls, $exceptLabels)
    {
        $columns = [
            'gender' =>
                [
                    'attribute' => 'gender',
                    'value' => function ($model) {
                        return \app\tools\OutFormat::formatSex($model->gender);
                    },
                ],

            'community' => [
                'attribute' => 'community',
                'value' => function ($model) {
                    return \app\tools\OutFormat::formatDict($model->community, \app\tools\Constants::DICT_CONTENT_SQ);
                },
            ],

            'is_latest' => [
                'attribute' => 'is_latest',
                'value' => function ($model) {
                    return \app\tools\OutFormat::formatYesNo($model->is_latest);
                },
            ],
            'is_force' => [
                'attribute' => 'isNPC',
                'value' => function ($model) {
                    return \app\tools\OutFormat::formatYesNo($model->is_force);
                },
            ],
        ];
        $newCls = [];
        foreach ($cls as $attributeKey) {
            if ($attributeKey == 'id') {
                continue;
            }
            if (isset($exceptLabels[$attributeKey])) {
                continue;
            }
            if (isset($columns[$attributeKey])) {
                $newCls[] = $columns[$attributeKey];
            } else {
                $newCls[] = $attributeKey;
            }
        }
        return $newCls;

    }

    public static function resolveHeaders(&$headers, $exceptHeaders)
    {
        if (empty($exceptHeaders) == false) {
            foreach ($exceptHeaders as $key => $value) {
                unset($headers[$key]);
            }
        }
    }

    /**
     * 导出excel
     * @param $searchModel
     * @param $fileName
     * @param $exceptLabels
     * @return string
     */
    public static function exportSearch($searchModel, $fileName, $exceptLabels = [])
    {
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 3000; //最大支持导出3000个用户数据
        $columnLabelMap = $searchModel->attributeLabels();
        $columnLabels = array_keys($columnLabelMap);
        $columns = self::resolveColumns($columnLabels, $exceptLabels);
        $data = $dataProvider->getModels();
        $labels = $searchModel->attributeLabels();
        self::resolveHeaders($labels, $exceptLabels);
        return Excel::widget([
            'models' => $data,
            'mode' => 'export',
            'format' => 'Excel5',
            'columns' => $columns,
            'headers' => $labels,
            'fileName' => $fileName
        ]);
    }
}