<?php
/**
 * @testCase
 * @phpVersion > 7.0.0
 */
namespace Facedown\Model\Integration\Access;

use Tester,
    Tester\Assert;
use Facedown\{
    Fake, Model, TestCase
};
use Facedown\Model\Access;
use Nette\Security;

require __DIR__ . '/../../../bootstrap.php';

final class Authenticator extends TestCase\Database {
    public function testSuccessfulAuthentication() {
        $authenticator = new Access\Authenticator(
            $this->entities,
            new Fake\Passwords($verify = true)
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
            new Fake\Passwords($verify = true)
        ))->authenticate([0 => 'fooBar', 1 => 'xxxx']);
    }

    /**
     * @throws \Nette\Security\AuthenticationException Špatné heslo
     */
    public function testWrongPassword() {
        (new Access\Authenticator(
            $this->entities,
            new Fake\Passwords($verify = false)
        ))->authenticate([0 => 'facedown', 1 => 'based on $verify variable']);
    }

    public function testRehashing() {
        $passwords = new Security\Passwords;
        $facedownId = 1;
        $plainPassword = 'somePass';
        $facedownPassword = $passwords->hash($plainPassword, ['cost' => 4]);
        $this->connection->executeQuery(
            'UPDATE users SET `password` = ? WHERE ID = ?',
            [$facedownPassword, $facedownId]
        );
        Assert::contains(
            '$2y$04$',
            $this->connection->fetchColumn(
                'SELECT `password` FROM users WHERE ID = ?',
                [$facedownId]
            )
        );
        $identity = (new Access\Authenticator($this->entities, $passwords))
            ->authenticate([0 => 'facedown', 1 => $plainPassword]);
        Assert::contains(
            '$2y$12$',
            $this->connection->fetchColumn(
                'SELECT `password` FROM users WHERE ID = ?',
                [$facedownId]
            )
        );
        Assert::same($facedownId, $identity->getId());
        Assert::same(['creator'], $identity->getRoles());
    }
}

(new Authenticator())->run();
