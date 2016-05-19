<?php
declare(strict_types = 1);
namespace Facedown\Model;

interface Discussion {
    public function iterate(): array;
    public function comment(int $id): Comment;
    public function post(string $content, string $author): Comment;
    public function count(): int;
}