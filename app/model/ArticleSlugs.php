<?php
declare(strict_types = 1);
namespace Facedown\Model;

use Kdyby\Doctrine;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Facedown\Exception\ExistenceException;

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

    public function slug($identifier): Slug {
        if(is_string($identifier))
            $slug = $this->slugs->findOneByName($identifier);
        else
            $slug = $this->slugs->findOneByOrigin($identifier);
        if($slug !== null)
            return $slug;
        throw new ExistenceException(
            sprintf(
                'Slug %s neexistuje',
                (string)$identifier
            )
        );
    }

    public function add(int $origin, string $name): Slug {
        try {
            $slug = new ArticleSlug(
                $this->articles->article($origin),
                $name
            );
            $this->entities->persist($slug);
            $this->entities->flush();
            return $slug;
        } catch(UniqueConstraintViolationException $ex) {
            throw new ExistenceException('Tento slug již existuje');
        }
    }
}