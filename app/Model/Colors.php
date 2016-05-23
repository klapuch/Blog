<?php
declare(strict_types = 1);
namespace Facedown\Model;

use Facedown;

interface Colors {
    /**
     * Go through all the colors
     * @return Color[]
     */
    public function iterate(): array;

    /**
     * Add a new color
     * @param Color $color
     * @throws Facedown\Exception\DuplicateException
     */
    public function add(Color $color);

    /**
     * Give color by the name
     * @param string $name
     * @throws Facedown\Exception\ExistenceException
     * @return Color
     */
    public function color(string $name): Color;

    /**
     * Does the color name exist?
     * @param string $name
     * @return bool
     */
    public function exists(string $name): bool;
}