<?php
namespace Facedown\Model;

use Nette;
use Kdyby\Doctrine;
use Facedown\Exception;

class ArticleDiscussion extends Nette\Object implements Discussion {
    private $entities;
    private $article;
    private $comments;

    public function __construct(
        Doctrine\EntityManager $entities,
        Article $article
    ) {
        $this->entities = $entities;
        $this->article = $article;
        $this->comments = $entities->getRepository(ArticleComment::class);
    }

    public function iterate(): array {
        return $this->comments->findBy(
            ['article' => $this->article],
            ['date' => 'DESC']
        );
    }

    public function post(string $content, string $author): Comment {
        $comment = new ArticleComment(
            $content,
            $author,
            $this->article
        );
        $this->entities->persist($comment);
        $this->entities->flush();
        return $comment;
    }

    public function comment(int $id): Comment {
        $comment = $this->comments->findOneBy(
            ['id' => $id, 'article' => $this->article]
        );
        if($comment === null)
            throw new Exception\ExistenceException('Komentář neexistuje');
        return $comment;
    }

    public function count(): int {
        return $this->comments->countBy(['article' => $this->article]);
    }
}