<?php
namespace Facedown\Model;

use Facedown;

interface Tags {
    /**
     * @return Tag[]
     */
    public function iterate(): array;

    /**
     * Pin the current tags to the given target
     * @throws Facedown\Exception\ExistenceException
     * @param mixed $target
     */
    public function pin($target);

    /**
     * Give tag by the ID
     * @throws Facedown\Exception\ExistenceException
     * @param int $id
     * @return Tag
     */
    public function tag(int $id): Tag;

    /**
     * Remove from the current tags
     * @param int $id
     */
    public function remove(Tag $tag);
}