<?php
/**
 * @testCase
 * @phpVersion > 7.0.0
 */
namespace Facedown\Model\Unit;

use Tester,
    Tester\Assert;
use Facedown\Model;
use Facedown\Model\Fake;
use Klapuch\Ini;

require __DIR__ . '/../../bootstrap.php';

final class IniColors extends Tester\TestCase {
    public function testIterating() {
        $colors = (new Model\IniColors(new Ini\Fake(
            ['OOP' => '#abcdef', 'Clean Code' => '#123456']
        )))->iterate();
        Assert::same(2, count($colors));
        Assert::type(Model\UnspecifiedColor::class, $colors[0]);
        Assert::same('OOP', $colors[0]->name());
        Assert::same('#abcdef', $colors[0]->print());
        Assert::type(Model\UnspecifiedColor::class, $colors[1]);
        Assert::same('Clean Code', $colors[1]->name());
        Assert::same('#123456', $colors[1]->print());
    }

    public function testAdding() {
        $ini = new Ini\Fake(
            ['OOP' => '#abcdef', 'Clean Code' => '#123456']
        );
        (new Model\IniColors($ini))->add(new Fake\Color('security', '#000000'));
        Assert::same(
            [
                'OOP' => '#abcdef',
                'Clean Code' => '#123456',
                'security' => '#000000',
            ],
            $ini->read()
        );
    }

    /**
     * @throws \Facedown\Exception\DuplicateException Název barvy OOP již existuje
     */
    public function testAddingDuplication() {
        (new Model\IniColors(new Ini\Fake(
            ['OOP' => '#abcdef', 'Clean Code' => '#123456']
        )))->add(new Fake\Color('OOP', '#ffffff'));
    }

    public function testGivingKnownColor() {
        $color = (new Model\IniColors(new Ini\Fake(
            ['OOP' => '#abcdef', 'Clean Code' => '#123456']
        )))->color('OOP');
        Assert::equal(new Model\UnspecifiedColor('OOP', '#abcdef'), $color);
    }

    /**
     * @throws \Facedown\Exception\ExistenceException Název barvy foo neexistuje
     */
    public function testUnknownColor() {
        (new Model\IniColors(new Ini\Fake(
            ['OOP' => '#abcdef', 'Clean Code' => '#123456']
        )))->color('foo');
    }
}


(new IniColors())->run();
