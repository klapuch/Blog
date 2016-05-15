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
}