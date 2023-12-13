<?php

namespace tests\unit\models;

use app\models\UserBase;

class UserTest extends \Codeception\Test\Unit
{
    public function testFindUserById()
    {
        verify($user = UserBase::findIdentity(100))->notEmpty();
        verify($user->username)->equals('admin');

        verify(UserBase::findIdentity(999))->empty();
    }

    public function testFindUserByAccessToken()
    {
        verify($user = UserBase::findIdentityByAccessToken('100-token'))->notEmpty();
        verify($user->username)->equals('admin');

        verify(UserBase::findIdentityByAccessToken('non-existing'))->empty();
    }

    public function testFindUserByUsername()
    {
        verify($user = UserBase::findByUsername('admin'))->notEmpty();
        verify(UserBase::findByUsername('not-admin'))->empty();
    }

    /**
     * @depends testFindUserByUsername
     */
    public function testValidateUser()
    {
        $user = UserBase::findByUsername('admin');
        verify($user->validateAuthKey('test100key'))->notEmpty();
        verify($user->validateAuthKey('test102key'))->empty();

        verify($user->validatePassword('admin'))->notEmpty();
        verify($user->validatePassword('123456'))->empty();        
    }

}
