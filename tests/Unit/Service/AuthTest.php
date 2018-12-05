<?php

namespace IntecPhp\Test\Unit\Service;

use PHPUnit\Framework\TestCase;
use IntecPhp\Model\User;
use IntecPhp\Service\Auth;

class AuthTest extends TestCase
{
    public function testVerifyPassword()
    {
        $mock = $this->createMock(Auth::class);
        $reflection = new \ReflectionClass(Auth::class);
        $method = $reflection->getMethod('verifyPassword');
        $method->setAccessible(true);

        $password = '123456789';
        $correctEncryptedPass = password_hash($password, PASSWORD_BCRYPT);
        $wrongEncryptedPass = 'aaaaaa';

        $this->assertTrue($method->invokeArgs($mock, [$password, $correctEncryptedPass]));
        $this->assertFalse($method->invokeArgs($mock, [$password, $wrongEncryptedPass]));
    }

    public function testValidate()
    {
        $id = 10;
        $email = 'helloworld';
        $password = 'secret';
        $correctEncryptedPass = password_hash($password, PASSWORD_BCRYPT);
        $wrongEncryptedPass = 'aaaaaa';
        $info = [
            'id' => $id,
            'senha' => $correctEncryptedPass,
            'papel' => 'comum'
        ];

        $userStub = $this->createMock(User::class);
        $userStub
            ->method('searchByEmail')
            ->with($email)
            ->willReturn($info);

        $auth = new Auth($userStub);
        $this->assertSame($info, $auth->validate($email, $password));
        $this->assertNull($auth->validate($email, '???'));
    }

    public function testEmptyUser()
    {
        $email = 'helloworld';

        $userStub = $this->createMock(User::class);
        $userStub
            ->expects($this->once())
            ->method('searchByEmail')
            ->with($email)
            ->willReturn(null);

        $auth = new Auth($userStub);
        $this->assertNull($auth->validate($email, '???'));
    }

    public function testGenerate()
    {
        $pass = '1654987241asd';
        $userStub = $this->createMock(User::class);
        $auth = new Auth($userStub);

        $encPass = $auth->generate($pass);
        $this->assertTrue(password_verify($pass, $encPass));
    }
}
