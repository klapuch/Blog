<?php
declare(strict_types = 1);
namespace Facedown\Model\Fake;

use Facedown\Model;

final class Color implements Model\Color {
    private $name;
    private $color;

    public function __construct(string $name = null, string $color = null) {
        $this->name = $name;
        $this->color = $color;
    }

    public function name(): string {
        return $this->name;
    }

    public function print(): string {
        return $this->color;
    }


}