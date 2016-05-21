<?php
/**
 * @testCase
 * @phpVersion > 7.0.0
 */
namespace Facedown\Model\Unit;

use Tester,
    Tester\Assert;
use Facedown\Model\Access;
use Nette\Security;
use Facedown\TestCase;

require __DIR__ . '/../../../bootstrap.php';

final class IdentityFactory extends TestCase\Mockery {
    public function testNoOneIdentity() {
        $user = $this->mockery('Nette\Security\User')
            ->shouldReceive('getIdentity')
            ->andReturnNull()
            ->getMock();
        $identity = (new Access\IdentityFactory($user))->create();
        Assert::type('Facedown\Model\Access\NoOneIdentity', $identity);
    }

    public function testLoggedUserIdentity() {
        $user = $this->mockery('Nette\Security\User')
            ->shouldReceive('getIdentity')
            ->andReturn(new Security\Identity(1))
            ->getMock();
        $identity = (new Access\IdentityFactory($user))->create();
        Assert::type('Nette\Security\Identity', $identity);
    }
}


(new IdentityFactory())->run();
