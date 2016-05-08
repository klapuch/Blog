<?php
namespace Facedown\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="article_slugs")
 */
class ArticleSlug implements Slug {
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(name="ID", type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Article
     * @ORM\OneToOne(targetEntity="Article")
     * @ORM\JoinColumn(name="origin", referencedColumnName="ID")
     */
    private $article;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=100, unique=true)
     */
    private $name;

    public function __construct(Article $article, string $name) {
        $this->article = $article;
        $this->name = $name;
    }

    public function origin(): int {
        return $this->article->id();
    }

    public function __toString() {
        return $this->name;
    }

    public function rename(string $name): Slug {
        if(!$this->isSlug($name)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%s není slug',
                    $name
                )
            );
        }
        $this->name = $name;
        return $this;
    }

    private function isSlug(string $slug): bool {
        return (bool)preg_match('~^[a-z0-9]+(?:-[a-z0-9]+)*\z~', $slug);
    }
}