<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "oa_project".
 *
 * @property integer $id
 * @property string $no
 * @property string $name
 * @property string $desc
 * @property integer $saler_id
 * @property integer $customer_id
 * @property integer $is_expatriated
 * @property integer $is_finished
 * @property integer $start_date
 * @property integer $end_date
 */
class Project extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oa_project';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['no', 'name'], 'required'],
            [['saler_id', 'customer_id', 'is_expatriated', 'is_finished', 'start_date', 'end_date'], 'integer'],
            [['no'], 'string', 'max' => 30],
            [['name'], 'string', 'max' => 100],
            [['desc'], 'string', 'max' => 2000],
            [['no'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '编号'),
            'no' => Yii::t('app', '项目编号'),
            'name' => Yii::t('app', '项目名称'),
            'desc' => Yii::t('app', '项目描述'),
            'saler_id' => Yii::t('app', '销售人员'),
            'customer_id' => Yii::t('app', '客户'),
            'is_expatriated' => Yii::t('app', '是否外派'),
            'is_finished' => Yii::t('app', '是否结束'),
            'start_date' => Yii::t('app', '开始日期'),
            'end_date' => Yii::t('app', '结束日期'),
        ];
    }
}
