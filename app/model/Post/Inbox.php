<?php
namespace Facedown\Model\Post;

interface Inbox {
    public function put(
        string $subject,
        string $content,
        string $sender
    ): Message;
    public function message(int $id): Message;
    public function iterate(): array;
    public function count(): int;
}