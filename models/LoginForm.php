<?php

namespace app\models;


use app\models\User;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    // public function validatePassword($attribute, $params)
    // {
    //     if (!$this->hasErrors()) {
    //         $user = $this->getUser();
    //         if (!$user || !$user->validatePassword($this->password)) {
    //             $this->addError($attribute, 'Incorrect username or password.');
    //         } else {
    //             //更新token
    //             $token = Yii::$app->security->generateRandomString();
    //             $user->accessToken = $token;
    //             $user->updateAccessToken($user->id, $token);
    //             return true;
    //         }
    //     }
    // }
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
                $this->addError($attribute, '用户名或密码错误.');
            }
        }
    }
    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
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
            $this->_user = UserAuth::findByUsername($this->username);
        }

        return $this->_user;
    }

    public function attributeLabels()
    {
        return [
            'rememberMe' => '记住密码',
            'username' => '用户名',
            'password' => '密码',
        ];
    }
}
