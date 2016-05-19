<?php
declare(strict_types = 1);
namespace Facedown\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="comments")
 */
class ArticleComment implements Comment {
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="ID", type="integer")
     */
    private $id;

    /**
     * @var \DateTimeInterface
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var string
     * @ORM\Column(name="author", type="string", length=50)
     */
    private $author;

    /**
     * @var Article
     * @ORM\ManyToOne(targetEntity="Article")
     * @ORM\JoinColumn(name="article_id", referencedColumnName="ID")
     */
    private $article;

    /**
     * @var bool
     * @ORM\Column(name="visible", type="boolean", options={"default": true})
     */
    private $visible;

    public function __construct(
        string $content,
        string $author,
        Article $article
    ) {
        $this->content = $content;
        $this->author = $author;
        $this->article = $article;
        $this->visible = true;
        $this->date = new \DateTimeImmutable;
    }

    public function id(): int {
        return $this->id;
    }

    public function date(): \DateTimeInterface {
        return $this->date;
    }

    public function content(): string {
        return $this->content;
    }

    public function author(): string {
        return $this->author;
    }

    public function erase() {
        if($this->visible === false)
            throw new \LogicException('Komentář již nemůže být smazán');
        $this->visible = false;
    }

    public function visible(): bool {
        return $this->visible;
    }
}