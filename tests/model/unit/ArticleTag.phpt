<?php
/**
 * @testCase
 * @phpVersion > 7.0.0
 */
namespace Facedown\Model\Unit;

use Tester,
    Tester\Assert;
use Facedown\Model,
    Facedown\Model\Fake;

require __DIR__ . '/../../bootstrap.php';

final class ArticleTag extends Tester\TestCase {
    public function testName() {
        $tag = new Model\ArticleTag('security   ');
        Assert::same('security   ', (string)$tag);
    }

    public function testPinning() {
        $tag = new Model\ArticleTag('some tag');
        Assert::false($tag->pinned());
        $tag->pin(new Fake\Article(1));
        Assert::true($tag->pinned());
    }

    public function testUnpinning() {
        $tag = new Model\ArticleTag('some tag');
        Assert::false($tag->pinned());
        $tag->unpin();
        Assert::false($tag->pinned());
        $tag->pin(new Fake\Article(1));
        Assert::true($tag->pinned());
        $tag->unpin();
        Assert::false($tag->pinned());
    }
}


(new ArticleTag())->run();
