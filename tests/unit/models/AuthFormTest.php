<?php

namespace tests\models;

use app\models\AuthForm;

class AuthFormTest extends \Codeception\Test\Unit
{
    private $model;

    protected function _after()
    {
        \Yii::$app->user->logout();
    }

    public function testLoginNoUser()
    {
        $this->model = new AuthForm([
            'nickname' => 'not_existing',
        ]);

        expect_that($this->model->login());
		expect_not(\Yii::$app->user->isGuest);
        expect(\Yii::$app->user->identity->nickname)->equals('not_existing');
    }

    public function testLoginExistsUser()
    {
        $this->model = new AuthForm([
            'nickname' => 'not_existing',
        ]);

        expect_that($this->model->login());
        expect_not(\Yii::$app->user->isGuest);
        expect(\Yii::$app->user->identity->nickname)->equals('not_existing');
    }

}
