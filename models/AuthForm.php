<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * AuthForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class AuthForm extends Model
{
    public $nickname;

    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username are required
            [['nickname'], 'required'],
            ['nickname', 'validateNickname'],
        ];
    }

    /**
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateNickname($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user) {
                $this->_user = User::create($this->nickname);
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), 0);
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
            $this->_user = User::findByUsername($this->nickname);
        }

        return $this->_user;
    }
}
