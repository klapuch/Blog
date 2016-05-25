<?php
declare(strict_types = 1);
namespace Facedown\Model;

final class HexColor implements Color {
    private $name;
    private $print;

    public function __construct(string $name, string $print) {
        $this->name = $name;
        $this->print = $print;
    }

    public function name(): string {
        return $this->name;
    }

    public function print(): string {
        if($this->isHex())
            return $this->print;
        throw new \InvalidArgumentException('Barva musí být v hex tvaru');
    }

    /**
     * Check whether print is in hex format
     * Does not care about case sensitivity
     * @return bool
     */
    private function isHex(): bool {
        return (bool)preg_match('~^#[a-f0-9]{6}\z~i', $this->print);
    }
}