<?php
namespace Facedown\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="article_tags",
 *     indexes={@ORM\Index(columns={"name"})},
 *     uniqueConstraints={@ORM\UniqueConstraint(columns={"name", "article", "pinned"})}
 *  )
 */
class ArticleTag implements Tag {
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="ID", type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * @var Article
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="tags")
     * @ORM\JoinColumn(name="article", referencedColumnName="ID")
     */
    private $article;

    /**
     * @var bool
     * @ORM\Column(name="pinned", type="boolean", options={"default": false})
     */
    private $pinned;

    public function __construct(string $name) {
        $this->name = $name;
        $this->pinned = false;
    }

    public function id(): int {
        return $this->id;
    }

    /**
     * @param Article $target
     */
    public function pin($target) {
        $this->article = $target;
        $this->pinned = true;
    }

    public function unpin() {
        $this->pinned = false;
    }

    public function pinned(): bool {
        return $this->pinned;
    }

    public function __toString() {
        return $this->name;
    }
}