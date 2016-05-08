<?php
namespace Facedown\Model;

interface Slugs {
    public function slug(string $name): Slug;
    public function add(int $origin, string $name): Slug;
}