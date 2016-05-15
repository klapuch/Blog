<?php
namespace Facedown\Model;

use Nette,
    Nette\Security;
use Kdyby\Doctrine;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Facedown\Exception\ExistenceException;

final class NewestArticles extends Nette\Object implements Articles {
    private $entities;
    private $users;
    private $articles;
    private $myself;

    public function __construct(
        Doctrine\EntityManager $entities,
        Users $users,
        Security\IIdentity $myself
    ) {
        $this->entities = $entities;
        $this->users = $users;
        $this->articles = $entities->getRepository(Article::class);
        $this->myself = $myself;
    }

    public function publish(string $title, string $content): Article {
        try {
            $article = new Article(
                $title,
                $content,
                $this->users->user($this->myself->getId())
            );
            $this->entities->persist($article);
            $this->entities->flush();
            return $article;
        } catch(UniqueConstraintViolationException $ex) {
            throw new ExistenceException(
                sprintf(
                    'Titulek %s již existuje',
                    $title
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