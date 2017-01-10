<?php
class LoginFormCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnRoute('site/login');
    }

    public function openLoginPage(\FunctionalTester $I)
    {
        $I->see('Авторизация', 'h1');
    }

    public function loginWithEmptyCredentials(\FunctionalTester $I)
    {
        $I->submitForm('#auth-form', []);
        $I->expectTo('see validations errors');
        $I->see('Nickname cannot be blank.');
    }

    public function loginSuccessfully(\FunctionalTester $I)
    {
        $I->submitForm('#auth-form', [
            'AuthForm[nickname]' => 'admin',
        ]);
        $I->see('Logout (admin)');
        $I->dontSeeElement('form#auth-form');
    }
}