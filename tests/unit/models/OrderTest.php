<?php
namespace tests\models;
use app\models\Order;
use app\models\AuthForm;
use Codeception\Specify;

class OrderTest extends \Codeception\Test\Unit
{
	use Specify;

    /**
     * @var \UnitTester
     */
    protected $tester;

	/**
	 *
	 * @var Order
	 */
	private $order;

	protected function _before()
    {
		$model = new AuthForm([
            'nickname' => 'test_user',
        ]);

        $model->login();

		$this->order = new Order();
    }

    protected function _after()
    {
		 \Yii::$app->user->logout();
    }

    // tests
	public function testValidation()
    {
        $this->specify('fields are required', function() {
            $this->order->sender_name    = null;
            $this->order->recipient_name = null;
            $this->order->count = null;
            expect('model is not valid', $this->order->validate())->false();
            expect('sender_name has error', $this->order->getErrors())->hasKey('sender_name');
            expect('recipient_name has error', $this->order->getErrors())->hasKey('recipient_name');
            expect('count has error', $this->order->getErrors())->hasKey('count');
        });
        $this->specify('fields are wrong', function() {
            $this->order->sender_name    = 'too_long_nickname';
            $this->order->recipient_name = 'too_long_nickname';
            $this->order->count          = -100;
            $this->order->status         = 999;
            expect('model is not valid', $this->order->validate())->false();
			expect('sender_name has error', $this->order->getErrors())->hasKey('sender_name');
            expect('recipient_name has error', $this->order->getErrors())->hasKey('recipient_name');
            expect('status has error', $this->order->getErrors())->hasKey('status');
            expect('count has error', $this->order->getErrors())->hasKey('count');
        });
        $this->specify('fields are correct', function() {
            $this->order->sender_name    = 'test_user';
            $this->order->recipient_name = 'test_user_2';
            $this->order->count          = 100;
            $this->order->status         = 0;
            expect('model is not valid', $this->order->validate())->true();
        });
    }

    public function testSaveOrder()
    {
		$order = new Order([
			'sender_name'    => 'test_user',
			'recipient_name' => 'test_r_user',
			'count' => 100
		]);

		expect('model is not valid', $order->save())->true();
    }

	public function testSendMoney()
	{
		$order = new Order([
			'recipient_name' => 'test_r_user',
			'count' => 100,
			'action' => Order::ACTION_SEND
		]);

		expect('model is not valid', $order->save())->true();
		expect($order->sender_name)->equals(\Yii::$app->user->identity->nickname);
		expect($order->status)->equals(Order::STATUS_PROCESSING);
	}

	public function testSendScore()
	{
		$order = new Order([
			'recipient_name' => 'test_r_user',
			'count' => 100,
			'action' => Order::ACTION_RECEIVE
		]);

		expect('model is not valid', $order->save())->true();
		expect($order->recipient_name)->equals(\Yii::$app->user->identity->nickname);
		expect($order->sender_name)->equals('test_r_user');
		expect($order->status)->equals(Order::STATUS_PROCESSING);
	}

	public function testDeclineOrder() {
		$this->specify('Wrong order user', function() {
            $this->order->sender_name    = 'test_user_2';
            $this->order->recipient_name = 'test_user_4';
            $this->order->count          = 100;
            $this->order->save();
            $this->order->decline();
			expect($this->order->status)->equals(Order::STATUS_PROCESSING);
        });
		$this->specify('Correct order user', function() {
            $this->order->sender_name    = \Yii::$app->user->identity->nickname;
            $this->order->recipient_name = 'test_user_4';
            $this->order->count          = 100;
			$this->order->save();
            $this->order->decline();
			expect($this->order->status)->equals(Order::STATUS_DECLINED);
        });
	}

	public function testAcceptOrder() {
		$this->specify('Wrong order user', function() {
            $this->order->sender_name    = 'test_user_2';
            $this->order->recipient_name = 'test_user_4';
            $this->order->count          = 100;
            $this->order->save();
            $this->order->accept();
			expect($this->order->status)->equals(Order::STATUS_PROCESSING);
        });
		$this->specify('Correct order user', function() {
            $this->order->sender_name    = \Yii::$app->user->identity->nickname;
            $this->order->recipient_name = 'test_user_4';
            $this->order->count          = 100;
			$this->order->save();
			$beforeBalance = \Yii::$app->user->identity->balance;
            $this->order->accept();
			$afterBalance = \Yii::$app->user->identity->balance;
			expect($this->order->status)->equals(Order::STATUS_ACCEPTED);
			expect($beforeBalance)->notEquals($afterBalance);
        });
	}
}