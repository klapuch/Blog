<?php
/**
 * @testCase
 * @phpVersion > 7.0.0
 */
namespace Facedown\Model\Integration;

use Tester,
    Tester\Assert;
use Facedown\{
    Fake, Model, TestCase
};
use Nette\Security;

require __DIR__ . '/../../bootstrap.php';

final class ArticleSlugs extends TestCase\Database {
    /** @var $articles Model\Slugs */
    private $slugs;

    public function setUp() {
        parent::setUp();
        $this->slugs = new Model\ArticleSlugs(
            $this->entities,
            new Model\NewestArticles(
                $this->entities,
                new Model\Users($this->entities, new Fake\Passwords),
                new Fake\Identity(1)
            )
        );
    }

    public function testSlug() {
        Assert::same(1, $this->slugs->slug('footitle')->origin());
    }

    /**
     * @throws \Facedown\Exception\ExistenceException Slug neexistuje
     */
    public function testUnknownSlugName() {
        $this->slugs->slug('fooBar');
    }

    public function testAdding() {
        $slug = $this->slugs->add(2, 'bla-bla');
        Assert::same(2, $slug->origin());
        Assert::same('bla-bla', (string)$slug);
    }
}

(new ArticleSlugs())->run();
