<?php
declare(strict_types = 1);
namespace Facedown\Model;

interface Color {
    /**
     * What is the name of the color?
     * @return string
     */
    public function name(): string;

    /**
     * Print the color
     * @throws \InvalidArgumentException
     * @return string
     */
    public function print(): string;
}