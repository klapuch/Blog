<?php
declare(strict_types = 1);
namespace Facedown\Model\Post;

interface Inbox {
    public function put(Message $message): Message;
    public function message(int $id): Message;
    public function iterate(): array;
    public function count(): int;
}