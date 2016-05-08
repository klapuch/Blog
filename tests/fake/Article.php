<?php
namespace Facedown\Fake;

use Facedown\Model;

final class Article extends Model\Article {
    private $id;

    public function __construct(int $id = null) {
        $this->id = $id;
    }

    public function id(): int {
        return $this->id;
    }
}