<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%oa_project_user}}".
 *
 * @property integer $id
 * @property integer $project_id
 * @property integer $user_id
 * @property integer $role_id
 */
class ProjectUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%oa_project_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id', 'user_id', 'role_id'], 'required'],
            [['project_id', 'user_id', 'role_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '编号'),
            'project_id' => Yii::t('app', '项目'),
            'user_id' => Yii::t('app', '用户'),
            'role_id' => Yii::t('app', '角色'),
        ];
    }
}
