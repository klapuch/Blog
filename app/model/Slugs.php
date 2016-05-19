<?php
declare(strict_types = 1);
namespace Facedown\Model;

interface Slugs {
    public function slug($identifier): Slug;
    public function add(int $origin, string $name): Slug;
}