<?php
namespace Facedown\Model\Fake;

use Facedown\Model;

final class Tag implements Model\Tag {
    private $name;

    public function __construct(string $name = null) {
        $this->name = $name;
    }

    public function id(): int {

    }

    public function pin($target) {

    }

    public function unpin() {

    }

    public function pinned(): bool {

    }

    public function __toString() {
        return (string)$this->name;
    }
}