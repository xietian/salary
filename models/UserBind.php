<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_bind".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $openid
 * @property string $appid
 */
class UserBind extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_bind';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'openid', 'appid'], 'required'],
            [['user_id'], 'integer'],
            [['openid', 'appid'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '编号'),
            'user_id' => Yii::t('app', '用户编号'),
            'openid' => Yii::t('app', '第三方id'),
            'appid' => Yii::t('app', '第三方appid'),
        ];
    }
}
