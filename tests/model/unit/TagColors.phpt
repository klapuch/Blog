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

require __DIR__ . '/../../bootstrap.php';

final class TagColors extends Tester\TestCase {
    public function testIterating() {
        $colors = (new Model\TagColors($this->preparedFilesystem()))->iterate();
        Assert::same(2, count($colors));
        Assert::same('OOP', $colors[0]->name());
        Assert::same('#abcdef', $colors[0]->print());
        Assert::same('Clean Code', $colors[1]->name());
        Assert::same('#123456', $colors[1]->print());
    }

    public function testAdding() {
        $ini = $this->preparedFilesystem();
        (new Model\TagColors($ini))
            ->add(new Fake\Color('security', '#000000'));
        Assert::same(
            [
                'OOP' => '#abcdef',
                'Clean Code' => '#123456',
                'security' => '#000000',
            ],
            parse_ini_file($ini)
        );
    }

    /**
     * @throws \Facedown\Exception\DuplicateException Barva OOP již existuje
     */
    public function testAddingDuplication() {
        (new Model\TagColors($this->preparedFilesystem()))
            ->add(new Fake\Color('OOP', '#ffffff'));
    }

    public function testColor() {
        $color = (new Model\TagColors($this->preparedFilesystem()))
            ->color('OOP');
        Assert::equal(new Model\HexColor('OOP', '#abcdef'), $color);
    }

    public function testColorWithArrayAccess() {
        $colors = new Model\TagColors($this->preparedFilesystem());
        Assert::true(isset($colors['OOP']));
        Assert::false(isset($colors['foo']));
    }

    /**
     * @throws \Facedown\Exception\ExistenceException Barva foo neexistuje
     */
    public function testUnknownColor() {
        (new Model\TagColors($this->preparedFilesystem()))
            ->color('foo');
    }

    protected function preparedFilesystem() {
        return Tester\FileMock::create(
            "OOP = #abcdef\r\nClean Code = #123456\r\n",
            'ini'
        );
    }
}


(new TagColors())->run();
