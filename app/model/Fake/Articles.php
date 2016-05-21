<?php
declare(strict_types = 1);
namespace Facedown\Model\Fake;

use Facedown\Model;

final class Articles implements Model\Articles {
    public function iterate(): array {

    }

    public function publish(Model\Article $article): Model\Article {

    }

    public function article(int $id): Model\Article {

    }

    public function count(): int {

    }
}