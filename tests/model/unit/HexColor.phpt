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

final class HexColor extends Tester\TestCase {
    protected function validColors() {
        return [
            ['#123456'],
            ['#c12efb'],
            ['#C12EFB'],
            ['#C12eFb'],
            ['#abcdef'],
            ['#000000'],
            ['#FFFFFF'],
        ];
    }

    protected function invalidColors() {
        return [
            ['0'],
            ['#0'],
            [''],
            ['#gggggg'],
            ['#GGGGGG'],
            ['123456'],
            ['ffffff'],
            ['#000000f'],
        ];
    }

    /**
     * @dataProvider validColors
     */
    public function testValidColors($color) {
        $name = 'weird color';
        $hexColor = new Model\HexColor($name, $color);
        Assert::same($color, $hexColor->print());
        Assert::same($name, $hexColor->name());
    }

    /**
     * @dataProvider invalidColors
     */
    public function testInvalidColors($color) {
        Assert::exception(function() use ($color) {
            (new Model\HexColor('weird color', $color))->print();
        }, \InvalidArgumentException::class, 'Barva musí být v hex tvaru');
    }
}


(new HexColor())->run();
