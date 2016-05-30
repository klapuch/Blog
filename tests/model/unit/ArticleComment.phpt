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

final class ArticleComment extends Tester\TestCase {
    /**
     * @var Model\Comment
     */
    private $comment;

    protected function setUp() {
        parent::setUp();
        $this->comment = new Model\ArticleComment(
            'fooContent',
            'username',
            new Model\Article(
                'fooTitle',
                'fooContent',
                new Model\User(
                    'username',
                    'password',
                    new Model\Role('member')
                )
            )
        );
    }

    public function testErasing() {
        Assert::true($this->comment->visible());
        $this->comment->erase();
        Assert::false($this->comment->visible());
    }
    
    public function testErasingAlreadyErasedOne() {
        $this->comment->erase();
        Assert::exception(function() {
            $this->comment->erase();
        }, \LogicException::class, 'Komentář již nemůže být smazán');
    }
}


(new ArticleComment())->run();
