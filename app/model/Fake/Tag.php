<?php
namespace Facedown\Model\Fake;

use Facedown\Model;

final class Tag implements Model\Tag {
    private $name;
    private $id;

    public function __construct(string $name = null, $id = null) {
        $this->name = $name;
        $this->id = $id;
    }

    public function id(): int {
        return $this->id;
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