<?php
declare(strict_types = 1);
namespace Facedown\Model;

interface Tag {
    public function id(): int;

    /**
     * Pin the current tag to the given target
     * @param mixed $target
     */
    public function pin($target);

    /**
     * Unpin the current tag from the target
     * @return void
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