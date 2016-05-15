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

final class ArticleTags extends TestCase\Database {
    public function testIterating() {
        $tags = (new Model\ArticleTags($this->entities))->iterate();
        Assert::same(6, count($tags));
        Assert::same('clean code', (string)$tags[0]);
        Assert::same('fooTag', (string)$tags[1]);
        Assert::same('OOP', (string)$tags[2]);
        Assert::same('OOP', (string)$tags[3]);
        Assert::same('security', (string)$tags[4]);
        Assert::same('security', (string)$tags[5]);
    }

    /**
     * @throws \LogicException Všechny tagy nelze připnout za konkrétní cíl
     */
    public function testPinning() {
        (new Model\ArticleTags($this->entities))->pin(new Fake\Article(1));
    }

    public function testTag() {
        $tag = (new Model\ArticleTags($this->entities))->tag(1);
        Assert::same('security', (string)$tag);
    }

    /**
     * @throws \Facedown\Exception\ExistenceException Tag neexistuje
     */
    public function testUnknownTag() {
        (new Model\ArticleTags($this->entities))->tag(666);
    }

    public function testRemoving() {
        $tags = new Model\ArticleTags($this->entities);
        Assert::same(6, count($tags->iterate()));
        $tags->remove($tags->iterate()[0]);
        Assert::same(5, count($tags->iterate()));
    }
}

(new ArticleTags())->run();
