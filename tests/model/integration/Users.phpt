<?php
/**
 * @testCase
 * @phpVersion > 7.0.0
 */
namespace Facedown\Model\Integration;

use Tester,
    Tester\Assert;
use Facedown\{
    Fake, Model, TestCase
};
use Nette\Security;

require __DIR__ . '/../../bootstrap.php';

final class Users extends TestCase\Database {
    /** @var $articles Model\Users */
    private $users;

    public function setUp() {
        parent::setUp();
        $this->users = new Model\Users(
            $this->entities,
            new Fake\Passwords
        );
    }

    public function testRegistration() {
        $registeredUser = $this->users->register('newUser', 'secret :)');
        Assert::same(3, $registeredUser->id());
        Assert::same('hashed', $registeredUser->password());
        Assert::same('newUser', $registeredUser->username());
        Assert::same('member', (string)$registeredUser->role());
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
}

(new Users())->run();
