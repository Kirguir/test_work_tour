<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property string $sender_name
 * @property string $recipient_name
 * @property string $count
 * @property integer $status
 * @property string $process_time
 *
 * @property User $recipientName
 * @property User $senderName
 */
class Order extends \yii\db\ActiveRecord
{
	const ACTION_SEND    = 1;
	const ACTION_RECEIVE = 2;

	const STATUS_PROCESSING = 0;
	const STATUS_ACCEPTED = 1;
	const STATUS_DECLINED = 2;

	public $action;

	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sender_name', 'recipient_name', 'count'], 'required'],
            [['count'], 'number', 'min' => 0],
            [['action'], 'in', 'range' => array_keys(self::getActions()), 'on' => 'create'],
            [['status'], 'in', 'range' => array_keys(self::getStatuses())],
            [['process_time'], 'safe'],
            [['sender_name', 'recipient_name'], 'string', 'max' => 15],
			[['recipient_name', 'sender_name'], 'validateUsername'],
            [['recipient_name'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['recipient_name' => 'nickname']],
            [['sender_name'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['sender_name' => 'nickname']],
        ];
    }

	public function beforeValidate()
	{
		if($this->action == self::ACTION_SEND){
			$this->sender_name = Yii::$app->user->identity->nickname;
		}elseif($this->action == self::ACTION_RECEIVE){
			$this->sender_name = $this->recipient_name;
			$this->recipient_name = Yii::$app->user->identity->nickname;
		}

		return parent::beforeValidate();
	}

	public function validateUsername($attribute, $params)
    {
        if (User::findByUsername($this->$attribute) === null) {
            User::create($this->$attribute);
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'action' => 'Action',
            'sender_name' => 'Sender Name',
            'recipient_name' => 'Recipient Name',
            'count' => 'Count',
            'status' => 'Status',
            'process_time' => 'Process Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecipientName()
    {
        return $this->hasOne(User::className(), ['nickname' => 'recipient_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSenderName()
    {
        return $this->hasOne(User::className(), ['nickname' => 'sender_name']);
    }

	public static function getActions()
	{
		return [ 
			self::ACTION_SEND   => 'SEND MONEY',
			self::ACTION_RECEIVE => 'SEND SCORE',
		];
	}

	public static function getStatuses()
	{
		return [
			self::STATUS_PROCESSING => 'Processing',
			self::STATUS_ACCEPTED   => 'Accepted',
			self::STATUS_DECLINED   => 'Declined',
		];
	}

	public function accept() {
		if($this->sender_name === Yii::$app->user->identity->nickname) {
			$this->status = self::STATUS_ACCEPTED;

			return $this->update();
		} else {
			return false;
		}
	}

	public function decline() {
		if($this->sender_name === Yii::$app->user->identity->nickname
			|| $this->recipient_name === Yii::$app->user->identity->nickname) {
			
			$this->status = self::STATUS_DECLINED;

			return $this->update();
		} else {
			return false;
		}
	}
}
