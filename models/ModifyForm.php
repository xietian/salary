<?php

namespace app\models;

use app\models\AdminAuth;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class ModifyForm extends Model
{
    public $password;
    public $newPassword;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['password', 'newPassword'], 'required'],
            // rememberMe must be a boolean value
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * 自定义的密码认证方法
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        // hasErrors方法，用于获取rule失败的数据
        if (!$this->hasErrors()) {
            // 调用当前模型的getUser方法获取用户
            $user = $this->getUser();

            // 获取到用户信息，然后校验用户的密码对不对，校验密码调用的是 backend\models\UserBackend 的validatePassword方法，
            // 这个我们下面会在UserBackend方法里增加
            if (!$user || !$user->validatePassword($this->password)) {
                // 验证失败，调用addError方法给用户提醒信息
                $this->addError($attribute, '旧密码错误.');
            }else{
                return true;
            }
        }

    }

    public function save()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            $user->setPassword($this->newPassword);
            if ($user->save()) {
                return true;
            }
        }
        return false;
    }


    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            return UserAuth::findIdentity(Yii::$app->user->getId());
        } else {
            return $this->_user;
        }
    }

    public function attributeLabels()
    {
        return [
            'password' => '旧密码',
            'newPassword' => '新密码',
        ];
    }
}
