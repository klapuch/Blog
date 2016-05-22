<?php
declare(strict_types = 1);
namespace Facedown\Model;

final class HexColor implements Color {
    private $name;
    private $color;

    public function __construct(string $name, string $color) {
        $this->name = $name;
        $this->color = $color;
    }

    public function name(): string {
        return $this->name;
    }

    public function print(): string {
        if($this->isHex())
            return $this->color;
        throw new \InvalidArgumentException('Barva musí být v hex tvaru');
    }

    /**
     * Check whether color is in hex format
     * Does not care about case sensitivity
     * @return bool
     */
    private function isHex(): bool {
        return (bool)preg_match('~^#[a-f0-9]{6}\z~i', $this->color);
    }
}