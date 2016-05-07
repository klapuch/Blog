<?php
namespace Facedown\Model;

use Nette,
    Nette\Security;
use Kdyby\Doctrine;
use Facedown\Exception;

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
        $article = new Article(
            $title,
            $content,
            $this->users->user($this->myself->getId())
        );
        $this->entities->persist($article);
        $this->entities->flush();
        return $article;
    }

    public function article(int $id): Article {
        $article = $this->articles->find($id);
        if($article === null)
            throw new Exception\ExistenceException('Článek neexistuje');
        return $article;
    }

    public function iterate(): array {
        return $this->articles->findBy([], ['date' => 'DESC']);
    }

    public function count(): int {
        return $this->articles->countBy();
    }
}