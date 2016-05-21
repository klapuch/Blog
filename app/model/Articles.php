<?php
declare(strict_types = 1);
namespace Facedown\Model;

interface Articles {
    public function iterate(): array;
    public function publish(Article $article): Article;
    public function article(int $id): Article;
    public function count(): int;
}