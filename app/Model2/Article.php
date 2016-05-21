<?php
declare(strict_types = 1);
namespace Facedown\Model;

use Doctrine\Common\Collections;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="articles")
 */
class Article {
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(name="ID", type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="title", type="string", unique=true, length=100)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTimeInterface
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="ID")
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="ArticleTag", mappedBy="article")
     * @ORM\JoinColumn(referencedColumnName="article")
     */
    private $tags;

    public function __construct(string $title, string $content, User $author) {
        $this->title = $title;
        $this->content = $content;
        $this->author = $author;
        $this->date = new \DateTimeImmutable;
        $this->tags = new Collections\ArrayCollection;
    }

    public function edit(string $title, string $content): self {
        $this->title = $title;
        $this->content = $content;
        return $this;
    }

    public function id(): int {
        return $this->id;
    }

    public function title(): string {
        return $this->title;
    }

    public function content(): string {
        return $this->content;
    }

    public function date(): \DateTimeInterface {
        return $this->date;
    }

    public function author(): User {
        return $this->author;
    }

    public function tags(): Collections\Collection {
        return $this->tags;
    }
}