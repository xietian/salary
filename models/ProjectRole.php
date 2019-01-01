<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%oa_project_role}}".
 *
 * @property integer $role_id
 * @property string $role_name
 * @property integer $role_no
 */
class ProjectRole extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%oa_project_role}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_name', 'role_no'], 'required'],
            [['role_no'], 'integer'],
            [['role_name'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'role_id' => Yii::t('app', '角色编号'),
            'role_name' => Yii::t('app', '角色名称'),
            'role_no' => Yii::t('app', '角色权限'),
        ];
    }
}
