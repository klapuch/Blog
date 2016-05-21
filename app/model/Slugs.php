<?php
declare(strict_types = 1);
namespace Facedown\Model;

use Facedown;

interface Slugs {
    /**
     * Give slug by the given origin or name
     * @param int|string $identifier
     * @return Slug
     */
    public function slug($identifier): Slug;

    /**
     * Add new slug to the slugs
     * @param int $origin
     * @param string $name
     * @throws Facedown\Exception\ExistenceException
     * @return Slug
     */
    public function add(int $origin, string $name): Slug;
}