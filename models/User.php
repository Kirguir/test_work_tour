<?php

namespace app\models;

use yii\db\Query;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $nickname
 * @property string $authKey
 * @property string $accessToken
 *
 * @property Order[] $orders
 * @property Order[] $orders0
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
	public $recive;
	public $sended;

	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nickname', 'authKey', 'accessToken'], 'required'],
            [['nickname'], 'string', 'max' => 15],
            [['authKey', 'accessToken'], 'string', 'max' => 64],
            [['nickname'], 'unique'],
            [['authKey'], 'unique'],
            [['accessToken'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nickname' => 'Nickname',
            'balance' => 'Balalnce',
            'authKey' => 'Auth Key',
            'accessToken' => 'Access Token',
        ];
    }

	 /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
		return self::findOne($id);
    }

	/**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return self::findOne(['nickname' => $username]);
    }

	/**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
		return self::findOne(['accessToken' => $token]);
    }

	/**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

	/**
	 * Create user by nickname
	 *
	 * @param string $nickname
	 * @return \static|boolean
	 */
	public static function create($nickname)
	{
		$user = new static();
		$user->nickname = $nickname;
		$user->authKey = md5($nickname . 'key');
		$user->accessToken = md5($nickname . 'token');

		if($user->insert())
		{
			return $user;
		}

		return false;
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdersReceived()
    {
        return $this->hasMany(Order::className(), ['recipient_name' => 'nickname']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdersSended()
    {
        return $this->hasMany(Order::className(), ['sender_name' => 'nickname']);
    }

	public function getTotalReciveAmount() {
		$query = (new Query())->from('order')
			->where('recipient_name = :nickname AND status = 1', [':nickname' => $this->nickname]);

		return $query->sum('count');
	}

	public function getTotalSendAmount() {
		$query = (new Query())->from('order')
			->where('sender_name = :nickname AND status = 1', [':nickname' => $this->nickname]);

		return $query->sum('count');
	}

	public function getBalance() {
		return $this->getTotalReciveAmount() - $this->getTotalSendAmount();
	}
}
