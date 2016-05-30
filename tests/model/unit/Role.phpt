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

final class Role extends Tester\TestCase {
    public function testRanks() {
        Assert::same(3, (new Model\Role('creator'))->rank());
        Assert::same(2, (new Model\Role('administrator'))->rank());
        Assert::same(1, (new Model\Role('member'))->rank());
        Assert::same(-1, (new Model\Role('foo'))->rank());
    }

    public function testNames() {
        Assert::same('creator', (string)new Model\Role('creator'));
        Assert::same('administrator', (string)new Model\Role('administrator'));
        Assert::same('member', (string)new Model\Role('member'));
        Assert::same('guest', (string)new Model\Role('foo'));
    }

    public function testPromoting() {
        $member = new Model\Role('member');
        $promotedMember = $member->promote();
        Assert::same(2, $promotedMember->rank());
        Assert::same('administrator', (string)$promotedMember);
        $administrator = new Model\Role('administrator');
        $promotedAdministrator = $administrator->promote();
        Assert::same(3, $promotedAdministrator->rank());
        Assert::same('creator', (string)$promotedAdministrator);
    }

    public function testDegrading() {
        $administrator = new Model\Role('administrator');
        $degradedAdministrator = $administrator->degrade();
        Assert::same(1, $degradedAdministrator->rank());
        Assert::same('member', (string)$degradedAdministrator);
        $creator = new Model\Role('creator');
        $degradedCreator = $creator->degrade();
        Assert::same(2, $degradedCreator->rank());
        Assert::same('administrator', (string)$degradedCreator);
    }

    /**
     * @throws \OverflowException Vyšší role neexistuje
     */
    public function testOverflowPromoting() {
        (new Model\Role('creator'))->promote();
    }

    /**
     * @throws \UnderflowException Nižší role neexistuje
     */
    public function testUnderflowDegrading() {
        (new Model\Role('member'))->degrade();
    }
}


(new Role())->run();
