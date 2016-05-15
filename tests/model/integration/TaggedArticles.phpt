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

final class TaggedArticles extends TestCase\Database {
    public function testCounting() {
        Assert::same(
            2,
            (new Model\TaggedArticles(
                'security',
                $this->entities,
                new Fake\Articles
            ))->count()
        );
    }

    public function testIterating() {
        $articles = (new Model\TaggedArticles(
            'security',
            $this->entities,
            new Fake\Articles
        ))->iterate();
        Assert::same(2, count($articles));
        Assert::same($articles[0]->id(), 2);
        Assert::same($articles[1]->id(), 1);
    }

    /**
     * @throws \Facedown\Exception\ExistenceException Pro tag fooBar???? nebyly nalezený žádné články
     */
    public function testIteratingOnUnknownTag() {
        (new Model\TaggedArticles(
            'fooBar????',
            $this->entities,
            new Fake\Articles
        ))->iterate();
    }
}

(new TaggedArticles())->run();
