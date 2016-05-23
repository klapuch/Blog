<?php
declare(strict_types = 1);
namespace Facedown\Model;

use Facedown\Exception;
use Nette\NotImplementedException;

final class TagColors implements Colors {
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
        if ($this->exists($color->name())) {
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
        if(!$this->exists($name)) {
            throw new Exception\ExistenceException(
                sprintf('Barva %s neexistuje', $name)
            );
        }
        return current(
            array_filter(
                $this->iterate(),
                function (Color $color) use ($name) {
                    return $color->name() === $name;
                }
            )
        );
    }

    private function exists(string $name): bool {
        $colors = array_filter(
            $this->iterate(),
            function (Color $color) use ($name) {
                return $color->name() === $name;
            }
        );
        return count($colors) > 0;
    }
}