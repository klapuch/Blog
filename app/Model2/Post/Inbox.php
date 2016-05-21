<?php
declare(strict_types = 1);
namespace Facedown\Model\Post;

use Facedown;

interface Inbox {
    /**
     * Put message to the inbox
     * @param Message $message
     * @return Message
     */
    public function receive(Message $message): Message;

    /**
     * Give message by ID from the inbox
     * @param int $id
     * @throws Facedown\Exception\ExistenceException
     * @return Message
     */
    public function message(int $id): Message;

    /**
     * Go through all messages in the inbox
     * @return Message[]
     */
    public function iterate(): array;

    /**
     * Count messages in the inbox
     * @return int
     */
    public function count(): int;
}