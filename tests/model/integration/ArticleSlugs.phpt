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

final class ArticleSlugs extends TestCase\Database {
    /** @var $articles Model\Slugs */
    private $slugs;

    public function setUp() {
        parent::setUp();
        $this->slugs = new Model\ArticleSlugs(
            $this->entities,
            new Model\NewestArticles(
                $this->entities,
                new Model\Users($this->entities, new Fake\Cipher),
                new Fake\Identity(1)
            )
        );
    }

    public function testSlug() {
        Assert::same(1, $this->slugs->slug('footitle')->origin());
    }

    public function testSlugByOrigin() {
        Assert::same('bartitle', (string)$this->slugs->slug(2));
    }

    /**
     * @throws \Facedown\Exception\ExistenceException Slug fooBar neexistuje
     */
    public function testUnknownSlugName() {
        $this->slugs->slug('fooBar');
    }

    public function testAdding() {
        $slug = $this->slugs->add(3, 'bla-bla');
        Assert::same(3, $slug->origin());
        Assert::same('bla-bla', (string)$slug);
    }

    public function testAddingDuplicatedSlugName() {
        $this->slugs->add(2, 'bla-bla');
        Assert::exception(
            function() {
                $this->slugs->add(3, 'bla-bla');
            }, \Facedown\Exception\DuplicateException::class,
            'Tento slug již existuje'
        );
    }

    public function testAddingDuplicatedOrigin() {
        $this->slugs->add(2, 'bla-bla');
        Assert::exception(
            function() {
                $this->slugs->add(2, 'foo-bar');
            }, \Facedown\Exception\DuplicateException::class,
            'Tento slug již existuje'
        );
    }
}

(new ArticleSlugs())->run();
