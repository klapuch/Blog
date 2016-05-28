<?php
declare(strict_types = 1);
namespace Facedown\Model;

use Facedown\Exception;
use Klapuch\Ini;

final class IniColors implements Colors {
    private $ini;
    const PARSE_SECTIONS = false;
    const PRESERVE_TYPES = INI_SCANNER_TYPED;

    public function __construct(Ini\Ini $ini) {
        $this->ini = $ini;
    }

    public function iterate(): array {
        $colors = $this->ini->read();
        return array_reduce(
            array_keys($colors),
            function($previous, string $name) use($colors) {
                $previous[] = new UnspecifiedColor($name, $colors[$name]);
                return $previous;
            }
        );
    }

    public function add(Color $color): Color {
        if ($this->exists($color->name())) {
            throw new Exception\DuplicateException(
                sprintf('Název barvy %s již existuje', $color->name())
            );
        }
        $this->ini->write([$color->name() => $color->print()]);
        return $color;
    }

    public function color(string $name): Color {
        if(!$this->exists($name)) {
            throw new Exception\ExistenceException(
                sprintf('Název barvy %s neexistuje', $name)
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