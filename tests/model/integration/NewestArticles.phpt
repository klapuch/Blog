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

final class NewestArticles extends TestCase\Database {
    /** @var $articles Model\NewestArticles */
    private $articles;

    public function setUp() {
        parent::setUp();
        $this->articles = new Model\NewestArticles($this->entities);
    }

    public function testCounting() {
        Assert::same(3, $this->articles->count());
    }

    public function testArticle() {
        $article = $this->articles->article(2);
        Assert::same(2, $article->id());
        Assert::same('barContent', $article->content());
        Assert::same('barTitle', $article->title());
        Assert::same(
            (new \DateTimeImmutable('2006-01-01 01:01:01'))->format('j.n.Y H:i'),
            $article->date()->format('j.n.Y H:i')
        );
        Assert::same(1, $article->author()->id());
    }

    /**
     * @throws \Facedown\Exception\ExistenceException Článek neexistuje
     */
    public function testUnknownArticle() {
        $this->articles->article(666);
    }

    public function testIterating() {
        $articles = $this->articles->iterate();
        Assert::same(3, count($articles));
        Assert::same($articles[0]->id(), 2);
        Assert::same($articles[1]->id(), 1);
        Assert::same($articles[2]->id(), 3);
    }

    public function testPublishing() {
        $publishedArticle = $this->articles->publish(
            new Model\Article(
                'newTitle',
                'newContent',
                $this->entities->find(Model\User::class, 1)
            )
        );
        Assert::same(4, $publishedArticle->id());
        Assert::same('newTitle', $publishedArticle->title());
        Assert::same('newContent', $publishedArticle->content());
        Assert::same(
            (new \DateTimeImmutable)->format('j.n.Y'),
            $publishedArticle->date()->format('j.n.Y')
        );
        Assert::same(1, $publishedArticle->author()->id());
    }

    /**
     * @throws \Facedown\Exception\DuplicateException Titulek newTitle již existuje
     */
    public function testPublishingDuplicate() {
        $this->articles->publish(
            new Model\Article('newTitle',
                'newContent',
                $this->entities->find(Model\User::class, 1)
            )
        );
        $this->articles->publish(
            new Model\Article(
                'newTitle',
                'newContent',
                $this->entities->find(Model\User::class, 1)
            )
        );
    }
}

(new NewestArticles())->run();
