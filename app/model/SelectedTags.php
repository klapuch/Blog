<?php
namespace Facedown\Model;

use Facedown\Exception;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Kdyby\Doctrine;

final class SelectedTags implements Tags {
    private $entities;

    /**
     * @var Tag[]
     */
    private $tags;

    /**
     * @param Doctrine\EntityManager $entities
     * @param Tag[] $tags
     */
    public function __construct(Doctrine\EntityManager $entities, array $tags) {
        $this->entities = $entities;
        $this->tags = $tags;
    }

    public function iterate(): array {
        return $this->tags;
    }

    public function pin($target) {
        try {
            foreach($this->iterate() as $tag) {
                $tag->pin($target);
                $this->entities->persist($tag);
            }
            $this->entities->flush();
        } catch(UniqueConstraintViolationException $ex) {
            throw new Exception\ExistenceException('Tag již existuje');
        }
    }
}