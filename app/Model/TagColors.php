<?php
declare(strict_types = 1);
namespace Facedown\Model;

use Facedown\Exception;
use Nette\NotImplementedException;

final class TagColors implements Colors, \ArrayAccess {
    private $path;
    const PARSE_SECTIONS = false;
    const PRESERVE_TYPES = INI_SCANNER_TYPED;

    public function __construct(string $path) {
        $this->path = $path;
    }

    public function iterate(): array {
        $colors = parse_ini_file(
            $this->path,
            self::PARSE_SECTIONS,
            self::PRESERVE_TYPES
        );
        return (array)array_reduce(
            array_keys($colors),
            function($previous, string $name) use($colors) {
                $previous[] = new HexColor($name, $colors[$name]);
                return $previous;
            }
        );
    }

    public function add(Color $color): Color {
        if ($this->exists($color)) {
            throw new Exception\DuplicateException(
                sprintf('Barva %s již existuje', $color->name())
            );
        }
        file_put_contents(
            $this->path,
            sprintf("%s=%s\r\n", $color->name(), $color->print()),
            FILE_APPEND
        );
        return $color;
    }

    public function color(string $name): Color {
        $colors = array_filter(
            $this->iterate(),
            function (Color $color) use ($name) {
                return $color->name() === $name;
            }
        );
        if(count($colors) === 1)
            return current($colors);
        throw new Exception\ExistenceException(
            sprintf('Barva %s neexistuje', $name)
        );
    }

    private function exists(Color $color) {
        try {
            return (bool)$this->color($color->name());
        } catch(Exception\ExistenceException $ex) {
            return false;
        }
    }

    /** ArrayAccess implementation */

    public function offsetSet($offset, $value) {
        throw new NotImplementedException('Set is not implemented');
    }

    public function offsetExists($name) {
        return $this->exists(new class ($name) implements Color {
            private $name;
            public function __construct(string $name) {
                $this->name = $name;
            }
            public function print(): string {   }
            public function name(): string {
                return $this->name;
            }
        });
    }

    public function offsetUnset($offset) {
        throw new NotImplementedException('Unset is not implemented');
    }

    public function offsetGet($offset) {
        throw new NotImplementedException('Get is not implemented');
    }
}