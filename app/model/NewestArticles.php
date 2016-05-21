<?php
declare(strict_types = 1);
namespace Facedown\Model;

use Nette;
use Kdyby\Doctrine;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Facedown\Exception\{
    ExistenceException, DuplicateException
};

final class NewestArticles extends Nette\Object implements Articles {
    private $entities;
    private $articles;

    public function __construct(Doctrine\EntityManager $entities) {
        $this->entities = $entities;
        $this->articles = $entities->getRepository(Article::class);
    }

    public function publish(Article $article): Article {
        try {
            $this->entities->persist($article);
            $this->entities->flush();
            return $article;
        } catch(UniqueConstraintViolationException $ex) {
            throw new DuplicateException(
                sprintf(
                    'Titulek %s již existuje',
                    $article->title()
                )
            );
        }
    }

    public function article(int $id): Article {
        $article = $this->articles->find($id);
        if($article !== null)
            return $article;
        throw new ExistenceException('Článek neexistuje');
    }

    public function iterate(): array {
        return $this->articles->findBy([], ['date' => 'DESC']);
    }

    public function count(): int {
        return $this->articles->countBy();
    }
}