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

require __DIR__ . '/../../bootstrap.php';

final class AllArticleTags extends TestCase\Database {
    public function testIterating() {
        $tags = (new Model\AllArticleTags($this->entities))->iterate();
        Assert::same(3, count($tags));
        Assert::same('clean code', (string)$tags[0]);
        Assert::same('OOP', (string)$tags[1]);
        Assert::same('security', (string)$tags[2]);
    }

    /**
     * @throws \LogicException Všechny tagy nelze připnout za konkrétní cíl
     */
    public function testPinning() {
        (new Model\AllArticleTags($this->entities))->pin(new Fake\Article(1));
    }

    public function testTag() {
        $tag = (new Model\AllArticleTags($this->entities))->tag(1);
        Assert::same('security', (string)$tag);
    }

    /**
     * @throws \Facedown\Exception\ExistenceException Tag neexistuje
     */
    public function testUnknownTag() {
        (new Model\AllArticleTags($this->entities))->tag(666);
    }
}

(new AllArticleTags())->run();
