<?php
declare(strict_types = 1);
namespace Facedown\Model;

use Nette;
use Facedown\Exception;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Kdyby\Doctrine;

final class SelectedTags extends Nette\Object implements Tags {
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

    public function tag(int $id): Tag {
        $tag = array_filter($this->tags, function(Tag $tag) use($id) {
            return $tag->id() === $id;
        });
        if(count($tag) === 1)
            return current($tag);
        throw new Exception\ExistenceException('Tag neexistuje');
    }

    public function remove(Tag $tag) {
        $key = array_search($tag, $this->tags, $strict = false);
        if($key !== false)
            unset($this->tags[$key]);
    }
}