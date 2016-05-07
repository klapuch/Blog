<?php
namespace Facedown\Model;

use Facedown;

interface Articles {
    public function iterate(): array;
    public function publish(string $title, string $content): Article;
    public function article(int $id): Article;
    public function count(): int;
}