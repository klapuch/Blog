<?php
namespace Facedown\Model;

interface Tag {
    public function id(): int;

    /**
     * @param mixed $target
     * Pin the current tag to the given target
     */
    public function pin($target);

    /**
     * Unpin the current tag from the target
     */
    public function unpin();

    /**
     * Is the current tag pinned to some target?
     * @return bool
     */
    public function pinned(): bool;

    /**
     * Name of the current tag
     * @return string
     */
    public function __toString();
}