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

final class ArticleDiscussion extends TestCase\Database {
    /** @var $discussion Model\Discussion */
    private $discussion;

    /** @var $article Model\Article */
    private $article;

    public function setUp() {
        parent::setUp();
        $this->article = $this->entities->find(Model\Article::class, 1);
        $this->discussion = new Model\ArticleDiscussion(
            $this->entities,
            $this->article
        );
    }

    public function testIterating() {
        $discussion = $this->discussion->iterate();
        Assert::same(3, count($discussion));
        /** @var $comment Model\Comment */
        $comment = $discussion[0];
        Assert::same(1, $comment->id());
        /** @var $comment Model\Comment */
        $comment = $discussion[1];
        Assert::same(2, $comment->id());
        /** @var $comment Model\Comment */
        $comment = $discussion[2];
        Assert::same(3, $comment->id());
        Assert::false($comment->visible());
    }

    public function testCounting() {
        Assert::same(3, $this->discussion->count());
    }

    public function testComment() {
        Assert::same(1, $this->discussion->comment(1)->id());
    }

    /**
     * @throws \Facedown\Exception\ExistenceException Komentář neexistuje
     */
    public function testUnknownComment() {
        $this->discussion->comment(666);
    }

    /**
     * @throws \Facedown\Exception\ExistenceException Komentář neexistuje
     */
    public function testUnknownCommentInDiscussion() {
        $this->discussion->comment(4);
    }

    public function testPosting() {
        $comment = $this->discussion->post(
            new Model\ArticleComment(
                'some new content',
                'noOne',
                $this->article
            )
        );
        Assert::same(5, $comment->id());
        Assert::same(4, $this->discussion->count());
    }
}

(new ArticleDiscussion())->run();
