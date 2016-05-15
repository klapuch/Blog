<?php
namespace Facedown\Model;

use Nette,
    Nette\Security;
use Kdyby\Doctrine;
use Facedown\Exception;

final class TaggedArticles extends Nette\Object implements Articles {
    private $tag;
    private $entities;
    private $origin;

    public function __construct(
        string $tag,
        Doctrine\EntityManager $entities,
        Articles $origin
    ) {
        $this->tag = $tag;
        $this->entities = $entities;
        $this->origin = $origin;
    }

    public function publish(string $title, string $content): Article {
        return $this->origin->publish($title, $content);
    }

    public function article(int $id): Article {
        return $this->origin->article($id);
    }

    public function iterate(): array {
        if($this->count() === 0) {
            throw new Exception\ExistenceException(
                sprintf(
                    'Pro tag %s nebyly nalezeny žádné články',
                    $this->tag
                )
            );
        }
        return $this->entities->createQueryBuilder()
            ->select(['articles', 'tags'])
            ->from(Article::class, 'articles')
            ->leftJoin('articles.tags', 'tags')
            ->where('tags.name = :tag')
            ->orderBy('articles.date', 'DESC')
            ->setParameter('tag', $this->tag)
            ->getQuery()
            ->getResult();
    }

    public function count(): int {
        return $this->entities->createQueryBuilder()
            ->select('COUNT(a)')
            ->from(Article::class, 'a')
            ->leftJoin('a.tags', 't')
            ->where('t.name = :tag')
            ->setParameter('tag', $this->tag)
            ->getQuery()
            ->getSingleScalarResult();
    }
}