<?php
/**
 * @testCase
 * @phpVersion > 7.0.0
 */
namespace Facedown\Model\Integration;

use Tester,
    Tester\Assert;
use Facedown\{
    Model, TestCase
};
use Facedown\Model\Fake;
use Nette\Security;

require __DIR__ . '/../../bootstrap.php';

final class Users extends TestCase\Database {
    /** @var $articles Model\Users */
    private $users;

    public function setUp() {
        parent::setUp();
        $this->users = new Model\Users(
            $this->entities,
            new Fake\Cipher
        );
    }

    public function testRegistration() {
        $registeredUser = $this->users->register(
            new Model\User(
                'newUser',
                'secret :)',
                $this->entities->find(Model\Role::class, 1)
            )
        );
        Assert::same(3, $registeredUser->id());
        Assert::same('secret :)', $registeredUser->password());
        Assert::same('newUser', $registeredUser->username());
        Assert::same('creator', (string)$registeredUser->role());
    }

    public function testUser() {
        $user = $this->users->user(2);
        Assert::same(2, $user->id());
    }

    /**
     * @throws \Facedown\Exception\ExistenceException Uživatel neexistuje
     */
    public function testUnknownUser() {
        $this->users->user(666);
    }

    /**
     * @throws \Facedown\Exception\DuplicateException Uživatelské jméno newUser již existuje
     */
    public function testRegisteringDuplicate() {
        $this->users->register(
            new Model\User(
                'newUser',
                'secret :)',
                $this->entities->find(Model\Role::class, 1)
            )
        );
        $this->users->register(
            new Model\User(
                'newUser',
                'secret :)',
                $this->entities->find(Model\Role::class, 1)
            )
        );
    }
}

(new Users())->run();
