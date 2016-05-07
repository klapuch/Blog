<?php
/**
 * @testCase
 * @phpVersion > 7.0.0
 */
namespace Facedown\Model\Unit;

use Tester,
    Tester\Assert;
use Facedown\Model;

require __DIR__ . '/../../bootstrap.php';

final class User extends Tester\TestCase {
    public function testRenaming() {
        $user = new Model\User(
            'facedown',
            'password',
            new Model\Role('member')
        );
        Assert::same('facedown', $user->username());
        Assert::same('password', $user->password());
        Assert::same('member', (string)$user->role());
        $renamedUser = $user->rename('renamedUser');
        Assert::same('renamedUser', $renamedUser->username());
        Assert::same('password', $renamedUser->password());
        Assert::same('member', (string)$renamedUser->role());
        Assert::same($renamedUser, $user); // can not cloned
    }

    public function testChangingPassword() {
        $user = new Model\User(
            'facedown',
            'password',
            new Model\Role('member')
        );
        Assert::same('password', $user->password());
        $user->changePassword('newPassword');
        Assert::same('newPassword', $user->password());
    }
}


(new User())->run();
