<?php
declare(strict_types = 1);
namespace Facedown\Model;

use Nette;
use Facedown\Exception;
use Kdyby\Doctrine;

final class PinnedArticleTags extends Nette\Object implements Tags {
    private $entities;
    private $origin;

    public function __construct(
        Doctrine\EntityManager $entities,
        Tags $origin
    ) {
        $this->entities = $entities;
        $this->origin = $origin;
    }

    public function iterate(): array {
        return $this->entities->createQueryBuilder()
            ->select('t')
            ->from(ArticleTag::class, 't')
            ->where('t.pinned = true')
            ->groupBy('t.name')
            ->orderBy('t.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function pin($target) {
        $this->origin->pin($target);
    }

    public function tag(int $id): Tag {
        return $this->origin->tag($id);
    }

    public function remove(Tag $tag) {
        $this->origin->remove($tag);
    }
}