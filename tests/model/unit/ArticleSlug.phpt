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

final class ArticleSlug extends Tester\TestCase {
    public function testOrigin() {
        Assert::same(
            6,
            (new Model\ArticleSlug(new Fake\Article(6), 'new-slug'))->origin()
        );
    }

    public function testSlug() {
        Assert::same(
            'new-slug',
            (string)new Model\ArticleSlug(new Fake\Article(6), 'new-slug')
        );
    }

    public function testRenamingWithoutChangingOrigin() {
        $slug = new Model\ArticleSlug(new Fake\Article(6), 'new-slug');
        Assert::same(6, $slug->origin());
        Assert::same('new-slug', (string)$slug);
        $renamedSlug = $slug->rename('brand-new-slug');
        Assert::same(6, $renamedSlug->origin());
        Assert::same('brand-new-slug', (string)$renamedSlug);
        Assert::same($renamedSlug, $slug); // can not be cloned
    }

    /**
     * @throws \InvalidArgumentException fooBar není slug
     */
    public function testRenamingToInvalidSlug() {
        (new Model\ArticleSlug(new Fake\Article(6), 'new-slug'))
            ->rename('fooBar');
    }
}


(new ArticleSlug())->run();
