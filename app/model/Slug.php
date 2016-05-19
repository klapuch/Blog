<?php
declare(strict_types = 1);
namespace Facedown\Model;

interface Slug {
    /**
     * The origin (ID) that is covered by slug itself
     * @return int
     */
    public function origin(): int;

    /**
     * Current slug as a string representation
     * @return string
     */
    public function __toString();

    /**
     * Rename current slug and keep origin (ID) still the same
     * @param string $slug
     * @return Slug
     */
    public function rename(string $slug): self;
}