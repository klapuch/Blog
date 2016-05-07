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

final class Article extends Tester\TestCase {
    public function testEditing() {
        $article = new Model\Article(
            'fooTitle',
            'fooContent',
            new Model\User('facedown', 'password', new Model\Role('foo'))
        );
        Assert::same('fooTitle', $article->title());
        Assert::same('fooContent', $article->content());
        Assert::same(
            (new \DateTimeImmutable)->format('j.n.Y'),
            $article->date()->format('j.n.Y')
        );
        Assert::same('facedown', $article->author()->username());
        $editedArticle = $article->edit('barTitle', 'barContent');
        Assert::same('barTitle', $editedArticle->title());
        Assert::same('barContent', $editedArticle->content());
        Assert::same($editedArticle, $article); // can not cloned
    }
}


(new Article())->run();
