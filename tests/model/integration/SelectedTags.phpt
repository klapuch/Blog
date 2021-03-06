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

final class SelectedTags extends TestCase\Database {
    public function testPinning() {
        Assert::same(
            '3',
            $this->connection->fetchColumn(
                'SELECT COUNT(*) FROM article_tags WHERE article = 1'
            )
        );
        (new Model\SelectedTags(
            $this->entities,
            [
                new Model\ArticleTag('foo'), new Model\ArticleTag('bar')
            ]
        ))->pin($this->entities->find(Model\Article::class, 1));
        Assert::same(
            '5',
            $this->connection->fetchColumn(
                'SELECT COUNT(*) FROM article_tags WHERE article = 1'
            )
        );
    }

    /**
     * @throws \Facedown\Exception\DuplicateException Tag již existuje
     */
    public function testDuplicatePinning() {
        (new Model\SelectedTags(
            $this->entities,
            [
                new Model\ArticleTag('foo'), new Model\ArticleTag('security')
            ]
        ))->pin($this->entities->find(Model\Article::class, 1));
    }

    public function testIterating() {
        $tags = (new Model\SelectedTags(
            $this->entities,
            [new Fake\Tag('foo'), new Fake\Tag('bar')]
        ))->iterate();
        Assert::same(2, count($tags));
        Assert::same('foo', (string)$tags[0]);
        Assert::same('bar', (string)$tags[1]);
    }

    public function testTag() {
        $tag = (new Model\SelectedTags(
            $this->entities,
            [new Fake\Tag('foo', 1), new Fake\Tag('bar', 2)]
        ))->tag(2);
        Assert::same('bar', (string)$tag);
    }

    /**
     * @throws \Facedown\Exception\ExistenceException Tag neexistuje
     */
    public function testUnknownTag() {
        (new Model\SelectedTags(
            $this->entities,
            [new Fake\Tag('foo', 1), new Fake\Tag('bar', 2)]
        ))->tag(3);
    }

    public function testRemoving() {
        $tags = new Model\SelectedTags(
            $this->entities,
            [
                new Fake\Tag('foo', 1),
                new Fake\Tag('bar', 2),
                new Fake\Tag('fooBar', 3)
            ]
        );
        Assert::same(3, count($tags->iterate()));
        $tags->remove(new Fake\Tag('bar', 2));
        Assert::same(2, count($tags->iterate()));
        $tags->remove(new Fake\Tag('xxxxxxxxxxxxx', 666));
        Assert::same(2, count($tags->iterate()));
    }
}

(new SelectedTags())->run();
