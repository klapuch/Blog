<?php
namespace Facedown\Model;

use Kdyby\Doctrine;
use Facedown\Exception;

final class ArticleSlugs implements Slugs {
    private $entities;
    private $articles;
    private $slugs;

    public function __construct(
        Doctrine\EntityManager $entities,
        Articles $articles
    ) {
        $this->entities = $entities;
        $this->articles = $articles;
        $this->slugs = $entities->getRepository(ArticleSlug::class);
    }

    public function slug(string $name): Slug {
        $slug = $this->slugs->findOneByName($name);
        if($slug === null)
            throw new Exception\ExistenceException('Slug neexistuje');
        return $slug;
    }

    public function add(int $origin, string $name): Slug {
        $slug = new ArticleSlug(
            $this->articles->article($origin),
            $name
        );
        $this->entities->persist($slug);
        $this->entities->flush();
        return $slug;
    }
}