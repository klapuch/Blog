<?php
namespace Facedown\Model;

use Facedown\Exception;
use Kdyby\Doctrine;

final class AllArticleTags implements Tags {
    private $entities;

    public function __construct(Doctrine\EntityManager $entities) {
        $this->entities = $entities;
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
        throw new \LogicException(
            'Všechny tagy nelze připnout za konkrétní cíl'
        );
    }
}