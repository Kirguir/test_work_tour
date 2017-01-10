<?php
namespace tests\models;
use app\models\User;

class UserTest extends \Codeception\Test\Unit
{
	public function testCreateUser()
	{
		expect_that($user = User::create('username'));
		expect($user->nickname)->equals('username');

		expect_not(User::create('username'));
		expect_not(User::create('too_long_username'));

		return $user;
	}

	/**
     * @depends testCreateUser
     */
    public function testFindUserById($newUser)
    {
        expect_that($user = User::findIdentity($newUser->getId()));
        expect($user->nickname)->equals('username');

        expect_not(User::findIdentity(999));
    }

	/**
     * @depends testCreateUser
	 *
	 * @param User $newUser
	 */
    public function testFindUserByAccessToken($newUser)
    {
        expect_that($user = User::findIdentityByAccessToken(md5('usernametoken')));
        expect($user->nickname)->equals($newUser->nickname);

        expect_not(User::findIdentityByAccessToken('non-existing'));        
    }

	/**
     * @depends testCreateUser
	 *
	 * @param User $newUser
	 */
    public function testFindUserByUsername($newUser)
    {
        expect_that($user = User::findByUsername($newUser->nickname));
        expect_not(User::findByUsername('not-admin'));
    }

    /**
     * @depends testCreateUser
     */
    public function testValidateUser($user)
    {
        expect_that($user->validateAuthKey(md5('usernamekey')));
        expect_not($user->validateAuthKey(md5('not-username')));
    }

}
