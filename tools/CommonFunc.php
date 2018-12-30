<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/10/11
 * Time: 14:51
 */

namespace app\tools;


use app\models\Factory;
use moonland\phpexcel\Excel;
use Yii;

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

    /**
     * 获取工厂列表
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getFactoryList()
    {
        return Factory::find()
            ->select(['name as value', 'name as  label', 'id as id'])->asArray()->all();
    }

    /**
     * 获取工厂map
     * @return array
     */
    public static function getFactoryListMap()
    {
        $list = self::getFactoryList();
        $map = [];
        if (empty($list) == false) {
            foreach ($list as $rec) {
                $map[$rec['id']] = $rec['label'];
            }
        }
        return $map;
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