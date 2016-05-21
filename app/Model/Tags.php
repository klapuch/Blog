<?php
declare(strict_types = 1);
namespace Facedown\Model;

use Facedown;

interface Tags {
    /**
     * Go through all the tags
     * @return Tag[]
     */
    public function iterate(): array;

    /**
     * Pin the current tags on the given target
     * @param mixed $target
     * @throws Facedown\Exception\ExistenceException
     */
    public function pin($target);

    /**
     * Give tag by the ID
     * @param int $id
     * @throws Facedown\Exception\ExistenceException
     * @return Tag
     */
    public function tag(int $id): Tag;

    /**
     * Remove tag from the current tags
     * @param Tag $tag
     */
    public function remove(Tag $tag);
}