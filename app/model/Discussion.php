<?php
declare(strict_types = 1);
namespace Facedown\Model;

interface Discussion {
    public function iterate(): array;
    public function post(Comment $comment): Comment;
    public function comment(int $id): Comment;
    public function count(): int;
}