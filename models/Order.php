<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property integer $sender_id
 * @property integer $recipient_id
 * @property string $count
 * @property integer $status
 * @property string $process_time
 *
 * @property User $recipient
 * @property User $sender
 */
class Order extends \yii\db\ActiveRecord
{

	const ACTION_SEND    = 1;
	const ACTION_RECEIVE = 2;

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
            [['sender_id', 'recipient_id', 'count'], 'required'],
            [['sender_id', 'recipient_id', 'status'], 'integer'],
            [['count'], 'number'],
            [['process_time'], 'safe'],
            [['recipient_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['recipient_id' => 'id']],
            [['sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['sender_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sender_id' => 'Sender ID',
            'recipient_id' => 'Recipient ID',
            'count' => 'Count',
            'status' => 'Status',
            'process_time' => 'Process Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecipient()
    {
        return $this->hasOne(User::className(), ['id' => 'recipient_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(User::className(), ['id' => 'sender_id']);
    }

	public static function getActions()
	{
		return [
			self::ACTION_SEND    => 'SEND MONEY',
			self::ACTION_RECEIVE => 'SEND SCORE',
		];
	}
}
