<?php
/**
 * @testCase
 * @phpVersion > 7.0.0
 */
namespace Facedown\Model\Integration\Access;

use Tester,
    Tester\Assert;
use Facedown\{
    Model, TestCase
};
use Facedown\Model\Fake;
use Facedown\Model\Access;
use Nette\Security;

require __DIR__ . '/../../../bootstrap.php';

final class Authenticator extends TestCase\Database {
    public function testSuccessfulAuthentication() {
        $authenticator = new Access\Authenticator(
            $this->entities,
            new Fake\Cipher($decrypt = true)
        );
        $identity = $authenticator->authenticate(
            [0 => 'facedown', 1 => 'somePassword']
        );
        Assert::same(1, $identity->getId());
        Assert::same(['creator'], $identity->getRoles());
    }

    /**
     * @throws \Nette\Security\AuthenticationException Uživatel neexistuje
     */
    public function testUnknownUsername() {
        (new Access\Authenticator(
            $this->entities,
            new Fake\Cipher($decrypt = true)
        ))->authenticate([0 => 'fooBar', 1 => 'xxxx']);
    }

    /**
     * @throws \Nette\Security\AuthenticationException Nesprávné heslo
     */
    public function testWrongPassword() {
        (new Access\Authenticator(
            $this->entities,
            new Fake\Cipher($decrypt = false)
        ))->authenticate([0 => 'facedown', 1 => 'based on $decrypt variable']);
    }

    public function testRehashing() {
        $cipher = new Fake\Cipher($decrypt = true, $deprecated = true);
        $facedownId = 1;
        Assert::notSame(
            'encrypted',
            $this->connection->fetchColumn(
                'SELECT `password` FROM users WHERE ID = ?',
                [$facedownId]
            )
        );
        (new Access\Authenticator($this->entities, $cipher))
            ->authenticate([0 => 'facedown', 1 => 'somePass']);
        Assert::same(
            'encrypted',
            $this->connection->fetchColumn(
                'SELECT `password` FROM users WHERE ID = ?',
                [$facedownId]
            )
        );
    }
}

(new Authenticator())->run();
